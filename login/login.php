<?php
session_start();
include '../koneksi.php';

if (isset($_POST['nohp'])) {

    $nohp = trim($_POST['nohp']);
    $password = trim($_POST['password']);

    // cek user berdasarkan no hp
    $query = mysqli_query($koneksi, "
        SELECT * FROM users 
        WHERE no_hp='$nohp'
    ");

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        if($user['status'] == 'nonaktif'){
            echo "<script>
                alert('Akun anda sudah dinonaktifkan admin!');
                window.location='index.php';
            </script>";
            exit;
        }

        // cek password
        if(password_verify($password, $user['password'])){

            // cek status akun
            if($user['status'] == 'nonaktif'){

                echo "
                <script>
                    alert('Akun anda telah dinonaktifkan admin!');
                    window.location='index.php';
                </script>
                ";

                exit;
            }

            // simpan session
            $_SESSION['id_user']  = $user['id_user'];
            $_SESSION['username'] = $user['nama_lengkap'];
            $_SESSION['role']     = $user['role'];

            // redirect berdasarkan role
            if($user['role'] == 'admin'){

                header("Location: ../admin/dashboard.php");

            } else {

                header("Location: ../beranda/beranda.php");

            }

            exit;

        } else {

            echo "
            <script>
                alert('Password salah!');
                window.location='index.php';
            </script>
            ";
        }

    } else {

        echo "
        <script>
            alert('Nomor HP tidak ditemukan!');
            window.location='index.php';
        </script>
        ";
    }
}
?>