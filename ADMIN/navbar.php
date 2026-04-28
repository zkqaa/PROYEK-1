<?php $page = basename($_SERVER['PHP_SELF']); ?>

<!-- STYLE LANGSUNG DI SINI -->
<style>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 40px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* LEFT */
.nav-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
}

.nav-left h2 {
    font-size: 20px;
    font-weight: bold;
}

.nav-left span {
    color: #8bc34a;
}

/* CENTER */
.nav-center {
    display: flex;
    gap: 25px;
}

.nav-center a {
    text-decoration: none;
    color: #111;
    padding: 10px 20px;
    border-radius: 20px;
    transition: 0.3s;
    font-weight: 500;
}

.nav-center a.active {
    background: #8bc34a;
    color: #fff;
}

.nav-center a:hover {
    background: #a5d64f;
    color: #fff;
}

/* RIGHT */
.nav-right {
    display: flex;
    align-items: center;
    gap: 18px;
}

/* CART */
.cart {
    position: relative;
    background: #2ecc71;
    padding: 12px;
    border-radius: 12px;
    color: white;
    font-size: 18px;
}

.cart .badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: red;
    color: #fff;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 50%;
}

/* ICON */
.icon {
    font-size: 20px;
    cursor: pointer;
}

/* PROFILE */
.profile {
    width: 38px;
    height: 38px;
    background: #eee;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile i {
    color: #999;
}
</style>

<!-- NAVBAR -->
<header class="navbar">

    <div class="nav-left">
        <img src="logo.png" class="logo">
        <h2>MIE AYAM <span>HIJAU</span></h2>
    </div>

    <nav class="nav-center">
        <a href="beranda.php" class="<?= ($page=='beranda.php')?'active':'' ?>">Beranda</a>
        <a href="rekomendasi.php" class="<?= ($page=='rekomendasi.php')?'active':'' ?>">Rekomendasi</a>
        <a href="pesanan.php" class="<?= ($page=='pesanan.php')?'active':'' ?>">Pesanan</a>
    </nav>

    <div class="nav-right">
        <div class="cart">
            <i class="fa-solid fa-basket-shopping"></i>
            <span class="badge">0</span>
        </div>

        <i class="fa-solid fa-magnifying-glass icon"></i>

        <div class="profile">
            <i class="fa-solid fa-user"></i>
        </div>
    </div>

</header>