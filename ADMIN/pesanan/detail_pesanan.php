<?php
include "../koneksi.php";
$id = $_GET['id'];

// Ambil data detail pesanan dan join dengan pelanggan
$query = mysqli_query($koneksi, "SELECT p.*, pel.nama_pelanggan, pel.no_hp, pel.alamat 
                                FROM pesanan p 
                                JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
                                WHERE p.id_pesanan = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .label-detail { font-weight: bold; color: #666; font-size: 0.9rem; }
        .value-detail { font-size: 1.1rem; color: #333; margin-bottom: 15px; }
        .header-green { background: #71b033; color: white; border-radius: 15px 15px 0 0; padding: 15px; }
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
                    <p class="value-detail"><?= $data['alamat']; ?></p>
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
                    <p class="label-detail">Total Tagihan</p>
                    <h3 class="text-success">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>