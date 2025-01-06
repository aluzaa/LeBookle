<?php
// Koneksi ke database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "db_lebooklex"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil 3 artikel terbaru berdasarkan tanggal pembuatan
$query = "SELECT * FROM blog ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($query);

// Cek apakah query berhasil dieksekusi
if ($result === false) {
    // Jika query gagal, tampilkan pesan error
    die("Error: " . $conn->error);
}

$latest_articles = [];
if ($result->num_rows > 0) {
    // Jika ada hasil, masukkan ke dalam array $latest_articles
    while ($row = $result->fetch_assoc()) {
        $latest_articles[] = $row;
    }
} else {
    echo "Tidak ada artikel terbaru.";
}

// Mengambil data buku dari database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$book_id = 1; // ID buku, sesuaikan sesuai logika Anda

// Query ulasan buku
$review_sql = "SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM reviews WHERE book_id = ?";
$review_stmt = $conn->prepare($review_sql);
$review_stmt->bind_param("i", $id);
$review_stmt->execute();
$review_result = $review_stmt->get_result();
$reviews = $review_result->fetch_assoc();

// Hitung rata-rata rating
$average_rating = round($reviews['avg_rating'] ?? 0, 1);
$total_reviews = $reviews['total_reviews'] ?? 0;

// Konversi rata-rata rating ke jumlah bintang (maksimal 5)
$stars = floor($average_rating);

// Ambil enum kategori
$query_kategori = "SHOW COLUMNS FROM books LIKE 'kategori'";
$result_kategori = $conn->query($query_kategori);
$row_kategori = $result_kategori->fetch_assoc();
$enum_kategori = str_replace(["enum(", "'", ")"], "", $row_kategori['Type']);
$categories = explode(",", $enum_kategori);

// Ambil enum genre
$query_genre = "SHOW COLUMNS FROM books LIKE 'genre'";
$result_genre = $conn->query($query_genre);
$row_genre = $result_genre->fetch_assoc();
$enum_genre = str_replace(["enum(", "'", ")"], "", $row_genre['Type']);
$genres = explode(",", $enum_genre);

// Query utama dengan filter
$where_clauses = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where_clauses[] = "(judul LIKE '%$search%' OR penulis LIKE '%$search%')";
}

if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategori = $conn->real_escape_string($_GET['kategori']);
    $where_clauses[] = "kategori = '$kategori'";
}

if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $genre = $conn->real_escape_string($_GET['genre']);
    $where_clauses[] = "genre = '$genre'";
}

// Gabungkan WHERE jika ada filter
$where_sql = count($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Query final
$query = "SELECT * FROM books $where_sql";
$result = $conn->query($query);

// Debugging (opsional)
if (!$result) {
    die("Query Error: " . $conn->error);
}

// Default nilai filter
$price_min_input = isset($_GET['price_min']) ? (float) $_GET['price_min'] : 0;
$price_max_input = isset($_GET['price_max']) ? (float) $_GET['price_max'] : 1000000;

$where_clauses = [];

// Filter harga
if ($price_min_input >= 0 && $price_max_input > $price_min_input) {
    $where_clauses[] = "price BETWEEN $price_min_input AND $price_max_input";
}

// Filter lainnya
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where_clauses[] = "(judul LIKE '%$search%' OR penulis LIKE '%$search%')";
}

if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategori = $conn->real_escape_string($_GET['kategori']);
    $where_clauses[] = "kategori = '$kategori'";
}

if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $genre = $conn->real_escape_string($_GET['genre']);
    $where_clauses[] = "genre = '$genre'";
}

// Query final
$where_sql = count($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT * FROM books $where_sql ORDER BY price ASC";
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}

// Produk per halaman
$products_per_page = 9;

// Halaman saat ini
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

// Hitung offset
$offset = ($current_page - 1) * $products_per_page;

// Total produk
$sql_total = "SELECT COUNT(*) AS total FROM books";
$result_total = $conn->query($sql_total);
$total_products = $result_total->fetch_assoc()['total'];

// Total halaman
$total_pages = ceil($total_products / $products_per_page);

