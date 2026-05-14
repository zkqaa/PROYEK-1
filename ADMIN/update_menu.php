<?php
include "../koneksi.php";

$id_menu     = (int) $_POST['id_menu'];
$nama_menu   = mysqli_real_escape_string($conn, $_POST['nama_menu']);
$harga       = (int) $_POST['harga'];
$id_kategori = (int) $_POST['id_kategori'];
$gambar_lama = $_POST['gambar_lama'];

$gambar = $gambar_lama;

if (!empty($_FILES['gambar']['name'])) {
    if (!empty($gambar_lama) && file_exists("img/" . $gambar_lama)) {
        unlink("img/" . $gambar_lama);
    }

    $gambar = time() . "_" . basename($_FILES['gambar']['name']);
    $tmp    = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "img/" . $gambar);
}

mysqli_query($conn, "
    UPDATE menu SET
        nama_menu = '$nama_menu',
        id_kategori = '$id_kategori',
        harga = '$harga',
        gambar = '$gambar'
    WHERE id_menu = '$id_menu'
");

header("Location: menu.php");
exit;
?>
