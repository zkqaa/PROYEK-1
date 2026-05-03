<?php
include "koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT menu.*, kategori.nama_kategori
    FROM menu
    LEFT JOIN kategori ON menu.id_kategori = kategori.id_kategori
    ORDER BY menu.id_menu ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
                <a href="menu.php" class="menu-item active"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
                <a href="pesanan/pesanan.php" class="menu-item"><i class="fa-solid fa-basket-shopping"></i><span>Pesanan</span></a>
                <a href="laporan/laporan.php" class="menu-item"><i class="fa-regular fa-file-lines"></i><span>Laporan</span></a>
            </nav>
        </div>

        <div class="sidebar-footer">
            <div class="admin-box">
                <div class="admin-avatar"><i class="fa-solid fa-user"></i></div>
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
            <div class="header-row">
                <div>
                    <h1>Daftar Menu</h1>
                    <p>Kelola semua data menu makanan dan minuman.</p>
                </div>

                <a href="tambah_menu.php" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Menu</span>
                </a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td class="col-center"><?= $no++; ?></td>

                            <td>
                                <div class="product-cell">
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" class="product-thumb" alt="">
                                    <?php else: ?>
                                        <div class="empty-thumb"></div>
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($row['nama_menu']); ?></span>
                                </div>
                            </td>

                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>

                            <td>
                                <?php if (!empty($row['nama_kategori'])): ?>
                                    <span class="badge <?= strtolower($row['nama_kategori']) == 'makanan' ? 'makanan' : 'minuman'; ?>">
                                        <?= htmlspecialchars($row['nama_kategori']); ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td class="col-center">
                                <?php if (!empty($row['gambar'])): ?>
                                    <button type="button" class="icon-box">
                                        <i class="fa-regular fa-image"></i>
                                    </button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="action-group">
                                    <a href="edit_menu.php?id=<?= $row['id_menu']; ?>" class="action-btn edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <a href="hapus_menu.php?id=<?= $row['id_menu']; ?>" class="action-btn delete"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>

                                    <button type="button" class="more-btn">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>