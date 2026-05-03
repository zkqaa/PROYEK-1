<?php
$conn = mysqli_connect("localhost", "root", "", "mie_ayam_hijau", 3307);

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
