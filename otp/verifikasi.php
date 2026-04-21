<?php
session_start();

if (
    !isset($_SESSION['otp']) ||
    !isset($_SESSION['nohp']) ||
    !isset($_POST['o1']) ||
    !isset($_POST['o2']) ||
    !isset($_POST['o3']) ||
    !isset($_POST['o4'])
) {
    echo "Akses tidak valid";
    exit();
}

$otp_input = $_POST['o1'] . $_POST['o2'] . $_POST['o3'] . $_POST['o4'];
$otp_asli  = (string) $_SESSION['otp'];

if ($otp_input === $otp_asli) {
    // tandai bahwa user sudah lolos verifikasi OTP
    $_SESSION['otp_verified'] = true;

    echo "<script>
        alert('OTP Benar!');
        window.location='../resetpassword/index.php';
    </script>";
    exit();
} else {
    echo "<script>
        alert('OTP Salah!');
        window.location='index.php';
    </script>";
    exit();
}
?>