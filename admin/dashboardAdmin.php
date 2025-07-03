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

// Ambil daftar pesanan dari database
$result = mysqli_query($conn, "SELECT * FROM pesanan WHERE status != 'Menunggu Konfirmasi'");
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
        <h3>Daftar Pesanan Kriuk</h3>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered text-center">
              <thead>
                <tr>
                  <th>No. Pesanan</th>
                  <th>Tanggal Pesan</th>
                  <th>ID Pembeli</th>
                  <th>Detail Pesanan</th>
                  <th>Total Pembayaran</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?= $pesanan['no_pesanan'] ?></td>
                    <td><?= $pesanan['tanggal'] ?></td>
                    <td><?= $pesanan['id_pembeli'] ?></td>
                    <td><a href="detail_pesanan.php?no_pesanan=<?= $pesanan['no_pesanan'] ?>">Detail</a></td>
                    <td><?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                    <td><?= $pesanan['status'] ?></td>
                    <td>
                      <a href="dashboardAdmin.php?action=selesai&no_pesanan=<?= $pesanan['no_pesanan'] ?>" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i></a>
                      <a href="dashboardAdmin.php?action=selesai&no_pesanan=<?= $pesanan['no_pesanan'] ?>" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i></a>

                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <!-- Jika belum pernah memesan -->
          <div class="card border-0 shadow-sm text-center py-5 px-4 mb-5">
            <div class="card-body">
              <div class="mb-4">
                <i class="bi bi-cart-x-fill" style="font-size: 3rem; color: #6c757d;"></i>
              </div>
              <h4 class="mb-3 fw-bold">Belum Ada Pesanan Kriuk</h4>
            </div>
          </div>
        <?php endif; ?>
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