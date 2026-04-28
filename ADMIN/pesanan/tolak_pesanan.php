<?php
include "../koneksi.php";

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk update status menjadi Ditolak
    $query = "UPDATE pesanan SET 
              status = 'Ditolak' 
              WHERE id_pesanan = '$id'";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, balik ke halaman pesanan
        header("Location: pesanan.php?pesan=berhasil_tolak");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: pesanan.php");
}
?>