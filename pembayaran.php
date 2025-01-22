<?php
session_start(); // Memulai session

// Menghubungkan ke database
include 'db.php'; // Meng-include file db.php

// Cek apakah session ada
if (!isset($_SESSION['name']) || !isset($_SESSION['address'])) {
    header("Location: checkout.php"); // Redirect ke halaman checkout jika tidak ada data
    exit();
}

// Ambil data dari session
$name = $_SESSION['name'];
$address = $_SESSION['address'];

// Mengambil data keranjang
try {
    $sql = "SELECT * FROM keranjang";
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
    <title>Pembayaran - Toko Obat Gevarma</title>
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
        .payment-option {
            margin: 20px 0;
        }
        .payment-method {
            margin: 10px 0;
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
    <h1><i class="fas fa-credit-card"></i> Pembayaran</h1>
</header>

<div class="container">
    <h2>Ringkasan Pesanan</h2>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($name); ?></p>
    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($address); ?></p>

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
        <strong>Total Keseluruhan: Rp <?php echo number_format($grandTotal, 2, ',', '.'); ?></ strong>
    </div>

    <div class="payment-option">
        <h3>Pilih Metode Pembayaran:</h3>
        <div class="payment-method">
            <input type="radio" id="cash" name="payment" value="cash" onclick="togglePaymentDetails('cash')" checked>
            <label for="cash">Cash</label>
        </div>
        <div class="payment-method">
            <input type="radio" id="digital" name="payment" value="digital" onclick="togglePaymentDetails('digital')">
            <label for="digital">Digital</label>
        </div>
    </div>

    <div id="digital-options" style="display:none;">
        <h4>Pilih Metode Digital:</h4>
        <div class="payment-method">
            <input type="radio" id="qris" name="digital_payment" value="qris">
            <label for="qris">QRIS</label>
        </div>
        <div class="payment-method">
            <input type="radio" id="transfer" name="digital_payment" value="transfer">
            <label for="transfer">Transfer</label>
        </div>
    </div>

    <button class="btn" onclick="confirmPayment()">Konfirmasi Pembayaran</button>
</div>

<footer>
 <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>
</footer>

<script>
    function togglePaymentDetails(paymentType) {
        const digitalOptions = document.getElementById('digital-options');
        digitalOptions.style.display = paymentType === 'digital' ? 'block' : 'none';
    }

    function confirmPayment() {
        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
        if (paymentMethod === 'cash') {
            // Redirect ke invoice.php dengan parameter lunas
            window.location.href = 'invoice.php?status=lunas';
        } else {
            let paymentDetails = `Metode Pembayaran: ${paymentMethod}`;
            const digitalPayment = document.querySelector('input[name="digital_payment"]:checked');
            if (digitalPayment) {
                paymentDetails += `\nDetail: ${digitalPayment.value}`;
            } else {
                alert('Silakan pilih metode digital.');
                return;
            }
            alert(paymentDetails + '\nPembayaran berhasil!');
        }
    }
</script>

<footer>
    <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>Facebook : tokoobatgevarma </p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>
</footer>
</body>
</html>