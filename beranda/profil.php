<?php
session_start();

$nama = "Nama";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil</title>

    <link rel="stylesheet" href="profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="profil-page">
    <div class="profil-wrapper">

        <!-- Sidebar -->
        <aside class="profil-sidebar">
            <div class="sidebar-avatar">
                <div class="avatar-circle">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>

            <div class="sidebar-name"><?= $nama; ?></div>

            <div class="sidebar-edit">
                <i class="fa-regular fa-pen-to-square"></i>
                <span>Ubah Profile</span>
            </div>

            <button class="sidebar-btn active">Profile</button>

            <a href="#" class="sidebar-link">Ubah Kata Sandi</a>
        </aside>

        <!-- Content -->
        <main class="profil-content">
            <div class="profil-card">
                <div class="back-header">
                    <a href="beranda.php" class="back-btn">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
                <h2 class="profil-title">Pengaturan</h2>

                <div class="profil-header-bar">Profile Saya</div>
                <p class="profil-desc">
                    *Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun
                </p>

                <form action="" method="post" class="profil-form">
                    <div class="form-row">
                        <label>Username</label>
                        <div class="form-input-group">
                            <input type="text" name="username">
                            <div class="action-icons">
                                <button type="button" class="icon-btn edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="icon-btn delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Nama</label>
                        <div class="form-input-group">
                            <input type="text" name="nama">
                            <div class="action-icons">
                                <button type="button" class="icon-btn edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="icon-btn delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>No Telepon</label>
                        <div class="form-input-group">
                            <input type="text" name="telepon">
                            <div class="action-icons">
                                <button type="button" class="icon-btn edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="icon-btn delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Jenis Kelamin</label>
                        <div class="gender-group">
                            <label class="radio-item">
                                <input type="radio" name="jk" value="Laki-laki">
                                <span>Laki-laki</span>
                            </label>

                            <label class="radio-item">
                                <input type="radio" name="jk" value="Perempuan">
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Alamat</label>
                        <div class="form-input-group textarea-group">
                            <textarea name="alamat"></textarea>
                            <div class="action-icons">
                                <button type="button" class="icon-btn edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="icon-btn delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Simpan</button>
                        <button type="reset" class="btn-cancel">Batal</button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</div>

</body>
</html>