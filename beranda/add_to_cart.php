<?php
session_start();
header('Content-Type: application/json');

$products = [
    1 => ['id'=>1,'nama'=>'Mie Ayam Hijau Polos','harga'=>10000,'gambar'=>'img/mie ayam polos.png'],
    2 => ['id'=>2,'nama'=>'Mie Ayam Hijau Ceker','harga'=>10000,'gambar'=>'img/mie ayam ceker.jpeg'],
    3 => ['id'=>3,'nama'=>'Mie Ayam Hijau Mentah','harga'=>12000,'gambar'=>'img/mie ayam mentah.jpeg'],
    4 => ['id'=>4,'nama'=>'Kerupuk','harga'=>1000,'gambar'=>'img/kerupuk.jpg'],
    5 => ['id'=>5,'nama'=>'Es Teh (Manis/Tawar)','harga'=>3000,'gambar'=>'img/es teh manis.jpg'],
    6 => ['id'=>6,'nama'=>'Teh Hangat','harga'=>3000,'gambar'=>'img/teh hangat.jpg'],
    7 => ['id'=>7,'nama'=>'Es Susu Coklat','harga'=>3000,'gambar'=>'img/Es-Coklat.jpg'],
    8 => ['id'=>8,'nama'=>'Es Nutrisari Jeruk Peras','harga'=>3000,'gambar'=>'img/es nutrisari.png'],
    9 => ['id'=>9,'nama'=>'Susu Hangat','harga'=>3000,'gambar'=>'img/susu hangat.jpg'],
    10 => ['id'=>10,'nama'=>'Mie Ayam Hijau Polos','harga'=>10000,'gambar'=>'img/mie ayam polos.png'],
    11 => ['id'=>11,'nama'=>'Es Teh (Manis/Tawar)','harga'=>3000,'gambar'=>'img/es teh manis.jpg'],
    12 => ['id'=>12,'nama'=>'Kerupuk','harga'=>1000,'gambar'=>'img/kerupuk.jpg'],
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id > 0 && isset($products[$id])) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $products[$id]['id'],
            'nama' => $products[$id]['nama'],
            'harga' => $products[$id]['harga'],
            'gambar' => $products[$id]['gambar'],
            'qty' => 1
        ];
    }
}

$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['qty'];
}

echo json_encode([
    'success' => true,
    'cart_count' => $cartCount
]);