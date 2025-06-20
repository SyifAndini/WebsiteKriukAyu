<?php
require_once 'koneksi.php';
session_start();
$id_pembeli = $_SESSION['id_user'];

if (isset($_POST['tambah_kriuk'])) {
  $jenis_kriuk = $_POST['jenisKriuk'] ?? '';
  $rasa_kriuk = $_POST['rasaKriuk'] ?? '';
  $jumlah_kriuk = $_POST['jumlah'] ?? '';

  if (empty($jenis_kriuk) || empty($rasa_kriuk) || empty($jumlah_kriuk)) {
    echo "<script>alert('Semua field harus diisi!')</script>";
  } else {
    // Ambil id_kriuk berdasarkan jenis dan rasa
    $query = mysqli_query($conn, "SELECT id_kriuk FROM produk WHERE jenis_kriuk = '$jenis_kriuk' AND rasa_kriuk = '$rasa_kriuk'");
    $data = mysqli_fetch_assoc($query);
    $id_kriuk = $data['id_kriuk'];

    $query = "INSERT INTO cart (id_pembeli, id_kriuk, jumlah) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $id_pembeli, $id_kriuk, $jumlah_kriuk);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Kriuk berhasil ditambahkan!')</script>";
    header("Location: order.php");
    exit();
  }
}

if (isset($_GET['hal'])) {
  //Pengujian jika edit Data
  if ($_GET['hal'] == "edit") {
    //Tampilkan Data yang akan diedit
    $tampil = mysqli_query($koneksi, "SELECT * FROM cart WHERE cart = '$_GET[id_cart]' ");
    $data = mysqli_fetch_array($tampil);

    if ($data) {
      //Jika data ditemukan, maka jumlah kriuk akan ditampilkan dalam modal


    }
  } else if ($_GET['hal'] == "hapus") {
    //Persiapan hapus data
    $hapus = mysqli_query($conn, "DELETE FROM cart WHERE id_cart = '$_GET[id_cart]' ");
    if ($hapus) {
      echo "<script>
                    alert('Kriuk Berhasil Dihapus!');
                    document.location = 'order.php';
                 </script>";
    }
  }
}
function generateOrderId($conn)
{
  $query = "SELECT COUNT(*) AS total FROM pesanan";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return 'P' . str_pad(($row['total'] + 1), 4, '0', STR_PAD_LEFT);
}
// Hitung total harga dari semua kriuk yang ada di cart
$total_query = "SELECT SUM(p.harga * c.jumlah) AS total FROM cart c JOIN produk p ON c.id_kriuk = p.id_kriuk WHERE c.id_pembeli = '$id_pembeli'";
$total_result = mysqli_query($conn, $total_query);
$subtotal = mysqli_fetch_assoc($total_result);
$subtotal_produk = $subtotal['total'] ?? 0;
$biaya_pengiriman = 10000; // Biaya tetap pengiriman  
$total_pembayaran = $subtotal_produk + $biaya_pengiriman;

