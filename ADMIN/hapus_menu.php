<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu='$id'");
$row = mysqli_fetch_assoc($data);

if ($row) {
    if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
        unlink("uploads/" . $row['gambar']);
    }

    mysqli_query($koneksi, "DELETE FROM menu WHERE id_menu='$id'");
}

header("Location: menu.php");
exit;
?>