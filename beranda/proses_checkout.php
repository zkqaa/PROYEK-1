<?php
session_set_cookie_params([
    'path' => '/'
]);
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_pelanggan'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Silakan login terlebih dahulu'
    ]);
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Keranjang kosong'
    ]);
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$alamat = $_POST['alamat'] ?? '';
$detail_alamat = $_POST['detail_alamat'] ?? '';
$catatan = $_POST['catatan'] ?? '';
$metode_pengiriman = strtolower(trim($_POST['metode_pengiriman'] ?? 'antar'));
$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'cod';

$total = 0;
$ongkir = 0;

foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['qty'];
}

$ongkir = ($total < 50000 && $total > 0) ? 3000 : 0;
$grandTotal = $total + $ongkir;

//Simpan ke pesanan
$grandTotal = $total + $ongkir;

mysqli_query($conn, "INSERT INTO pesanan (id_pelanggan, tanggal, total_harga, ongkir,
            alamat_pengiriman, detail_alamat, catatan, metode_pengiriman, metode_pembayaran)
            VALUES ('$id_pelanggan', NOW(), '$grandTotal', '$ongkir', '$alamat',
            '$detail_alamat', '$catatan', '$metode_pengiriman', '$metode_pembayaran')");

$id_pesanan = mysqli_insert_id($conn);

//Simpan detail
foreach ($_SESSION['cart'] as $item) {
    $subtotal = $item['harga'] * $item['qty'];

    mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal)
                VALUES ('$id_pesanan', '{$item['id']}', '{$item['qty']}', '$subtotal')");
}

//Kosongkan cart
$_SESSION['cart'] = [];

echo json_encode([
    'success' => true
]);
