<?php
// Mulai session
session_start();

// Ambil status dari URL
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Menghubungkan ke database
include 'db.php'; // Pastikan Anda memiliki file db.php yang mengatur koneksi PDO

// Mengambil data keranjang
try {
    $sql = "SELECT * FROM keranjang"; // Ambil semua data dari keranjang
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Toko Obat Gevarma</title>
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
        footer {
            background: #35424a;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #35424a;
            color: white;
        }
        .btn-belanja-lagi {
            display: inline-block;
            padding: 10px;
            background-color: #007BFF; /* Biru */
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            font-size: 16px;
            margin-top: 20px; /* Jarak atas */
        }
        .btn-belanja-lagi:hover {
            background-color: #0056b3; /* Warna lebih gelap saat hover */
        }
        .btn-belanja-lagi i {
            margin-right: 5px; /* Jarak antara ikon dan teks */
        }
    </style>
</head>
<body>

<header>
    <h1>Invoice Pembayaran</h1>
</header>

<div class="container">
    <?php if ($status === 'lunas'): ?>
        <h2>Pembayaran Anda Lunas!</h2>
        <p>Dengan detail pembayaran seperti berikut :</p>
        <h3>Detail Pesanan:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
            <?php
            $grandTotal = 0; // Inisialisasi total keseluruhan
            if ($result) {
                foreach ($result as $row) {
                    $grandTotal += $row["total"]; // Menambahkan total setiap item ke grand total
                    echo "<tr>
                            <td>" . htmlspecialchars($row["id"]) . "</td>
                            <td>" . htmlspecialchars($row["produk"]) . "</td>
                            <td>" . htmlspecialchars($row["jumlah"]) . "</td>
                            <td>Rp " . number_format($row["harga"], 2, ',', '.') . "</td>
                            <td>Rp " . number_format($row["total"], 2, ',', '.') . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Keranjang Anda kosong.</td></tr>";
            }
            ?>
        </table>
        <div class="total">
            <strong>Total Keseluruhan: Rp <?php echo number_format($grandTotal, 2, ',', '.'); ?></strong>
        </div>
         <!-- Tombol Print -->
        <button onclick="window.print()" style="margin-top: 20px; padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Invoice
        </button>
    <?php else: ?>
        <h2>Pembayaran Gagal</h2>
        <p>Silakan coba lagi.</p>
    <?php endif; ?>
    
    <!-- Tombol Belanja Lagi -->
    <a href="produk.php" class="btn-belanja-lagi"><i class="fas fa-shopping-cart"></i>Belanja Lagi</a>
</div>

<footer>
     <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>Facebook : tokoobatgevarma </p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>
</footer>

</body>
</html>