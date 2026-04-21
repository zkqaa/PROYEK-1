<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function get_checkout_cart_response() {
    $cart = array_values($_SESSION['cart']);
    $cartTotal = 0;

    foreach ($cart as $item) {
        $cartTotal += $item['harga'] * $item['qty'];
    }

    $ongkir = $cartTotal > 0 ? 25000 : 0;
    $grandTotal = $cartTotal + $ongkir;

    return [
        'success' => true,
        'cart' => $cart,
        'cart_total' => $cartTotal,
        'ongkir' => $ongkir,
        'grand_total' => $grandTotal,
        'is_empty' => empty($cart)
    ];
}

$action = $_POST['action'] ?? '';
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($action === 'plus' && $id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
    echo json_encode(get_checkout_cart_response());
    exit;
}

if ($action === 'minus' && $id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] -= 1;

    if ($_SESSION['cart'][$id]['qty'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }

    echo json_encode(get_checkout_cart_response());
    exit;
}

if ($action === 'remove' && $id > 0 && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    echo json_encode(get_checkout_cart_response());
    exit;
}

if ($action === 'get') {
    echo json_encode(get_checkout_cart_response());
    exit;
}

echo json_encode([
    'success' => false,
    'message' => 'Aksi tidak valid'
]);