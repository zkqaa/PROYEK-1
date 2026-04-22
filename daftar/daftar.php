<?php
include '../koneksi.php';

$nohp = $_POST['nohp'];
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

//Cek password
if ($password !== $konfirmasi) {
    echo "<script>
        alert('Password tidak sama!');
        window.location='index.php';
    </script>";
    exit();
}

//Enkripsi password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

//Cek no.hp apakah sudah ada atau belum
$cek = mysqli_query($conn, "SELECT * FROM pelanggan WHERE no_hp='$nohp'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('Nomor sudah terdaftar!');
        window.location='index.php';
    </script>";
} else {
    mysqli_query($conn, "INSERT INTO pelanggan (no_hp, password) VALUES ('$nohp', '$password_hash')");

    echo "<script>
        alert('Pendaftaran berhasil!');
        window.location='../login/login.php';
    </script>";
}
?>
