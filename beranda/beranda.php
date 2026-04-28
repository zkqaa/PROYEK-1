<?php
session_start();
include '../koneksi.php';

$data = mysqli_query ($conn, "SELECT menu.*, kategori.nama_kategori FROM menu
                              JOIN kategori ON menu.id_kategori = kategori.id_kategori");

$products = [];
while($row = mysqli_fetch_assoc($data)) {
  $products[] = [
    'id' => $row['id_menu'],
    'nama' => $row['nama_menu'],
    'harga' => $row['harga'],
    'gambar' => 'img/'. $row['gambar'],
    'kategori' => $row['nama_kategori'],
    'badge' => '',
    'badge_class' => ''
  ];
}

function rupiah($angka)
{
    return 'Rp. ' . number_format($angka, 0, ',', '.');
}

function filterProductsByCategory($products, $kategori)
{
    $hasil = [];
    foreach ($products as $product) {
        if ($product['kategori'] === $kategori) {
            $hasil[] = $product;
        }
    }
    return $hasil;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartCount = 0;
$cartTotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['qty'];
    $cartTotal += $item['harga'] * $item['qty'];
}

$ongkir = ($cartTotal < 50000 && $cartTotal > 0) ? 3000 : 0;
$diskon = 0;
//Total harga
$grandTotal = $cartTotal - $diskon + $ongkir;

$produkMakanan = filterProductsByCategory($products, 'makanan');
$produkMinuman = filterProductsByCategory($products, 'minuman');
$produkCampur = array_merge($produkMakanan, $produkMinuman);

$produkCampur = array_filter($produkCampur, function ($item) {
    return trim(strtolower($item['nama'])) !== trim(strtolower('Mie Ayam Hijau Mentah'));
});

$produkCampur = array_values($produkCampur); // penting biar index rapi
shuffle($produkCampur);
$produkRekomendasi = array_slice($produkCampur, 0, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mie Ayam Hijau</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />

  <style>
    .profile-wrapper{
      position:relative;
    }

    .profile-icon{
      width:46px;
      height:46px;
      display:flex;
      align-items:center;
      justify-content:center;
      border:none;
      border-radius:50%;
      background:linear-gradient(145deg,#f8f8f8,#e9e9e9);
      color:#b7b7b7;
      font-size:20px;
      cursor:pointer;
      padding:0;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.9),
        0 4px 10px rgba(0,0,0,0.06);
      transition:all .22s ease;
      position:relative;
        overflow:hidden;
    }

    .profile-icon::after{
      content:"";
      position:absolute;
      inset:0;
      border-radius:50%;
      background:radial-gradient(circle at top left, rgba(255,255,255,0.8), transparent 55%);
      pointer-events:none;
    }

    .profile-icon:hover{
      transform:translateY(-2px);
      color:#8f8f8f;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,1),
        0 8px 18px rgba(0,0,0,0.10);
    }

    .profile-icon:active{
      transform:scale(0.96);
    }

    .profile-icon i{
      pointer-events:none;
    }

    .profile-dropdown{
      position:absolute;
      top:54px;
      right:0;
      width:180px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 10px 24px rgba(0,0,0,0.12);
      padding:14px 12px;
      display:none;
      z-index:1000;
    }

    .profile-dropdown.show{
      display:block;
    }

    .profile-avatar-box{
      display:flex;
      justify-content:center;
      margin-bottom:12px;
    }

    .profile-avatar-circle{
      width:42px;
      height:42px;
      border-radius:50%;
      border:2px solid #9cc83e;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#bdbdbd;
      font-size:18px;
      background:#f5f5f5;
    }

    .profile-menu-item{
      display:block;
      text-decoration:none;
      text-align:center;
      background:#f5f5f5;
      color:#444;
      font-size:15px;
      font-weight:500;
      padding:12px 10px;
      border-radius:6px;
      margin-bottom:10px;
      transition:all .2s ease;
    }

    .profile-menu-item:last-child{
      margin-bottom:0;
    }

    .profile-menu-item:hover{
      background:#ededed;
    }

    .profile-menu-item.logout{
      color:#ff6b6b;
    }

    .logo-circle{
      width:48px;
      height:48px;
      border-radius:50%;
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#fff;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
    }

    /* gambar di dalam */
    .logo-circle img{
      width:100%;
      height:100%;
      object-fit:cover; /* biar pas lingkaran */
    }

    .page-with-cart{
      display:flex;
      gap:24px;
      align-items:flex-start;
    }

    .main-content{
      flex:1;
      min-width:0;
    }

    .nav-menu{
        position:relative;
        display:flex;
        gap:10px;
        background:#f3f3f3;
        padding:8px;
        border-radius:30px;
        width:max-content;
    }

    /* item menu */
    .nav-item{
        position:relative;
        padding:10px 20px;
        border-radius:25px;
        text-decoration:none;
        color:#222;
        font-weight:600;
        z-index:2;
        transition:color 0.2s ease;
    }

    /* highlight hijau */
    .nav-highlight{
        position:absolute;
        top:8px;
        left:8px;
        height:calc(100% - 16px);
        width:100px;
        background:#8dc63f;
        border-radius:25px;
        transition:all 0.3s ease;
        z-index:1;
    }

    /* warna teks saat aktif */
    .nav-item.active{
        color:#222;
    }

    /* hover teks */
    .nav-item:hover{
        color:#fff;
    }

    .cart-sidebar{
      width:320px;
      position:fixed;
      top:95px;
      right:30px;
      z-index:999;
      display:none;
    }

    .cart-sidebar.show{
      display:block;
    }

    .cart-panel{
      background:#fff;
      border-radius:14px;
      overflow:hidden;
      box-shadow:0 8px 20px rgba(0,0,0,.08);
      border:1px solid #e8e8e8;
      height:calc(100vh - 120px);
      display:flex;
      flex-direction:column;
    }

    .cart-panel-header{
      background:#28a745;
      color:#fff;
      padding:14px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      font-weight:700;
      flex-shrink:0;
    }

    .cart-panel-header .left{
      display:flex;
      align-items:center;
      gap:10px;
    }

    .cart-header-actions{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .close-cart-btn{
      width:30px;
      height:30px;
      border:none;
      border-radius:8px;
      background:rgba(255,255,255,0.18);
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      transition:all .2s ease;
      padding:0;
    }

    .close-cart-btn:hover{
      background:#fff;
      color:#28a745;
      transform:scale(1.08);
    }

    .close-cart-btn i{
      font-size:14px;
      line-height:1;
      pointer-events:none;
    }

    .cart-panel-body{
      padding:14px;
      flex:1;
      min-height:0;
      display:flex;
      flex-direction:column;
    }

    .cart-empty{
      text-align:center;
      color:#666;
      padding:18px 10px;
      font-size:14px;
    }

    .cart-items{
      flex:1;
      min-height:0;
      overflow-y:auto;
      padding-right:4px;
    }

    .cart-item{
      display:flex;
      gap:12px;
      padding:12px 0;
      border-bottom:1px solid #eee;
    }

    .cart-item:last-child{
      border-bottom:none;
    }

    .cart-item img{
      width:60px;
      height:60px;
      object-fit:cover;
      border-radius:10px;
      flex-shrink:0;
    }

    .cart-item-info{
      flex:1;
    }

    .cart-item-info h4{
      margin:0 0 4px;
      font-size:14px;
      line-height:1.3;
    }

    .cart-item-info p{
      margin:0 0 8px;
      font-size:13px;
      color:#666;
    }

    .cart-item-actions{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      flex-wrap:wrap;
    }

    .qty-box{
      display:flex;
      align-items:center;
      gap:8px;
    }

    .qty-btn{
      width:28px;
      height:28px;
      border-radius:50%;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      text-decoration:none;
      background:#f2f2f2;
      color:#222;
      font-weight:700;
      border:none;
      cursor:pointer;
    }

    .remove-btn{
      background:transparent;
      color:#999;
      font-size:14px;
      padding:0;
      border:none;
      cursor:pointer;
    }

    .cart-summary{
      margin-top:0;
      border-top:none;
      padding-top:0;
    }

    .summary-row{
      display:flex;
      justify-content:space-between;
      margin-bottom:8px;
      font-size:14px;
      color:#333;
    }

    .cart-total{
      margin-top:14px;
      background:#e8f7e8;
      color:#2f8d36;
      border-radius:10px;
      padding:12px 14px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      font-weight:700;
    }

    .checkout-btn{
      width:100%;
      border:none;
      background:linear-gradient(135deg,#1e8e3e,#28a745);
      color:#fff;

      padding:12px;            /* sebelumnya 16px → diperkecil */
      border-radius:25px;      /* sedikit lebih kecil */
      font-size:14px;          /* sebelumnya 16px */
      font-weight:600;
      margin-top:12px;

      display:flex;
      align-items:center;
      justify-content:center;
      gap:6px;

      cursor:pointer;
      box-shadow:0 4px 12px rgba(40,167,69,0.3);
      transition:all 0.2s ease;
    }

    /* icon */
    .checkout-btn .icon{
      width:20px;
      height:20px;
      font-size:11px;
    }

    /* hover */
    .checkout-btn:hover{
      transform:translateY(-1px);
      box-shadow:0 6px 16px rgba(40,167,69,0.4);
    }

    /* klik */
    .checkout-btn:active{
      transform:scale(0.96);
    }

    .clear-cart{
      font-size:12px;
      color:#fff;
      opacity:.95;
      background:none;
      border:none;
      cursor:pointer;
    }

    .btn-cart{
      border:none;
      cursor:pointer;
      transition:all 0.15s ease;
      position:relative;
      overflow:hidden;
    }

    .btn-cart:active{
      transform:scale(0.95);
      box-shadow:0 3px 8px rgba(0,0,0,0.2) inset;
    }

    .btn-cart span.ripple{
      position:absolute;
      border-radius:50%;
      transform:scale(0);
      animation:ripple 0.5s linear;
      background:rgba(255,255,255,0.6);
    }

    .summary{
      margin-bottom:14px;
      padding-bottom:10px;
      border-bottom:1px solid #eee;
    }

    @keyframes ripple{
      to{
        transform:scale(4);
        opacity:0;
      }
    }

    @media (max-width: 991px){
      .page-with-cart{
        flex-direction:column;
      }

      .cart-sidebar{
        width:90%;
        right:5%;
        top:85px;
      }

      .cart-panel{
        height:calc(100vh - 100px);
      }
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="container navbar">
      <a href="#" class="brand">
        <div class="logo-circle">
          <img src="assets/logo.jpeg" alt="Logo">
        </div>
        <div class="brand-name">
          <span class="brand-dark">MIE AYAM</span>
          <span class="brand-green">HIJAU</span>
        </div>
      </a>

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="nav-menu">
        <div class="nav-highlight"></div>

        <a href="#" class="nav-item active">Beranda</a>
        <a href="#" class="nav-item">Rekomendasi</a>
        <a href="pesanan.php" class="nav-item">Pesanan</a>
    </div>

      <div class="nav-icons">
        <a href="javascript:void(0)" class="cart-box" id="cartToggle" aria-label="Keranjang">
          <i class="fa-solid fa-basket-shopping"></i>
          <span class="cart-count"><?= $cartCount; ?></span>
        </a>
        <a href="#" class="search-icon" aria-label="Pencarian">
          <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <div class="profile-wrapper">
        <button type="button" class="profile-icon" id="profileToggle" aria-label="Profil">
          <i class="fa-solid fa-user"></i>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
          <div class="profile-avatar-box">
            <div class="profile-avatar-circle">
              <i class="fa-solid fa-user"></i>
            </div>
          </div>

          <a href="profil.php" class="profile-menu-item">Kelola Akun</a>
          <a href="../login/logout.php" class="profile-menu-item logout">Keluar</a>
        </div>
      </div>
      </div>
    </div>
  </header>

  <main class="page-bg">
    <div class="container page-with-cart">

      <div class="main-content">
        <section class="hero-section">
          <div class="hero-banner">
            <img src="img/banner.jpeg" alt="Mie Ayam Hijau" />
            <div class="hero-overlay"></div>
            <div class="hero-content">
              <p class="hero-badge">🔥 BEST SELLER</p>
              <h1>LAGI LAPER?<br>DI MIE AYAM HIJAU AJA!</h1>
              <p class="hero-subtitle">Sekali coba, pasti kepengen lagi</p>
              <a href="#" class="btn-order">ORDER NOW</a>
            </div>
          </div>
        </section>

        <section class="product-section">
          <h2 class="section-title">Makanan <span>🍜</span></h2>
          <div class="product-grid grid-4">
            <?php foreach ($produkMakanan as $produk): ?>
              <article class="product-card">
                <?php if (!empty($produk['badge'])): ?>
                  <span class="badge-tag <?= $produk['badge_class']; ?>"><?= $produk['badge']; ?></span>
                <?php endif; ?>

                <div class="product-image">
                  <img src="<?= $produk['gambar']; ?>?v=<?= time(); ?>" alt="<?= htmlspecialchars($produk['nama']); ?>" />
                </div>

                <div class="product-info">
                  <h3><?= htmlspecialchars($produk['nama']); ?></h3>
                  <p class="price"><?= rupiah($produk['harga']); ?></p>
                  <button type="button" class="btn-cart add-to-cart" data-id="<?= $produk['id']; ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Tambah Ke Keranjang
                  </button>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="product-section">
          <h2 class="section-title">Minumannya</h2>
          <div class="product-grid grid-5">
            <?php foreach ($produkMinuman as $produk): ?>
              <article class="product-card small-card">
                <div class="product-image">
                  <img src="<?= $produk['gambar']; ?>?v=<?= time(); ?>" alt="<?= htmlspecialchars($produk['nama']); ?>" />
                </div>
                <div class="product-info">
                  <h3><?= htmlspecialchars($produk['nama']); ?></h3>
                  <p class="price"><?= rupiah($produk['harga']); ?></p>
                  <button type="button" class="btn-cart add-to-cart" data-id="<?= $produk['id']; ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Tambah Ke Keranjang
                  </button>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="product-section recommend-section">
          <h2 class="section-title recommendation-title"><strong>Rekomendasi Hari Ini</strong> <span>pilihan terbaik</span></h2>
          <div class="recommend-layout">
            <?php foreach ($produkRekomendasi as $index => $produk): ?>
              <article class="product-card recommend-card <?= $index === 2 ? 'favorite-card' : ''; ?>">
                <?php if ($index === 2): ?>
                  <button class="favorite-btn" aria-label="favorite">
                    <i class="fa-solid fa-heart"></i>
                  </button>
                <?php endif; ?>

                <div class="product-image">
                  <img src="<?= $produk['gambar']; ?>?v=<?= time(); ?>" alt="<?= htmlspecialchars($produk['nama']); ?>" />
                </div>
                <div class="product-info">
                  <h3><?= htmlspecialchars($produk['nama']); ?></h3>
                  <p class="price"><?= rupiah($produk['harga']); ?></p>
                  <button type="button" class="btn-cart add-to-cart" data-id="<?= $produk['id']; ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Tambah Ke Keranjang
                  </button>
                </div>
              </article>
            <?php endforeach; ?>

            <aside class="promo-banner">
              <div class="promo-box">
                <h3>GRATIS<br>KERUPUK</h3>
                <p>Setiap Pembelian di atas Rp. 5006</p>
                <a href="#" class="promo-btn">BELANJA SEKARANG</a>
              </div>
            </aside>
          </div>
        </section>

        <section class="why-us-section">
          <h2 class="why-title">Kenapa Pilih Kami</h2>
          <p class="why-subtitle">
            Kami menghadirkan produk berkualitas dengan bahan pilihan terbaik, diproses secara higienis,
            dan disajikan dengan cita rasa yang konsisten untuk memberikan pengalaman terbaik di setiap sajian
          </p>

          <div class="why-grid">
            <div class="why-item">
              <div class="why-icon">
                <i class="fa-solid fa-money-bill-wave"></i>
              </div>
              <h3>Harga Terjangkau</h3>
              <p>Harga bersahabat dengan kualitas terbaik</p>
            </div>

            <div class="why-item">
              <div class="why-icon">
                <i class="fa-solid fa-seedling"></i>
              </div>
              <h3>Bahan Segar</h3>
              <p>Kami menggunakan bahan pilihan yang selalu fresh setiap hari</p>
            </div>

            <div class="why-item">
              <div class="why-icon">
                <i class="fa-solid fa-truck-fast"></i>
              </div>
              <h3>Pengiriman Cepat</h3>
              <p>Pesanan diproses dan dikirim dengan cepat dan aman</p>
            </div>
          </div>
        </section>

        <section class="about-section">
          <h2 class="about-title">Tentang kami</h2>
          <div class="about-grid">
            <div class="about-item">
              <h4>Jam Kerja</h4>
              <p>SENIN - SABTU</p>
              <p>11.00 - 17.00 WIB</p>
            </div>

            <div class="about-item">
              <h4>Alamat</h4>
              <p>Jl. Raya Lelea - Tugu, Desa Pangauban,</p>
              <p>Kecamatan Lelea, Kabupaten Indramayu,</p>
              <p>Jawa Barat, 45261</p>
            </div>

            <div class="about-item">
              <h4>Sosial Media</h4>
              <p>Facebook</p>
              <p>WhatsApp</p>
            </div>

            <div class="about-item">
              <h4>&nbsp;</h4>
              <p>Mie Ayam Hijau</p>
              <p>+62 89661095345</p>
            </div>
          </div>
        </section>
      </div>

      <aside class="cart-sidebar" id="keranjangSaya">
        <div class="cart-panel">
          <div class="cart-panel-header">
            <div class="left">
              <i class="fa-solid fa-basket-shopping"></i>
              <span>Keranjang Saya</span>
            </div>

            <div class="cart-header-actions">
              <button type="button" class="clear-cart" id="clearCartBtn">Kosongkan</button>
              <button type="button" class="close-cart-btn" id="closeCart">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
          </div>

          <div class="cart-panel-body" id="cartPanelBody">
            <?php if (empty($_SESSION['cart'])): ?>
              <div class="cart-empty">
                Keranjang masih kosong
              </div>
            <?php else: ?>
              <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                  <div class="cart-item">
                    <img src="<?= $item['gambar']; ?>" alt="<?= htmlspecialchars($item['nama']); ?>">

                    <div class="cart-item-info">
                      <h4><?= htmlspecialchars($item['nama']); ?></h4>
                      <p><?= rupiah($item['harga']); ?></p>

                      <div class="cart-item-actions">
                        <div class="qty-box">
                          <button type="button" class="qty-btn cart-action-btn" data-action="minus" data-id="<?= $item['id']; ?>">-</button>
                          <span><?= $item['qty']; ?></span>
                          <button type="button" class="qty-btn cart-action-btn" data-action="plus" data-id="<?= $item['id']; ?>">+</button>
                        </div>

                        <button type="button" class="remove-btn cart-action-btn" data-action="remove" data-id="<?= $item['id']; ?>">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="cart-footer">
                <div class="cart-summary">
                  <div class="summary-row">
                    <span>Harga:</span>
                    <span><?= number_format($cartTotal, 0, ',', '.'); ?></span>
                  </div>
                  <div class="summary-row">
                    <span>Diskon:</span>
                    <span><?= $diskon > 0 ? number_format($diskon, 0, ',', '.') : '-'; ?></span>
                  </div>
                  <div class="summary-row">
                    <span>Biaya Ongkir:</span>
                    <span><?= number_format($ongkir, 0, ',', '.'); ?></span>
                  </div>
                </div>

                <div class="cart-total">
                  <span>Total Pembayaran</span>
                  <strong><?= number_format($grandTotal, 0, ',', '.'); ?></strong>
                </div>

                <button class="checkout-btn">
                  <span class="icon"><i class="fa-solid fa-circle-check"></i></span>
                  <span>Checkout</span>
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </aside>

    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>Mie Ayam Hijau @Copyright 2028, All Rights Reserved.</p>
      <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms</a>
        <a href="#">Pricing</a>
        <a href="#">Do not sell or share my personal information</a>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>

  <script>
    const cartToggle = document.getElementById('cartToggle');
    const cartSidebar = document.getElementById('keranjangSaya');
    const closeCart = document.getElementById('closeCart');
    const cartCountEl = document.querySelector('.cart-count');
    const cartPanelBody = document.getElementById('cartPanelBody');
    const clearCartBtn = document.getElementById('clearCartBtn');
    const navItems = document.querySelectorAll('.nav-item');
    const highlight = document.querySelector('.nav-highlight');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');

    profileToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      profileDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
      if (!profileDropdown.contains(e.target) && !profileToggle.contains(e.target)) {
        profileDropdown.classList.remove('show');
      }
    });

    function formatRupiah(angka) {
      return 'Rp. ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function addRippleEffect(button, event) {
      const circle = document.createElement('span');
      circle.classList.add('ripple');

      const rect = button.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);

      circle.style.width = circle.style.height = size + 'px';
      circle.style.left = (event.clientX - rect.left - size / 2) + 'px';
      circle.style.top = (event.clientY - rect.top - size / 2) + 'px';

      button.appendChild(circle);

      setTimeout(() => {
        circle.remove();
      }, 500);
    }

    function renderCart(data) {
      cartCountEl.textContent = data.cart_count;
      clearCartBtn.style.display = data.is_empty ? 'none' : 'inline-block';

      if (data.is_empty) {
        cartPanelBody.innerHTML = `
          <div class="cart-empty">
            Keranjang masih kosong
          </div>
        `;
        return;
      }

      let itemsHtml = '';

      data.cart.forEach(item => {
        itemsHtml += `
          <div class="cart-item">
            <img src="${item.gambar}" alt="${item.nama}">

            <div class="cart-item-info">
              <h4>${item.nama}</h4>
              <p>${formatRupiah(item.harga)}</p>

              <div class="cart-item-actions">
                <div class="qty-box">
                  <button type="button" class="qty-btn cart-action-btn" data-action="minus" data-id="${item.id}">-</button>
                  <span>${item.qty}</span>
                  <button type="button" class="qty-btn cart-action-btn" data-action="plus" data-id="${item.id}">+</button>
                </div>

                <button type="button" class="remove-btn cart-action-btn" data-action="remove" data-id="${item.id}">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        `;
      });

      cartPanelBody.innerHTML = `
        <div class="cart-items">
          ${itemsHtml}
        </div>

        <div class="cart-footer">
          <div class="cart-summary">
            <div class="summary-row">
              <span>Harga:</span>
              <span>${new Intl.NumberFormat('id-ID').format(data.cart_total)}</span>
            </div>
            <div class="summary-row">
              <span>Diskon:</span>
              <span>${data.diskon > 0 ? new Intl.NumberFormat('id-ID').format(data.diskon) : '-'}</span>
            </div>
            <div class="summary-row">
              <span>Biaya Ongkir:</span>
              <span>${new Intl.NumberFormat('id-ID').format(data.ongkir)}</span>
            </div>
          </div>

          <div class="cart-total">
            <span>Total Pembayaran</span>
            <strong>${new Intl.NumberFormat('id-ID').format(data.grand_total)}</strong>
          </div>

          <a href="checkout.php" class="checkout-btn">
            <i class="fa-solid fa-circle-check"></i> Checkout!
          </a>
        </div>
      `;
    }

    async function cartRequest(action, id = null) {
      const formData = new URLSearchParams();
      formData.append('action', action);
      if (id !== null) formData.append('id', id);

      try {
        const response = await fetch('cart_api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: formData.toString()
        });

        const data = await response.json();

        if (data.success) {
          renderCart(data);

          const badge = document.querySelector('.cart-count');
          badge.classList.add('animate');

          setTimeout(()=>{
            badge.classList.remove('animate');
          },300);

          cartSidebar.classList.add('show');
        }
      } catch (error) {
        console.error('Cart error:', error);
      }
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', function (e) {
        addRippleEffect(this, e);
        const id = this.dataset.id;
        cartRequest('add', id);
      });
    });

    cartPanelBody.addEventListener('click', function (e) {
      const btn = e.target.closest('.cart-action-btn');
      if (!btn) return;

      const action = btn.dataset.action;
      const id = btn.dataset.id;

      if (action === 'remove') {
        const ok = confirm('Hapus item ini?');
        if (!ok) return;
      }

      cartRequest(action, id);
    });

    clearCartBtn.addEventListener('click', function () {
      const ok = confirm('Kosongkan keranjang?');
      if (ok) cartRequest('clear');
    });

    cartToggle.addEventListener('click', function () {
      cartSidebar.classList.toggle('show');
      if (cartSidebar.classList.contains('show')) {
        cartRequest('get');
      }
    });

    closeCart.addEventListener('click', function () {
      cartSidebar.classList.remove('show');
    });

    document.addEventListener('click', function (e) {
      if (!cartSidebar.contains(e.target) && !cartToggle.contains(e.target)) {
        cartSidebar.classList.remove('show');
      }
    });

    window.addEventListener('load', function () {
      if (clearCartBtn) {
        clearCartBtn.style.display = <?= empty($_SESSION['cart']) ? "'none'" : "'inline-block'" ?>;
      }
    });

    navItems.forEach(item => {
      item.addEventListener('mouseenter', function () {
        const rect = this.getBoundingClientRect();
        const parentRect = this.parentElement.getBoundingClientRect();

        highlight.style.width = rect.width + 'px';
        highlight.style.left = (rect.left - parentRect.left) + 'px';
      });
    });

    /* balik ke active saat mouse keluar */
    document.querySelector('.nav-menu').addEventListener('mouseleave', function () {
      const active = document.querySelector('.nav-item.active');
      const rect = active.getBoundingClientRect();
      const parentRect = active.parentElement.getBoundingClientRect();

      highlight.style.width = rect.width + 'px';
      highlight.style.left = (rect.left - parentRect.left) + 'px';
    });

    /* set posisi awal */
    window.addEventListener('load', function () {
      const active = document.querySelector('.nav-item.active');
      const rect = active.getBoundingClientRect();
      const parentRect = active.parentElement.getBoundingClientRect();

      highlight.style.width = rect.width + 'px';
      highlight.style.left = (rect.left - parentRect.left) + 'px';
    });
  </script>

  <script src="page-transition.js"></script>
</body>
</html>
