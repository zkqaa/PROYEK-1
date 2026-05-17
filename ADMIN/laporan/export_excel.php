<?php
include "../../koneksi.php";

// Ambil parameter tanggal
$dari   = isset($_GET['dari'])   ? $_GET['dari']   : '';
$hingga = isset($_GET['hingga']) ? $_GET['hingga'] : '';

// Bangun query dengan filter tanggal opsional
$where = "WHERE 1=1";
if (!empty($dari)) {
    $dari_aman = mysqli_real_escape_string($koneksi, $dari);
    $where .= " AND DATE(p.tanggal) >= '$dari_aman'";
}
if (!empty($hingga)) {
    $hingga_aman = mysqli_real_escape_string($koneksi, $hingga);
    $where .= " AND DATE(p.tanggal) <= '$hingga_aman'";
}

$query = "SELECT 
            p.id_pesanan,
            u.nama_lengkap AS nama,
            p.status,
            p.total_harga,
            p.tanggal,
            p.waktu_selesai
          FROM pesanan p
          JOIN users u ON p.id_pelanggan = u.id_user
          $where
          ORDER BY p.tanggal DESC";
$result = mysqli_query($koneksi, $query);

// Nama file ekspor
$namaFile = 'Laporan_Pesanan';
if (!empty($dari))   $namaFile .= '_' . $dari;
if (!empty($hingga)) $namaFile .= '_sd_' . $hingga;
$namaFile .= '.xls';

// Set header untuk download Excel (format HTML-table compatible dengan .xls)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

// Hitung total
$total_pendapatan = 0;
$total_pesanan    = 0;
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $total_pendapatan += $row['total_harga'];
    $total_pesanan++;
    $rows[] = $row;
}
?>
<html>
<head>
<meta charset="UTF-8">
<style>
    table  { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; font-size: 13px; }
    th     { background: #6aaa2a; color: #fff; font-weight: bold; }
    .header-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
    .summary td  { font-weight: bold; background: #f0f0f0; }
    .status-selesai   { color: #27ae60; }
    .status-lain      { color: #e67e22; }
</style>
</head>
<body>

<p class="header-title">Laporan Pesanan - Mie Ayam Hijau</p>
<?php if (!empty($dari) || !empty($hingga)): ?>
<p>Periode: <?= !empty($dari) ? date('d/m/Y', strtotime($dari)) : '-' ?> s/d <?= !empty($hingga) ? date('d/m/Y', strtotime($hingga)) : '-' ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr class="summary">
            <td colspan="3">Total Pendapatan</td>
            <td>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            <td colspan="3">Total Pesanan: <?= $total_pesanan ?></td>
        </tr>
        <tr>
            <th>No</th>
            <th>ID Pesanan</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Total Harga</th>
            <th>Tanggal Pesan</th>
            <th>Waktu Selesai</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($rows as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['id_pesanan']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td class="<?= (strtolower($row['status']) == 'selesai') ? 'status-selesai' : 'status-lain' ?>">
                <?= htmlspecialchars($row['status']) ?>
            </td>
            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td><?= !empty($row['tanggal']) ? date('d/m/Y H:i', strtotime($row['tanggal'])) : '-' ?></td>
            <td><?= !empty($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-' ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
        <tr><td colspan="7" style="text-align:center;">Tidak ada data pesanan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
