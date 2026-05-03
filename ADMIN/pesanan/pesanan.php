<?php
include "../koneksi.php";

$cari   = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$where = " WHERE 1=1 ";

if ($cari != '') {
    $cari_aman = mysqli_real_escape_string($koneksi, $cari);
    $where .= " AND (p.id_pesanan LIKE '%$cari_aman%' OR pl.nama_pelanggan LIKE '%$cari_aman%') ";
}

if ($status != '' && $status != 'Semua') {
    $status_aman = mysqli_real_escape_string($koneksi, $status);
    $where .= " AND p.status = '$status_aman' ";
}

$query = mysqli_query($koneksi, "
    SELECT p.*, pel.nama_pelanggan 
    FROM pesanan p
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    ORDER BY p.id_pesanan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Pesanan</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php if (isset($_GET['pesan'])): ?>
    <script>
        <?php if ($_GET['pesan'] == 'berhasil_terima'): ?>
            alert('Pesanan berhasil diterima!');
        <?php elseif ($_GET['pesan'] == 'berhasil_tolak'): ?>
            alert('Pesanan telah ditolak.');
        <?php endif; ?>
    </script>
<?php endif; ?>

<div class="layout">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <img src="../logo.png" alt="Logo" class="brand-logo">
                <div class="brand-text">
                    <h2>MIE AYAM <span>HIJAU</span></h2>
                    <p>Admin Dashboard</p>
                </div>
            </div>

            <nav class="menu-nav" style="margin-top:40px;">
                <a href="../dashboard.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="../menu.php" class="menu-item">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Menu</span>
                </a>

                <a href="index.php" class="menu-item active">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span>Pesanan</span>
                </a>

                <a href="../laporan/laporan.php" class="menu-item">
                    <i class="fa-regular fa-file-lines"></i>
                    <span>Laporan</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-footer">
            <div class="admin-box">
                <div class="admin-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="admin-info">
                    <h4>Admin</h4>
                    <p>Administrator</p>
                </div>
                <i class="fa-solid fa-chevron-down admin-arrow"></i>
            </div>
        </div>

        <div class="leaf-decor">
            <span class="leaf leaf1"></span>
            <span class="leaf leaf2"></span>
            <span class="leaf leaf3"></span>
            <span class="leaf leaf4"></span>
            <span class="leaf leaf5"></span>
        </div>
    </aside>

    <main class="content">
        <section class="order-card">
            <h1 class="order-title">List Pesanan</h1>

            <form method="GET" class="order-toolbar">
                <div class="order-search">
                    <input type="text" name="cari" placeholder="Cari ID Pesanan, Nama Pelanggan...." value="<?= htmlspecialchars($cari); ?>">
                </div>

                <div class="order-filter-group">
                    <div class="order-filter">
                        <label>Status</label>
                        <select name="status">
                            <option value="Semua" <?= ($status == 'Semua' || $status == '') ? 'selected' : ''; ?>>Semua</option>
                            <option value="Baru" <?= $status == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                            <option value="Diproses" <?= $status == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="Diterima" <?= $status == 'Diterima' ? 'selected' : ''; ?>>Diterima</option>
                            <option value="Ditolak" <?= $status == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>

                    <a href="index.php" class="btn-reset">Bersihkan Filter</a>
                    <button type="submit" class="btn-search">CARI PESANAN</button>
                </div>
            </form>

            <div class="order-table-wrap">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>ID_Pesanan</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Total Harga</th>
                            <th>Tanggal Pesan</th>
                            <th>Tanggal Diantar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id_pesanan']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                                    <td>
                                        <?php
                                        // Menyesuaikan class badge berdasarkan status di DB
                                        $statusClass = 'status-baru';
                                        $status = $row['status'];
                                        if ($status == 'Diproses') $statusClass = 'status-proses';
                                        if ($status == 'Selesai' || $status == 'Diterima') $statusClass = 'status-terima';
                                        if ($status == 'Ditolak') $statusClass = 'status-tolak';
                                        ?>
                                        <span class="status-badge <?= $statusClass; ?>">
                                            <?= htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td><?= !empty($row['tanggal']) ? date('d/m/Y H:i', strtotime($row['tanggal'])) : '-'; ?></td>
                                    <td><?= !empty($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?></td>
                                    <td>
                                        <div class="aksi-group">
                                            <a href="terima_pesanan.php?id=<?= urlencode($row['id_pesanan']); ?>" class="btn-aksi btn-terima" title="Terima"><i class="fa-solid fa-check"></i></a>
                                            <a href="tolak_pesanan.php?id=<?= urlencode($row['id_pesanan']); ?>" class="btn-aksi btn-tolak" title="Tolak"><i class="fa-solid fa-xmark"></i></a>
                                            <a href="detail_pesanan.php?id=<?= urlencode($row['id_pesanan']); ?>" class="btn-aksi btn-detail" title="Detail">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 20px;">Data pesanan tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>