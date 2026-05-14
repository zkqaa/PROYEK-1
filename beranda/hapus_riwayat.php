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

mysqli_query($conn, "UPDATE pesanan SET hidden_user = 1 WHERE id_pesanan = '$id'");

echo json_encode([
    'success' => true
]);