// Query dengan LIMIT dan OFFSET
$query = "SELECT * FROM books $where_sql LIMIT $products_per_page OFFSET $offset";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>LeBookle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/logof.png">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->

    <!-- Font CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">

    <!-- Vendor CSS (Bootstrap & Icon Font) -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <!-- Plugins CSS (All Plugins Files) -->
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/plugins/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/plugins/fancybox.min.css">
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.min.css">

</head>

<body>
    <div class="preloader">
        <div class="layer"></div>
        <!-- end layer -->
        <div class="inner">
          <figure><img src="assets/images/preloader.gif" alt="Image"></figure>
        </div>
        <!-- end inner --> 
      </div>
      <script>
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            setTimeout(() => {
                preloader.style.display = 'none'; // Menghilangkan preloader setelah 2 detik
            }, 2000); // Ubah 2000 menjadi waktu dalam milidetik (2000 ms = 2 detik)
            preloader.classList.add("hidden"); // Menambahkan kelas "hidden" untuk memicu animasi memudar

        });
    </script>
    <!--== Wrapper Start ==-->
    <div class="wrapper">

        <!--== Start Header Wrapper ==-->
        <header class="header-area sticky-header header-transparent">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-5 col-lg-2 col-xl-1">
                        <div class="header-logo">
                            <a href="index.php">
                                <img class="logo-main" src="assets/images/logo.png" width="95" height="68" alt="Logo" />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-xl-7 d-none d-lg-block">
                        <div class="header-navigation ps-7">
                            <ul class="main-nav justify-content-start">
                                <li class="has-submenu"><a href="index.php">Beranda</a>
                                </li>
                                <li><a href="about-us.php">Tentang Kami</a></li>
                                <li class="has-submenu position-static"><a href="product.php">Toko</a>
                                </li>
                                <li class="has-submenu"><a href="blog.php">Blog</a>
                                </li>
                                <li class="has-submenu"><a href="#">Halaman</a>
                                    <ul class="submenu-nav">
                                        <li><a href="my-account.php">Akun Saya</a></li>
                                        <li><a href="product-cart.php">Keranjang</a></li>
                                        <li><a href="product-wishlist.php">Daftar Keinginan</a></li>
                                        <li><a href="product-compare.php">Status Pesanan</a></li>
                                        <li><a href="faq.php">Halaman FAQs</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact.php">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-7 col-lg-3 col-xl-4">
                        <div class="header-action justify-content-end">
                            <button class="header-action-btn ms-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasSearch" aria-controls="AsideOffcanvasSearch">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                            </button>

                            <button class="header-action-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasCart" aria-controls="AsideOffcanvasCart">
                                <span class="icon">
                  
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </span>
                            </button>

                            <a class="header-action-btn" href="my-account.php">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                            </a>

                            <button class="header-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasMenu" aria-controls="AsideOffcanvasMenu">
                                <!-- Ikon SVG yang diganti -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-justify">
                                    <line x1="21" y1="10" x2="3" y2="10"></line>
                                    <line x1="21" y1="6" x2="3" y2="6"></line>
                                    <line x1="21" y1="14" x2="3" y2="14"></line>
                                    <line x1="21" y1="18" x2="3" y2="18"></line>
                                </svg>
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--== End Header Wrapper ==-->

        <main class="main-content">

            <!--== Start Hero Area Wrapper ==-->
            <section class="hero-slider-area position-relative">
                <div class="swiper hero-slider-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide hero-slide-item">
                            <div class="container">
                                <div class="row align-items-center position-relative">
                                    <div class="col-12 col-md-6">
                                        <div class="hero-slide-content">
                                            <h2 class="hero-slide-title">Jelajah Buku</h2>
                                            <p class="hero-slide-desc">Selamat datang di toko buku bekas kami, tempat di mana halaman-halaman lama kembali hidup. Jelajahi dunia sastra yang penuh kenangan dan keajaiban.</p>
                                            <a class="btn btn-border-dark" href="product.php">BELI SEKARANG</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="hero-slide-thumb">
                                            <img src="assets/images/slider/3.png" alt="Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide hero-slide-item">
                            <div class="container">
                                <div class="row align-items-center position-relative">
                                    <div class="col-12 col-md-6">
                                        <div class="hero-slide-content">
                                            <h2 class="hero-slide-title">Koleksi Langka</h2>
                                            <p class="hero-slide-desc">Setiap buku bekas memiliki cerita dan perjalanan uniknya sendiri. Temukan buku bekas yang siap menemani hari-hari Anda dengan harga terjangkau.</p>
                                            <a class="btn btn-border-dark" href="product.php">BELI SEKARANG</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="hero-slide-thumb">
                                            <img src="assets/images/slider/2.png" alt="Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--== Add Pagination ==-->
                    <div class="hero-slider-pagination"></div>
                </div>
            </section>
            <!--== End Hero Area Wrapper ==-->

            <!--== Start Product Category Area Wrapper ==-->
            <section class="section-space pb-0">
                <div class="container">
                    <div class="row g-3 g-sm-6">
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item">
                                <img class="icon" src="assets/images/shop/category/4.png" width="70" height="80" alt="Image-HasTech">
                                <h3 class="title">Sejarah</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item" data-bg-color="#F8DCAF">
                                <img class="icon" src="assets/images/shop/category/5.png" width="80" height="80" alt="Image-HasTech">
                                <h3 class="title">Fiksi Ilmiah</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-lg-0 mt-sm-6 mt-4">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item" data-bg-color="#D4AA70">
                                <img class="icon" src="assets/images/shop/category/8.png" width="80" height="80" alt="Image-HasTech">
                                <h3 class="title">Horor</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item" data-bg-color="#F8DCAF">
                                <img class="icon" src="assets/images/shop/category/9.png" width="80" height="80" alt="Image-HasTech">
                                <h3 class="title">Romansa</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item" data-bg-color="#D4AA70">
                                <img class="icon" src="assets/images/shop/category/7.png" width="80" height="80" alt="Image-HasTech">
                                <h3 class="title">Misteri</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                            <!--== Start Product Category Item ==-->
                            <a href="product.php" class="product-category-item" data-bg-color="#F8DCAF">
                                <img class="icon" src="assets/images/shop/category/6.png" width="80" height="80" alt="Image-HasTech">
                                <h3 class="title">Sains</h3>
                            </a>
                            <!--== End Product Category Item ==-->
                        </div>
                    </div>
                </div>
            </section>
            <!--== End Product Category Area Wrapper ==-->

            <!--== Start Product Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-center">
                                <h2 class="title">Terbaru</h2>
                                <p>Temukan buku bekas paling baru di toko kami dan temukan bacaan favorit Anda dengan harga terjangkau</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
                    <?php
                                    // Koneksi ke database
                                    require_once 'db.php'; // Pastikan file ini berisi koneksi database menggunakan mysqli

                                    // Query untuk mendapatkan 3 produk terbaru
                                    $sql = "SELECT id, judul, cover, price FROM books ORDER BY created_at DESC LIMIT 6";
                                    $result = $conn->query($sql);

                                    // Periksa jika ada hasil
                                    if ($result->num_rows > 0) {
                                        while ($book = $result->fetch_assoc()) {
                                            ?>
                            <div class="col-6 col-lg-4 mb-4 mb-sm-9">
                                <!--== Start Product Item ==-->
                                <div class="product-item">
                                    <div class="product-thumb">
                                    <a class="d-block" href="product-details.php?id=<?php echo $book['id']; ?>">
                                                            <img src="../Admin/<?php echo htmlspecialchars($book['cover']); ?>" width="370" height="450" alt="<?php echo htmlspecialchars($book['judul']); ?>">
                                                        </a>
                                        <div class="product-action">
                                            <button type="button" class="product-action-btn action-btn-quick-view" data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
                                                <i class="fa fa-expand"></i>
                                            </button>
                                            <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal" data-bs-target="#action-CartAddModal">
                                                <span>tambahkan </span>
                                            </button>
                                            <button type="button" class="product-action-btn action-btn-wishlist" data-bs-toggle="modal" data-bs-target="#action-WishlistModal">
                                                <i class="fa fa-heart-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-info">
                                                        <div class="product-rating">
                                                            <div class="rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fa <?php echo $i <= $stars ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                                            <?php endfor; ?>
                                                            </div>
                                                            <div class="product-details-review">
                                                                <div class="product-review-icon">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <i class="fa <?php echo $i <= $stars ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                                                    <?php endfor; ?>
                                                                </div>
                                                                <button type="button" class="product-review-show">
                                                                    <?php echo htmlspecialchars($total_reviews); ?> ulasan
                                                                </button>
                                                            </div>                                                        </div>
                                                        <h4 class="title"><a href="product-details.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['judul']); ?></a></h4>
                                                        <div class="prices">
                                                            <span class="price">Rp. <?php echo number_format($book['price'], 0, ',', '.'); ?></span>
                                                        </div>
                                                        <div class="product-action">
                                                            <button type="button" class="product-action-btn action-btn-cart btn-add-to-cart" 
                                                                    data-id="<?php echo htmlspecialchars($book['id']); ?>"
                                                                    data-name="<?php echo htmlspecialchars($book['judul']); ?>"
                                                                    data-img="../Admin/<?php echo htmlspecialchars($book['cover']); ?>"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#action-CartAddModal">
                                                                Tambahkan
                                                            </button>
                                                            <button type="button" class="product-action-btn action-btn-quick-view" data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
                                                                <i class="fa fa-expand"></i>
                                                            </button>
                                                            <button type="button" class="product-action-btn action-btn-wishlist btn-wishlist" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#action-WishlistModal"
                                                                    data-id="<?php echo htmlspecialchars($book['id']); ?>"
                                                                    data-name="<?php echo htmlspecialchars($book['judul']); ?>"
                                                                    data-img="../Admin/<?php echo htmlspecialchars($book['cover']); ?>">
                                                                <i class="fa fa-heart-o"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                </div>
                                <!--== End Product Item ==-->
                            </div>
                            <?php
                                        }
                                    } else {
                                        echo "<p>Tidak ada produk terbaru yang tersedia.</p>";
                                    }

                                    // Tutup koneksi database
                                    $conn->close();
                                    ?>
                    </div>

                </div>
            </section>
            <!--== End Product Area Wrapper ==-->

            <!--== Start Blog Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-center">
                                <h2 class="title">Baca dan Temukan</h2>
                                <p>Baca artikel kami tentang buku bekas, tips pembelian, dan sejarah Pasar Buku Wilis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-n9">
    <?php foreach ($latest_articles as $article): ?>
    <div class="col-sm-4 col-lg-4 mb-8">
        <!--== Start Blog Item ==-->
        <div class="post-item">
            <a href="blog-details.php?id=<?= htmlspecialchars($article['id']); ?>" class="thumb">
                <img src="../Admin/<?= htmlspecialchars($article['cover_image']); ?>" width="370" height="320" alt="<?= htmlspecialchars($article['title']); ?>">
            </a>
            <div class="content">
                <h4 class="title">
                    <a href="blog-details.php?id=<?= htmlspecialchars($article['id']); ?>"><?= htmlspecialchars($article['title']); ?></a>
                </h4>
            </div>
        </div>
        <!--== End Blog Item ==-->
    </div>
    <?php endforeach; ?>
