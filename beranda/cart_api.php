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

function get_cart_response() {
    $cart = array_values($_SESSION['cart']);
    $cartCount = 0;
    $cartTotal = 0;

    foreach ($cart as $item) {
        $cartCount += $item['qty'];
        $cartTotal += $item['harga'] * $item['qty'];
    }

    $ongkir = $cartTotal > 0 ? 25000 : 0;
    $diskon = 0;
    $grandTotal = $cartTotal - $diskon + $ongkir;

    return [
        'success' => true,
        'cart' => $cart,
        'cart_count' => $cartCount,
        'cart_total' => $cartTotal,
        'diskon' => $diskon,
        'ongkir' => $ongkir,
        'grand_total' => $grandTotal,
        'is_empty' => empty($cart)
    ];
}

$action = $_POST['action'] ?? '';
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($action === 'add' && $id > 0 && isset($products[$id])) {
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

    echo json_encode(get_cart_response());
    exit;
}

if ($action === 'plus' && $id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
    echo json_encode(get_cart_response());
    exit;
}

if ($action === 'minus' && $id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] -= 1;

    if ($_SESSION['cart'][$id]['qty'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }

    echo json_encode(get_cart_response());
    exit;
}

if ($action === 'remove' && $id > 0 && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    echo json_encode(get_cart_response());
    exit;
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    echo json_encode(get_cart_response());
    exit;
}

if ($action === 'get') {
    echo json_encode(get_cart_response());
    exit;
}

echo json_encode([
    'success' => false,
    'message' => 'Aksi tidak valid'
]);