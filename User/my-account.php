<?php
session_start(); // Mulai sesi untuk memeriksa login

// Cek apakah pengguna sudah login (session 'user_id' ada)
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: /LeBookle/Auth/index.php"); // Ganti dengan path login Anda
    exit();
}

// Koneksi ke database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_lebooklex', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data pengguna berdasarkan user_id yang disimpan di sesi
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Ambil hasil query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $username = $user['username']; // Ambil username dari database
    } else {
        // Jika data pengguna tidak ditemukan, logout otomatis
        session_unset();
        session_destroy();
        header("Location: /LeBookle/Auth/index.php");
        exit();
    }
// Ambil data customer berdasarkan user_id
$stmt = $pdo->prepare("SELECT * FROM customer WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    // Data customer tidak ditemukan
    $is_customer_empty = true;
} else {
    $is_customer_empty = false;
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_pwd = $_POST['current_password'];
    $new_pwd = $_POST['new_password'];
    $confirm_pwd = $_POST['confirm_password'];

    // Validasi kata sandi saat ini
    if (!empty($current_pwd) && password_verify($current_pwd, $user['password_hash'])) {
        // Validasi kata sandi baru
        if (!empty($new_pwd) && $new_pwd === $confirm_pwd) {
            $new_password_hash = password_hash($new_pwd, PASSWORD_BCRYPT);
        } else {
            $error = "Kata sandi baru tidak cocok atau kosong.";
        }
    } elseif (!empty($current_pwd)) {
        $error = "Kata sandi saat ini salah.";
    }

    // Perbarui data pengguna
    if (!isset($error)) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email" .
            (isset($new_password_hash) ? ", password_hash = :password_hash" : "") .
            " WHERE id = :id");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if (isset($new_password_hash)) {
            $stmt->bindParam(':password_hash', $new_password_hash, PDO::PARAM_STR);
        }
        $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $success = "Informasi berhasil diperbarui.";
    }
}
} catch (PDOException $e) {
echo "Koneksi gagal: " . $e->getMessage();
}

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
            <section class="page-header-area pt-10 pb-9">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="page-header-st3-content text-center text-md-start">
                                <h2 class="page-header-title">Akun Saya</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--== End Page Header Area Wrapper ==-->

            <!--== Start My Account Area Wrapper ==-->
            <section class="my-account-area section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="my-account-tab-menu nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="dashboad-tab" data-bs-toggle="tab" data-bs-target="#dashboad" type="button" role="tab" aria-controls="dashboad" aria-selected="true">Dashboard</button>
                                <button class="nav-link" id="address-edit-tab" data-bs-toggle="tab" data-bs-target="#address-edit" type="button" role="tab" aria-controls="address-edit" aria-selected="false">Alamat</button>
                                <button class="nav-link" id="account-info-tab" data-bs-toggle="tab" data-bs-target="#account-info" type="button" role="tab" aria-controls="account-info" aria-selected="false">Detail Akun</button>
                                <button class="nav-link nav-link-logout" onclick="window.location.href='/LeBookle/Auth/index.php'" type="button">Logout</button>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="dashboad" role="tabpanel" aria-labelledby="dashboad-tab">
                                <div class="myaccount-content">
                                    <h3>Dashboard</h3>
                                    <div class="welcome">
                                        <p>Halo, <strong><?php echo htmlspecialchars($username); ?></strong> (Jika Bukan <strong><?php echo htmlspecialchars($username); ?>!</strong><a href="http://localhost/LeBookle/Auth/index.php" class="logout"> Logout</a>)</p>
                                    </div>
                                    <p>Dari Dasboard akun Anda, Anda dapat dengan mudah memeriksa dan mengelola alamat pengiriman, serta mengedit detail akun Anda.</p>
                                </div>
                            </div>

                                <div class="tab-pane fade" id="address-edit" role="tabpanel" aria-labelledby="address-edit-tab">
                                    <div class="myaccount-content">
                                        <h3>Detail Akun</h3>
                                        <?php if ($is_customer_empty): ?>
                                            <p class="text-danger">Pengguna belum mendaftarkan informasi pribadi.</p>
                                        <?php else: ?>
                                            <address>
                                                <p><strong><?php echo htmlspecialchars($customer['fullname']); ?></strong></p>
                                                <p>Alamat: <?php echo htmlspecialchars($customer['address']); ?><br></p> 
                                                <p>Kota: <?php echo htmlspecialchars($customer['city']); ?><br></p> 
                                                <p>Provinsi: <?php echo htmlspecialchars($customer['state']); ?><br></p> 
                                                <p>Kode Pos: <?php echo htmlspecialchars($customer['postal_code']); ?><br></p> 
                                                <p>Negara: <?php echo htmlspecialchars($customer['country']); ?><br></p> 
                                                <p>Telepon: <?php echo htmlspecialchars($customer['phone']); ?><br></p> 
                                            </address>
                                        <?php endif; ?>
                                        <a href="#editAddressModal" data-bs-toggle="modal" class="check-btn sqr-btn">
                                            <i class="fa fa-edit"></i> <?php echo $is_customer_empty ? 'Tambah Informasi' : 'Ubah Alamat'; ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="account-info" role="tabpanel" aria-labelledby="account-info-tab">
                                <div class="myaccount-content">
    <h3>Edit Akun</h3>
    <div class="account-details-form">
        <?php if (isset($error)): ?>
            <p class="text-danger"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="text-success"><?php echo $success; ?></p><br><br>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="single-input-item">
                <label for="username" class="required">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required />
            </div>
            <div class="single-input-item">
                <label for="email" class="required">Alamat Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
            </div>
            <fieldset>
                <legend>Ubah Kata Sandi</legend>
                <div class="single-input-item" style="position: relative; margin-bottom: 15px;">
    <label for="current_password" class="required">Kata Sandi Saat Ini</label>
    <input 
        type="password" 
        id="current_password" 
        name="current_password" 
        style="width: 100%; padding-right: 35px;" />
    <i 
        class="fa fa-eye" 
        id="toggle-current-password" 
        style="position: absolute; top: 68%; right: 10px; transform: translateY(-50%); cursor: pointer;">
    </i>
