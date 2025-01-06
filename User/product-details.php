<?php
// Koneksi ke database
require_once 'db.php'; // Pastikan file ini berisi koneksi database menggunakan mysqli
// Tes koneksi
if (!$pdo) {
    die("Koneksi database gagal.");
}
// Ambil ID buku dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID buku tidak valid.");
}

$id = intval($_GET['id']);

// Query detail buku
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    die("Buku tidak ditemukan.");
}

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
        <link rel="stylesheet" href="assets/css/swiper.min.css">
    
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    </head>

<body>
    <div class="preloader">
        <div class="layer">
        <!-- end layer -->
        <div class="inner">
          <figure><img src="assets/images/preloader.gif" alt="Image"></figure>
        </div>
        <!-- end inner --> 
      </div>
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

            <!--== Start Product Details Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="row product-details" data-id="<?php echo htmlspecialchars($book['id']); ?>">
                        <div class="col-lg-4">
                            <div class="product-details-thumb">
                                <img src="../Admin/<?php echo htmlspecialchars($book['cover']); ?>" width="500" height="auto" alt="Image">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="product-details-content">
                                <h5 class="product-details-collection"><?php echo htmlspecialchars($book['penulis']); ?></h5>
                                <h3 class="product-details-title"><?php echo htmlspecialchars($book['judul']); ?></h3>
                                <div class="product-details-review">
                                    <div class="product-review-icon">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa <?php echo $i <= $stars ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <button type="button" class="product-review-show">
                                        <?php echo htmlspecialchars($total_reviews); ?> ulasan
                                    </button>
                                </div><br>
                                <p class="mb-7"><?php echo nl2br(htmlspecialchars($book['sinopsis'])); ?></p>
                                <div class="product-details-pro-qty">
                                <div class="quantity-control">
                                            <button type="button" class="btn-decrement">-</button>
                                            <input type="number" class="quantity-input" value="1" min="1" max="<?php echo $book['stock']; ?>" data-price="<?php echo $book['price']; ?>">
                                            <button type="button" class="btn-increment">+</button>
                                        </div>
                                </div>
                                <div class="product-details-action">
                                <h4 class="price" id="total-price">Rp. <?php echo number_format($book['price'], 0, ',', '.'); ?></h4>
                                <div class="product-details-cart-wishlist">
                                            <button type="button" class="btn-wishlist" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#action-WishlistModal"
                                                    data-id="<?php echo htmlspecialchars($book['id']); ?>"
                                                    data-name="<?php echo htmlspecialchars($book['judul']); ?>"
                                                    data-img="../Admin/<?php echo htmlspecialchars($book['cover']); ?>">
                                                <i class="fa fa-heart-o"></i>
                                            </button>
                                            <button type="button" class="btn btn-add-to-cart" 
                                                    data-id="<?php echo htmlspecialchars($book['id']); ?>"
                                                    data-name="<?php echo htmlspecialchars($book['judul']); ?>"
                                                    data-img="../Admin/<?php echo htmlspecialchars($book['cover']); ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#action-CartAddModal">
                                                Tambahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="nav product-details-nav" id="product-details-nav-tab" role="tablist">
                                <button class="nav-link active" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-controls="specification" aria-selected="true">Detail Buku</button>
                                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab" aria-controls="review" aria-selected="false">Ulasan</button>
                                <button class="nav-link" id="stock-shipping-tab" data-bs-toggle="tab" data-bs-target="#stock-shipping" type="button" role="tab" aria-controls="stock-shipping" aria-selected="false">Stok & Pengiriman</button>
                            </div>
                        
                            <div class="tab-content" id="product-details-nav-tabContent">
                                <!--== Start Specification Tab ==-->
                                <div class="tab-pane fade show active" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                                    <ul class="product-details-info-wrap">
                                        <li><span>Judul</span><p><?php echo htmlspecialchars($book['judul']); ?></p></li>
                                        <li><span>Penulis</span><p><?php echo htmlspecialchars($book['penulis']); ?></p></li>
                                        <li><span>Penerbit</span><p><?php echo htmlspecialchars($book['penerbit']); ?></p></li>
                                        <li><span>Tahun Terbit</span><p><?php echo htmlspecialchars($book['tahun_terbit']); ?></p></li>
                                        <li><span>Genre</span><p><?php echo htmlspecialchars($book['genre']); ?></p></li>
                                        <li><span>Kategori</span><p><?php echo htmlspecialchars($book['kategori']); ?></p></li>
                                        <li><span>Halaman</span><p><?php echo htmlspecialchars($book['halaman']); ?> Halaman</p></li>
                                        <li><span>Berat</span><p><?php echo htmlspecialchars($book['berat']); ?> Gram</p></li>
                                        <li><span>Isbn</span><p><?php echo htmlspecialchars($book['isbn']); ?></p></li>
                                        <li><span>Sinopsis</span>
                                            <p><?php echo nl2br(htmlspecialchars($book['sinopsis'])); ?></p>
                                        </li>
                                    </ul>
                                </div>
                                <!--== End Specification Tab ==-->
                        
                                <!--== Start Reviews Tab ==-->
                                <div class="tab-pane" id="review" role="tabpanel" aria-labelledby="review-tab">
                                    <div class="product-review-items">
                                        <?php
                                        // Koneksi ke database
                                        require_once 'db.php';

                                        // Ambil 3 review terbaru berdasarkan ID buku
                                        $book_id = $_GET['id']; // ID buku yang diteruskan lewat URL
                                        $stmt = $pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = :book_id ORDER BY r.created_at DESC LIMIT 3");
                                        $stmt->execute(['book_id' => $book_id]);
                                        $reviews = $stmt->fetchAll();

                                        if (empty($reviews)) {
                                            echo '<p>Belum ada ulasan untuk buku ini.</p>';
                                        } else {
                                            foreach ($reviews as $review) {
                                                // Tampilkan review
                                                $rating = $review['rating'];
                                                echo '<div class="product-review-item">';
                                                echo '    <div class="product-review-top">';
                                                echo '        <div class="product-review-content">';
                                                echo '            <span class="product-review-name">' . htmlspecialchars($review['username']) . '</span>';
                                                echo '            <div class="product-review-icon">';
                                                for ($i = 0; $i < $rating; $i++) {
                                                    echo '<i class="fa fa-star"></i>';
                                                }
                                                for ($i = $rating; $i < 5; $i++) {
                                                    echo '<i class="fa fa-star-o"></i>';
                                                }
                                                echo '            </div>';
                                                echo '        </div>';
                                                echo '    </div>';
                                                echo '    <p class="desc">' . htmlspecialchars($review['comment']) . '</p>';
                                                echo '</div>';
                                            }
                                        }
                                        ?>
                                    </div><br>
                                    <button class="readall" data-bs-toggle="modal" data-bs-target="#reviewsModal">
                                        Lihat Semua Review <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div><br>


                                <!-- Tambahkan garis tipis di sini -->
                                <hr class="review-divider">


                                <!--== End Reviews Tab ==-->
                        
                                <!--== Start Stock & Shipping Tab ==-->
                                <div class="tab-pane fade" id="stock-shipping" role="tabpanel" aria-labelledby="stock-shipping-tab">
                                    <ul class="product-details-info-wrap">
                                        <li><span>Stok</span>
                                            <p>
                                                <?php 
                                                if ($book['stock'] > 0) {
                                                    echo "Tersedia (" . htmlspecialchars($book['stock']) . " buah)";
                                                } else {
                                                    echo "Stok Habis";
                                                }
                                                ?>
                                            </p>
                                        </li>
                                        <li><span>Kondisi</span>
                                            <p><?php echo htmlspecialchars($book['kondisi']); ?></p>
                                        </li>
                                        <li><span>Pengiriman</span>
                                            <p>Pengiriman dalam 1-2 hari kerja setelah pembayaran dikonfirmasi. Pengiriman tersedia ke seluruh Indonesia. Estimasi waktu pengiriman bergantung pada lokasi tujuan.</p>
                                        </li>
                                        <li><span>Biaya Pengiriman</span>
                                            <p>Biaya pengiriman ditentukan berdasarkan lokasi pengiriman dan berat buku. Biaya akan dihitung secara otomatis saat checkout.</p>
                                        </li>
                                    </ul>
                                </div>
                                <!--== End Stock & Shipping Tab ==-->
                            </div>
                        </div>
                        
                        <div class="col-lg-5">
    <div class="product-reviews-form-wrap">
        <h4 class="product-form-title">Beri Ulasan</h4>
        <div class="product-reviews-form">
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
                <div class="form-input-item">
                    <textarea class="form-control" name="comment" placeholder="Masukkan ulasan Anda" required></textarea>
                </div>
                <div class="form-input-item">
                    <div class="form-ratings-item">
                        <select id="product-review-form-rating-select" class="select-ratings" name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <span class="title">Beri Penilaian Anda</span>
                        <div class="product-ratingsform-form-wrap">
                            <div class="product-ratingsform-form-icon">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <div id="product-review-form-rating" class="product-ratingsform-form-icon-fill">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-input-item mb-0">
                    <button type="submit" class="btn">KIRIM</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        
                    </div>
                </div>
            </section>
            <!--== End Product Details Area Wrapper ==-->

            <!--== Start Product Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h2 class="title">Produk Lainnya</h2>
                                <p class="m-0">Lihat rekomendasi produk lainnya yang mungkin Anda sukai, pilih dari berbagai koleksi buku menarik yang tersedia di toko kami.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-n10">
                        <div class="col-12">
                            <div class="swiper related-product-slide-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    // Koneksi ke database
                                    require_once 'db.php'; // Pastikan file ini berisi koneksi database menggunakan mysqli

                                    // Query untuk mendapatkan 3 produk terbaru
                                    $sql = "SELECT id, judul, cover, price FROM books ORDER BY created_at DESC LIMIT 3";
                                    $result = $conn->query($sql);

                                    // Periksa jika ada hasil
                                    if ($result->num_rows > 0) {
                                        while ($book = $result->fetch_assoc()) {
                                            ?>
                                            <div class="swiper-slide mb-10">
                                                <!--== Start Product Item ==-->
                                                <div class="product-item product-st2-item">
                                                    <div class="product-thumb">
                                                        <a class="d-block" href="product-details.php?id=<?php echo $book['id']; ?>">
                                                            <img src="../Admin/<?php echo htmlspecialchars($book['cover']); ?>" width="370" height="450" alt="<?php echo htmlspecialchars($book['judul']); ?>">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="product-rating">
                                                            <div class="rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fa <?php echo $i <= $stars ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                                            <?php endfor; ?>
                                                            </div>
                                                            <div class="reviews">0 ulasan</div> <!-- Anda bisa mengganti ini dengan ulasan asli -->
                                                        </div>
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
                            <img id="wishlist-img" src="assets/images/shop/modal1.png" alt="Product Image" width="466" height="320">
                        </div>
                        <h4 class="product-name" id="wishlist-product-name">
                            <a href="product-details.php" id="wishlist-product-link">Orang Gagal</a>
                        </h4>
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
                            <img id="cart-img" src="assets/images/shop/modal1.png" alt="Product Image" width="466" height="320">
                        </div>
                        <h4 class="product-name" id="cart-product-name">
                            <a href="product-details.php" id="cart-product-link">Orang Gagal</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<!--== End Product Quick Add Cart Modal ==-->

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewsModalLabel">Semua Ulasan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" 
        style="display: flex; justify-content: center; align-items: center; padding: 10px; background-color: transparent; border: none;">
    <i class="fa fa-times" style="font-size: 18px; color: #333;"></i>
</button>
      </div>
      <div class="modal-body" style="max-height: 370px; overflow-y: auto;">
        <?php
        // Ambil semua review dari database
        $stmt = $pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = :book_id ORDER BY r.created_at DESC");
        $stmt->execute(['book_id' => $book_id]);
        $all_reviews = $stmt->fetchAll();

        if (empty($all_reviews)) {
            echo '<p>Belum ada ulasan untuk buku ini.</p>';
        } else {
            // Loop untuk menampilkan semua review
            foreach ($all_reviews as $review) {
                $rating = $review['rating'];
                echo '<div class="product-review-item" style="border-bottom: 1px solid #ddd; margin-bottom: 10px;">';
                echo '    <div class="product-review-top" style="margin-bottom: 5px;">';
                echo '        <div class="product-review-content" style="display: flex; justify-content: space-between;">';
                echo '            <span class="product-review-name" style="font-weight: bold;">' . htmlspecialchars($review['username']) . '</span>';
                echo '            <div class="product-review-icon">';
                for ($i = 0; $i < $rating; $i++) {
                    echo '<i class="fa fa-star" style="color: #f39c12;"></i>';
                }
                for ($i = $rating; $i < 5; $i++) {
                    echo '<i class="fa fa-star-o" style="color: #f39c12;"></i>';
                }
                echo '            </div>';
                echo '        </div>';
                echo '    </div>';
                echo '    <p class="desc" style="margin-bottom: 5px;">' . htmlspecialchars($review['comment']) . '</p>';
                echo '    <small class="text-muted">' . date("d M Y H:i", strtotime($review['created_at'])) . '</small>';
                echo '</div>';
            }
        }
        ?>
      </div>
      <div class="modal-footer" style="height: 60px;">
      </div>
    </div>
  </div>
</div>




        
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

    <script>
        // Event listener untuk tombol wishlist
$('.btn-wishlist').on('click', function() {
    var productId = $(this).data('id');
    var productName = $(this).data('name');
    var productImg = $(this).data('img');

    // Update modal dengan data produk
    $('#wishlist-img').attr('src', productImg);
    $('#wishlist-product-name').text(productName);
    $('#wishlist-product-link').attr('href', 'product-details.php?id=' + productId);
});

// Event listener untuk tombol add-to-cart
$('.btn-add-to-cart').on('click', function() {
    var productId = $(this).data('id');
    var productName = $(this).data('name');
    var productImg = $(this).data('img');

    // Update modal dengan data produk
    $('#cart-img').attr('src', productImg);
    $('#cart-product-name').text(productName);
    $('#cart-product-link').attr('href', 'product-details.php?id=' + productId);
});

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