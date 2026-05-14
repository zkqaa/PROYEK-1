<?php
include "koneksi.php";

/* HEADER EXCEL */
header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_order.xls");

/* AMBIL DATA */
$query = mysqli_query($koneksi, "
    SELECT *
    FROM pesanan
    ORDER BY tanggal DESC
");
?>

<table border="1">
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>Tanggal</th>
            <th>Metode Pengiriman</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    <?php while($data = mysqli_fetch_assoc($query)): ?>

        <tr>
            <td><?= $data['id_pesanan']; ?></td>

            <td>
                <?= date('d-m-Y H:i', strtotime($data['tanggal'])); ?>
            </td>

            <td>
                <?= $data['metode_pengiriman']; ?>
            </td>

            <td>
                Rp <?= number_format($data['total_harga'],0,',','.'); ?>
            </td>

            <td>
                <?= $data['status']; ?>
            </td>
        </tr>

    <?php endwhile; ?>

    </tbody>
</table>