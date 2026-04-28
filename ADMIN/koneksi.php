<?php
$koneksi = mysqli_connect("127.0.0.1:3307", "root", "", "mie_ayam_hijau");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>