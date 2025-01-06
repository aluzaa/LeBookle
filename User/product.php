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

// Mengambil data buku dari database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$book_id = 1; // ID buku, sesuaikan sesuai logika Anda

// Menghitung total ulasan
$sql_reviews = "SELECT COUNT(*) AS total_reviews FROM reviews WHERE book_id = $book_id";
$result_reviews = $conn->query($sql_reviews);

$total_reviews = 0; // Default
if ($result_reviews && $row_reviews = $result_reviews->fetch_assoc()) {
    $total_reviews = $row_reviews['total_reviews'];
}

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
    <link rel="stylesheet" href="assets/css/plugins/range-slider.css">
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
        <header class="header-area sticky-header">
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

            <!--== Start Page Header Area Wrapper ==-->
            <section class="page-header-area pt-10 pb-9" >
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="page-header-st3-content text-center text-md-start">
                                <h2 class="page-header-title">Semua Produk</h2>
                                <p class="m-0">Berbagai macam buku bekas dari genre apapun menanti untuk ditemukan. Nikmati membaca sambil berhemat, <br>cerita yang bagus tidak harus mahal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--== End Page Header Area Wrapper ==-->

            <!--== Start Product Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="row justify-content-between flex-xl-row-reverse">
                        <div class="col-xl-9">
                            <div class="row g-3 g-sm-6">
                            <?php
                                if ($result->num_rows > 0) {
                                    // Looping untuk menampilkan data buku
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <div class="col-6 col-lg-4 col-xl-4 mb-4 mb-sm-8">
                                            <!--== Start Product Item ==-->
                                            <div class="product-item product-st3-item">
                                                <div class="product-thumb">
                                                    <a class="d-block" href="product-details.php?id=<?php echo $row['id']; ?>">
                                                        <img src="../Admin/<?php echo $row['cover']; ?>" width="370" height="450" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                                                    </a>
                                                    <div class="product-action">
                                                        <button type="button" class="product-action-btn action-btn-quick-view" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
                                                            <i class="fa fa-expand"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="product-action-btn action-btn-cart" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#action-CartAddModal" 
                                                                data-id="<?= $row['id'] ?>" 
                                                                data-name="<?= $row['judul'] ?>" 
                                                                data-image="<?= $row['cover'] ?>">
                                                            <span>tambahkan</span>
                                                        </button>

                                                        <button type="button" 
                                                                class="product-action-btn action-btn-wishlist" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#action-WishlistModal" 
                                                                data-id="<?= $row['id'] ?>" 
                                                                data-name="<?= $row['judul'] ?>" 
                                                                data-image="<?= $row['cover'] ?>">
                                                            <i class="fa fa-heart-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product-info">
                                                <div class="product-rating">
                                                    <div class="rating">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fa <?php echo ($i <= $average_rating) ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <div class="reviews"><?php echo $total_reviews; ?> usulan</div>
                                                </div>
                                                    <h4 class="title"><a href="product-details.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['judul']); ?></a></h4>
                                                    <div class="prices">
                                                        <span class="price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--== End Product Item ==-->
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "Tidak ada produk yang ditemukan.";
                                }
                                ?>
                                <div class="col-12">
                                    <ul class="pagination justify-content-center me-auto ms-auto mt-5 mb-10" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                        <!-- Tombol Previous -->
                                        <?php if ($current_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link previous" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; padding: 0;">
                                                    <span class="fa fa-chevron-left" aria-hidden="true"></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Nomor Halaman -->
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; padding: 0;"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <!-- Tombol Next -->
                                        <?php if ($current_page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link next" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; padding: 0;">
                                                    <span class="fa fa-chevron-right" aria-hidden="true"></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="product-sidebar-widget">
                                <div class="product-widget-search">
                                    <form action="" method="GET">
                                        <input type="search" name="search" placeholder="Cari Buku/Penulis" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <button type="submit"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="product-widget">
                                    <h4 class="product-widget-title">Kisaran Harga</h4>
                                    <div class="product-widget-range-input">
                                    <form action="" method="GET">
                                        <div class="form-group">
                                            <label for="price_min">Harga Minimum:</label>
                                            <input 
                                                type="number" 
                                                id="price_min" 
                                                name="price_min" 
                                                class="filter-price-input" 
                                                value="<?php echo htmlspecialchars($price_min_input); ?>" 
                                                placeholder="Masukkan harga minimum" 
                                                min="0"
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="price_max">Harga Maksimum:</label>
                                            <input 
                                                type="number" 
                                                id="price_max" 
                                                name="price_max" 
                                                class="filter-price-input" 
                                                value="<?php echo htmlspecialchars($price_max_input); ?>" 
                                                placeholder="Masukkan harga maksimum" 
                                                min="0"
                                            >
                                        </div>
                                        <button type="submit" class="filter-price-button">Terapkan</button>
                                    </form>
                                    </div>
                                </div>
                                <div class="product-widget">
                                    <h4 class="product-widget-title">Kategori</h4>
                                    <ul class="product-widget-category">
                                        <?php foreach ($categories as $category): ?>
                                            <li><a href="product.php?kategori=<?= urlencode($category) ?>"><?= ucfirst($category) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="product-widget mb-0">
                                    <h4 class="product-widget-title">Genre</h4>
                                    <ul class="product-widget-tags">
                                        <?php foreach ($genres as $genre): ?>
                                            <li><a href="product.php?genre=<?= urlencode($genre) ?>"><?= ucfirst($genre) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div style="text-align: center; margin-top: 20px;">
                                    <button id="reset-filters" class="btn btn-secondary mt-3" 
                                        style="background-color: #8B0000; color: white; border: none;">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--== End Product Area Wrapper ==-->

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
                                    <img src="" alt="Produk" width="auto" height="100px">
                                </div>
                                <h4 class="product-name"><a href="#"></a></h4>
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
                                    <img src="" alt="Produk" width="466" height="320">
                                </div>
                                <h4 class="product-name"><a href="#"></a></h4>
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
                                        <div class="product-single-thumb">
                                            <img src="" alt="" width="350" height="auto">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="product-details-content">
                                            <h5 class="product-details-collection"></h5>
                                            <h3 class="product-details-title"></h3>
                                            <p></p>
                                            <div class="product-details-pro-qty">
                                                <div class="quantity-control">
                                                    <button type="button" class="btn-decrement">-</button>
                                                    <input type="number" class="quantity-input" value="1" min="1" max="10" data-price="50000">
                                                    <button type="button" class="btn-increment">+</button>
                                                </div>
                                            </div>
                                            <div class="product-details-action">
                                                <h4 class="price">Rp. <?php echo number_format($book['price'], 0, ',', '.'); ?></h4>
                                                <div class="product-details-cart-wishlist">
                                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#action-CartAddModal">tambahkan</button>
                                                </div>
                                            </div>
                                        </div>
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
    <script src="assets/js/plugins/range-slider.js"></script>
    <script src="assets/js/plugins/jquery.nice-select.min.js"></script>

    <!-- Custom Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".action-btn-quick-view").forEach(button => {
            button.addEventListener("click", function() {
                const bookId = this.getAttribute("data-id");

                fetch(`get-book-details.php?id=${bookId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector("#action-QuickViewModal .product-details-collection").textContent = data.penulis;
                            document.querySelector("#action-QuickViewModal .product-details-title").textContent = data.judul;
                            document.querySelector("#action-QuickViewModal .price").textContent = `Rp. ${data.price.toLocaleString('id-ID')}`;
                            document.querySelector("#action-QuickViewModal .product-single-thumb img").src = `../Admin/${data.cover}`;
                            document.querySelector("#action-QuickViewModal .product-single-thumb img").alt = data.judul;
                            document.querySelector("#action-QuickViewModal p").textContent = data.sinopsis;
                        }
                    });
            });
        });
    });
</script>
<script>
    // Fungsi untuk memperbarui modal
    document.addEventListener('DOMContentLoaded', function () {
        const cartModal = document.getElementById('action-CartAddModal');
        const wishlistModal = document.getElementById('action-WishlistModal');

        // Event Listener untuk tombol "Tambahkan ke Keranjang"
        cartModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang diklik
            const productName = button.getAttribute('data-name');
            const productImage = button.getAttribute('data-image');

            // Update konten modal
            cartModal.querySelector('.product-name a').textContent = productName;
            cartModal.querySelector('.thumb img').src = "../Admin/" + productImage;
        });

        // Event Listener untuk tombol "Tambahkan ke Wishlist"
        wishlistModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang diklik
            const productName = button.getAttribute('data-name');
            const productImage = button.getAttribute('data-image');

            // Update konten modal
            wishlistModal.querySelector('.product-name a').textContent = productName;
            wishlistModal.querySelector('.thumb img').src = "../Admin/" + productImage;
        });
    });
    cartModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Tombol yang diklik
    const productName = button.getAttribute('data-name');
    const productImage = button.getAttribute('data-image');
    
    console.log("Product Image:", productImage); // Debug jalur gambar

    // Update konten modal
    cartModal.querySelector('.product-name a').textContent = productName;
    cartModal.querySelector('.thumb img').src = "uploads/" + productImage;
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const resetButton = document.getElementById("reset-filters");
        resetButton.addEventListener("click", function () {
            window.location.href = "product.php";
        });
    });
</script>
<script>
    $(document).ready(function() {
    // Mengupdate harga ketika jumlah diubah
    $('.quantity-input').on('input', function() {
        var quantity = $(this).val(); // Ambil jumlah produk yang dipilih
        var price = $(this).data('price'); // Ambil harga satuan produk
        var totalPrice = quantity * price; // Hitung total harga

        // Update harga total di halaman produk
        $('#total-price').text('Rp. ' + totalPrice.toLocaleString('id-ID')); // Format harga

        // Update harga total di modal cart
        $('#cart-total-price').text('Rp. ' + totalPrice.toLocaleString('id-ID')); // Format harga
    });

    // Tombol tambah jumlah
    $('.btn-increment').on('click', function() {
        var quantityInput = $(this).siblings('.quantity-input');
        var currentQuantity = parseInt(quantityInput.val());
        var maxQuantity = parseInt(quantityInput.attr('max'));

        if (currentQuantity < maxQuantity) {
            quantityInput.val(currentQuantity + 1);
            quantityInput.trigger('input'); // Update harga
        }
    });

    // Tombol kurangi jumlah
    $('.btn-decrement').on('click', function() {
        var quantityInput = $(this).siblings('.quantity-input');
        var currentQuantity = parseInt(quantityInput.val());

        if (currentQuantity > 1) {
            quantityInput.val(currentQuantity - 1);
            quantityInput.trigger('input'); // Update harga
        }
    });

    // Event listener untuk tombol add-to-cart
    $('.btn-add-to-cart').on('click', function() {
        var productId = $(this).data('id');
        var productName = $(this).data('name');
        var productImg = $(this).data('img');
        var quantity = $(this).siblings('.quantity-control').find('.quantity-input').val();
        var price = $(this).siblings('.quantity-control').find('.quantity-input').data('price');
        var totalPrice = quantity * price;

        // Update modal dengan data produk
        $('#cart-img').attr('src', productImg);
        $('#cart-product-name').text(productName);
        $('#cart-product-link').attr('href', 'product-details.php?id=' + productId);
        $('#cart-total-price').text('Rp. ' + totalPrice.toLocaleString('id-ID')); // Format harga
    });
});

</script>


</body>


</html>