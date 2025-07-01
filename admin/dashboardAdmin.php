<?php
require_once '../koneksi.php';
session_start();
$id_admin = $_SESSION['id_admin'];
if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}

// Ambil info admin
$query = mysqli_query($conn, "SELECT * FROM admin WHERE id_admin = '$id_admin'");
$admin = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../my_css/style.css">
</head>

<body>
  <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-lg-3 sidebar d-flex flex-column justify-content-between px-4 py-3" id="sidebar">
        <div class="mb-4 text-center">
          <h5><strong>Kriuk Ayu</strong></h5>
        </div>
        <div class="text-center mb-3">
          <img src=<?= $admin['foto_profil'] ?> alt="Profil Admin" width="50" height="50" class="rounded-circle">
          <div><strong><?= $_SESSION['nama_admin'] ?></strong></div>
          <small><?= $_SESSION['email_admin'] ?></small>
        </div>
        <nav class="nav flex-column mb-4">
          <a class="nav-link" href="profilAdmin.php">
            <i class="bi bi-person"></i>
            <span>Profil Saya</span>
          </a>
          <a class="nav-link active" href="dashboardAdmin.php">
            <i class="bi bi-cart"></i>
            <span>Daftar Pesanan</span>
          </a>
          <a class="nav-link" href="pembayaran.php">
            <i class="bi bi-cart"></i>
            <span>Pembayaran</span>
          </a>
        </nav>
        <div class="mt-auto">
          <form method="POST">
            <button type="submit" name="logout" class="btn btn-outline-danger w-100">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 px-4 py-3 justify-content-center">
        <!-- Mobile Menu Button -->
        <button class="btn btn-outline-secondary d-lg-none mb-3" onclick="toggleSidebar()">☰ Menu</button>

        <h4><strong>Halo, <?= $_SESSION['nama_admin'] ?>!</strong></h4>
        <hr>
        <h3>Info Pesanan</h3>
        <div class="alert alert-info text-center" role="alert">
          <p>Belum Ada Pesanan Masuk</p>
        </div>
      </div>
      <!-- Bootstrap JS & Sidebar Toggle -->
      <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
      <script>
        function toggleSidebar() {
          const sidebar = document.getElementById("sidebar");
          const overlay = document.getElementById("overlay");
          sidebar.classList.toggle("show");
          overlay.classList.toggle("show");
        }
      </script>
</body>

</html>