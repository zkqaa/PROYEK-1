<?php
session_start();

include "../koneksi.php";

$page = "beranda";

/* STATISTIK */
$totalMenu = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM menu
"))['total'];

$totalPesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM pesanan
"))['total'];

$totalSelesai = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM pesanan WHERE status = 'Selesai'
"))['total'];

$totalPendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COALESCE(SUM(total_harga), 0) AS total 
    FROM pesanan 
    WHERE status = 'Selesai'
"))['total'];

$rataPenjualan = ($totalPesanan > 0) ? $totalPendapatan / $totalPesanan : 0;

/* PESANAN TERBARU */
$statusFilter = $_GET['status'] ?? '';
$weekly = $_GET['weekly'] ?? '';

$queryOrderSQL = "
    SELECT *
    FROM pesanan p
";

$where = [];

if ($statusFilter != '') {
    $where[] = "p.status = '$statusFilter'";
}

if ($weekly == '1') {
    $where[] = "
        DATE(p.tanggal)
        >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ";
}

if (count($where) > 0) {
    $queryOrderSQL .= "
        WHERE " . implode(" AND ", $where);
}

$queryOrderSQL .= "
    ORDER BY p.id_pesanan DESC
    LIMIT 5
";

$queryOrders = mysqli_query($koneksi, $queryOrderSQL);