</div>
                    </div>
                </div>
            </section>
            <!--== End Blog Area Wrapper ==-->

        </main>

        <!--== Start Footer Area Wrapper ==-->
        <footer class="footer-area">
            <!--== Start Footer Main ==-->
            <div class="footer-main">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="widget-item">
                                <div class="widget-about">
                                    <a class="widget-logo" href="index.php">
                                        <img src="assets/images/logof.png" width="95" height="68" alt="Logo">
                                    </a>
                                    <p class="desc">Temukan buku bekas berkualitas dengan harga terbaik. Jelajahi koleksi kami sekarang.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-5 mt-md-0 mt-9">
                            <div class="widget-item">
                                <h4 class="widget-title">Informasi</h4>
                                <ul class="widget-nav">
                                    <li><a href="blog.php">Blog</a></li>
                                    <li><a href="about-us.php">Tentang Kami</a></li>
                                    <li><a href="contact.php">Kontak</a></li>
                                    <li><a href="account-login.php">Login</a></li>
                                    <li><a href="product.php">Toko</a></li>
                                    <li><a href="my-account.php">Akun saya</a></li>
                                    <li><a href="faq.php">FAQs</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mt-lg-0 mt-6">
                            <div class="widget-item">
                                <h4 class="widget-title">Sosial Media</h4>
                                <div class="widget-social">
                                    <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-whatsapp"></i></a>
                                    <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-instagram"></i></a>
                                    <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--== End Footer Main ==-->

            <!--== Start Footer Bottom ==-->
            <div class="footer-bottom">
                <div class="container pt-0 pb-0">
                    <div class="footer-bottom-content">
                        <p class="copyright">© 2024 Aida Lutfiah Zahra</p>
                    </div>
                </div>
            </div>
            <!--== End Footer Bottom ==-->
        </footer>
        <!--== End Footer Area Wrapper ==-->

        <!--== Scroll Top Button ==-->
        <div id="scroll-to-top" class="scroll-to-top"><span class="fa fa-angle-up"></span></div>

        <!--== Start Product Quick Wishlist Modal ==-->
        <aside class="product-action-modal modal fade" id="action-WishlistModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="product-action-view-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="modal-action-messages">
                                <i class="fa fa-check-square-o"></i> Berhasil ditambahkan ke daftar keinginan!
                            </div>
                            <div class="modal-action-product">
                                <div class="thumb">
                                    <img src="assets/images/shop/modal1.png" alt="Organic Food Juice" width="466" height="320">
                                </div>
                                <h4 class="product-name"><a href="product-details.php">Orang Gagal</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!--== End Product Quick Wishlist Modal ==-->

        <!--== Start Product Quick Add Cart Modal ==-->
        <aside class="product-action-modal modal fade" id="action-CartAddModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="product-action-view-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="modal-action-messages">
                                <i class="fa fa-check-square-o"></i> Berhasil ditambahkan ke keranjang!
                            </div>
                            <div class="modal-action-product">
                                <div class="thumb">
                                    <img src="assets/images/shop/modal1.png" alt="Organic Food Juice" width="466" height="320">
                                </div>
                                <h4 class="product-name"><a href="product-details.php">Orang Gagal</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!--== End Product Quick Add Cart Modal ==-->

        <!--== Start Aside Search Form ==-->
        <aside class="aside-search-box-wrapper offcanvas offcanvas-top" tabindex="-1" id="AsideOffcanvasSearch" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="d-none" id="offcanvasTopLabel">Aside Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="container pt--0 pb--0">
            <div class="search-box-form-wrap">
                <form action="product.php" method="GET">
                    <div class="aside-search-form position-relative">
                        <label for="SearchInput" class="visually-hidden">Search</label>
                        <input id="SearchInput" name="search" type="search" class="form-control" placeholder="Cari judul buku atau nama penulis..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</aside>

        <!--== End Aside Search Form ==-->

        <!--== Start Product Quick View Modal ==-->
        <aside class="product-cart-view-modal modal fade" id="action-QuickViewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="product-quick-view-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                <span class="fa fa-close"></span>
                            </button>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <!--== Start Product Thumbnail Area ==-->
                                        <div class="product-single-thumb">
                                            <img src="assets/images/shop/1.png" width="544" height="560" alt="Image-HasTech">
                                        </div>
                                        <!--== End Product Thumbnail Area ==-->
                                    </div>
                                    <div class="col-lg-6">
                                        <!--== Start Product Info Area ==-->
                                        <div class="product-details-content">
                                            <h5 class="product-details-collection">Dazai Osamu</h5>
                                            <h3 class="product-details-title">Orang Gagal</h3>
                                            <div class="product-details-review mb-5">
                                                <div class="product-review-icon">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <button type="button" class="product-review-show">150 ulasan</button>
                                            </div>
                                            <p class="mb-6">Orang Gagal (No Longer Human) karya Dazai Osamu menceritakan kisah Yozo Oba, seorang pria yang merasa terasing dari masyarakat dan tidak mampu menyesuaikan diri dengan norma sosial. Dalam catatan hariannya, Yozo mengungkapkan perjuangan batinnya, kecemasan, serta rasa tidak berdaya yang membuatnya merasa "gagal" menjadi manusia. Novel ini mengeksplorasi tema alienasi, depresi, dan identitas, mencerminkan pergulatan batin mendalam yang dialami penulisnya sendiri.</p>
                                            <div class="product-details-pro-qty">
                                                <div class="pro-qty">
                                                    <input type="text" title="Quantity" value="01">
                                                </div>
                                            </div>
                                            <div class="product-details-action">
                                                <h4 class="price">Rp. 47.000</h4>
                                                <div class="product-details-cart-wishlist">
                                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#action-CartAddModal">tambahkan </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--== End Product Info Area ==-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!--== End Product Quick View Modal ==-->

        <!--== Start Aside Cart ==-->
        <aside class="aside-cart-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasCart" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h1 class="d-none" id="offcanvasRightLabel">Keranjang</h1>
                <button class="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Keranjang<i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="offcanvas-body">
                <ul class="aside-cart-product-list">
                    <li class="aside-product-list-item">
                        <a href="#/" class="remove">×</a>
                        <a href="product-details.php">
                            <img src="assets/images/shop/1.png" width="68" height="84" alt="Image">
                            <span class="product-title">Orang Gagal</span>
                        </a>
                        <span class="product-price">1 x Rp. 47.000</span>
                    </li>
                    <li class="aside-product-list-item">
                        <a href="#/" class="remove">×</a>
                        <a href="product-details.php">
                            <img src="assets/images/shop/3.png" width="68" height="84" alt="Image">
                            <span class="product-title">Crime and Punishment</span>
                        </a>
                        <span class="product-price">1 × Rp. 62.500</span>
                    </li>
                </ul>
                <p class="cart-total"><span>Subtotal:</span><span class="amount">Rp. 109.500</span></p>
                <a class="btn-total" href="product-cart.php">Lihat keranjang</a>
                <a class="btn-total" href="product-checkout.php">Checkout</a>
            </div>
        </aside>
        <!--== End Aside Cart ==-->

        <!--== Start Aside Menu ==-->
        <aside class="off-canvas-wrapper offcanvas offcanvas-start" tabindex="-1" id="AsideOffcanvasMenu" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h1 class="d-none" id="offcanvasExampleLabel">Aside Menu</h1>
                <button class="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">menu <i class="fa fa-chevron-left"></i></button>
            </div>
            <div class="offcanvas-body">
                <div id="offcanvasNav" class="offcanvas-menu-nav">
                    <ul>
                        <li class="has-submenu"><a href="index.php">Beranda</a>
                        </li>
                        <li><a href="about-us.php">Tentang Kami</a></li>
                        <li class="has-submenu position-static"><a href="product.php">Toko</a>
                        </li>
                        <li class="has-submenu"><a href="blog.php">Blog</a>
                        </li>
                        <li class="has-submenu"><a href="#">Halaman</a>
                            <ul class="submenu-nav">
                                <li><a href="my-account.php">Akun Saya</a></li>
                                <li><a href="product-cart.php">Keranjang</a></li>
                                <li><a href="product-wishlist.php">Daftar Keinginan</a></li>
                                <li><a href="product-compare.php">Status Pesanan</a></li>
                                <li><a href="faq.php">Halaman FAQs</a></li>
                            </ul>
                        </li>
                        <li><a href="contact.php">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </aside>
        <!--== End Aside Menu ==-->

    </div>
    <!--== Wrapper End ==-->

    <!-- JS Vendor, Plugins & Activation Script Files -->

    <!-- Vendors JS -->
    <script src="assets/js/vendor/modernizr-3.11.7.min.js"></script>
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>

    <!-- Plugins JS -->
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/fancybox.min.js"></script>
    <script src="assets/js/plugins/jquery.nice-select.min.js"></script>

    <!-- Custom Main JS -->
    <script src="assets/js/main.js"></script>

</body>


</html>