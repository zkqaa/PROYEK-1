<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nohp'])) {

    $nohp = trim($_POST['nohp']);

    // cek user berdasarkan no hp
    $cek = mysqli_query($koneksi, "
        SELECT * FROM users 
        WHERE no_hp='$nohp'
    ");

    if (mysqli_num_rows($cek) > 0) {

        // generate OTP
        $otp = rand(1000, 9999);

        $_SESSION['nohp'] = $nohp;
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_last_sent'] = time();

        echo "
        <script>
            alert('OTP kamu: $otp');
            window.location='../otp/index.php';
        </script>
        ";

        exit();

    } else {

        echo "
        <script>
            alert('Nomor tidak terdaftar');
            window.location='index.php';
        </script>
        ";

        exit();
    }

} else {

    echo "
    <script>
        alert('Akses tidak valid');
        window.location='index.php';
    </script>
    ";

    exit();
}
?>