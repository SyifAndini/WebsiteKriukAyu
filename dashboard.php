<?php
session_start();
$id_pembeli = $_SESSION['id_user'];
require_once 'koneksi.php';
if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}

// Tampilkan pesanan user
$result = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pembeli = '$id_pembeli'");
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
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
          <img src=<?= $_SESSION['foto_profil'] ?> alt="Profil User" width="50" height="50" class="rounded-circle">
          <div><strong><?= $_SESSION['nama_user'] ?></strong></div>
          <small><?= $_SESSION['email_user'] ?></small>
        </div>
        <nav class="nav flex-column mb-4">
          <a class="nav-link" href="index.php">
            <i class="bi bi-house"></i>
            <span>Beranda</span>
          </a>
          <a class="nav-link" href="profile.php">
            <i class="bi bi-person"></i>
            <span>Profil Saya</span>
          </a>
          <a class="nav-link active" href="dashboard.php">
            <i class="bi bi-cart"></i>
            <span>Pesanan Saya</span>
          </a>
          <a class="nav-link" href="order.php">
            <i class="bi bi-plus-circle"></i>
            <span>Buat Pesanan</span>
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

        <h4><strong>Halo, <?= $_SESSION['nama_user'] ?>!</strong></h4>
        <hr>
        <h3>Pesanan Saya</h3>
        <a class="btn btn-primary" href="order.php">Buat Pesanan Baru</a>
        <!-- Jika belum pernah memesan -->
        <?php if (mysqli_num_rows($result) > 0): ?>
  <div class="table-responsive">
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>No. Pesanan</th>
          <th>Tanggal Pesan</th>
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
            <td><a href="#">Detail</a></td>
            <td><?= number_format($pesanan['total'], 0, ',', '.') ?></td>
            <td><?= $pesanan['status'] ?></td>
            <td>
              <button class="btn btn-success">Pesanan Diterima</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-info text-center" role="alert">
    <p>Anda belum pernah memesan kriuk.</p>
    <p>Silakan klik tombol "Buat Pesanan".</p>
  </div>
<?php endif; ?>


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