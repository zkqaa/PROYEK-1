<?php
session_start();
include "../../koneksi.php";

$cari   = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$tanggal_awal  = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$where = " WHERE 1=1 ";

if ($cari != '') {
    $cari_aman = mysqli_real_escape_string($koneksi, $cari);
    $where .= " AND p.id_pesanan LIKE '%$cari_aman%' ";
}

if ($status != '' && $status != 'Semua') {
    $status_aman = mysqli_real_escape_string($koneksi, $status);
    $where .= " AND p.status = '$status_aman' ";
}

if ($tanggal_awal != '' && $tanggal_akhir != '') {
    $where .= " AND DATE(p.tanggal)
                BETWEEN '$tanggal_awal'
                AND '$tanggal_akhir'";
}

$query = mysqli_query($koneksi, "
    SELECT * 
    FROM pesanan p
    $where
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
                    <img src="../img/logo.jpeg" alt="Logo" class="brand-logo">
                    <div class="brand-text">
                        <h2>MIE AYAM <span>HIJAU</span></h2>
                        <p>Admin Dashboard</p>
                    </div>
                </div>

                <nav class="menu-nav" style="margin-top:40px;">
                    <a href="../dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>

                    <a href="../menu.php" class="menu-item">
                        <i class="fa-solid fa-utensils"></i>
                        <span>Menu</span>
                    </a>

                    <a href="pesanan.php" class="menu-item active">
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
                <div class="admin-box" onclick="toggleAdminDropdown()">
                    <div class="admin-dropdown" id="adminDropdown">
                        <div class="admin-dropdown-avatar"><i class="fa-solid fa-user"></i></div>
                        <a href="../kelola_akun.php">Kelola Akun</a>
                        <a href="../../login/logout.php" class="logout">Keluar</a>
                    </div>
                    <div class="admin-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="admin-info">
                        <h4><?= $_SESSION['nama_lengkap'] ?? 'Admin'; ?></h4>
                        <p>Administrator</p>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-arrow" id="adminArrow"></i>
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
                        <input type="text"
                            name="cari"
                            placeholder="Cari ID Pesanan, Nama Pelanggan...."
                            value="<?= htmlspecialchars($cari); ?>"
                            onkeyup="cariPesanan(event)">
                    </div>

                    <div class="date-filter">
                        <div class="date-item">
                            <label>Dari Tanggal:</label>
                            <input type="date"
                                name="tanggal_awal"
                                value="<?= $tanggal_awal; ?>"
                                onchange="this.form.submit()">
                        </div>

                        <div class="date-item">
                            <label>Hingga</label>
                            <input type="date"
                                name="tanggal_akhir"
                                value="<?= $tanggal_akhir; ?>"
                                onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="order-filter-group">
                        <div class="order-filter">
                            <label>Status</label>
                            <select name="status" onchange="this.form.submit()">
                                <option value="Semua" <?= ($status == 'Semua' || $status == '') ? 'selected' : ''; ?>>
                                    Semua
                                </option>

                                <option value="Dalam_Antrian" <?= $status == 'Dalam_Antrian' ? 'selected' : ''; ?>>
                                    Baru
                                </option>

                                <option value="Diproses" <?= $status == 'Diproses' ? 'selected' : ''; ?>>
                                    Diproses
                                </option>

                                <option value="Dikirim" <?= $status == 'Dikirim' ? 'selected' : ''; ?>>
                                    Dikirim
                                </option>

                                <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : ''; ?>>
                                    Selesai
                                </option>

                                <option value="Dibatalkan" <?= $status == 'Dibatalkan' ? 'selected' : ''; ?>>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <a href="pesanan.php" class="btn-reset">Bersihkan Filter</a>
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
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id_pesanan']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                                        <td>
                                            <?php
                                            // Menyesuaikan class badge berdasarkan status di DB
                                            $status = trim($row['status']);

                                            $statusClass = 'status-baru';
                                            $statusText  = 'Baru';

                                            if ($status == 'Diproses') {
                                                $statusClass = 'status-proses';
                                                $statusText  = 'Diproses';
                                            }

                                            if ($status == 'Dikirim') {
                                                $statusClass = 'status-kirim';
                                                $statusText  = 'Dikirim';
                                            }

                                            if ($status == 'Selesai') {
                                                $statusClass = 'status-terima';
                                                $statusText  = 'Selesai';
                                            }

                                            if ($status == 'Dibatalkan') {
                                                $statusClass = 'status-tolak';
                                                $statusText  = 'Ditolak';
                                            }
                                            ?>
                                            <span class="status-badge <?= $statusClass; ?>">
                                                <?= htmlspecialchars($statusText); ?>
                                            </span>
                                        </td>
                                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                        <td><?= $row['tanggal']; ?></td>
                                        <td>
                                            <?php if (trim($row['status']) == 'Selesai'): ?>
                                                <?= date('d/m/Y H:i', strtotime($row['waktu_selesai'])); ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="aksi-group">
                                                <?php $statusSekarang = strtolower($row['status']);
                                                if (
                                                    !in_array($statusSekarang, ['selesai', 'dibatalkan'])
                                                ): ?>
                                                    <select class="status-select"
                                                        onchange="ubahStatus(this)">
                                                        <option value="">Ubah Status</option>
                                                        <option value="terima_pesanan.php?id=<?= $row['id_pesanan']; ?>">Diproses</option>
                                                        <option value="kirim_pesanan.php?id=<?= $row['id_pesanan']; ?>">Dikirim</option>
                                                        <option value="selesai_pesanan.php?id=<?= $row['id_pesanan']; ?>">Selesai</option>
                                                        <option value="tolak_pesanan.php?id=<?= $row['id_pesanan']; ?>">Batalkan</option>
                                                    </select>
                                                <?php endif; ?>
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

    <script>
        function toggleAdminDropdown() {
            var dd = document.getElementById('adminDropdown');
            var arrow = document.getElementById('adminArrow');
            dd.classList.toggle('show');
            arrow.classList.toggle('rotated');
        }
        document.addEventListener('click', function(e) {
            var box = document.querySelector('.admin-box');
            if (box && !box.contains(e.target)) {
                document.getElementById('adminDropdown').classList.remove('show');
                document.getElementById('adminArrow').classList.remove('rotated');
            }
        });
    </script>
    <script>
        function ubahStatus(select) {
            const url = select.value;
            if (url) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        function cariPesanan(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                event.target.form.submit();
            }
        }
    </script>

</body>

</html>