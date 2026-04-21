<?php
session_start();
include '../koneksi.php';

$nohp = $_POST['nohp'];
$password = $_POST['password'];

$data = mysqli_query($conn, "SELECT * FROM users WHERE nohp='$nohp'");
$user = mysqli_fetch_assoc($data);

if($user){

    // Cek password
    if(password_verify($password, $user['password'])){
        
        $_SESSION['nohp'] = $user['nohp'];

        header("Location: ../beranda/beranda.php");
        exit();

    } else {
        echo "<script>
        alert('Password salah!');
        window.location='index.php';
        </script>";
    }

} else {
    echo "<script>
    alert('Nomor tidak ditemukan!');
    window.location='index.php';
    </script>";
}
?>