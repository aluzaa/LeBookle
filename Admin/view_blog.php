<?php
require 'db.php';

// Ambil ID blog dari parameter URL
$id = $_GET['id'] ?? null;

// Cek apakah ID valid
if ($id) {
    // Query untuk mengambil detail blog berdasarkan ID
    $query = "SELECT title, content, quote, quote_author, created_at, header_image FROM blog WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah blog ditemukan
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        echo "Blog tidak ditemukan.";
        exit;
    }
} else {
    echo "ID blog tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <link rel="stylesheet" href="assets/css/backend-plugin.min.css"> <!-- Pastikan file CSS sesuai -->
    <style>
        .blog-detail {
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .blog-detail-blockquote {
            padding: 20px;
            border-left: 5px solid #ddd;
            background-color: #f9f9f9;
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
        .header-image {
            width: 100%;
            height: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <section class="blog-detail">
        <div class="container">
            <!-- Judul Blog -->
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
                </div>
            </div>

            <!-- Gambar Header (Jika ada) -->
            <?php if (!empty($blog['header_image'])): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <img src="<?php echo htmlspecialchars($blog['header_image']); ?>" alt="Gambar Header" class="header-image">
                </div>
            </div>
            <?php endif; ?>

            <!-- Konten Blog -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <p class="desc"><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                </div>
            </div>

            <!-- Kutipan -->
            <?php if (!empty($blog['quote']) && !empty($blog['quote_author'])): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8 text-center">
                    <blockquote class="blog-detail-blockquote">
                        <p>“<?php echo htmlspecialchars($blog['quote']); ?>”</p>
                        <span>- <?php echo htmlspecialchars($blog['quote_author']); ?></span>
                    </blockquote>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tombol Kembali -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10 text-center">
                    <a href="javascript:history.back()" class="btn-back">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
