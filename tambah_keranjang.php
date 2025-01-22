<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "toko_obat"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data keranjang dari database
$sql_keranjang = "SELECT * FROM keranjang";
$result_keranjang = $conn->query($sql_keranjang);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Obat Gevarma - Keranjang</title>
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
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
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
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Toko Obat Gevarma</h1>
</header>

<div class="container">
    <h2>Keranjang Belanja</h2>
    <?php
    // Cek apakah ada produk di keranjang
    if ($result_keranjang->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>Nama Produk</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr>';
        
        // Output data dari setiap baris keranjang
        while($row = $result_keranjang->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["nama_produk"] . '</td>';
            echo '<td>' . $row["jumlah"] . '</td>';
            echo '<td>Rp ' . number_format($row["harga"], 0, ',', '.') . '</td>';
            echo '<td>Rp ' . number_format($row["total"], 0, ',', '.') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Keranjang Anda kosong.</p>';
    }
    ?>
</div>

<footer>
    <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>
</footer>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>