<?php
session_start();

if (!isset($_SESSION['password'])) {
    $_SESSION['password'] = '123456';
}

$success = '';
$error = '';

if (isset($_POST['simpan_profile'])) {
    $_SESSION['profile_saved'] = true;
    $_SESSION['profile'] = [
        'nama' => $_POST['nama'] ?? '',
        'username' => $_POST['username'] ?? '',
        'telepon' => $_POST['telepon'] ?? '',
        'jk' => $_POST['jk'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
    ];

    header("Location: profil.php");
    exit;
}

if (isset($_POST['ubah_password'])) {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($old !== $_SESSION['password']) {
        $error = "Kata sandi lama salah!";
    } elseif (strlen($new) < 4) {
        $error = "Password minimal 4 karakter!";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi password tidak sama!";
    } else {
        $_SESSION['password'] = $new;
        $success = "Password berhasil diubah!";
    }
}

$page = $_GET['page'] ?? 'profile';
$isEdit = isset($_GET['edit']);
$isSaved = $_SESSION['profile_saved'] ?? false;

$profile = $_SESSION['profile'] ?? [
    'nama' => '',
    'username' => '',
    'telepon' => '',
    'jk' => '',
    'alamat' => '',
];

$namaSidebar = !empty($profile['nama']) ? $profile['nama'] : 'Nama';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya</title>
  <link rel="stylesheet" href="profil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="profil-page">
  <aside class="profil-sidebar">
    <div class="avatar-circle">
      <i class="fa-solid fa-user"></i>
    </div>

    <div class="sidebar-name"><?= htmlspecialchars($namaSidebar); ?></div>

    <a href="profil.php" class="sidebar-menu <?= $page === 'profile' ? 'active' : ''; ?>">Profile</a>

    <a href="profil.php?page=password" class="sidebar-menu <?= $page === 'password' ? 'active' : ''; ?>">
    Ubah Kata Sandi
    </a>
  </aside>

  <main class="profil-main">
    <a href="beranda.php" class="back-link">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali
    </a>

    <?php if ($page === 'password'): ?>

      <section class="profile-form-card">
        <h1>Ubah Kata Sandi</h1>
        <p class="form-subtitle">Gunakan kata sandi yang kuat agar akun kamu tetap aman.</p>

        <?php if ($error): ?>
          <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert success"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" class="profile-form">
          <label>
            Kata Sandi Lama
            <input type="password" name="old_password" required>
          </label>

          <label>
            Kata Sandi Baru
            <input type="password" name="new_password" required>
          </label>

          <label>
            Konfirmasi Kata Sandi Baru
            <input type="password" name="confirm_password" required>
          </label>

          <div class="form-actions">
            <button type="submit" name="ubah_password" class="btn-save">Simpan</button>
            <a href="profil.php" class="btn-cancel">Batal</a>
          </div>
        </form>
      </section>

    <?php elseif ($isSaved && !$isEdit): ?>

      <section class="profile-view-card">
        <div class="profile-cover"></div>

        <div class="profile-view-header">
          <div class="profile-avatar-big">
            <i class="fa-solid fa-user"></i>
          </div>

          <div class="profile-name-box">
            <h1><?= htmlspecialchars($profile['nama']); ?></h1>
            <p>@<?= htmlspecialchars($profile['username']); ?></p>
          </div>

          <a href="profil.php?edit=1" class="edit-main-btn">
            <i class="fa-regular fa-pen-to-square"></i>
            Edit Profil
          </a>
        </div>

        <div class="profile-info-grid">
          <div class="info-card">
            <span>Username</span>
            <strong><?= htmlspecialchars($profile['username']); ?></strong>
          </div>

          <div class="info-card">
            <span>No Telepon</span>
            <strong><?= htmlspecialchars($profile['telepon']); ?></strong>
          </div>

          <div class="info-card">
            <span>Jenis Kelamin</span>
            <strong><?= htmlspecialchars($profile['jk']); ?></strong>
          </div>

          <div class="info-card wide">
            <span>Alamat</span>
            <strong><?= htmlspecialchars($profile['alamat']); ?></strong>
          </div>
        </div>
      </section>

    <?php else: ?>

      <section class="profile-form-card">
        <h1>Pengaturan Profil</h1>
        <p class="form-subtitle">Lengkapi data profil agar akun kamu lebih mudah dikenali.</p>

        <form method="POST" class="profile-form">
          <label>
            Username
            <input type="text" name="username" value="<?= htmlspecialchars($profile['username']); ?>" required>
          </label>

          <label>
            Nama
            <input type="text" name="nama" value="<?= htmlspecialchars($profile['nama']); ?>" required>
          </label>

          <label>
            No Telepon
            <input type="text" name="telepon" value="<?= htmlspecialchars($profile['telepon']); ?>">
          </label>

          <div class="gender-box">
            <span>Jenis Kelamin</span>
            <label>
              <input type="radio" name="jk" value="Laki-laki" <?= $profile['jk'] === 'Laki-laki' ? 'checked' : ''; ?>>
              Laki-laki
            </label>
            <label>
              <input type="radio" name="jk" value="Perempuan" <?= $profile['jk'] === 'Perempuan' ? 'checked' : ''; ?>>
              Perempuan
            </label>
          </div>

          <label>
            Alamat
            <textarea name="alamat"><?= htmlspecialchars($profile['alamat']); ?></textarea>
          </label>

          <div class="form-actions">
            <button type="submit" name="simpan_profile" class="btn-save">Simpan</button>
            <a href="profil.php" class="btn-cancel">Batal</a>
          </div>
        </form>
      </section>

    <?php endif; ?>
  </main>
</div>

</body>
</html>