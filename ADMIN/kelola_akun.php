<?php
session_start();
include "../koneksi.php";

/* TAMBAH USER */
if(isset($_POST['tambah_admin'])){

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nohp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    mysqli_query($koneksi, "
        INSERT INTO users
        (nama_lengkap, no_hp, password, alamat, email, role)
        VALUES
        ('$nama','$nohp','$password','$alamat','$email','$role')
    ");

    header("Location: kelola_akun.php");
    exit;
}

/* HAPUS USER */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM users
        WHERE id_user='$id'
    ");

    header("Location: kelola_akun.php");
    exit;
}

/* NONAKTIF USER */
if(isset($_GET['nonaktif'])){

    $id = $_GET['nonaktif'];

    mysqli_query($koneksi, "
        UPDATE users
        SET status='nonaktif'
        WHERE id_user='$id'
    ");

    header("Location: kelola_akun.php");
    exit;
}

/* AKTIFKAN USER */
if(isset($_GET['aktif'])){

    $id = $_GET['aktif'];

    mysqli_query($koneksi, "
        UPDATE users
        SET status='aktif'
        WHERE id_user='$id'
    ");

    header("Location: kelola_akun.php");
    exit;
}

/* EDIT USER */
if(isset($_POST['edit_admin'])){

    $id = $_POST['id_admin'];

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nohp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    mysqli_query($koneksi, "
        UPDATE users
        SET
            nama_lengkap='$nama',
            no_hp='$nohp',
            password='$password',
            alamat='$alamat',
            email='$email',
            role='$role'
        WHERE id_user='$id'
    ");

    header("Location: kelola_akun.php");
    exit;
}

/* AMBIL DATA USER */
$queryAdmin = mysqli_query($koneksi, "
    SELECT *
    FROM users
    ORDER BY id_user DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Settings Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f7fb;
    padding:40px;
}

/* CONTAINER */

.settings-wrapper{
    max-width:1400px;
    margin:auto;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.topbar h1{
    font-size:34px;
    color:#111827;
}

.topbar p{
    color:#6b7280;
    margin-top:6px;
}

.topbar-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.back-btn{
    width:52px;
    height:52px;
    border-radius:16px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    color:#111827;
    font-size:18px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    transition:.3s;
}

.back-btn:hover{
    transform:translateY(-2px);
    background:#22c55e;
    color:white;
}

.add-btn{
    border:none;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    padding:14px 22px;
    border-radius:14px;
    font-size:15px;
    cursor:pointer;
    font-weight:600;
    box-shadow:0 8px 20px rgba(34,197,94,0.25);
    transition:.3s;
}

.add-btn:hover{
    transform:translateY(-2px);
}

/* CARD */

.settings-card{
    background:white;
    border-radius:28px;
    padding:30px;
    box-shadow:0 15px 40px rgba(0,0,0,0.05);
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

th{
    padding:18px;
    text-align:left;
    color:#6b7280;
    font-size:14px;
    font-weight:600;
}

td{
    padding:22px 18px;
    border-bottom:1px solid #f1f5f9;
}

/* PROFILE */

.admin-profile{
    display:flex;
    align-items:center;
    gap:16px;
}

.admin-avatar{
    width:56px;
    height:56px;
    border-radius:18px;
    background:linear-gradient(135deg,#22c55e,#15803d);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:22px;
    box-shadow:0 8px 18px rgba(34,197,94,0.2);
}

.admin-name{
    font-size:16px;
    font-weight:700;
    color:#111827;
}

.admin-email{
    font-size:13px;
    color:#6b7280;
    margin-top:4px;
}

/* BADGE */

.role-badge{
    padding:10px 18px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.role-admin{
    background:#dcfce7;
    color:#15803d;
}

.role-user{
    background:#dbeafe;
    color:#2563eb;
}

/* STATUS */

.status-active{
    color:#16a34a;
    font-weight:700;
}

/* BUTTON */

.action-btns{
    display:flex;
    gap:12px;
}

.btn-edit,
.btn-delete{
    width:42px;
    height:42px;
    border:none;
    border-radius:14px;
    cursor:pointer;
    color:white;
    font-size:15px;
    transition:.3s;
}

.btn-edit{
    background:#f59e0b;
}

.btn-delete{
    background:#ef4444;
}

.btn-edit:hover,
.btn-delete:hover{
    transform:scale(1.08);
}

/* MODAL */

.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,0.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999;
    backdrop-filter:blur(5px);
}

.modal-content{
    width:480px;
    background:white;
    border-radius:26px;
    padding:30px;
    animation:popup .25s ease;
}

@keyframes popup{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.modal-header h3{
    font-size:24px;
    color:#111827;
}

.close-btn{
    border:none;
    background:#f3f4f6;
    width:40px;
    height:40px;
    border-radius:12px;
    font-size:20px;
    cursor:pointer;
}

.status-btn{
    text-decoration:none;
    padding:8px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
    display:inline-block;
}

.status-btn.aktif{
    background:#d4f8d4;
    color:#1b8f1b;
}

.status-btn.nonaktif{
    background:#ffdede;
    color:#d62828;
}

/* FORM */

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
    color:#374151;
}

.form-group input,
.form-group select{
    width:100%;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    outline:none;
    transition:.3s;
    font-size:14px;
}

.form-group input:focus,
.form-group select:focus{
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,0.15);
}

.save-btn{
    width:100%;
    padding:15px;
    border:none;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    border-radius:16px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    margin-top:10px;
}

.save-btn:hover{
    opacity:.95;
}

/* GUIDE BOX */

.guide-box{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px;
    margin-bottom:28px;
}

.guide-title{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:22px;
}

.guide-title i{
    color:#22c55e;
    font-size:22px;
}

.guide-title h3{
    font-size:20px;
    color:#111827;
}

.guide-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:18px;
}

.guide-item{
    background:white;
    border-radius:18px;
    padding:18px;
    display:flex;
    gap:14px;
    align-items:flex-start;
    box-shadow:0 4px 12px rgba(0,0,0,0.04);
}

.guide-icon{
    min-width:50px;
    height:50px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:18px;
}

.guide-icon.green{
    background:#22c55e;
}

.guide-icon.orange{
    background:#f59e0b;
}

.guide-icon.red{
    background:#ef4444;
}

.guide-icon.blue{
    background:#3b82f6;
}

.guide-item h4{
    margin-bottom:6px;
    color:#111827;
    font-size:15px;
}

.guide-item p{
    font-size:13px;
    line-height:1.6;
    color:#6b7280;
}

</style>
</head>
<body>

<div class="settings-wrapper">

    <div class="topbar">

        <div class="topbar-left">

            <a href="dashboard.php" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div>
                <h1>Settings Admin</h1>
                <p>Kelola akun admin dan user sistem</p>
            </div>

        </div>

        <button class="add-btn" onclick="openModal()">
            <i class="fa-solid fa-plus"></i>
            Tambah Admin
        </button>

    </div>

    <div class="settings-card">

        <!-- PETUNJUK -->
    <div class="guide-box">

        <div class="guide-title">
            <i class="fa-solid fa-circle-info"></i>
            <h3>Petunjuk Penggunaan</h3>
        </div>

        <div class="guide-grid">

            <div class="guide-item">
                <div class="guide-icon green">
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div>
                    <h4>Tambah Admin/User</h4>
                    <p>
                        Klik tombol <b>Tambah Admin</b> untuk menambahkan akun baru ke dalam sistem.
                    </p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon orange">
                    <i class="fa-solid fa-pen"></i>
                </div>

                <div>
                    <h4>Edit Data</h4>
                    <p>
                        Gunakan tombol edit untuk mengubah nama, nomor HP, email, password, dan role user.
                    </p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon red">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <div>
                    <h4>Hapus User</h4>
                    <p>
                        Tombol hapus digunakan untuk menghapus akun secara permanen dari database.
                    </p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon blue">
                    <i class="fa-solid fa-power-off"></i>
                </div>

                <div>
                    <h4>Status Akun</h4>
                    <p>
                        Klik status aktif/nonaktif untuk mengatur apakah user dapat login ke aplikasi atau tidak.
                    </p>
                </div>
            </div>

        </div>

    </div>

        <table>

            <thead>
                <tr>
                    <th>Admin</th>
                    <th>No HP</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php while($admin = mysqli_fetch_assoc($queryAdmin)): ?>

            <tr>

                <td>

                    <div class="admin-profile">

                        <div class="admin-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>
                            <div class="admin-name">
                                <?= htmlspecialchars($admin['nama_lengkap']); ?>
                            </div>

                            <div class="admin-email">
                                <?= htmlspecialchars($admin['email']); ?>
                            </div>
                        </div>

                    </div>

                </td>

                <td>
                    <?= htmlspecialchars($admin['no_hp']); ?>
                </td>

                <td>

                    <?php if($admin['role'] == 'admin'): ?>

                        <span class="role-badge role-admin">
                            Admin
                        </span>

                    <?php else: ?>

                        <span class="role-badge role-user">
                            User
                        </span>

                    <?php endif; ?>

                </td>

                <td>

                <?php if($admin['status'] == 'aktif'): ?>

                    <a 
                        href="kelola_akun.php?nonaktif=<?= $admin['id_user']; ?>"
                        onclick="return confirm('Nonaktifkan user ini?')"
                        class="status-btn aktif"
                    >
                        ● Aktif
                    </a>

                <?php else: ?>

                    <a 
                        href="kelola_akun.php?aktif=<?= $admin['id_user']; ?>"
                        onclick="return confirm('Aktifkan user ini?')"
                        class="status-btn nonaktif"
                    >
                        ● Nonaktif
                    </a>

                <?php endif; ?>

                </td>

                <td>

                    <div class="action-btns">

                        <button 
                            class="btn-edit"
                            onclick="openEditModal(
                                '<?= $admin['id_user']; ?>',
                                '<?= $admin['nama_lengkap']; ?>',
                                '<?= $admin['no_hp']; ?>',
                                '<?= $admin['email']; ?>',
                                '<?= $admin['alamat']; ?>',
                                '<?= $admin['password']; ?>',
                                '<?= $admin['role']; ?>'
                            )"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <a href="kelola_akun.php?hapus=<?= $admin['id_user']; ?>" onclick="return confirm('Yakin ingin menghapus user ini?')">

                            <button class="btn-delete" type="button">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </a>

                    </div>

                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH -->

<div class="modal" id="adminModal">

    <div class="modal-content">

        <div class="modal-header">
            <h3>Tambah Admin</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <form method="POST">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" required>
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <button type="submit" name="tambah_admin" class="save-btn">
                Simpan Admin
            </button>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->

<div class="modal" id="editModal">

    <div class="modal-content">

        <div class="modal-header">
            <h3>Edit Admin</h3>
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
        </div>

        <form method="POST">

            <input type="hidden" name="id_admin" id="edit_id">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit_nama" required>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" id="edit_nohp" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" id="edit_alamat">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" id="edit_password" required>
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="role" id="edit_role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <button type="submit" name="edit_admin" class="save-btn">
                Update Admin
            </button>

        </form>

    </div>

</div>

<script>

function openModal(){
    document.getElementById('adminModal').style.display = 'flex';
}

function closeModal(){
    document.getElementById('adminModal').style.display = 'none';
}

function openEditModal(id,nama,nohp,email,alamat,password,role){

    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nohp').value = nohp;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_password').value = password;
    document.getElementById('edit_role').value = role;
}

function closeEditModal(){
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(e){

    if(e.target == document.getElementById('adminModal')){
        closeModal();
    }

    if(e.target == document.getElementById('editModal')){
        closeEditModal();
    }
}

</script>

</body>
</html>