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
// Ambil nilai ENUM untuk status
$sql = "DESCRIBE orders status";
$result = $conn->query($sql);
$enum_values = [];
if ($result) {
    $row = $result->fetch_assoc();
    preg_match_all("/'(.*?)'/", $row['Type'], $matches);
    $enum_values = $matches[1]; // Menyimpan nilai ENUM
}
// Proses update data order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status']; // Status yang dipilih
    $total = $_POST['total'];

    // Siapkan query untuk update
    $sql = $conn->prepare("UPDATE orders SET status = ?, total = ? WHERE order_id = ?");
    $sql->bind_param("ssi", $status, $total, $order_id);
    
    if ($sql->execute()) {
        header("Location: page-list-order.php");
    } else {
        echo "Error: " . $sql->error;
    }
    
    $sql->close();
}
// Query untuk mengambil data orders dan jumlah item terkait
$sql = "SELECT o.id AS order_id, o.user_id, o.order_date, o.status, o.total, COUNT(oi.id) AS item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id";
$result = $conn->query($sql);

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
            <!-- Popper.js dan Bootstrap 5 JS -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
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
                      <li class="">
                          <a href="page-list-product.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash13" width="20" height="20"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>            
                              <span class="ml-4">Produk</span>
                          </a>
                      </li>
                      <li class="">
                          <a href="page-list-customer.php" class="svg-icon">
                                <svg class="svg-icon" id="p-dash10" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                              <span class="ml-4">Pelanggan</span>
                          </a>
                      </li>
                      <li class="active">
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="header-title">
                    <h4 class="card-title mb-0">Daftar Pesanan</h4>
                </div>
                <div class="user-list-files d-flex">
                    <a href="page-add-customer.php" class="btn btn-primary add-list mr-2"><i class="las la-plus mr-2"></i>Tambah Pesanan</a>
                    <a class="btn btn-secondary" href="javascript:void(0);" onclick="exportToPDF()">Ekspor PDF</a>
                </div>
            </div>
            <div class="card-body">
            <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="entries">
                        <label for="entries-select">Tampilkan</label>
                        <select id="entries-select" class="form-control form-control-sm d-inline-block" style="width: auto;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <label for="entries-select">entri</label>
                    </div>
                    <div class="search">
                        <input type="text" id="search-input" class="form-control form-control-sm" placeholder="Cari...">
                    </div>
                </div>
                <hr class="my-4">
                <div class="table-responsive">
    <table id="order-list-table" class="table table-striped dataTable mt-4" role="grid" aria-describedby="order-list-page-info">
        <thead>
            <tr class="ligth">
                <th>ID Order<i class="sort-icon"></i></th>
                <th>Status<i class="sort-icon"></i></th>
                <th>Total<i class="sort-icon"></i></th>
                <th>Jumlah Item<i class="sort-icon"></i></th>
                <th style="min-width: 100px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo "Rp " . number_format($row['total'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['item_count']); ?></td>
                        <td>
                            <div class="flex align-items-center list-order-action">
                            <a class="btn btn-sm bg-primary view-order-btn" 
   data-toggle="tooltip" 
   data-placement="top" 
   title="View" 
   data-id="<?php echo $row['order_id']; ?>">
    <i class="ri-eye-line mr-0"></i>
</a>
<a class="btn btn-sm bg-success" data-bs-toggle="modal" data-bs-target="#editOrderModal" 
   data-id="<?php echo $row['order_id']; ?>" 
   data-status="<?php echo htmlspecialchars($row['status']); ?>" 
   data-total="<?php echo htmlspecialchars($row['total']); ?>" 
   data-order_date="<?php echo htmlspecialchars($row['order_date']); ?>">
    <i class="ri-pencil-line mr-0"></i>
</a>

                                <a href="javascript:void(0);" class="btn btn-sm bg-warning delete-order" 
                                   data-id="<?php echo $row['order_id']; ?>" title="Delete">
                                    <i class="ri-delete-bin-line mr-0"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada pesanan yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

                <!-- Container for Show Entries, Search, and Pagination -->
            <hr class="my-4">
            <div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
                <span id="pagination-info" class="pagination-info">Menampilkan 1 sampai 5 dari 10 entri</span>
                <div class="d-flex">
                    <button id="previous-page" class="btn btn-primary btn-sm mr-2">Sebelum</button>
                    <div id="pagination-numbers" class="d-flex"></div>
                    <button id="next-page" class="btn btn-primary btn-sm ml-2">Selanjutnya</button>
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
  </div>
  <!-- Wrapper End-->
   <!-- Modal for Viewing User -->
