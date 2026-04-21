<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nohp'])) {
    $nohp = trim($_POST['nohp']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE nohp='$nohp'");

    if (mysqli_num_rows($cek) > 0) {
        $otp = rand(1000, 9999);

        $_SESSION['nohp'] = $nohp;
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_last_sent'] = time();

        echo "<script>
            alert('OTP kamu: $otp');
            window.location='../otp/index.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Nomor tidak terdaftar');
            window.location='index.php';
        </script>";
        exit();
    }
} else {
    echo "Akses tidak valid";
    exit();
}
?>