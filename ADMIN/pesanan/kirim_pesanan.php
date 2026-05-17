<?php
include "../../koneksi.php";

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $query = "UPDATE pesanan SET
              status = 'dikirim'
              WHERE id_pesanan = '$id'";

    if (mysqli_query($koneksi, $query)) {

        header("Location: pesanan.php");

    } else {

        echo "Error: " . mysqli_error($koneksi);
    }

} else {

    header("Location: pesanan.php");
}
?>