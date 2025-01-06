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
        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" >
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <h2 class="page-header-title">Keranjang</h2>
                            <p class="m-0">Periksa produk di keranjang sebelum pembayaran.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

            <!--== Start Product Area Wrapper ==-->
            <section class="section-space">
                <div class="container">
                    <div class="shopping-cart-form table-responsive">
                        <form action="#" method="post">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name">Produk</th>
                                        <th class="product-price">Harga</th>
                                        <th class="product-quantity">Jumlah</th>
                                        <th class="product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tbody-item">
                                        <td class="product-remove">
                                            <a class="remove" href="javascript:void(0)">×</a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <div class="thumb">
                                                <a href="product-details.php">
                                                    <img src="assets/images/shop/p1.png" width="68" height="84" alt="Image-HasTech">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a class="title" href="product-details.php">Orang Gagal</a>
                                        </td>
                                        <td class="product-price">
                                            <span class="price">Rp. 47.000</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="pro-qty">
                                                <input type="text" class="quantity" title="Quantity" value="1">
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="price">Rp. 47.000</span>
                                        </td>
                                    </tr>
                                    <tr class="tbody-item">
                                        <td class="product-remove">
                                            <a class="remove" href="javascript:void(0)">×</a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <div class="thumb">
                                                <a href="product-details.php">
                                                    <img src="assets/images/shop/p6.png" width="68" height="84" alt="Image-HasTech">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a class="title" href="product-details.php">Belenggu</a>
                                        </td>
                                        <td class="product-price">
                                            <span class="price">Rp. 35.000</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="pro-qty">
                                                <input type="text" class="quantity" title="Quantity" value="1">
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="price">Rp. 35.000</span>
                                        </td>
                                    </tr>
                                    <tr class="tbody-item">
                                        <td class="product-remove">
                                            <a class="remove" href="javascript:void(0)">×</a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <div class="thumb">
                                                <a href="product-details.php">
                                                    <img src="assets/images/shop/p8.png" width="68" height="84" alt="Image-HasTech">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a class="title" href="product-details.php">A Study in Scarlet</a>
                                        </td>
                                        <td class="product-price">
                                            <span class="price">Rp. 86.000</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="pro-qty">
                                                <input type="text" class="quantity" title="Quantity" value="1">
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="price">Rp. 86.000</span>
                                        </td>
                                    </tr>
                                    <tr class="tbody-item-actions">
                                        <td colspan="6">
                                            <button type="submit" class="btn-update-cart" disabled>Perbarui Keranjang</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6"></div>
                        <div class="col-12 col-lg-6">
                            <div class="cart-totals-wrap">
                                <h2 class="title">Total Keranjang</h2>
                                <table>
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Subtotal</th>
                                            <td><span class="amount">Rp. 168.000</span></td>
                                        </tr>
                                        <tr class="shipping-totals">
                                            <th>Ongkos Kirim</th>
                                            <td>
                                                <ul class="shipping-list">
                                                    <li class="radio">
                                                        <input type="radio" name="shipping" id="radio1" checked>
                                                        <label for="radio1">Flat rate: <span>Rp. 10.000</span></label>
                                                    </li>
                                                    <li class="radio">
                                                        <input type="radio" id="radio2" name="shipping" value="free_shipping" disabled>
                                                        <label for="radio2">Gratis Ongkir</label>                                                    </li>
                                                </ul>
                                                <p class="destination">Dikirim ke <strong>Jakarta</strong>.</p>
                                                <a href="javascript:void(0)" class="btn-shipping-address">Ubah Alamat</a>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td><span class="amount">Rp. 178.000</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-end">
                                    <a href="product-checkout.php" class="checkout-button">Lanjutkan ke Checkout</a>
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