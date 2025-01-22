<?php
// Mulai session
session_start();

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

// Cek jika tombol "Belanja Lagi" ditekan
if (isset($_GET['action']) && $_GET['action'] == 'belanja_lagi') {
    // Hapus semua item dari keranjang di tampilan
    $result = []; // Reset hasil keranjang
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Toko Obat Gevarma</title>
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
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        footer {
            background: #35424a;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .btn-pembayaran {
            display: inline-block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50; /* Hijau */
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            font-size: 16px;
        }
        .btn-pembayaran:hover {
            background-color: #45a049; /* Warna lebih gelap saat hover */
        }
        .btn-pembayaran i {
            margin-right: 5px; /* Jarak antara ikon dan teks */
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
        <h1>Keranjang Belanja</h1>
    </header>
    <table>
        <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
        <?php if (empty($result)): ?>
            <tr>
                <td colspan="4">Keranjang Anda kosong.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($result as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['produk']); ?></td> <!-- Menggunakan 'produk' -->
                    <td><?php echo $item['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($item['total'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <div style="text-align: center;">
        <a href="pembayaran.php" class="btn-pembayaran"><i class="fas fa-credit-card"></i>Pembayaran</a> <!-- Tombol Pembayaran dengan ikon -->
        <a href="keranjang.php?action=belanja_lagi" class="btn-belanja-lagi"><i class="fas fa-shopping-cart"></i>Belanja Lagi</a> <!-- Tombol Belanja Lagi dengan ikon -->
    </div>
    <footer>
    <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>Facebook : tokoobatgevarma </p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>
    </footer>
</body>
</html>