<?php
require_once 'koneksi.php';
session_start();
$id_pembeli = $_SESSION['id_user'];
// Ambil data user dari database
$query = "SELECT * FROM pembeli WHERE id_pembeli = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id_pembeli);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

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
function generateOrderId()
{
  $tanggalPesan = date("Ymd");
  $angka = mt_rand(1, 99);
  return 'TR' . $tanggalPesan . str_pad($angka, 2, "0", STR_PAD_LEFT);
}
// Hitung total harga dari semua kriuk yang ada di cart
$total_query = "SELECT SUM(p.harga * c.jumlah) AS total FROM cart c JOIN produk p ON c.id_kriuk = p.id_kriuk WHERE c.id_pembeli = '$id_pembeli'";
$total_result = mysqli_query($conn, $total_query);
$subtotal = mysqli_fetch_assoc($total_result);
$subtotal_produk = $subtotal['total'] ?? 0;
$biaya_pengiriman = 10000; // Biaya tetap pengiriman  
$total_pembayaran = $subtotal_produk + $biaya_pengiriman;

if (isset($_POST['buat_pesanan'])) {
  $no_pesanan = generateOrderId();
  $metode_pembayaran = $_POST['metode_pembayaran'];
  if ($_POST['metode_pembayaran'] !== "Cash On Delivery (COD)") {
    $status = "Menunggu Konfirmasi";
  } else {
    $status = "Sedang Diproses";
    $bukti_bayar = '';
  }

  // Handle foto bukti bayar
  if (isset($_FILES['bukti_bayar'])) {
    $uploadDir = 'uploads/';
    $fileName = $_FILES['bukti_bayar']['name'];
    $fileTmp = $_FILES['bukti_bayar']['tmp_name'];
    $fileSize = $_FILES['bukti_bayar']['size'];
    $fileError = $_FILES['bukti_bayar']['error'];

    // Validasi file
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {
      if ($fileError === 0) {
        if ($fileSize < 2000000) { // Maksimal 2MB
          $tanggalHariIni = date("Ymd");
          $uniq = uniqid(); // misalnya: 666034db408ec
          $newFileName = 'buktiBayar_' . $tanggalHariIni . '_' . $uniq . "." . $fileExt;
          $fileDestination = $uploadDir . $newFileName;
          if (move_uploaded_file($fileTmp, $fileDestination)) {
            $bukti_bayar = $fileDestination;
            // Insert ke tabel pesanan (ringkasan)
            mysqli_query($conn, "INSERT INTO pesanan (no_pesanan, id_pembeli, subtotal_produk, biaya_pengiriman, total, metode_pembayaran, bukti_bayar, status) 
                     VALUES ('$no_pesanan', '$id_pembeli', $subtotal_produk, $biaya_pengiriman, $total_pembayaran, '$metode_pembayaran', '$bukti_bayar', '$status')");

            // Ambil data dari cart
            $query_cart = mysqli_query($conn, "SELECT cart.id_kriuk, cart.jumlah, produk.harga
                                   FROM cart 
                                   JOIN produk ON cart.id_kriuk = produk.id_kriuk 
                                   WHERE cart.id_pembeli = '$id_pembeli'");
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
            $_SESSION['success'] = "Pesanan Anda sudah kami terima. Pantau terus pesananmu ya!";
            // header('Location: dashboard.php');
          } else {
            $_SESSION['error'] = "Gagal mengupload file";
          }
        } else {
          $_SESSION['error'] = "Ukuran file terlalu besar (maks 2MB)";
        }
      } else {
        $_SESSION['error'] = "Error saat upload file";
      }
    } else {
      $_SESSION['error'] = "Format file tidak didukung (hanya JPG, JPEG, PNG)";
    }
  }
}
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
  <?php if (isset($_SESSION['success'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          title: "Berhasil!",
          text: <?= json_encode($_SESSION['success']) ?>,
          icon: "success",
        });
      });
    </script>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          title: "Terjadi Error!",
          text: <?= json_encode($_SESSION['error']) ?>,
          icon: "error",
        })
      });
    </script>
    <?php unset($_SESSION['error']); ?>
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
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah Kriuk</button>
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
                        <a href="order.php?hal=edit&id_cart=<?= $row['id_cart'] ?>" class="btn btn-sm btn-warning">Ubah Jumlah</a>
                        <a href="order.php?hal=hapus&id_cart=<?= $row['id_cart'] ?>" class="btn btn-sm btn-danger">Hapus</a>
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
          <form method="post" action="" enctype="multipart/form-data">
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
            <div class="mb-3">
              <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
              <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required onchange="tampilkanMetode();">
                <option value="" disabled selected>Pilih Metode Pembayaran</option>
                <option value="Cash On Delivery (COD)">Cash On delivery (COD)</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet (DANA/OVO)</option>
              </select>
            </div>
            <section id="cardTransfer" class="card shadow-sm border-0 mb-4 d-none">
              <div class="card-body text-center">
                <h4 class="card-title mb-2"><i class="bi bi-bank"></i> Transfer Bank</h4>
                <p class="mb-1">Bank <strong>BNI</strong></p>
                <h3 class="text-primary fw-bold">0212 3345 464</h3>
                <hr class="my-4">
                <div class="text-start">
                  <h6 class="fw-bold">Langkah-langkah Pembayaran:</h6>
                  <ol class="ps-3">
                    <li>Buka aplikasi Mobile Banking Anda</li>
                    <li>Pilih transfer ke Bank BNI</li>
                    <li>Masukkan nominal total pembayaran</li>
                    <li>Upload bukti pembayaran di kolom yang disediakan</li>
                  </ol>
                </div>
              </div>
            </section>
            <section id="cardEWallet" class="card shadow-sm border-0 mb-4 d-none">
              <div class="card-body text-center">
                <h4 class="card-title mb-2"><i class="bi bi-wallet2"></i> Pembayaran E-Wallet</h4>
                <p class="mb-1">E-Wallet Tersedia:</p>
                <div class="mb-3">
                  <span class="badge bg-warning me-2"><i class="bi bi-phone"></i> DANA</span>
                  <span class="badge bg-success"><i class="bi bi-phone"></i> OVO</span>
                </div>
                <h6 class="text-muted mb-1">Nomor Tujuan:</h6>
                <h3 class="text-primary fw-bold">0812 3456 7890</h3>
                <hr class="my-4">
                <div class="text-start">
                  <h6 class="fw-bold">Langkah-langkah Pembayaran:</h6>
                  <ol class="ps-3">
                    <li>Buka aplikasi DANA atau OVO di ponsel Anda</li>
                    <li>Pilih menu Kirim / Transfer ke Sesama</li>
                    <li>Masukkan nomor tujuan: <strong>0812 3456 7890</strong></li>
                    <li>Masukkan nominal pembayaran sesuai tagihan</li>
                    <li>Upload bukti pembayaran di kolom yang tersedia</li>
                  </ol>
                </div>
              </div>
            </section>
            <section id="cardCOD" class="card shadow-sm border-0 mb-4 d-none">
              <div class="card-body text-center">
                <h4 class="card-title mb-2"><i class="bi bi-truck"></i> Bayar di Tempat (COD)</h4>
                <p class="mb-3">Pembayaran dilakukan saat pesanan Anda sampai di alamat tujuan.</p>
                <div class="alert alert-warning text-start" role="alert">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>
                  <strong>Harap siapkan uang pas</strong> saat paket diterima, kurir tidak selalu membawa uang kembalian.
                </div>
                <div class="text-start">
                  <h6 class="fw-bold">Langkah-langkah:</h6>
                  <ol class="ps-3">
                    <li>Lakukan pemesanan seperti biasa</li>
                    <li>Pilih metode pembayaran <strong>COD (Bayar di Tempat)</strong></li>
                    <li>Tunggu kurir mengantarkan pesanan ke alamat Anda</li>
                    <li>Bayarkan total tagihan secara tunai saat paket diterima</li>
                  </ol>
                </div>
              </div>
            </section>
            <div id="uploadBukti" class="mb-3 d-none">
              <label for="buktiPembayaran" class="form-label">Upload Bukti Transfer:</label>
              <input class="form-control" type="file" id="buktiPembayaran" name="bukti_bayar" accept="image/*" required>
            </div>
            <div class="text-end">
              <button type="submit" name="buat_pesanan" class="btn btn-success fw-bold">Buat Pesanan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="my_js/main.js"></script>
</body>

</html>