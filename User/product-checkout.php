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

            <!--== Start Shopping Checkout Area Wrapper ==-->
            <section class="shopping-checkout-wrap section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <!--== Start Billing Accordion ==-->
                            <div class="checkout-billing-details-wrap">
                                <h2 class="title">Detail Pembayaran</h2>
                                <div class="billing-form-wrap">
                                    <form action="save_address.php" method="POST">
                                    <input type="hidden" name="customer_id" value="6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['fullname']); ?>" 
                                       required>
                                    </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['phone']); ?>" 
                                       required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $is_customer_empty ? '' : htmlspecialchars($customer['address']); ?></textarea>
                    
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label for="city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['city']); ?>">
                            </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
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
</select></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                <label for="postal_code" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['postal_code']); ?>">
                            
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                            <label for="country" class="form-label">Negara</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="<?php echo $is_customer_empty ? '' : htmlspecialchars($customer['country']); ?>" 
                                       required>
                                            </div>
                                            <div id="CheckoutBillingAccordion2" class="col-md-12">
                                                <div class="checkout-box" data-bs-toggle="collapse" data-bs-target="#CheckoutTwo" aria-expanded="false" role="toolbar">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input visually-hidden" id="ship-to-different-address">
                                                        <label class="custom-control-label" for="ship-to-different-address">Kirim ke alamat berbeda?</label>
                                                    </div>
                                                </div>
                                                <div id="CheckoutTwo" class="collapse" data-bs-parent="#CheckoutBillingAccordion2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="f_name2">Nama Depan <abbr class="required" title="wajib">*</abbr></label>
                                                                <input id="f_name2" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="l_name2">Nama Belakang <abbr class="required" title="wajib">*</abbr></label>
                                                                <input id="l_name2" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="com_name2">Nama Perusahaan (opsional)</label>
                                                                <input id="com_name2" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <div class="form-group">
                                                                <label for="country2">Negara <abbr class="required" title="wajib">*</abbr></label>
                                                                <select id="country2" class="form-control wide">
                                                                    <option>Indonesia</option>
                                                                    <option>Bangladesh</option>
                                                                    <option>Afghanistan</option>
                                                                    <option>Albania</option>
                                                                    <option>Algeria</option>
                                                                    <option>Armenia</option>
                                                                    <option>India</option>
                                                                    <option>Pakistan</option>
                                                                    <option>England</option>
                                                                    <option>China</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="street-address2-3">Alamat Jalan <abbr class="required" title="wajib">*</abbr></label>
                                                                <input id="street-address2-3" type="text" class="form-control" placeholder="Nomor rumah dan nama jalan">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="street-address2-2" class="visually-hidden">Alamat Jalan 2 (opsional)</label>
                                                                <input id="street-address2-2" type="text" class="form-control" placeholder="Apartemen, suite, unit, dll. (opsional)">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="town3">Kota <abbr class="required" title="wajib">*</abbr></label>
                                                                <input id="town3" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-4">
                                                            <div class="form-group">
                                                                <label for="province2">Provinsi <abbr class="required" title="wajib">*</abbr></label>
                                                                <select id="province2" class="form-control wide">
                                                                    <option>Jawa Timur</option>
                                                                    <option>Jawa Tengah</option>
                                                                    <option>Jawa Barat</option>
                                                                    <option>Sumatera Utara</option>
                                                                    <option>Bali</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="pz-code2">Kode Pos / ZIP (opsional)</label>
                                                                <input id="pz-code2" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <label for="order-notes">Catatan Pesanan (opsional)</label>
                                                    <textarea id="order-notes" class="form-control" placeholder="Catatan tentang pesanan Anda, misalnya catatan khusus untuk pengiriman."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!--== End Billing Accordion ==-->
                        </div>
                        <div class="col-lg-6">
                            <!--== Start Order Details Accordion ==-->
                            <div class="checkout-order-details-wrap">
                                <div class="order-details-table-wrap table-responsive">
                                    <h2 class="title mb-25">Pesanan Anda</h2>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="product-name">Produk</th>
                                                <th class="product-total">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-body">
                                            <tr class="cart-item">
                                                <td class="product-name">Orang Gagal <span class="product-quantity">× 1</span></td>
                                                <td class="product-total">Rp47.000</td>
                                            </tr>
                                            <tr class="cart-item">
                                                <td class="product-name">Belenggu <span class="product-quantity">× 1</span></td>
                                                <td class="product-total">Rp35.000</td>
                                            </tr>
                                            <tr class="cart-item">
                                                <td class="product-name">A Study in Scarlet <span class="product-quantity">× 1</span></td>
                                                <td class="product-total">Rp86.000</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-foot">
                                            <tr class="cart-subtotal">
                                                <th>Subtotal</th>
                                                <td>Rp168.000</td>
                                            </tr>
                                            <tr class="shipping">
                                                <th>Ongkos Kirim</th>
                                                <td>Flat rate: Rp10.000</td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Total</th>
                                                <td>Rp178.000</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                            
                                    <div class="shop-payment-method">
                                        <div id="PaymentMethodAccordion">
                                            <div class="card">
                                                <div class="card-header" id="bank_transfer">
                                                    <h5 class="title" data-bs-toggle="collapse" data-bs-target="#bankTransfer" aria-controls="bankTransfer" aria-expanded="true">Transfer Bank</h5>
                                                </div>
                                                <div id="bankTransfer" class="collapse show" aria-labelledby="bank_transfer" data-bs-parent="#PaymentMethodAccordion">
                                                    <div class="card-body">
                                                        <p>Silakan lakukan pembayaran ke rekening bank kami. Gunakan ID Pesanan Anda sebagai referensi pembayaran. Pesanan Anda tidak akan dikirimkan sampai dana diterima.</p>
                                                    </div>
                                                </div>
                                            </div>
                            
                                            <div class="card">
                                                <div class="card-header" id="gopay_payment">
                                                    <h5 class="title" data-bs-toggle="collapse" data-bs-target="#gopayPayment" aria-controls="gopayPayment" aria-expanded="false">GoPay</h5>
                                                </div>
                                                <div id="gopayPayment" class="collapse" aria-labelledby="gopay_payment" data-bs-parent="#PaymentMethodAccordion">
                                                    <div class="card-body">
                                                        <p>Silakan lakukan pembayaran melalui GoPay ke akun kami. Gunakan ID Pesanan Anda sebagai referensi pembayaran.</p>
                                                    </div>
                                                </div>
                                            </div>
                            
                                            <div class="card">
                                                <div class="card-header" id="ovo_payment">
                                                    <h5 class="title" data-bs-toggle="collapse" data-bs-target="#ovoPayment" aria-controls="ovoPayment" aria-expanded="false">OVO</h5>
                                                </div>
                                                <div id="ovoPayment" class="collapse" aria-labelledby="ovo_payment" data-bs-parent="#PaymentMethodAccordion">
                                                    <div class="card-body">
                                                        <p>Silakan lakukan pembayaran melalui OVO ke akun kami. Gunakan ID Pesanan Anda sebagai referensi pembayaran.</p>
                                                    </div>
                                                </div>
                                            </div>
                            
                                            <div class="card">
                                                <div class="card-header" id="dana_payment">
                                                    <h5 class="title" data-bs-toggle="collapse" data-bs-target="#danaPayment" aria-controls="danaPayment" aria-expanded="false">Dana</h5>
                                                </div>
                                                <div id="danaPayment" class="collapse" aria-labelledby="dana_payment" data-bs-parent="#PaymentMethodAccordion">
                                                    <div class="card-body">
                                                        <p>Silakan lakukan pembayaran melalui Dana ke akun kami. Gunakan ID Pesanan Anda sebagai referensi pembayaran.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Upload Bukti Pembayaran -->
                                        <div class="upload-payment-proof mt-4">
                                            <h5 class="title">Upload Bukti Pembayaran</h5>
                                            <p>Upload bukti transfer atau pembayaran di sini untuk memproses pesanan Anda lebih cepat.</p>
                                            <input type="file" id="payment_proof" class="form-control-file">
                                        </div>
                            
                                        <p class="p-text mt-4">Data pribadi Anda akan digunakan untuk memproses pesanan Anda dan mendukung pengalaman Anda di situs web ini, serta untuk tujuan lain sesuai dengan <a href="#/">kebijakan privasi kami</a>.</p>
                                        <div class="agree-policy">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="privacy" class="custom-control-input visually-hidden">
                                                <label for="privacy" class="custom-control-label">Saya telah membaca dan setuju dengan syarat dan ketentuan situs web ini <span class="required">*</span></label>
                                            </div>
                                        </div>
                                        <a href="order-status.php" class="btn-place-order">Buat Pesanan</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!--== End Order Details Accordion ==-->
                        </div>
                    </div>
                </div>
            </section>
            <!--== End Shopping Checkout Area Wrapper ==-->

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