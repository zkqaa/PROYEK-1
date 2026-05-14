<?php
$koneksi = mysqli_connect("localhost", "root", "", "mie_ayam_hijau_new", 3307);

if (!$koneksi){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
