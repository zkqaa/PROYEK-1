<?php
include "../koneksi.php";

// 1. Ambil data dari database (Gunakan JOIN untuk mendapatkan nama pelanggan)
$query = "SELECT p.*, pel.nama_pelanggan AS nama 
          FROM pesanan p 
          JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
          ORDER BY p.tanggal DESC";
$result = mysqli_query($koneksi, $query);

// 2. Hitung Total Pendapatan & Total Pesanan secara dinamis
$sql_stats = "SELECT SUM(total_harga) as total_pendapatan, COUNT(*) as total_pesanan FROM pesanan";
$stats_res = mysqli_query($koneksi, $sql_stats);
$stats = mysqli_fetch_assoc($stats_res);

$totalPendapatan = $stats['total_pendapatan'] ?? 0;
$totalPesanan = $stats['total_pesanan'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan - Mie Ayam Hijau</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="laporan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <img src="../logo.png" class="brand-logo">
                <div class="brand-text">
                    <h2>MIE AYAM <span>HIJAU</span></h2>
                    <p>Admin Dashboard</p>
                </div>
            </div>
            <nav class="menu-nav" style="margin-top:40px;">
                <a href="../dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
                <a href="../menu.php" class="menu-item"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
                <a href="../pesanan/pesanan.php" class="menu-item"><i class="fa-solid fa-basket-shopping"></i><span>Pesanan</span></a>
                <a href="laporan.php" class="menu-item active"><i class="fa-regular fa-file-lines"></i><span>Laporan</span></a>
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
        <section class="content-card">
            <h1>Laporan Pesanan</h1>

            <div class="laporan-top">
                <div class="lap-card">
                    <p>Total Pendapatan</p>
                    <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></h3>
                </div>
                <div class="lap-card">
                    <p>Total Pesanan</p>
                    <h3><?= $totalPesanan; ?></h3>
                </div>
                <div class="filter">
                    <label>Dari:</label> <input type="date">
                    <label>Hingga:</label> <input type="date">
                    <button class="btn-export" onclick="exportExcel()">Ekspor (Excel)</button>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID_Pesanan</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Total Harga</th>
                            <th>Tanggal Pesan</th>
                            <th>Waktu Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        while($row = mysqli_fetch_assoc($result)): 
                            // Logika warna status
                            $statusClass = (strtolower($row['status']) == 'selesai') ? 'status-green' : 'status-yellow';
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['id_pesanan']; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><span class="status <?= $statusClass ?>"><?= $row['status']; ?></span></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                            <td><?= ($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?></td>
                            <td>
                                <button class="btn-detail" onclick="showDetail(
                                    '<?= $row['id_pesanan']; ?>',
                                    '<?= $row['nama']; ?>',
                                    '<?= $row['status']; ?>',
                                    '<?= number_format($row['total_harga'], 0, ',', '.'); ?>',
                                    '<?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?>',
                                    '<?= ($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?>'
                                )">Lihat Detail</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div id="modalDetail" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Detail Pesanan</h2>
        <div class="detail-grid">
            <p><strong>ID Pesanan:</strong> <span id="d_id"></span></p>
            <p><strong>Nama:</strong> <span id="d_nama"></span></p>
            <p><strong>Status:</strong> <span id="d_status"></span></p>
            <p><strong>Total Harga:</strong> Rp <span id="d_total"></span></p>
            <p><strong>Tanggal Pesan:</strong> <span id="d_tanggal"></span></p>
            <p><strong>Waktu Selesai:</strong> <span id="d_selesai"></span></p>
        </div>
    </div>
</div>

<script>
function showDetail(id, nama, status, total, tanggal, selesai) {
    document.getElementById("d_id").innerText = id;
    document.getElementById("d_nama").innerText = nama;
    document.getElementById("d_status").innerText = status;
    document.getElementById("d_total").innerText = total;
    document.getElementById("d_tanggal").innerText = tanggal;
    document.getElementById("d_selesai").innerText = selesai;
    document.getElementById("modalDetail").style.display = "flex";
}
function closeModal() { document.getElementById("modalDetail").style.display = "none"; }
</script>

</body>
</html>