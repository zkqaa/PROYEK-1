<?php
session_start();

if (!isset($_SESSION['nohp'])) {
    echo "Akses tidak valid";
    exit();
}

if (isset($_SESSION['otp_last_sent'])) {
    $selisih = time() - $_SESSION['otp_last_sent'];

    if ($selisih < 30) {
        $sisa = 30 - $selisih;
        echo "<script>
            alert('Tunggu $sisa detik untuk kirim ulang OTP');
            window.location='index.php';
        </script>";
        exit();
    }
}

$otp_baru = rand(1000, 9999);

$_SESSION['otp'] = $otp_baru;
$_SESSION['otp_last_sent'] = time();

echo "<script>
    alert('OTP baru kamu: $otp_baru');
    window.location='index.php';
</script>";
exit();
?>