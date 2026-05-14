<?php
include "../../koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($conn, "SELECT p.*, pel.nama_pelanggan, pel.no_hp
                                FROM pesanan p
                                JOIN pelanggan pel
                                ON p.id_pelanggan = pel.id_pelanggan
                                WHERE p.id_pesanan = '$id'");

$data = mysqli_fetch_assoc($query);

$detailQuery = mysqli_query($conn, "SELECT dp.*, m.nama_menu, m.gambar, m.harga
                                        FROM detail_pesanan dp
                                        JOIN menu m ON dp.id_menu = m.id_menu
                                        WHERE dp.id_pesanan = '$id'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .label-detail {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
        }

        .value-detail {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
        }

        .header-green {
            background: #71b033;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 15px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Pesanan: <span class="text-success">#<?= $id; ?></span></h2>
            <a href="pesanan.php" class="btn btn-outline-secondary">Kembali ke Daftar</a>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="header-green">
                        <h5 class="mb-0">Info Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <p class="label-detail">Nama Pelanggan</p>
                        <p class="value-detail"><?= $data['nama_pelanggan'] ?? '-'; ?></p>

                        <p class="label-detail">No. HP</p>
                        <p class="value-detail"><?= $data['no_hp']; ?></p>

                        <p class="label-detail">Alamat Pengiriman</p>
                        <p class="value-detail"><?= $data['alamat_pengiriman']; ?></p>

                        <p class="label-detail">Detail Alamat</p>
                        <p class="value-detail">
                            <?= $data['detail_alamat'] ?? '-'; ?>
                        </p>

                        <p class="label-detail">Catatan Pesanan</p>
                        <p class="value-detail">
                            <?= $data['catatan'] ?? '-'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="header-green">
                        <h5 class="mb-0">Info Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p class="label-detail">Status</p>
                                <span class="badge bg-success p-2 px-3"><?= $data['status']; ?></span>
                            </div>
                            <div class="col-6">
                                <p class="label-detail">Metode Bayar</p>
                                <p class="value-detail"><?= $data['metode_pembayaran'] ?? 'Tunai'; ?></p>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Detail Menu Pesanan</h5>
                        <?php while ($item = mysqli_fetch_assoc($detailQuery)): ?>
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <img src="../img/<?= $item['gambar']; ?>"
                                    width="80"
                                    height="80"
                                    style="object-fit:cover; border-radius:12px;">

                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <?= $item['nama_menu']; ?>
                                    </h6>

                                    <p class="mb-1">
                                        Jumlah:
                                        <?= $item['jumlah']; ?>
                                    </p>

                                    <p class="mb-1">
                                        Harga:
                                        Rp <?= number_format($item['harga'], 0, ',', '.'); ?>
                                    </p>

                                    <strong>
                                        Subtotal:
                                        Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                                    </strong>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>
                                Rp <?= number_format($data['total_harga'] - $data['ongkir'], 0, ',', '.'); ?>
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkir</span>
                            <strong>
                                Rp <?= number_format($data['ongkir'], 0, ',', '.'); ?>
                            </strong>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <strong>Total Pembayaran</strong>
                            <h3 class="text-success mb-0">
                                Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>