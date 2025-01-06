<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil ID buku dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data buku berdasarkan ID
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        die("Buku tidak ditemukan.");
    }
} else {
    die("ID buku tidak ada.");
}

// Proses form untuk mengupdate buku
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $genre = $_POST['genre'];
    $halaman = $_POST['halaman'];
    $berat = $_POST['berat'];
    $sinopsis = $_POST['sinopsis'];
    $kondisi = $_POST['kondisi'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $isbn = $_POST['isbn'];
    $cover_image = $book['cover']; // Gunakan gambar lama jika tidak diubah

    // Cek jika ada gambar baru
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        // Validasi tipe file gambar
        $fileType = mime_content_type($_FILES['cover_image']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($fileType, $allowedTypes)) {
            // Pastikan folder 'uploads/' ada
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            // Menyimpan file gambar ke server
            $uniqueFileName = time() . '_' . basename($_FILES['cover_image']['name']);
            $cover_image = 'uploads/' . $uniqueFileName;

            // Memindahkan file ke folder 'uploads'
            if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image)) {
                echo "Error: Gagal meng-upload gambar.";
                $cover_image = $book['cover']; // Gunakan gambar lama jika gagal upload
            }
        } else {
            echo "Error: File yang di-upload bukan gambar yang valid.";
        }
    }

    // Validasi input wajib
    if (empty($judul) || empty($penulis) || empty($penerbit) || empty($tahun_terbit)) {
        echo "Error: Semua kolom wajib diisi.";
        exit;
    }

    // Query untuk mengupdate data buku
    $sql = "UPDATE books 
            SET judul = ?, 
                penulis = ?, 
                penerbit = ?, 
                tahun_terbit = ?, 
                genre = ?, 
                halaman = ?, 
                berat = ?, 
                sinopsis = ?, 
                kondisi = ?, 
                price = ?, 
                stock = ?, 
                kategori = ?, 
                isbn = ?, 
                cover = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssssiissdisssi', 
        $judul, 
        $penulis, 
        $penerbit, 
        $tahun_terbit, 
        $genre, 
        $halaman, 
        $berat, 
        $sinopsis, 
        $kondisi, 
        $price, 
        $stock, 
        $kategori, 
        $isbn, 
        $cover_image, 
        $id
    );

    if ($stmt->execute()) {
        // Redirect ke halaman list produk jika berhasil
        header("Location: page-list-product.php");
        exit; // Hentikan eksekusi setelah redirect
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <title>LeBookle | Admin</title>
          
          <!-- Favicon -->
          <link rel="shortcut icon" href="assets/images/logo.png" />
          <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
          <link rel="stylesheet" href="assets/css/backend.css?v=1.0.0">
          <link rel="stylesheet" href="assets/css/all.min.css">
          <link rel="stylesheet" href="assets/css/line-awesome.min.css">
          <link rel="stylesheet" href="assets/css/remixicon.css"> 

            <!-- JS DataTables -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <!-- Tambahkan Font Awesome di head -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
      <body class="  ">
        <!-- Wrapper Start -->
        <div class="wrapper">
        <div class="iq-sidebar  sidebar-default ">
            <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
                <a href="index.php" class="header-logo">
                    <img src="assets/images/logo.png" class="img-fluid rounded-normal light-logo" alt="logo"><h5 class="logo-title light-logo ml-3">LeBookle</h5>
                </a>
                <div class="iq-menu-bt-sidebar ml-0">
                    <i class="las la-bars wrapper-menu"></i>
                </div>
            </div>
            <div class="data-scrollbar" data-scroll="1">
              <nav class="iq-sidebar-menu">
                  <ul id="iq-sidebar-toggle" class="iq-menu">
                      <li class=" ">
                          <a href="index.php" class="svg-icon">                        
                              <svg  class="svg-icon" id="p-dash1" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
                              </svg>
                              <span class="ml-4">Dashboard</span>
                          </a>
                      </li>
                      <li class="">
                          <a href="page-list-user.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash8" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                              <span class="ml-4">Pengguna</span>
                          </a>
                      </li>
                      <li class="active">
                          <a href="page-list-product.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash13" width="20" height="20"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>            
                              <span class="ml-4">Produk</span>
                          </a>
                      </li>
                      <li class=" ">
                          <a href="page-list-customer.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash10" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                              <span class="ml-4">Pelanggan</span>
                          </a>
                      </li>
                      <li class=" ">
                          <a href="page-list-order.php" class="svg-icon">
                          <svg class="svg-icon" id="p-dash2" width="20" height="20"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                              <span class="ml-4">Pesanan</span>
                          </a>
                      </li>
                      <li class=" ">
                          <a href="page-list-payment.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash5" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                  <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                              <span class="ml-4">Pembayaran</span>
                          </a>
                      </li>
                      <li class="">
                          <a href="page-list-blog.php" class="">
                                <svg class="svg-icon" id="p-dash7" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                              <span class="ml-4">Blog</span>
                          </a>
                      </li>
                      <li class=" ">
                          <a href="page-list-review.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash18" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                              <span class="ml-4">Review</span>
                          </a>
                      </li>
                  </ul>
              </nav>
              <div class="p-3"></div>
          </div>
            </div>      <div class="iq-top-navbar">
          <div class="iq-navbar-custom">
              <nav class="navbar navbar-expand-lg navbar-light p-0">
                  <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                      <i class="ri-menu-line wrapper-menu"></i>
                      <a href="index.php" class="header-logo">
                          <img src="assets/images/logo.png" class="img-fluid rounded-normal" alt="logo">
                          <h5 class="logo-title ml-3">LeBookle</h5>
      
                      </a>
                  </div>
                  <div class="d-flex align-items-center">
                      <button class="navbar-toggler" type="button" data-toggle="collapse"
                          data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                          aria-label="Toggle navigation">
                          <i class="ri-menu-3-line"></i>
                      </button>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav ml-auto navbar-list align-items-center">
                              <li class="nav-item nav-icon dropdown">
                                  <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round" class="feather feather-bell">
                                          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                      </svg>
                                      <span class="bg-primary "></span>
                                  </a>
                                  <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <div class="card shadow-none m-0">
                                          <div class="card-body p-0 ">
                                              <div class="cust-title p-3">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                      <h5 class="mb-0">Notifikasi</h5>
                                                      <a class="badge badge-primary badge-card" href="#">3</a>
                                                  </div>
                                              </div>
                                              <div class="px-3 pt-0 pb-0 sub-card">
                                                  <a href="#" class="iq-sub-card">
                                                      <div class="media align-items-center cust-card py-3 border-bottom">
                                                          <div class="">
                                                              <img class="avatar-50 rounded-small"
                                                                  src="assets/images/user/01.jpg" alt="01">
                                                          </div>
                                                          <div class="media-body ml-3">
                                                              <div class="d-flex align-items-center justify-content-between">
                                                                  <h6 class="mb-0">Emma Watson</h6>
                                                                  <small class="text-dark"><b>12 : 47 pm</b></small>
                                                              </div>
                                                              <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                          </div>
                                                      </div>
                                                  </a>
                                                  <a href="#" class="iq-sub-card">
                                                      <div class="media align-items-center cust-card py-3 border-bottom">
                                                          <div class="">
                                                              <img class="avatar-50 rounded-small"
                                                                  src="assets/images/user/02.jpg" alt="02">
                                                          </div>
                                                          <div class="media-body ml-3">
                                                              <div class="d-flex align-items-center justify-content-between">
                                                                  <h6 class="mb-0">Ashlynn Franci</h6>
                                                                  <small class="text-dark"><b>11 : 30 pm</b></small>
                                                              </div>
                                                              <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                          </div>
                                                      </div>
                                                  </a>
                                                  <a href="#" class="iq-sub-card">
                                                      <div class="media align-items-center cust-card py-3">
                                                          <div class="">
                                                              <img class="avatar-50 rounded-small"
                                                                  src="assets/images/user/03.jpg" alt="03">
                                                          </div>
                                                          <div class="media-body ml-3">
                                                              <div class="d-flex align-items-center justify-content-between">
                                                                  <h6 class="mb-0">Kianna Carder</h6>
                                                                  <small class="text-dark"><b>11 : 21 pm</b></small>
                                                              </div>
                                                              <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                          </div>
                                                      </div>
                                                  </a>
                                              </div>
                                              <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#"
                                                  role="button">
                                                  Lihat Semua
                                              </a>
                                          </div>
                                      </div>
                                  </div>
                              </li>
                              <li class="nav-item nav-icon dropdown caption-content">
                                  <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <img src="assets/images/user/1.png" class="img-fluid rounded" alt="user">
                                  </a>
                                  <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <div class="card shadow-none m-0">
                                          <div class="card-body p-0 text-center">
                                              <div class="media-body profile-detail text-center">
                                                  <img src="assets/images/page-img/profile-bg.jpg" alt="profile-bg"
                                                      class="rounded-top img-fluid mb-4">
                                                  <img src="assets/images/user/1.png" alt="profile-img"
                                                      class="rounded profile-img img-fluid avatar-70">
                                              </div>
                                              <div class="p-3">
                                                  <h5 class="mb-1">JoanDuo@property.com</h5>
                                                  <p class="mb-0">Sejak 10 Maret, 2020</p>
                                                  <div class="d-flex align-items-center justify-content-center mt-3">
                                                      <a href="user-profile.php" class="btn border mr-2">Profil</a>
                                                      <a href="auth-sign-in.php" class="btn border">Logout</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </li>
                          </ul>
                      </div>
                  </div>
              </nav>
          </div>
      </div>
        <div class="content-page">
        <div class="container-fluid">
        <div class="row">
    <div class="col-sm-12">
    <div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Buku</h4>
    </div>
    <div class="card-body">
        <form action="update_product.php?id=<?php echo $book['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul *</label>
                <input type="text" name="judul" class="form-control" value="<?php echo $book['judul']; ?>" required>
            </div>
            <div class="form-group">
                <label for="penulis">Penulis *</label>
                <input type="text" name="penulis" class="form-control" value="<?php echo $book['penulis']; ?>" required>
            </div>
            <div class="form-group">
                <label for="penerbit">Penerbit *</label>
                <input type="text" name="penerbit" class="form-control" value="<?php echo $book['penerbit']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" min="1000" max="9999" value="<?php echo $book['tahun_terbit']; ?>">
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="fiksi" <?php echo ($book['kategori'] == 'fiksi') ? 'selected' : ''; ?>>Fiksi</option>
                    <option value="nonfiksi" <?php echo ($book['kategori'] == 'nonfiksi') ? 'selected' : ''; ?>>Non-Fiksi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <select name="genre" class="form-control">
                    <option value="romansa" <?php echo ($book['genre'] == 'romansa') ? 'selected' : ''; ?>>Romansa</option>
                    <option value="horor" <?php echo ($book['genre'] == 'horor') ? 'selected' : ''; ?>>Horor</option>
                    <option value="fantasi" <?php echo ($book['genre'] == 'fantasi') ? 'selected' : ''; ?>>Fantasi</option>
                    <option value="fiksi ilmiah" <?php echo ($book['genre'] == 'fiksi ilmiah') ? 'selected' : ''; ?>>Fiksi Ilmiah</option>
                    <option value="thriller/misteri" <?php echo ($book['genre'] == 'thriller/misteri') ? 'selected' : ''; ?>>Thriller/Misteri</option>
                    <option value="komedi" <?php echo ($book['genre'] == 'komedi') ? 'selected' : ''; ?>>Komedi</option>
                    <option value="biografi" <?php echo ($book['genre'] == 'biografi') ? 'selected' : ''; ?>>Biografi</option>
                    <option value="pendidikan" <?php echo ($book['genre'] == 'pendidikan') ? 'selected' : ''; ?>>Pendidikan</option>
                    <option value="religi" <?php echo ($book['genre'] == 'religi') ? 'selected' : ''; ?>>Religi</option>
                    <option value="politik" <?php echo ($book['genre'] == 'politik') ? 'selected' : ''; ?>>Politik</option>
                    <option value="ekonomi" <?php echo ($book['genre'] == 'ekonomi') ? 'selected' : ''; ?>>Ekonomi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="halaman">Jumlah Halaman</label>
                <input type="number" name="halaman" class="form-control" value="<?php echo $book['halaman']; ?>">
            </div>
            <div class="form-group">
                <label for="berat">Berat (gram)</label>
                <input type="number" name="berat" class="form-control" value="<?php echo $book['berat']; ?>">
            </div>
            <div class="form-group">
                <label for="sinopsis">Sinopsis</label>
                <textarea name="sinopsis" class="form-control" rows="3"><?php echo $book['sinopsis']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="kondisi">Kondisi</label>
                <textarea name="kondisi" class="form-control" rows="3"><?php echo $book['kondisi']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="berat">Isbn</label>
                <input type="text" name="isbn" class="form-control" value="<?php echo $book['isbn']; ?>">
            </div>
            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $book['price']; ?>">
            </div>
            <div class="form-group">
                <label for="stock">Stok</label>
                <input type="number" name="stock" class="form-control" value="<?php echo $book['stock']; ?>">
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <input type="file" name="cover_image" class="form-control">
                <img src="<?php echo $book['cover']; ?>" alt="Cover Image" width="100px" class="mt-2">
            </div>
            <button type="submit" class="btn btn-primary">Update Buku</button>
        </form>
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
  <!-- Wrapper End-->
  <footer class="iq-footer">
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="privacy-policy.php">Kebijakan Privasi</a></li>
                        <li class="list-inline-item"><a href="terms-of-service.php">Syarat dan Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 text-right">
                    <span class="mr-1"><script>document.write(new Date().getFullYear())</script>©</span> <a href="#" class="">LeBookle</a>.
                </div>
            </div>
        </div>
    </div>
</div>
</footer>
    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/backend-bundle.min.js"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="assets/js/table-treeview.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="assets/js/customizer.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script async src="assets/js/chart-custom.js"></script>
    
    <!-- app JavaScript -->
    <script src="assets/js/app.js"></script>
  </body>
</html>