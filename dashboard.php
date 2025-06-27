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

// Handle pesanan selesai
if (isset($_GET['action'])) {
  if ($_GET['action'] == 'selesai') {
    $status = mysqli_query($conn, "UPDATE pesanan SET status = 'Pesanan Selesai'
    WHERE no_pesanan = '$_GET[no_pesanan]'");
    if ($status) {
      $_SESSION['success'] = 'Pesanan Anda telah selesai!';
    } else {
      $_SESSION['error'] = 'Pesanan Anda tidak dapat diselesaikan. Ada kesalahan dalam basis data.';
    }
  }
  header('Location: dashboard.php');
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
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
</head>

<body>
  <?php if (isset($_SESSION['error'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          title: "Terjadi Error!",
          text: <?= json_encode($_SESSION['error']) ?>,
          icon: "error"
        });
      });
    </script>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          title: "Berhasil!",
          text: <?= json_encode($_SESSION['success']) ?>,
          icon: "success"
        });
      });
    </script>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
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
        <?php if (mysqli_num_rows($result) > 0): ?>
          <div>
            <a href="order.php" class="btn btn-primary mt-3 mb-3 text-right"><i class="bi bi-plus-circle me-1"></i> Buat Pesanan Baru</a>
          </div>
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
                    <td><a href="detail_pesanan.php?no_pesanan=<?= $pesanan['no_pesanan'] ?>">Detail</a></td>
                    <td><?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                    <td><?= $pesanan['status'] ?></td>
                    <td>
                      <a href="dashboard.php?action=selesai&no_pesanan=<?= $pesanan['no_pesanan'] ?>" class="btn btn-success btn-sm">Pesanan Diterima</button>
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
              <h4 class="mb-3 fw-bold">Belum Ada Pesanan</h4>
              <p class="text-muted">Anda belum pernah memesan kriuk.</p>
              <p class="text-muted">Silakan klik tombol <strong>"Buat Pesanan"</strong> untuk mulai memesan!</p>
              <a href="order.php" class="btn btn-primary mt-3"><i class="bi bi-plus-circle me-1"></i> Buat Pesanan Sekarang</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <!-- Bootstrap JS & Sidebar Toggle -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="my_js/main.js"></script>
</body>

</html>