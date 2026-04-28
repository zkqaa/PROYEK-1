<?php
include "koneksi.php";

$nama_menu   = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
$harga       = (int) $_POST['harga'];
$id_kategori = (int) $_POST['id_kategori'];

$gambar = "";

if (!empty($_FILES['gambar']['name'])) {
    $gambar = time() . "_" . basename($_FILES['gambar']['name']);
    $tmp    = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "uploads/" . $gambar);
}

mysqli_query($koneksi, "
    INSERT INTO menu (nama_menu, id_kategori, harga, gambar)
    VALUES ('$nama_menu', '$id_kategori', '$harga', '$gambar')
");

header("Location: menu.php");
exit;
?>