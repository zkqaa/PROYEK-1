<?php
include '../koneksi.php';

function rupiah($angka){
  return 'Rp. ' . number_format($angka, 0, ',', '.');
}

function badgeStatus($status){
  if ($status == 'semua') return ['Menunggu Konfirmasi', 'badge-yellow'];
  if ($status == 'diproses') return ['Sedang Dibuat', 'badge-green'];
  return ['Dalam Pengantaran', 'badge-green'];
}

$data = mysqli_query($conn, "SELECT * FROM pesanan1 ORDER BY id_pesanan DESC");

while ($order = mysqli_fetch_assoc($data)):
  [$badgeText, $badgeClass] = badgeStatus($order['status']);
?>
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