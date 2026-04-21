<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['qty'];
}

$orders = [
    [
        'status' => 'semua',
        'badge_text' => 'Menunggu Konfirmasi',
        'badge_class' => 'badge-yellow',
    ],
    [
        'status' => 'diproses',
        'badge_text' => 'Sedang Dibuat',
        'badge_class' => 'badge-green',
    ],
    [
        'status' => 'dikirim',
        'badge_text' => 'Dalam Pengantaran',
        'badge_class' => 'badge-green',
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Saya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="pesanan.css">
</head>
<body>
    <header class="site-header">
    <div class="container navbar">
      <a href="#" class="brand">
        <div class="logo-circle">
          <img src="assets/logo.jpeg" alt="Logo">
        </div>
        <div class="brand-name">
          <span class="brand-dark">MIE AYAM</span>
          <span class="brand-green">HIJAU</span>
        </div>
      </a>

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="nav-menu">
        <div class="nav-highlight"></div>

        <a href="beranda.php" class="nav-item active">Beranda</a>
        <a href="#" class="nav-item">Rekomendasi</a>
        <a href="#" class="nav-item">Pesanan</a>
      </div>

      <div class="nav-icons">
        <a href="javascript:void(0)" class="cart-box" id="cartToggle" aria-label="Keranjang">
          <i class="fa-solid fa-basket-shopping"></i>
          <span class="cart-count"><?= $cartCount; ?></span>
        </a>
        <a href="#" class="search-icon" aria-label="Pencarian">
          <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <a href="#" class="profile-icon" aria-label="Profil">
          <i class="fa-solid fa-user"></i>
        </a>
      </div>
    </div>
  </header>

  <div class="pesanan-page">
    <div class="pesanan-wrapper">
      <div class="page-title">
        <a href="../beranda.php" class="back-btn">←</a>
        <h1>Pesanan Saya</h1>
      </div>

      <div class="tab-box">
        <button class="tab-btn active" data-tab="semua">Semua</button>
        <button class="tab-btn" data-tab="diproses">Diproses</button>
        <button class="tab-btn" data-tab="dikirim">Dikirim</button>
      </div>

      <div class="order-panel">
        <?php foreach ($orders as $order): ?>
          <div class="order-card" data-status="<?= $order['status']; ?>">
            <div class="order-top">
              <div class="store-info">
                <img src="../assets/logo.png" alt="Logo Toko" class="store-logo">
                <span class="store-name">MIE AYAM HIJAU</span>
              </div>
              <span class="status-badge <?= $order['badge_class']; ?>">
                <?= $order['badge_text']; ?>
              </span>
            </div>

            <div class="order-middle">
              <img src="../assets/menu1.jpg" alt="Menu" class="menu-thumb">

              <div class="menu-info">
                <div class="menu-title">Mie Ayam Hijau Polos <span>x1</span></div>
                <div class="menu-price">Rp. 10.000</div>

                <div class="menu-sub">Es Teh (Manis) <span>x2</span></div>
                <div class="menu-price">Rp. 3.000</div>
              </div>
            </div>

            <div class="order-bottom">
              <div class="order-total">Total Pesanan: Rp. 41.000</div>
              <div class="paid-badge">LUNAS</div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script src="pesanan.js"></script>
</body>
</html>