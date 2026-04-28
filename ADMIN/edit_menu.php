<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu='$id'");
$row = mysqli_fetch_assoc($data);

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");

if (!$row) {
    die("Data tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="style.css">
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
        </div>
    </aside>

    <main class="content">
        <section class="form-container">
            <div class="form-card">
                <h2>Edit Menu</h2>

                <form action="update_menu.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_menu" value="<?= $row['id_menu']; ?>">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($row['gambar']); ?>">

                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" value="<?= htmlspecialchars($row['nama_menu']); ?>" required>

                    <label>Harga</label>
                    <input type="number" name="harga" value="<?= $row['harga']; ?>" required>

                    <label>Kategori</label>
                    <select name="id_kategori" required>
                        <?php while($k = mysqli_fetch_assoc($kategori)) : ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= $k['id_kategori'] == $row['id_kategori'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($k['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label>Preview Gambar</label>
                    <div class="form-preview">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="">
                        <?php else: ?>
                            <p>Tidak ada gambar</p>
                        <?php endif; ?>
                    </div>

                    <label>Ganti Gambar</label>
                    <input type="file" name="gambar" accept="image/*">

                    <div class="form-actions">
                        <a href="menu.php" class="btn-back">Kembali</a>
                        <button type="submit" class="btn-save">Update</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

</body>
</html>