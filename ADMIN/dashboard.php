<?php
$page = "beranda";

$orders = [
    ["id" => "05gyv5", "metode" => "Pickup", "waktu" => "11 Jul 2023, 08:37 PM", "total" => "Rp 24.000", "status" => "New Order"],
    ["id" => "mwwjmw", "metode" => "Pickup", "waktu" => "12 Jun 2023, 06:23 PM", "total" => "Rp 12.000", "status" => "Confirmed"],
    ["id" => "g97rx5", "metode" => "Delivery", "waktu" => "09 Jun 2023, 03:02 PM", "total" => "Rp 36.000", "status" => "New Order"],
    ["id" => "nwl4n5", "metode" => "Delivery", "waktu" => "31 May 2023, 11:53 AM", "total" => "Rp 24.000", "status" => "Accepted"],
    ["id" => "ywm875", "metode" => "Delivery", "waktu" => "20 May 2023, 08:53 AM", "total" => "Rp 18.000", "status" => "Preparing"],
];

$menus = [
    ["nama" => "Mie Ayam Hijau Ceker", "harga" => "Rp 12.000", "terjual" => 120],
    ["nama" => "Mie Ayam Hijau Polos", "harga" => "Rp 12.000", "terjual" => 95],
    ["nama" => "Mie Ayam Hijau Bakso", "harga" => "Rp 14.000", "terjual" => 87],
    ["nama" => "Mie Ayam Hijau Pangsit", "harga" => "Rp 14.000", "terjual" => 75],
    ["nama" => "Mie Ayam Hijau Komplit", "harga" => "Rp 17.000", "terjual" => 60],
];
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <img src="logo.png" alt="Logo" class="brand-logo">
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

    <main class="content dashboard-content">
        <section class="dashboard-card">

        <section class="hero-grid">
            <div class="income-card">
                <div class="income-text">
                    <div class="icon-circle">
                        <i class="ri-wallet-3-fill"></i>
                    </div>
                    <p>Total Pendapatan</p>
                    <h2>Rp 150.000</h2>
                    <span>Total pendapatan dari pesanan yang tidak dibatalkan.</span>
                </div>
                <img src="assets/img/mie-ayam.png" alt="Mie Ayam">
            </div>

            <div class="promo-card">
                <h2>Mie Ayam Hijau</h2>
                <p>Nikmati kelezatan mie ayam dengan cita rasa hijau yang sehat dan menggugah selera!</p>
            </div>
        </section>

        <section class="stats">
            <div class="stat-card green">
                <i class="ri-bowl-fill"></i>
                <div>
                    <p>Total Menu</p>
                    <h3>40</h3>
                    <span>menu tersedia</span>
                </div>
            </div>

            <div class="stat-card orange">
                <i class="ri-clipboard-fill"></i>
                <div>
                    <p>Total Pesanan</p>
                    <h3>6</h3>
                    <span>pesanan masuk</span>
                </div>
            </div>

            <div class="stat-card blue">
                <i class="ri-checkbox-circle-fill"></i>
                <div>
                    <p>Pesanan Selesai</p>
                    <h3>4</h3>
                    <span>pesanan selesai</span>
                </div>
            </div>

            <div class="stat-card purple">
                <i class="ri-money-dollar-circle-fill"></i>
                <div>
                    <p>Rata-rata Penjualan</p>
                    <h3>Rp 25.000</h3>
                    <span>per pesanan</span>
                </div>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel orders-panel">
                <div class="panel-header">
                    <h2>Orders</h2>
                    <div class="panel-actions">
                        <button><i class="ri-download-line"></i></button>
                        <button><i class="ri-filter-3-line"></i> Filter</button>
                        <button><i class="ri-calendar-line"></i> Weekly</button>
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
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><span class="order-id"><?= $order['id']; ?></span></td>
                            <td><?= $order['metode']; ?></td>
                            <td><?= $order['waktu']; ?></td>
                            <td><?= $order['total']; ?></td>
                            <td>
                                <span class="status <?= strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                    <?= $order['status']; ?>
                                </span>
                            </td>
                            <td>
                                <button class="dot-btn">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <button class="see-all">Lihat Semua Pesanan <i class="ri-arrow-right-line"></i></button>
            </div>

            <div class="panel menu-panel">
                <div class="panel-header">
                    <h2>Menu Terlaris</h2>
                    <button class="small-btn">Lihat Semua</button>
                </div>

                <?php foreach ($menus as $menu): ?>
                    <div class="menu-item">
                        <img src="assets/img/mie-ayam.png" alt="Menu">
                        <div>
                            <h4><?= $menu['nama']; ?></h4>
                            <p><?= $menu['harga']; ?></p>
                        </div>
                        <span>Terjual <?= $menu['terjual']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        </section>            
    </main>
</div>

<script src="assets/js/dashboard.js"></script>
</body>
</html>