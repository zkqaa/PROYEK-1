<?php
session_start();
include '../koneksi.php';

if (!isset($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID pesanan tidak ditemukan'
    ]);
    exit;
}

$id = $_POST['id'];

// hanya ubah jika masih Dalam_Antrian
mysqli_query($conn, "
    UPDATE pesanan 
    SET status='Dibatalkan'
    WHERE id_pesanan='$id'
    AND status='Dalam_Antrian'
");

echo json_encode([
    'success' => true
]);