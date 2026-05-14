<?php
include "../../koneksi.php";

$dari   = isset($_GET['dari']) ? $_GET['dari'] : '';
$hingga = isset($_GET['hingga']) ? $_GET['hingga'] : '';

// Ambil data dari database (Gunakan JOIN untuk mendapatkan nama pelanggan)
$query = "SELECT p.*, pel.nama_pelanggan AS nama 
          FROM pesanan p 
          JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan ";

if ($dari != '' && $hingga != '') {

    $query .= "WHERE DATE(p.tanggal)
               BETWEEN '$dari'
               AND '$hingga' ";
}

$query .= "ORDER BY p.tanggal DESC";
$result = mysqli_query($conn, $query);

// Hitung Total Pendapatan & Total Pesanan secara dinamis
$sql_stats = "SELECT SUM(total_harga) as total_pendapatan, COUNT(*) as total_pesanan FROM pesanan";
$stats_res = mysqli_query($conn, $sql_stats);
$stats = mysqli_fetch_assoc($stats_res);

$totalPendapatan = $stats['total_pendapatan'] ?? 0;
$totalPesanan = $stats['total_pesanan'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan - Mie Ayam Hijau</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="laporan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>

    <div class="layout">
        <aside class="sidebar">
            <div>
                <div class="brand">
                    <img src="../img/logo.jpeg" class="brand-logo">
                    <div class="brand-text">
                        <h2>MIE AYAM <span>HIJAU</span></h2>
                        <p>Admin Dashboard</p>
                    </div>
                </div>
                <nav class="menu-nav" style="margin-top:40px;">
                    <a href="../dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
                    <a href="../menu.php" class="menu-item"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
                    <a href="../pesanan/pesanan.php" class="menu-item"><i class="fa-solid fa-basket-shopping"></i><span>Pesanan</span></a>
                    <a href="laporan.php" class="menu-item active"><i class="fa-regular fa-file-lines"></i><span>Laporan</span></a>
                </nav>
            </div>
            <div class="sidebar-footer">
                <div class="admin-box" onclick="toggleAdminDropdown()">
                    <div class="admin-dropdown" id="adminDropdown">
                        <div class="admin-dropdown-avatar"><i class="fa-solid fa-user"></i></div>
                        <a href="../kelola_akun.php">Kelola Akun</a>
                        <a href="../logout.php" class="logout">Keluar</a>
                    </div>
                    <div class="admin-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="admin-info">
                        <h4>Admin</h4>
                        <p>Administrator</p>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-arrow" id="adminArrow"></i>
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
                <h1>Laporan Pesanan</h1>

                <div class="laporan-top">
                    <div class="lap-card">
                        <p>Total Pendapatan</p>
                        <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></h3>
                    </div>
                    <div class="lap-card">
                        <p>Total Pesanan</p>
                        <h3><?= $totalPesanan; ?></h3>
                    </div>
                    <div class="filter">
                        <label>Dari:</label> <input type="date" id="dari" name="dari" value="<?= $dari; ?>" onchange="filterTanggal()">
                        <label>Hingga:</label> <input type="date" id="hingga" name="hingga" value="<?= $hingga; ?>" onchange="filterTanggal()">
                        <button class="btn-export" onclick="exportExcel()">Ekspor (Excel)</button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID_Pesanan</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Total Harga</th>
                                <th>Tanggal Pesan</th>
                                <th>Waktu Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)):
                                // Logika warna status
                                $statusClass = (strtolower($row['status']) == 'selesai') ? 'status-green' : 'status-yellow';
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['id_pesanan']; ?></td>
                                    <td><?= $row['nama']; ?></td>
                                    <td><span class="status <?= $statusClass ?>"><?= $row['status']; ?></span></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                    <td><?= ($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?></td>
                                    <td>
                                        <button class="btn-detail" onclick="showDetail(
                                            '<?= $row['id_pesanan']; ?>',
                                            '<?= $row['nama']; ?>',
                                            '<?= $row['status']; ?>',
                                            '<?= number_format($row['total_harga'], 0, ',', '.'); ?>',
                                            '<?= date('d/m/Y H:i', strtotime($row['tanggal'])); ?>',
                                            '<?= ($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?>',
                                            '<?= number_format($row['ongkir'], 0, ',', '.'); ?>')">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div id="modalDetail" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Pesanan</h2>
                <div class="header-right">
                    <button onclick="downloadPDF()"
                        class="btn-download">
                        <i class="ri-download-line"></i>
                        Simpan Bukti
                    </button>
                    <span class="close"
                        onclick="closeModal()">
                        &times;
                    </span>
                </div>
            </div>
            <div class="detail-grid" id="invoiceArea">
                <div class="invoice-header">
                    <img src="../img/logo.jpeg" class="invoice-logo">
                    <h1>MIE AYAM HIJAU</h1>
                    <p>Jl. Raya Lelea - Tugu, Desa Pangauban, Kecamatan Lelea, Kabupaten Indramayu, Jawa Barat, 45261</p>
                    <p>Telp: +62 89661095345</p>
                    <hr>
                    <h3>BUKTI TRANSAKSI</h3>
                </div>
                <p><strong>ID Pesanan:</strong> <span id="d_id"></span></p>
                <p><strong>Nama:</strong> <span id="d_nama"></span></p>
                <p><strong>Status:</strong> <span id="d_status"></span></p>
                <p><strong>Subtotal:</strong> Rp <span id="d_subtotal"></span></p>
                <p><strong>Ongkir:</strong> Rp <span id="d_ongkir"></span></p>
                <p><strong>Total Harga:</strong> Rp <span id="d_total"></span></p>
                <p><strong>Tanggal Pesan:</strong> <span id="d_tanggal"></span></p>
                <p><strong>Waktu Selesai:</strong> <span id="d_selesai"></span></p>
                <hr>
                <h3>Detail Menu</h3>
                <table class="table-detail">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detailMenuBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showDetail(id, nama, status, total, tanggal, selesai, ongkir) {
            document.getElementById("d_id").innerText = id;
            document.getElementById("d_nama").innerText = nama;
            document.getElementById("d_status").innerText = status;
            document.getElementById("d_ongkir").innerText = ongkir;
            document.getElementById("d_total").innerText = total;
            let totalNumber = parseInt(total.replace(/\./g, ''));
            let ongkirNumber = parseInt(ongkir.replace(/\./g, ''));
            let subtotal = totalNumber - ongkirNumber;
            document.getElementById("d_subtotal").innerText = subtotal.toLocaleString('id-ID');
            document.getElementById("d_tanggal").innerText = tanggal;
            document.getElementById("d_selesai").innerText = selesai;
            fetch('get_detail_menu.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("detailMenuBody").innerHTML = data;
                });
            document.getElementById("modalDetail").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("modalDetail").style.display = "none";
        }

        function downloadPDF() {
            const element =
                document.getElementById('invoiceArea');

            const opt = {
                margin: 0.5,
                filename: 'Bukti_Transaksi_' +
                    document.getElementById("d_id").innerText +
                    '.pdf',

                image: {
                    type: 'jpeg',
                    quality: 1
                },

                html2canvas: {
                    scale: 4,
                    useCORS: true
                },

                jsPDF: {
                    unit: 'mm',
                    format: [210, 80],
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        }

        function filterTanggal() {
            let dari = document.getElementById('dari').value;
            let hingga = document.getElementById('hingga').value;
            window.location.href = 'laporan.php?dari=' + dari + '&hingga=' + hingga;
        }

        function exportExcel() {
            var dari = document.getElementById('dari').value;
            var hingga = document.getElementById('hingga').value;
            var url = 'export_excel.php';
            if (dari && hingga) {
                url += '?dari=' + dari + '&hingga=' + hingga;
            } else if (dari) {
                url += '?dari=' + dari;
            } else if (hingga) {
                url += '?hingga=' + hingga;
            }
            window.location.href = url;
        }

        function toggleAdminDropdown() {
            var dd = document.getElementById('adminDropdown');
            var arrow = document.getElementById('adminArrow');
            dd.classList.toggle('show');
            arrow.classList.toggle('rotated');
        }
        document.addEventListener('click', function(e) {
            var box = document.querySelector('.admin-box');
            if (box && !box.contains(e.target)) {
                document.getElementById('adminDropdown').classList.remove('show');
                document.getElementById('adminArrow').classList.remove('rotated');
            }
        });
    </script>

</body>

</html>