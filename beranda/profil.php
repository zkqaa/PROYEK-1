<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login/index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($koneksi, "
    SELECT * FROM users
    WHERE id_user='$id_user'
");

$user = mysqli_fetch_assoc($query);

$success = '';
$error = '';

/* SIMPAN PROFILE */
if (isset($_POST['simpan_profile'])) {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jk']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    mysqli_query($koneksi, "
        UPDATE users
        SET
            nama_lengkap='$nama',
            email='$email',
            no_hp='$telepon',
            jenis_kelamin='$jk',
            alamat='$alamat'
        WHERE id_user='$id_user'
    ");

    $_SESSION['username'] = $nama;

    header("Location: profil.php");
    exit;
}

/* UBAH PASSWORD */
if (isset($_POST['ubah_password'])) {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    /* CEK BATAS 3 HARI */
    if (!empty($user['last_password_change'])) {

        $lastChange = strtotime($user['last_password_change']);
        $now = time();

        $selisih = $now - $lastChange;

        if ($selisih < (3 * 24 * 60 * 60)) {

            $sisa = ceil(((3 * 24 * 60 * 60) - $selisih) / 3600);

            $error = "Password hanya bisa diganti setiap 3 hari sekali. Tunggu sekitar $sisa jam lagi.";
        }
    }

    if (empty($error)) {

        if (!password_verify($old, $user['password'])) {

            $error = "Kata sandi lama salah!";

        } elseif (strlen($new) < 4) {

            $error = "Password minimal 4 karakter!";

        } elseif ($new !== $confirm) {

            $error = "Konfirmasi password tidak sama!";

        } else {

            $newHash = password_hash($new, PASSWORD_DEFAULT);

            mysqli_query($koneksi, "
                UPDATE users
                SET 
                    password='$newHash',
                    last_password_change=NOW()
                WHERE id_user='$id_user'
            ");

            $success = "Password berhasil diubah!";
        }
    }
}

$page = $_GET['page'] ?? 'profile';
$isEdit = isset($_GET['edit']);

$profile = [
    'nama' => $user['nama_lengkap'] ?? '',
    'email' => $user['email'] ?? '',
    'telepon' => $user['no_hp'] ?? '',
    'jk' => $user['jenis_kelamin'] ?? '',
    'alamat' => $user['alamat'] ?? '',
];

$isSaved = !empty($profile['nama']);

$namaSidebar = !empty($profile['nama'])
    ? $profile['nama']
    : 'Nama';
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
              <button 
                  type="submit" 
                  name="ubah_password" 
                  class="btn-save"
                  onclick="return confirm('Yakin ingin mengganti password? Setelah diganti, password tidak dapat diubah lagi selama 3 hari.')"
              >
                  Simpan
              </button>

              <a href="profil.php" class="btn-cancel">Batal</a>
          </div>

          <div class="admin-contact-box">

            <a 
                href="https://wa.me/6285182567016?text=Halo%20Admin,%20saya%20ingin%20mengganti%20password%20sebelum%203%20hari."
                class="btn-admin"
                target="_blank"
            >
                <i class="fa-brands fa-whatsapp"></i>
                Hubungi Admin
            </a>

            <p class="admin-note">
                Jika akun sangat penting dan tidak bisa menunggu 3 hari,
                silakan hubungi admin untuk bantuan reset password.
            </p>

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
            <p><?= htmlspecialchars($profile['email']); ?></p>
          </div>

          <a href="profil.php?edit=1" class="edit-main-btn">
            <i class="fa-regular fa-pen-to-square"></i>
            Edit Profil
          </a>
        </div>

        <div class="profile-info-grid">
          <div class="info-card">
            <span>Email</span>
            <strong><?= htmlspecialchars($profile['email']); ?></strong>
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
              Email
              <input type="email" name="email"
              value="<?= htmlspecialchars($profile['email']); ?>">
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