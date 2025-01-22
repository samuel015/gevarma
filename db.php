<?php
$servername = "sql303.infinityfree.com"; // Host MySQL Anda
$username = "if0_38115228"; // User MySQL Anda
$password = "dqS3CUZgDHz"; // Password MySQL Anda
$dbname = "if0_38115228_toko_obat"; // Nama database Anda

try {
    // Membuat koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname: " . $e->getMessage());
}
?>
