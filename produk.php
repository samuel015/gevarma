<?php
// Sertakan file koneksi
include 'db.php'; // Pastikan file ini berada di direktori yang sama

// Ambil data produk dari database
$sql_produk = "SELECT * FROM produk"; // Ambil semua produk
$result_produk = $pdo->query($sql_produk); // Gunakan $pdo

// Cek apakah query produk berhasil
if (!$result_produk) {
    die("Query produk gagal: " . $pdo->errorInfo()[2]); // Menggunakan errorInfo() untuk PDO
}

// Cek apakah ada data yang dikembalikan
if ($result_produk->rowCount() == 0) {
    die("Tidak ada produk yang ditemukan.");
}

// Ambil data layanan dari database
$sql_layanan = "SELECT * FROM layanan"; // Ambil semua layanan
$result_layanan = $pdo->query($sql_layanan); // Gunakan $pdo

// Cek apakah query layanan berhasil
if (!$result_layanan) {
    die("Query layanan gagal: " . $pdo->errorInfo()[2]); // Menggunakan errorInfo() untuk PDO
}

// Menambahkan item ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $produk = $_POST['produk'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $total = $harga * $jumlah;

    // Cek apakah item sudah ada di keranjang
    $sql_check = "SELECT * FROM keranjang WHERE id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$id]);
    $existing_item = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Jika item sudah ada, update jumlah dan total
        $new_jumlah = $existing_item['jumlah'] + $jumlah;
        $new_total = $harga * $new_jumlah;

        $sql_update = "UPDATE keranjang SET jumlah = ?, total = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$new_jumlah, $new_total, $id]);
    } else {
        // Jika item belum ada, insert baru
        $sql_insert = "INSERT INTO keranjang (id, produk, jumlah, harga, total) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$id, $produk, $jumlah, $harga, $total]);
    }

    // Redirect ke halaman keranjang
    header("Location: keranjang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Obat Gevarma</title>
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
        nav {
            margin: 20px 0;
        }
        nav a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        nav a:hover {
            background: #444;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        .products, .services {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }
        .product, .service {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            flex: 1 1 calc(30% - 20px);
            text-align: center;
            transition: transform 0.3s;
        }
        .product:hover, .service:hover {
            transform: translateY(-5px);
        }
        .product img, .service img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
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
        @media (max-width: 768px) {
            .product, .service {
                flex: 1 1 calc(45% - 20px);
            }
        }
        @media (max-width: 480px) {
            .product, .service {
                flex: 1 1 100%;
            }
        }
        .btn-add {
            display: inline-block;
            background-color: #4CAF50; /* Hijau */
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            margin-top: 10px; /* Jarak antara tombol */
        }
        .btn-add:hover {
            background-color: #45a049; /* Warna lebih gelap saat hover */
        }
    </style>
</head>
<body>
    <header>
        <h1>Toko Obat Gevarma</h1>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
        </nav>
    </header>
    <div class="container">
        <h2>Nama Kami</h2>
        <div class="products">
            <?php while ($row = $result_produk->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product">
                    <img src="path/to/image/<?php echo isset($row['image']) && !empty($row['image']) ? $row['image'] : 'default.jpg'; ?>" alt="<?php echo isset($row['produk']) && !empty($row['produk']) ? htmlspecialchars($row['produk']) : 'Nama Tanpa Nama'; ?>">
                    <h3><i class="fas fa-capsules"></i> <?php echo isset($row['produk']) && !empty($row['produk']) ? htmlspecialchars($row['produk']) : 'Nama Tanpa Nama'; ?></h3>
                    <p><?php echo isset($row['deskripsi']) && !empty($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : 'Deskripsi tidak tersedia'; ?></p>
                    <p>Harga: Rp <?php echo isset($row['harga']) ? number_format($row['harga'], 0, ',', '.') : 'Harga tidak tersedia'; ?></p>
                    <form action="produk.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="produk" value="<?php echo htmlspecialchars($row['nama']); ?>">
                        <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" style="width: 50px;">
                        <button type="submit" class="btn-add">Beli Sekarang</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <h2>Layanan Kami</h2>
        <div class="services">
            <?php while ($row = $result_layanan->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="service">
                    <img src="path/to/image/<?php echo isset($row['image']) && !empty($row['image']) ? $row['image'] : 'default.jpg'; ?>" alt="<?php echo isset($row['nama_layanan']) && !empty($row['nama_layanan']) ? htmlspecialchars($row['nama_layanan']) : 'Layanan Tanpa Nama'; ?>">
                    <h3><i class="fas fa-syringe"></i> <?php echo isset($row['nama_layanan']) && !empty($row['nama_layanan']) ? htmlspecialchars($row['nama_layanan']) : 'Layanan Tanpa Nama'; ?></h3>
                    <p><?php echo isset($row['deskripsi']) && !empty($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : 'Deskripsi tidak tersedia'; ?></p>
                    <p>Harga: Rp <?php echo isset($row['harga']) ? number_format($row['harga'], 0, ',', '.') : 'Harga tidak tersedia'; ?></p>
                    <form action="produk.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="produk" value="<?php echo htmlspecialchars($row['nama_layanan']); ?>">
                        <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" style="width: 50px;">
                        <button type="submit" class="btn-add">Beli Sekarang</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <footer>
    <h3>Kontak Kami</h3>
    <p>Email: tokoobatgevariel@gmail.com | Telepon: (021) 123-4567</p>
    <p>Facebook : tokoobatgevarma </p>
    <p>&copy; 2025 Toko Obat Gevarma. Semua hak dilindungi.</p>    
    </footer>
</body>
</html>