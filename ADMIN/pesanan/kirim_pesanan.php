<?php
include "../../koneksi.php";

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "UPDATE pesanan SET
              status = 'dikirim'
              WHERE id_pesanan = '$id'";

    if (mysqli_query($conn, $query)) {

        header("Location: pesanan.php");

    } else {

        echo "Error: " . mysqli_error($conn);
    }

} else {

    header("Location: pesanan.php");
}
?>