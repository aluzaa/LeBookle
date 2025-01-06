<?php
// Koneksi ke database
require_once 'db.php';

// Periksa apakah ID produk diterima
if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

$books_id = $_GET['id'];

// Ambil detail produk dari database
$query = $conn->prepare("SELECT * FROM books WHERE id = ?");
$query->bind_param("i", $books_id);
$query->execute();
$result = $query->get_result();

// Periksa apakah data produk ditemukan
if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$books = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($books['judul']); ?></title>
    <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
    <style>
        /* Global Style */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Container Style */
        .product-detail {
            padding: 40px 15px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Title and Meta */
        .product-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: #333;
        }

        .product-meta {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Cover Image */
        .cover-image {
            max-width: 40%;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Product Details */
        .product-desc {
            font-size: 1.1rem;
            margin-bottom: 20px;
            text-align: justify;
        }

        .product-info p {
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <section class="product-detail">
        <div class="container">
            <!-- Gambar Cover -->
            <?php if (!empty($books['cover'])): ?>
                <img src="<?php echo htmlspecialchars($books['cover']); ?>" alt="Cover Buku" class="cover-image">
            <?php else: ?>
                <p class="text-center text-muted">Gambar tidak tersedia</p>
            <?php endif; ?>

            <!-- Judul Produk -->
            <h1 class="product-title"><?php echo htmlspecialchars($books['judul']); ?></h1>
            <p class="product-meta">
                ID Buku: <?php echo htmlspecialchars($books['id']); ?> |
                Ditulis oleh: <?php echo htmlspecialchars($books['penulis']); ?> | 
                Penerbit: <?php echo htmlspecialchars($books['penerbit']); ?> | 
                Tahun Terbit: <?php echo htmlspecialchars($books['tahun_terbit']); ?>
            </p>

            <!-- Deskripsi Produk -->
            <div class="product-desc">
                <p><strong>Sinopsis:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($books['sinopsis'])); ?></p>
            </div>

            <!-- Informasi Tambahan -->
            <div class="product-info">
                <p><strong>Jumlah Halaman:</strong> <?php echo htmlspecialchars($books['halaman']); ?> halaman</p>
                <p><strong>Berat:</strong> <?php echo htmlspecialchars($books['berat']); ?> gram</p>
                <p><strong>Kondisi:</strong> <?php echo nl2br(htmlspecialchars($books['kondisi'])); ?></p>
                <p><strong>Kategori:</strong> <?php echo htmlspecialchars($books['kategori']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($books['genre']); ?></p>
                <p><strong>Isbn:</strong> <?php echo htmlspecialchars($books['isbn']); ?></p>
                <p><strong>Harga:</strong> Rp. <?php echo number_format($books['price'], 2, ',', '.'); ?></p>
                <p><strong>Stok:</strong> <?php echo htmlspecialchars($books['stock']); ?></p>
                <p><strong>Ditambahkan pada:</strong> <?php echo htmlspecialchars($books['created_at']); ?></p>
            </div>

            <!-- Tombol Kembali -->
            <div class="text-center mt-4">
                <a href="javascript:history.back()" class="btn-back">Kembali</a>
            </div>
        </div>
    </section>
</body>
</html>
