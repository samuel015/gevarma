<?php
// Menghubungkan ke database
include 'db.php'; // Meng-include file db.php

// Proses checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Simpan data ke session untuk digunakan di halaman pembayaran
    session_start();
    $_SESSION['name'] = $name;
    $_SESSION['address'] = $address;

    // Redirect ke halaman pembayaran
    header("Location: pembayaran.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Toko Obat Gevarma</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background: #35424a;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        footer {
            background: #35424a;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-shopping-cart"></i> Checkout</h1>
</header>

<div class="container">
    <h2>Form Checkout</h2>
    <form method="POST" action="">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="address">Alamat:</label>
        <input type="text" id="address" name="address" required><br><br>
        <button type="submit" class="btn">Pembayaran</button>
    </form>
</div>

<footer>
    <h3>Kontak Kami</h3>
    <p>&copy; 2023 Toko Obat Gevarma. All rights reserved.</p>
     <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
</footer>

</body>
</html>