if (isset($_POST['buat_pesanan'])) {
  $no_pesanan = generateOrderId($conn);
  $status = "Sedang Diproses";

  // Ambil data dari cart
  $query_cart = mysqli_query($conn, "SELECT cart.id_kriuk, cart.jumlah, produk.harga
                                   FROM cart 
                                   JOIN produk ON cart.id_kriuk = produk.id_kriuk 
                                   WHERE cart.id_pembeli = '$id_pembeli'");
  // Insert ke tabel pesanan (ringkasan)
  mysqli_query($conn, "INSERT INTO pesanan (no_pesanan, id_pembeli, subtotal_produk, biaya_pengiriman, total, status) 
                     VALUES ('$no_pesanan', '$id_pembeli', $subtotal_produk, $biaya_pengiriman, $total_pembayaran, '$status')");


  while ($row = mysqli_fetch_assoc($query_cart)) {
    $id_kriuk = $row['id_kriuk'];
    $jumlah = $row['jumlah'];
    $harga = $row['harga'];
    $subtotal = $jumlah * $harga;
    // Insert ke item_pesanan
    mysqli_query($conn, "INSERT INTO item_pesanan (no_pesanan, id_kriuk, jumlah, harga_akhir)
                         VALUES ('$no_pesanan', '$id_kriuk', $jumlah, $subtotal)");
  }

  // Kosongkan cart
  mysqli_query($conn, "DELETE FROM cart WHERE id_pembeli = '$id_pembeli'");
  echo "<script>alert(Pesanan Anda sudah masuk!)</script>";
  header('Location: order.php');
}

// Ambil data user dari database
$query = "SELECT * FROM pembeli WHERE id_pembeli = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat Pesanan Baru</title>
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
          <img src=<?=$_SESSION['foto_profil']?> alt="Profil User" width="50" height="50" class="rounded-circle">
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
          <a class="nav-link" href="dashboard.php">
            <i class="bi bi-cart"></i>
            <span>Pesanan Saya</span>
          </a>
          <a class="nav-link active" href="order.php">
            <i class="bi bi-plus-circle"></i>
            <span>Buat Pesanan</span>
          </a>
        </nav>
        <div class="mt-auto">
          <button class="btn btn-outline-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 px-4 py-3 justify-content-center">
        <!-- Mobile Menu Button -->
        <button class="btn btn-outline-secondary d-lg-none mb-3" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>

        <h4><strong>Halo, <?= $_SESSION['nama_user'] ?>!</strong></h4>
        <hr>
        <h5>Buat Pesanan Baru</h5>

        <div class="order-box mt-4">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="m-0"><strong>Detail Pesanan</strong></h6>
            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah Kriuk</button>
          </div>
          <?php
          // ambil pesanan sementara (cart) dari database
          $query = "SELECT id_cart, p.jenis_kriuk, p.rasa_kriuk, c.jumlah, (p.harga * c.jumlah) AS total_harga
                    FROM cart c
                    JOIN produk p ON c.id_kriuk = p.id_kriuk
                    WHERE c.id_pembeli = '$id_pembeli'";
          $result = mysqli_query($conn, $query); ?>

          <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
              <table class="table table-bordered text-center">
                <thead>
                  <tr>
                    <th>Jenis Kriuk</th>
                    <th>Rasa Kriuk</th>
                    <th>Jumlah</th>
                    <th>Harga Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                      <td><?= $row['jenis_kriuk'] ?></td>
                      <td><?= $row['rasa_kriuk'] ?></td>
                      <td><?= $row['jumlah'] ?></td>
                      <td><?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                      <td>
                        <a href="order.php?hal=edit&id_cart=<?= $row['id_cart'] ?>" class="btn btn-warning">Edit</a>
                        <a href="order.php?hal=hapus&id_cart=<?= $row['id_cart'] ?>" class="btn btn-danger">Hapus</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
          <div class="text-end">
            <p>Subtotal Produk: <strong>Rp. <?= number_format($subtotal_produk) ?></strong></p>
            <p>Biaya Pengiriman: <strong>Rp. <?= number_format($biaya_pengiriman) ?></strong></p>
            <p><strong>Total Pembayaran: Rp. <?= number_format($total_pembayaran) ?></strong></p>
          </div>

          <hr>


          <h6><strong>Informasi Pembeli</strong></h6>
          <form method="post" action="">
            <div class="row mb-3">
              <div class="col-md-4 mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value=<?= $_SESSION['nama_user'] ?>>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value=<?= $_SESSION['email_user'] ?>>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" value=<?= $user['no_telp'] ?>>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat Lengkap</label>
              <textarea class="form-control" rows="3"><?= $user['alamat'] ?></textarea>
            </div>
            <div class="text-end">
              <button type="submit" name="buat_pesanan" class="btn btn-dark">Buat Pesanan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Kriuk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="jenisKriuk" class="form-label">Jenis Kriuk</label>
              <select name="jenisKriuk" id="jenisKriuk" class="form-control" required>
                <option value="" disabled selected>Pilih Jenis Kriuk</option>
                <option value="Otak-Otak">Otak-Otak</option>
                <option value="Makaroni">Makaroni</option>
                <option value="Kerupuk Seblak">Kerupuk Seblak</option>
                <option value="Emping Jagung">Emping Jagung</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="rasaKriuk" class="form-label">Rasa Kriuk</label>
              <select name="rasaKriuk" id="rasaKriuk" class="form-control" required>
                <option value="" disabled selected>Pilih Rasa Kriuk</option>
                <option value="Jagung Bakar">Jagung Bakar</option>
                <option value="Pedas Manis">Pedas Manis</option>
                <option value="Ekstra Pedas">Ekstra Pedas</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="jumlahKriuk" class="form-label">Jumlah Kriuk</label>
              <input type="number" class="form-control" name="jumlah" id="jumlahKriuk" placeholder="Jumlah Kriuk" required>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" name="tambah_kriuk">Tambah Kriuk</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Bootstrap JS & Sidebar Toggle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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