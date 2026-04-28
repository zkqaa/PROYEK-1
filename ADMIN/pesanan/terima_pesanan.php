<?php
include "../koneksi.php";

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $waktu_sekarang = date('Y-m-d H:i:s');

    // Query untuk update status dan waktu selesai
    $query = "UPDATE pesanan SET 
              status = 'Diterima', 
              waktu_selesai = '$waktu_sekarang' 
              WHERE id_pesanan = '$id'";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, balik ke halaman pesanan
        header("Location: pesanan.php?pesan=berhasil_terima");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: pesanan.php");
}
?>