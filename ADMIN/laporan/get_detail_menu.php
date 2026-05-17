<?php
include "../../koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT dp.*, m.nama_menu, m.harga
                                FROM detail_pesanan dp
                                JOIN menu m
                                ON dp.id_menu = m.id_menu
                                WHERE dp.id_pesanan = '$id'");

while ($row = mysqli_fetch_assoc($query)) {
    echo "
    <tr>
        <td>{$row['nama_menu']}</td>
        <td>{$row['jumlah']}</td>
        <td>
            Rp " . number_format($row['harga'], 0, ',', '.') . "
        </td>
        <td>
            Rp " . number_format($row['subtotal'], 0, ',', '.') . "
        </td>
    </tr>
    ";
}
