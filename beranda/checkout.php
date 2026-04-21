<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function rupiah($angka)
{
    return 'Rp. ' . number_format($angka, 0, ',', '.');
}

$totalHarga = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalHarga += $item['harga'] * $item['qty'];
}

$ongkir = $totalHarga > 0 ? 25000 : 0;
$totalBayar = $totalHarga + $ongkir;

$namaPelanggan = "Nama pelanggan";
$nomorPelanggan = "(+62) 812 3456 7890";
$alamatUtama = "Jl. Raya Lohbener Lama No.68, Legok, Kec. Lohbener, Kabupaten Indramayu, Jawa Barat 45252";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="checkout.css">
</head>
<body>

<div class="checkout-wrapper">

    <a href="beranda.php" class="checkout-title">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Checkout!</span>
    </a>

    <div class="checkout-card alamat-card">
        <div class="checkout-card-header alamat-header">
            <div class="alamat-left">
                <span class="icon-box">🚚</span>
                <div>
                    <h3>Pengantaran</h3>
                    <small>Dianter dalam 0 menit</small>
                </div>
            </div>

            <button type="button" class="ganti-btn" id="btnTerapkanAlamat">Terapkan</button>
        </div>

        <div class="alamat-info">
            <p class="nama">
                <strong id="namaPelangganTampil"><?= $namaPelanggan; ?></strong>
                <span id="nomorPelangganTampil"><?= $nomorPelanggan; ?></span>
            </p>
            <p class="alamat-text" id="alamatTampil"><?= $alamatUtama; ?></p>
        </div>

        <div class="map-box">
            <iframe
                id="mapFrame"
                src="https://maps.google.com/maps?q=<?= urlencode($alamatUtama); ?>&t=&z=15&ie=UTF8&iwloc=&output=embed"
                loading="lazy"
                allowfullscreen>
            </iframe>
        </div>

        <div class="input-group">
            <label for="inputNama">Nama pelanggan</label>
            <input type="text" id="inputNama" value="<?= htmlspecialchars($namaPelanggan); ?>" placeholder="Nama penerima">
        </div>

        <div class="input-group">
            <label for="inputNomor">Nomor HP</label>
            <input type="text" id="inputNomor" value="<?= htmlspecialchars($nomorPelanggan); ?>" placeholder="08xxxxxxxxxx">
        </div>

        <div class="input-group">
            <label for="inputAlamat">Alamat utama</label>
            <textarea id="inputAlamat" placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($alamatUtama); ?></textarea>
        </div>

        <div class="input-group">
            <label for="inputDetailAlamat">Detail alamat (optional)</label>
            <input type="text" id="inputDetailAlamat" placeholder="Belakang masjid, pagar hitam">
        </div>

        <div class="input-group">
            <label for="inputCatatan">Catatan pengantaran</label>
            <textarea id="inputCatatan" placeholder="Cth: Tolong taruh di pager/pintu"></textarea>
        </div>
    </div>

    <div class="checkout-card">
        <div class="checkout-card-header">
            <span class="icon-box">🍱</span>
            <h3>Pesananmu</h3>
        </div>

        <div id="checkoutOrderBody">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-order">
                    Belum ada pesanan.
                </div>
            <?php else: ?>
                <div class="order-list">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="order-item">
                            <img src="<?= $item['gambar']; ?>" alt="<?= htmlspecialchars($item['nama']); ?>">

                            <div class="order-info">
                                <h4><?= htmlspecialchars($item['nama']); ?></h4>
                                <p><?= rupiah($item['harga']); ?></p>

                                <div class="order-qty">
                                    <button type="button" class="qty-btn order-action-btn" data-action="minus" data-id="<?= $item['id']; ?>">-</button>
                                    <strong><?= $item['qty']; ?></strong>
                                    <button type="button" class="qty-btn order-action-btn" data-action="plus" data-id="<?= $item['id']; ?>">+</button>
                                </div>
                            </div>

                            <button type="button" class="delete-btn order-action-btn" data-action="remove" data-id="<?= $item['id']; ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="add-more-box">
                    <div>
                        <small>Apakah ada tambahan menu?</small><br>
                        <span>Masih bisa menambah menu lainnya loh</span>
                    </div>
                    <a href="beranda.php" class="add-more-btn">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="checkout-card payment-card">
        <div class="checkout-card-header">
            <span class="icon-box">💳</span>
            <h3>Pembayaran</h3>
        </div>

        <div id="paymentSummary">
            <div class="payment-row">
                <span>Harga</span>
                <span><?= number_format($totalHarga, 0, ',', '.'); ?></span>
            </div>

            <div class="payment-row">
                <span>Biaya penanganan dan pengiriman</span>
                <span><?= number_format($ongkir, 0, ',', '.'); ?></span>
            </div>

            <hr>

            <div class="payment-total">
                <strong>Total Pembayaran</strong>
                <strong><?= number_format($totalBayar, 0, ',', '.'); ?></strong>
            </div>
        </div>
    </div>

    <div class="confirm-box">
        <label>
            <input type="checkbox" id="confirmOrder">
            Konfirmasi Pesanan <span>*</span>
            <small>(Pastikan pesanan sudah benar)</small>
        </label>
    </div>

    <button class="buy-btn" id="btnBeli" disabled>
        Beli
    </button>

</div>

<script src="checkout.js"></script>
<script src="page-transition.js"></script>
</body>
</html>