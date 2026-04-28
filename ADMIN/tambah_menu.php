<?php
include "koneksi.php";
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
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
                <h2>Tambah Menu</h2>

                <form action="simpan_menu.php" method="POST" enctype="multipart/form-data">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" required>

                    <label>Harga</label>
                    <input type="number" name="harga" required>

                    <label>Kategori</label>
                    <select name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while($k = mysqli_fetch_assoc($kategori)) : ?>
                            <option value="<?= $k['id_kategori']; ?>">
                                <?= htmlspecialchars($k['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label>Gambar</label>
                    <input type="file" name="gambar" accept="image/*">

                    <div class="form-actions">
                        <a href="menu.php" class="btn-back">Kembali</a>
                        <button type="submit" class="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

</body>
</html>