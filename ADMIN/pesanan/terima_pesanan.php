<?php
include "../../koneksi.php";

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $waktu_sekarang = date('Y-m-d H:i:s');

    // Query untuk update status dan waktu selesai
    $query = "UPDATE pesanan SET 
              status = 'diproses' 
              WHERE id_pesanan = '$id'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, balik ke halaman pesanan
        header("Location: pesanan.php?pesan=berhasil_terima");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: pesanan.php");
}
?>