/* MENU TERLARIS */
$queryMenus = mysqli_query($koneksi, "
    SELECT 
        m.id_menu,
        m.nama_menu,
        m.harga,
        m.gambar,
        COALESCE(SUM(dp.jumlah), 0) AS terjual
    FROM menu m
    LEFT JOIN detail_pesanan dp ON m.id_menu = dp.id_menu
    GROUP BY m.id_menu, m.nama_menu, m.harga, m.gambar
    ORDER BY terjual DESC
    LIMIT 5
");

/* GRAFIK PENJUALAN 7 HARI TERAKHIR */
$queryGrafik = mysqli_query($koneksi, "
    SELECT 
        DATE(tanggal) AS tanggal,
        SUM(total_harga) AS total
    FROM pesanan
    WHERE status != 'Dibatalkan'
    GROUP BY DATE(tanggal)
    ORDER BY tanggal ASC
    LIMIT 7
");

$labelGrafik = [];
$dataGrafik = [];

while ($grafik = mysqli_fetch_assoc($queryGrafik)) {
    $labelGrafik[] = date('d M', strtotime($grafik['tanggal']));
    $dataGrafik[] = (int)$grafik['total'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Mie Ayam Hijau</title>

    <!-- CSS sidebar sama seperti menu, pesanan, laporan -->
    <link rel="stylesheet" href="style.css">

    <!-- CSS khusus dashboard -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

    <div class="layout">
        <aside class="sidebar">
            <div>
                <div class="brand">
                    <img src="img/logo.jpeg" alt="Logo" class="brand-logo">
                    <div class="brand-text">
                        <h2>MIE AYAM <span>HIJAU</span></h2>
                        <p>Admin Dashboard</p>
                    </div>
                </div>

                <nav class="menu-nav" style="margin-top:40px;">
                    <a href="dashboard.php" class="menu-item active">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="menu.php" class="menu-item">
                        <i class="fa-solid fa-utensils"></i>
                        <span>Menu</span>
                    </a>

                    <a href="pesanan/pesanan.php" class="menu-item">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <span>Pesanan</span>
                    </a>

                    <a href="laporan/laporan.php" class="menu-item">
                        <i class="fa-regular fa-file-lines"></i>
                        <span>Laporan</span>
                    </a>
                </nav>
            </div>

        <div class="sidebar-footer">
            <div class="admin-box" onclick="toggleAdminDropdown()">
                <!-- Dropdown -->
                <div class="admin-dropdown" id="adminDropdown">
                    <div class="admin-dropdown-avatar"><i class="fa-solid fa-user"></i></div>
                    <a href="kelola_akun.php">Kelola Akun</a>
                    <a href="../login/logout.php" class="logout">Keluar</a>
                </div>
                <div class="admin-avatar"><i class="fa-solid fa-user"></i></div>
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

        <main class="content dashboard-content">
            <section class="dashboard-card">

                <section class="hero-grid">

                    <!-- TOTAL PENDAPATAN -->
                    <div class="income-card">
                        <div class="income-overlay"></div>

                        <div class="income-content">
                            <div class="income-icon">
                                <i class="ri-wallet-3-fill"></i>
                            </div>

                            <p class="income-title">Total Pendapatan</p>

                            <h2>
                                Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?>
                            </h2>

                            <span>
                                Total pendapatan dari pesanan yang tidak dibatalkan.
                            </span>
                        </div>
                    </div>

                    <!-- CHART -->
                    <div class="sales-chart-card">

                        <div class="chart-header">
                            <div>
                                <h2>Chart Order</h2>
                                <p>Laporan order masuk 7 hari terakhir</p>
                            </div>

                            <button class="chart-report-btn">
                                <i class="ri-download-line"></i>
                                Save Report
                            </button>
                        </div>

                        <div class="chart-wrapper">

                            <div class="chart-canvas">
                                <canvas id="salesChart"></canvas>
                            </div>

                            <div class="chart-side">
                                <span>Total Order</span>
                                <h3><?= $totalPesanan; ?></h3>
                            </div>

                        </div>

                    </div>

                </section>

                <section class="stats">
                    <div class="stat-card green">
                        <i class="ri-restaurant-2-fill"></i>
                        <div>
                            <p>Total Menu</p>
                            <h3><?= $totalMenu; ?></h3>
                            <span>menu tersedia</span>
                        </div>
                    </div>

                    <div class="stat-card orange">
                        <i class="ri-clipboard-fill"></i>
                        <div>
                            <p>Total Pesanan</p>
                            <h3><?= $totalPesanan; ?></h3>
                            <span>pesanan masuk</span>
                        </div>
                    </div>

                    <div class="stat-card blue">
                        <i class="ri-checkbox-circle-fill"></i>
                        <div>
                            <p>Pesanan Selesai</p>
                            <h3><?= $totalSelesai; ?></h3>
                            <span>pesanan selesai</span>
                        </div>
                    </div>

                    <div class="stat-card purple">
                        <i class="ri-money-dollar-circle-fill"></i>
                        <div>
                            <p>Rata-rata Penjualan</p>
                            <h3>Rp <?= number_format($rataPenjualan, 0, ',', '.'); ?></h3>
                            <span>per pesanan</span>
                        </div>
                    </div>
                </section>

                <section class="content-grid">
                    <div class="panel orders-panel">
                        <div class="panel-header">
                            <h2>Orders</h2>
                            <div class="panel-actions">
                                <?php
                                if ($weekly == '1') {
                                    $weeklyLink = "dashboard.php";
                                    if ($statusFilter != '') {
                                        $weeklyLink .= "?status=" . $statusFilter;
                                    }
                                } else {
                                    $weeklyLink = "dashboard.php?weekly=1";
                                    if ($statusFilter != '') {
                                        $weeklyLink .= "&status=" . $statusFilter;
                                    }
                                }
                                ?>
                                
                                <form method="GET" class="dashboard-filter">

                                    <div class="filter-box">

                                        <i class="ri-filter-3-line"></i>

                                        <select name="status"
                                            onchange="this.form.submit()">

                                            <option value="">Semua</option>

                                            <option value="Dalam_Antrian"
                                                <?= ($statusFilter == 'Dalam_Antrian') ? 'selected' : ''; ?>>
                                                Baru
                                            </option>

                                            <option value="Diproses"
                                                <?= ($statusFilter == 'Diproses') ? 'selected' : ''; ?>>
                                                Diproses
                                            </option>

                                            <option value="Dikirim"
                                                <?= ($statusFilter == 'Dikirim') ? 'selected' : ''; ?>>
                                                Dikirim
                                            </option>

                                            <option value="Selesai"
                                                <?= ($statusFilter == 'Selesai') ? 'selected' : ''; ?>>
                                                Selesai
                                            </option>

                                            <option value="Dibatalkan"
                                                <?= ($statusFilter == 'Dibatalkan') ? 'selected' : ''; ?>>
                                                Dibatalkan
                                            </option>

                                        </select>

                                    </div>

                                </form>
                                <a href="<?= $weeklyLink; ?>"
                                    class="weekly-btn <?= ($weekly == '1') ? 'active' : ''; ?>">
                                    <i class="ri-calendar-line"></i>
                                    Weekly
                                </a>
                            </div>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Metode</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($queryOrders)): ?>
                                    <tr>
                                        <td>
                                            <span class="order-id">
                                                <?= htmlspecialchars($order['id_pesanan']); ?>
                                            </span>
                                        </td>

                                        <td><?= htmlspecialchars($order['metode_pengiriman'] ?? '-'); ?></td>

                                        <td>
                                            <?= !empty($order['tanggal'])
                                                ? date('d/m/Y H:i', strtotime($order['tanggal']))
                                                : '-'; ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?php
                                            $status = $order['status'];

                                            if ($status == 'Dalam_Antrian') {
                                                $statusClass = 'status-baru';
                                                $statusText  = 'Dalam Antrian';
                                            } elseif ($status == 'Diproses') {
                                                $statusClass = 'status-proses';
                                                $statusText  = 'Diproses';
                                            } elseif ($status == 'Dikirim') {
                                                $statusClass = 'status-kirim';
                                                $statusText  = 'Dikirim';
                                            } elseif ($status == 'Selesai') {
                                                $statusClass = 'status-selesai';
                                                $statusText  = 'Selesai';
                                            } elseif ($status == 'Dibatalkan') {
                                                $statusClass = 'status-tolak';
                                                $statusText  = 'Dibatalkan';
                                            } else {
                                                $statusClass = 'status-baru';
                                                $statusText  = $status;
                                            }
                                            ?>

                                            <span class="status-badge <?= $statusClass; ?>">
                                                <?= htmlspecialchars($statusText); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="aksi-dashboard">
                                                <a href="pesanan/detail_pesanan.php?id=<?= $order['id_pesanan']; ?>"
                                                    class="btn-aksi btn-detail">
                                                    Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <div class="lihat-wrapper">
                            <a href="pesanan/pesanan.php" class="lihat-semua">
                                Lihat Semua Pesanan →
                            </a>
                        </div>
                    </div>

                    <div class="panel menu-panel">
                        <div class="panel-header">
                            <h2>Menu Terlaris</h2>
                            <button class="btn-lihat" onclick="openMenuModal()">Lihat Semua</button>
                        </div>

                        <?php while ($menu = mysqli_fetch_assoc($queryMenus)): ?>
                            <div class="menu-item">
                                <?php if (!empty($menu['gambar'])): ?>
                                    <img src="img/<?= htmlspecialchars($menu['gambar']); ?>" alt="Menu">
                                <?php else: ?>
                                    <img src="img/mie-ayam.png" alt="Menu">
                                <?php endif; ?>

                                <div>
                                    <h4><?= htmlspecialchars($menu['nama_menu']); ?></h4>
                                    <p>Rp <?= number_format($menu['harga'], 0, ',', '.'); ?></p>
                                </div>

                                <span>Terjual <?= $menu['terjual']; ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>

            </section>
        </main>
    </div>

    <div id="menuModal" class="modal-menu">
        <div class="modal-menu-content">
            <div class="modal-menu-header">
                <h2>Detail Menu Terjual</h2>
                <button onclick="closeMenuModal()" class="modal-close">&times;</button>
            </div>

            <div class="modal-menu-list">
                <?php
                $queryAllMenus = mysqli_query($koneksi, "
                SELECT 
                    m.nama_menu,
                    m.harga,
                    m.gambar,
                    COALESCE(SUM(dp.jumlah), 0) AS terjual
                FROM menu m
                LEFT JOIN detail_pesanan dp ON m.id_menu = dp.id_menu
                GROUP BY m.id_menu, m.nama_menu, m.harga, m.gambar
                ORDER BY terjual DESC
            ");
                ?>

                <?php while ($menu = mysqli_fetch_assoc($queryAllMenus)): ?>
                    <div class="modal-menu-item">
                        <?php if (!empty($menu['gambar'])): ?>
                            <img src="img/<?= htmlspecialchars($menu['gambar']); ?>" alt="">
                        <?php else: ?>
                            <img src="img/mie-ayam.png" alt="">
                        <?php endif; ?>

                        <div>
                            <h4><?= htmlspecialchars($menu['nama_menu']); ?></h4>
                            <p>Rp <?= number_format($menu['harga'], 0, ',', '.'); ?></p>
                        </div>

                        <span>Terjual <?= $menu['terjual']; ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        const salesLabels = <?= json_encode($labelGrafik); ?>;
        const salesData = <?= json_encode($dataGrafik); ?>;
    </script>

    <script src="assets/js/dashboard.js"></script>
</body>

</html>