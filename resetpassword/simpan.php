<?php
session_start();
include '../koneksi.php';

if (
    !isset($_SESSION['otp_verified']) ||
    $_SESSION['otp_verified'] !== true ||
    !isset($_SESSION['nohp'])
) {
    echo "Akses tidak valid";
    exit();
}

if (!isset($_POST['password_baru']) || !isset($_POST['konfirmasi_password'])) {
    echo "Akses tidak valid";
    exit();
}

$nohp = $_SESSION['nohp'];
$password_baru = trim($_POST['password_baru']);
$konfirmasi_password = trim($_POST['konfirmasi_password']);

if ($password_baru === "" || $konfirmasi_password === "") {
    echo "<script>
        alert('Password tidak boleh kosong!');
        window.location='index.php';
    </script>";
    exit();
}

if ($password_baru !== $konfirmasi_password) {
    echo "<script>
        alert('Konfirmasi password tidak sama!');
        window.location='index.php';
    </script>";
    exit();
}

// hash password baru
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

// update password berdasarkan nohp
$query = mysqli_query($conn, "UPDATE pelanggan SET password='$password_hash' WHERE no_hp='$nohp'");

if ($query) {
    // hapus session reset
    unset($_SESSION['otp']);
    unset($_SESSION['otp_verified']);
    unset($_SESSION['nohp']);

    echo "<script>
        alert('Password berhasil diubah!');
        window.location='../login/index.php';
    </script>";
    exit();
} else {
    echo "<script>
        alert('Gagal mengubah password!');
        window.location='index.php';
    </script>";
    exit();
}
?>