</div>

<div class="single-input-item" style="position: relative; margin-bottom: 15px;">
    <label for="new_password">Kata Sandi Baru</label>
    <input 
        type="password" 
        id="new_password" 
        name="new_password" 
        style="width: 100%; padding-right: 35px;" />
    <i 
        class="fa fa-eye" 
        id="toggle-new-password" 
        style="position: absolute; top: 68%; right: 10px; transform: translateY(-50%); cursor: pointer;">
    </i>
</div>

<div class="single-input-item" style="position: relative; margin-bottom: 15px;">
    <label for="confirm_password">Konfirmasi Kata Sandi</label>
    <input 
        type="password" 
        id="confirm_password" 
        name="confirm_password" 
        style="width: 100%; padding-right: 35px;" />
    <i 
        class="fa fa-eye" 
        id="toggle-confirm-password" 
        style="position: absolute; top: 68%; right: 10px; transform: translateY(-50%); cursor: pointer;">
    </i>
</div>
                <div class="single-input-item">
    <a href="forgot_account.php" class="forgot-link">Lupa Akun?</a>
</div>

            </fieldset>
            <div class="single-input-item">
                <button type="submit" class="check-btn sqr-btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--== End My Account Area Wrapper ==-->

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

        <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">
                    <?php echo $is_customer_empty ? 'Tambah Informasi' : 'Ubah Alamat'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
        style="display: flex; justify-content: center; align-items: center; padding: 10px; background-color: transparent; border: none;">
    <i class="fa fa-times" style="font-size: 18px; color: #333;"></i>
</button>

            </div>
            <div class="modal-body">
                <form action="save_address.php" method="POST">
                    <!-- Hidden input untuk menyimpan ID pelanggan jika data ada -->
                    <?php if (!$is_customer_empty): ?>
                        <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['fullname']); ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['phone']); ?>" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $is_customer_empty ? '' : htmlspecialchars($customer['address']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['city']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                            <label for="state" class="form-label">Provinsi</label>
                            <select class="form-group form-control wide" id="state" name="state" required >
    <option value="" disabled <?php echo $is_customer_empty ? 'selected' : ''; ?>>Pilih Provinsi</option>
    <?php 
    $states = [
        'Banten', 
        'Daerah Khusus Ibukota Jakarta', 
        'Jawa Barat', 
        'Jawa Tengah', 
        'Daerah Istimewa Yogyakarta', 
        'Jawa Timur', 
        'Luar Pulau Jawa'
    ];
    foreach ($states as $state) {
        $selected = (!$is_customer_empty && $customer['state'] === $state) ? 'selected' : '';
        echo "<option value=\"$state\" $selected>$state</option>";
    }
    ?>
</select>


                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['postal_code']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Negara</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['country']); ?>" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

    <script>
    document.getElementById("toggle-current-password").addEventListener("click", function() {
        let passwordField = document.getElementById("current_password");
        let icon = this;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });

    document.getElementById("toggle-new-password").addEventListener("click", function() {
        let passwordField = document.getElementById("new_password");
        let icon = this;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });

    document.getElementById("toggle-confirm-password").addEventListener("click", function() {
        let passwordField = document.getElementById("confirm_password");
        let icon = this;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
</script>

</body>


</html>