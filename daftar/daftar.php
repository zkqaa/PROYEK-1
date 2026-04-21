<?php
include '../koneksi.php';

$nohp = $_POST['nohp'];
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

if ($password !== $konfirmasi) {
    echo "<script>
        alert('Password tidak sama!');
        window.location='index.php';
    </script>";
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$cek = mysqli_query($conn, "SELECT * FROM users WHERE nohp='$nohp'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('Nomor sudah terdaftar!');
        window.location='index.php';
    </script>";
} else {
    mysqli_query($conn, "INSERT INTO users (nohp, password) VALUES ('$nohp', '$password_hash')");

    echo "<script>
        alert('Pendaftaran berhasil!');
        window.location='../login/login.php';
    </script>";
}
?>