<?php
session_start();

if(!isset($_SESSION['nohp'])){
    header("Location: index.php");
    exit();
}

echo "<h2>Selamat datang, ".$_SESSION['nohp']."</h2>";
echo "<a href='logout.php'>Logout</a>";
?>