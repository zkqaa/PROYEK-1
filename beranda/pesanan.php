<?php
session_start();
include '../koneksi.php';

function rupiah($angka){
  return 'Rp. ' . number_format($angka, 0, ',', '.');
}

function badgeStatus($status){
  if ($status == 'semua') {
    return ['Menunggu Konfirmasi', 'badge-yellow'];
  } elseif ($status == 'diproses') {
    return ['Sedang Dibuat', 'badge-green'];
  } else {
    return ['Dalam Pengantaran', 'badge-green'];
  }
}

$dataPesanan = mysqli_query($conn, "SELECT * FROM pesanan1 ORDER BY id_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Saya</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="pesanan.css">
</head>
<body>

<main class="pesanan-page">
  <div class="pesanan-wrapper">
    <div class="page-title">
      <a href="beranda.php" class="page-title">
        <span class="back-btn">←</span>
        <h1>Pesanan Saya</h1>
      </a>
    </div>

    <div class="order-box">
      <div class="tab-box">
        <button class="tab-btn active" data-tab="semua">Semua</button>
        <button class="tab-btn" data-tab="diproses">Diproses</button>
        <button class="tab-btn" data-tab="dikirim">Dikirim</button>
      </div>

      <div class="order-panel" id="orderPanel">
        <?php if (mysqli_num_rows($dataPesanan) > 0): ?>
          <?php while ($order = mysqli_fetch_assoc($dataPesanan)): ?>
            <?php [$badgeText, $badgeClass] = badgeStatus($order['status']); ?>

            <div class="order-card" data-status="<?= $order['status']; ?>">
              <div class="order-top">
                <div class="store-info">
                  <img src="assets/logo.jpeg" alt="Logo Toko" class="store-logo">
                  <span class="store-name">MIE AYAM HIJAU</span>
                </div>

                <span class="status-badge <?= $badgeClass; ?>">
                  <?= $badgeText; ?>
                </span>
              </div>

              <div class="order-middle">
                <img src="<?= htmlspecialchars($order['gambar']); ?>" alt="Menu" class="menu-thumb">

                <div class="menu-info">
                  <div class="menu-title">
                    <?= htmlspecialchars($order['nama_menu']); ?>
                    <span>x<?= $order['qty_menu']; ?></span>
                  </div>
                  <div class="menu-price"><?= rupiah($order['harga_menu']); ?></div>

                  <div class="menu-sub">
                    <?= htmlspecialchars($order['nama_minuman']); ?>
                    <span>x<?= $order['qty_minuman']; ?></span>
                  </div>
                  <div class="menu-price"><?= rupiah($order['harga_minuman']); ?></div>
                </div>
              </div>

              <div class="order-bottom">
                <div class="order-total">
                  Total Pesanan: <strong><?= rupiah($order['total']); ?></strong>
                </div>

                <div class="paid-badge">
                  <?= $order['status_bayar'] == 'lunas' ? 'LUNAS' : 'BELUM LUNAS'; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="empty-text">Belum ada pesanan.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<script src="pesanan.js?v=<?= time(); ?>"></script>
</body>
</html>