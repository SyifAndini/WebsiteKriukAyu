<?php
include_once 'koneksi.php';

// Function untuk generate ID pembeli (P0001, P0002, dst)
function generateUserId()
{
  return 'P' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
}

if (isset($_POST['daftar'])) {
  $nama = $_POST['nama'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $alamat = $_POST['alamat'] ?? '';
  $no_telp = $_POST['no_telp'] ?? '';

  // Validasi input
  if (empty($nama) || empty($email) || empty($password) || empty($alamat) || empty($no_telp)) {
    $_SESSION['error'] = "Harap mengisi semua field!";
  } else {
    // Handle Upload Foto Profil
    if (isset($_FILES['fotoProfil'])) {
      $uploadDir = 'uploads/';
      $fileName = $_FILES['fotoProfil']['name'];
      $fileTmp = $_FILES['fotoProfil']['tmp_name'];
      $fileSize = $_FILES['fotoProfil']['size'];
      $fileError = $_FILES['fotoProfil']['error'];

      // Validasi file
      $allowedExtensions = ['jpg', 'jpeg', 'png'];
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
          if ($fileSize < 2000000) { // Maksimal 2MB
            $tanggalHariIni = date("Ymd");
            $uniq = uniqid(); // misalnya: 666034db408ec
            $newFileName = 'profile_' . $tanggalHariIni . '_' . $uniq . "." . $fileExt;
            $fileDestination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $fileDestination)) {
              $foto_profil = $fileDestination;
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
    } else {
      // Redirect untuk menghindari resubmit form
      $foto_profil = 'assets/default-profile.svg';
    }
    // Generate ID pembeli
    $id_pembeli = generateUserId();

    // Cek jika ID pembeli tidak ada dalam database
    $result = mysqli_query($conn, "SELECT * FROM pembeli WHERE id_pembeli = '$id_pembeli'");
    $jumlahBaris = mysqli_num_rows($result);
    if ($jumlahBaris > 0) {
      $id_pembeli = generateUserId();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database dengan prepared statement
    $stmt = $conn->prepare("INSERT INTO pembeli (id_pembeli, nama, email, password, foto_profil, alamat, no_telp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $id_pembeli, $nama, $email, $hashed_password, $foto_profil, $alamat, $no_telp);

    if ($stmt->execute()) {
      $_SESSION['success'] = "Akun Anda berhasil dibuat! Silakan masuk menggunakan info akun Anda.";
      header('Location: login.php');
    } else {
      $_SESSION['error'] = "Error: " . addslashes($stmt->error);
      header('Location: register.php');
    }
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat Akun Baru</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
</head>

<body class="login-bg">
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
  <div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-container w-100 mx-3 mx-md-auto">
      <div class="row align-items-center">
        <!-- Gambar -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img src="assets/sign-up.png" alt="Login Illustration" class="login-image">
        </div>
        <!-- Form -->
        <div class="col-md-6">
          <h3 class="mb-4 text-center">Buat Akun Baru</h3>
          <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Lengkap</label>
              <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Saya">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="namasaya@mail.com">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="******">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label for="fotoProfil" class="form-label">Foto Profil</label>
              <input type="file" id="fotoProfil" name="fotoProfil" class="hidden">
            </div>

            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat</label>
              <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Jl. Mangga 2" required></textarea>
            </div>

            <div class="mb-3">
              <label for="no_telp" class="form-label">No. Telepon</label>
              <input type="tel" id="no_telp" name="no_telp" class="form-control" placeholder="0812xxxx" required>
            </div>

            <button type="submit" class="btn login-btn w-100 mb-2" name="daftar">Daftar</button>
            <div class="text-center small-text">
              Sudah punya akun? <a href="login.php">Masuk disini</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="my_js/main.js"></script>
</body>

</html>