<!-- Modal for Viewing Customer Details -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" role="dialog" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOrderModalLabel">Detail Order</h5>
            </div>
            <div class="modal-body" id="orderDetails">
                <!-- Detail order akan dimuat di sini melalui AJAX -->
                <p>Memuat detail order...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
            </div>
            <form id="editOrderForm" action="update_order.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="edit-order-id">
                    
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select class="form-control" id="edit-status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <?php
                            // Looping untuk menampilkan pilihan status dari ENUM
                            foreach ($enum_values as $status) {
                                echo "<option value=\"$status\">$status</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-total">Total</label>
                        <input type="text" class="form-control" id="edit-total" name="total" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-order_date">Order Date</label>
                        <input type="text" class="form-control" id="edit-order_date" name="order_date" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
<!-- Inisialisasi DataTables -->
            <script>
            document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("user-list-table");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const searchInput = document.getElementById("search-input");
    const entriesSelect = document.getElementById("entries-select");
    const paginationInfo = document.getElementById("pagination-info");
    const paginationNumbers = document.getElementById("pagination-numbers");
    const previousPageButton = document.getElementById("previous-page");
    const nextPageButton = document.getElementById("next-page");

    let currentPage = 1;
    let rowsPerPage = parseInt(entriesSelect.value);

    // Filter rows based on search input
    function getFilteredRows() {
        const searchTerm = searchInput.value.toLowerCase();
        return rows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            return rowText.includes(searchTerm);
        });
    }
    // Sort Functionality
    function sortTable(columnIndex, ascending) {
    const sortedRows = rows.sort((a, b) => {
        const aText = a.cells[columnIndex].innerText.trim();
        const bText = b.cells[columnIndex].innerText.trim();

        // Periksa apakah datanya numerik
        const aNum = parseFloat(aText);
        const bNum = parseFloat(bText);

        if (!isNaN(aNum) && !isNaN(bNum)) {
            // Sorting numerik
            return ascending ? aNum - bNum : bNum - aNum;
        } else {
            // Sorting string
            return ascending
                ? aText.localeCompare(bText)
                : bText.localeCompare(aText);
        }
    });

    sortedRows.forEach(row => tbody.appendChild(row));
    paginate();
}

            // Add sort functionality to table headers
            table.querySelectorAll("th").forEach((header, columnIndex) => {
                let ascending = true;

                const sortIcon = document.createElement("span");
                sortIcon.classList.add("sort-icon");
                sortIcon.innerHTML = " &#x25B2;"; // Default arrow up
                header.appendChild(sortIcon);

                header.addEventListener("click", () => {
                    ascending = !ascending;
                    sortIcon.innerHTML = ascending ? " &#x25B2;" : " &#x25BC;"; // Toggle arrows
                    sortTable(columnIndex, ascending);
                });
            });

    // Update pagination buttons
    function updatePaginationButtons(totalPages) {
        paginationNumbers.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement("button");
            pageButton.textContent = i;
            pageButton.classList.add("btn", "btn-outline", "btn-sm");
            if (i === currentPage) {
                pageButton.disabled = true;
            }
            pageButton.addEventListener("click", () => {
                currentPage = i;
                renderTable();
            });
            paginationNumbers.appendChild(pageButton);
        }

        previousPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages || totalPages === 0;
    }

    // Render table rows based on the current page and filters
    function renderTable() {
        const filteredRows = getFilteredRows();
        const totalRows = filteredRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        currentPage = Math.max(1, Math.min(currentPage, totalPages));

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        rows.forEach(row => (row.style.display = "none"));
        filteredRows.slice(startIndex, endIndex).forEach(row => (row.style.display = ""));

        const showingStart = totalRows > 0 ? startIndex + 1 : 0;
        const showingEnd = Math.min(endIndex, totalRows);
        paginationInfo.textContent = `Menampilkan ${showingStart} sampai ${showingEnd} dari ${totalRows} entri`;

        updatePaginationButtons(totalPages);
    }

    // Event listeners
    searchInput.addEventListener("input", () => {
        currentPage = 1;
        renderTable();
    });

    entriesSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(entriesSelect.value);
        currentPage = 1;
        renderTable();
    });

    previousPageButton.addEventListener("click", () => {
        currentPage--;
        renderTable();
    });

    nextPageButton.addEventListener("click", () => {
        currentPage++;
        renderTable();
    });

    // Initialize table
    renderTable();
});

    </script>
<script>
    // Event listener for view buttons
    document.querySelectorAll('.view-order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-id');

        // Ambil detail order menggunakan AJAX
        fetch(`view_order.php?id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                // Populate modal dengan data yang diambil
                document.getElementById('orderDetails').innerHTML = data;

                // Tampilkan modal
                $('#viewOrderModal').modal('show');
            })
            .catch(error => console.error('Error fetching order details:', error));
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editOrderModal = document.getElementById('editOrderModal');
    
    editOrderModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        const orderId = button.getAttribute('data-id');
        const status = button.getAttribute('data-status');
        const total = button.getAttribute('data-total');
        const orderDate = button.getAttribute('data-order_date');
        
        // Debugging: check the values in the console
        console.log(orderId, status, total, orderDate);

        // Fill the modal form with the values
        editOrderModal.querySelector('#edit-order-id').value = orderId;
        editOrderModal.querySelector('#edit-status').value = status;
        editOrderModal.querySelector('#edit-total').value = total;
        editOrderModal.querySelector('#edit-order_date').value = orderDate;
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.delete-user').forEach(function(button) {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-id'); // Ambil user_id dari data-id
            console.log("User ID yang akan dihapus:", userId); // Log ID untuk pengecekan

            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                fetch('delete_customer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(userId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message); 
                        location.reload(); 
                    } else {
                        alert(data.message); 
                    }
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    alert('Terjadi kesalahan saat menghubungi server.');
                });
            }
        });
    });
});

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Pilih elemen yang ingin diekspor
    const element = document.querySelector('.table-responsive'); 

    html2canvas(element).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const imgWidth = 190;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        // Masukkan gambar ke dalam PDF
        doc.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
        
        // Simpan file PDF
        doc.save("DaftarPelanggan.pdf");
    });
}

</script>



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