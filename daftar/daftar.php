<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nohp = trim($_POST['nohp']);
    $password = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi']);

    // Cek password sama atau tidak
    if ($password !== $konfirmasi) {

        echo "<script>
            alert('Password tidak sama!');
            window.location='index.php';
        </script>";
        exit();
    }

    // Cek nomor HP sudah ada atau belum
    $cek = mysqli_query($koneksi, "
        SELECT * FROM users 
        WHERE no_hp='$nohp'
    ");

    if (mysqli_num_rows($cek) > 0) {

        echo "<script>
            alert('Nomor HP sudah terdaftar!');
            window.location='index.php';
        </script>";
        exit();

    } else {

        // Enkripsi password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru
        mysqli_query($koneksi, "
            INSERT INTO users
            (nama_lengkap, no_hp, password, alamat, email, role, status)
            VALUES
            (
                '',
                '$nohp',
                '$password_hash',
                '',
                '',
                'user',
                'aktif'
            )
        ");

        echo "<script>
            alert('Pendaftaran berhasil!');
            window.location='../login/index.php';
        </script>";
        exit();
    }
}
?>