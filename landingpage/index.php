<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Ayam Hijau</title>

    <link rel="stylesheet" href="style-landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-wrap">
            <div class="logo-area">
                <div class="logo-icon">
                    <!-- Mengganti ikon <i> dengan tag <img> -->
                    <img src="img/logo.jpeg" alt="Logo Mie Ayam Hijau" style="width: 100%; height: auto; border-radius: 50%;">
                </div>
                <h2>Mie Ayam <span>Hijau</span></h2>
            </div>

            <nav class="nav-menu">
                <a href="#home">Home</a>
                <a href="#menu">Menu</a>
                <a href="#tentang">Tentang Kami</a>
                <a href="#kontak">Kontak</a>
            </nav>

            <a href="../login/index.php" class="btn-order">
                Pesan Sekarang
            </a>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="container hero-grid">
            <div class="hero-text">
                <p class="small-title">Nikmati</p>
                <h1>
                    Mie Ayam Hijau <br>
                    yang <span>Lezat & Sehat</span>
                </h1>

                <div class="line"></div>

                <p class="desc">
                    Mie ayam dengan cita rasa khas, dibuat dari bahan segar dan pilihan
                    untuk pengalaman makan yang lebih nikmat setiap hari.
                </p>

                <div class="hero-buttons">
                    <a href="../login/index.php" class="btn-primary">
                        Pesan Sekarang
                    </a>

                    <a href="#menu" class="btn-secondary">
                        Lihat Menu
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="img/mie-ayam.png" alt="Mie Ayam Hijau">

                <div class="badge-fresh">
                    <h3>100%</h3>
                    <p>Bahan Segar</p>
                    <i class="fa-solid fa-leaf"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section class="about-section section" id="tentang">
        <div class="container">
            <div class="section-heading center">
                <h2>Tentang Kami</h2>
                <div class="mini-line"></div>
            </div>

            <div class="about-content">
                <img src="img/2.jpg" alt="Tentang Mie Ayam Hijau">

                <div class="about-text">
                    <p>
                        Mie Ayam Hijau berkomitmen menyajikan makanan berkualitas
                        dengan bahan-bahan segar dan proses yang higienis.
                    </p>

                    <p>
                        Kami mengutamakan rasa, kesehatan, dan kepuasan pelanggan
                        dalam setiap mangkuk mie yang kami sajikan.
                    </p>

                    <a href="#" class="btn-small">
                        Selengkapnya
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY US -->
    <section class="why-section section">
        <div class="container">
            <div class="section-heading center">
                <h2>Kenapa Memilih Kami?</h2>
                <div class="mini-line"></div>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <i class="fa-solid fa-leaf"></i>
                    <h3>Bahan Segar</h3>
                    <p>Kami hanya menggunakan bahan-bahan segar dan berkualitas setiap hari.</p>
                </div>

                <div class="why-card">
                    <i class="fa-solid fa-bowl-food"></i>
                    <h3>Rasa Autentik</h3>
                    <p>Resep khas mie ayam hijau dengan cita rasa yang konsisten dan lezat.</p>
                </div>

                <div class="why-card">
                    <i class="fa-solid fa-motorcycle"></i>
                    <h3>Pelayanan Cepat</h3>
                    <p>Pesanan diproses cepat dan siap diantar ke lokasi Anda dengan aman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MENU -->
    <section class="menu-section section" id="menu">
        <div class="container">
            <div class="section-heading center">
                <p class="section-label">MENU FAVORIT</p>
                <h2>Pilihan Menu Favorit Kami</h2>
                <div class="mini-line"></div>
            </div>

            <div class="menu-grid">
                <div class="menu-card">
                    <img src="img/mie ayam polos.png" alt="Mie Ayam Original">
                    <div class="menu-info">
                        <h3>Mie Ayam Hijau Original</h3>
                        <p>Mie hijau kenyal dengan ayam berbumbu khas dan sayuran segar.</p>
                        <div class="price-row">
                            <span>Rp 18.000</span>
                            <button><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card">
                    <img src="img/mie ayam ceker.jpeg" alt="Mie Ayam Ceker">
                    <div class="menu-info">
                        <h3>Mie Ayam Hijau Ceker</h3>
                        <p>Mie hijau dengan ayam gurih dan bakso sapi pilihan.</p>
                        <div class="price-row">
                            <span>Rp 22.000</span>
                            <button><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card">
                    <img src="img/bakso.jpeg" alt="Mie Ayam Bakso">
                    <div class="menu-info">
                        <h3>Mie Ayam Hijau Bakso</h3>
                        <p>Mie hijau dengan ayam gurih dan pangsit isi ayam spesial.</p>
                        <div class="price-row">
                            <span>Rp 20.000</span>
                            <button><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-center">
                <a href="../login/index.php" class="btn-all-menu">
                    Lihat Semua Menu
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- TESTIMONI -->
    <section class="testimonial-section section">
        <div class="container">
            <div class="section-heading center">
                <h2>Apa Kata Pelanggan Kami</h2>
                <div class="mini-line"></div>
            </div>

            <div class="testimonial-box">
                <div class="quote-card">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <p>
                        Mie-nya kenyal, ayamnya gurih, dan kuahnya segar banget!
                        Porsi pas dan harga terjangkau. Pasti jadi langganan!
                    </p>

                    <div class="user">
                        <div class="user-img"></div>
                        <div>
                            <h4>Nana Irawan</h4>
                            <span>Pelanggan Setia</span>
                        </div>
                    </div>
                </div>

                <img src="img/testimoni1.jpeg" alt="Testimoni Mie Ayam Hijau">
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" id="kontak">
        <div class="container footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <i class="fa-solid fa-bowl-food"></i>
                    <h3>Mie Ayam <span>Hijau</span></h3>
                </div>

                <p>
                    Menyajikan mie ayam hijau lezat dan sehat dengan bahan segar
                    pilihan untuk Anda dan keluarga.
                </p>

                <div class="socials">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Kontak Kami</h4>
                <p><i class="fa-solid fa-location-dot"></i> Jl. Sehat No. 123, Bandung</p>
                <p><i class="fa-solid fa-phone"></i> 08 1234 5678 90</p>
                <p><i class="fa-solid fa-envelope"></i> mieayamhijau@gmail.com</p>
                <p><i class="fa-brands fa-instagram"></i> @mieayamhijau</p>
            </div>

            <div class="footer-col">
                <h4>Jam Buka</h4>
                <p><strong>Senin - Jumat</strong><br>10.00 - 21.00</p>
                <p><strong>Sabtu - Minggu</strong><br>09.00 - 21.00</p>
            </div>
        </div>

        <div class="footer-bottom">
            © 2026 Mie Ayam Hijau. All Rights Reserved.
        </div>
    </footer>

    <script src="script-landing.js"></script>
</body>
</html>