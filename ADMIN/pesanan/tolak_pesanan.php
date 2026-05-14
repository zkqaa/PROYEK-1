<?php
include "../../koneksi.php";

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query untuk update status menjadi Ditolak
    $query = "UPDATE pesanan SET 
              status = 'dibatalkan' 
              WHERE id_pesanan = '$id'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, balik ke halaman pesanan
        header("Location: pesanan.php?pesan=berhasil_tolak");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: pesanan.php");
}
?>