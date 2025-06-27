<?php
include_once 'koneksi.php';

// Function untuk generate ID pembeli (P0001, P0002, dst)
function generateUserId($conn)
{
  $sql = "SELECT COUNT(*) AS total FROM pembeli";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return 'P' . str_pad(($row['total'] + 1), 4, '0', STR_PAD_LEFT);
}

if (isset($_POST['daftar'])) {
  $nama = $_POST['nama'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validasi input
  if (empty($nama) || empty($email) || empty($password)) {
    $_SESSION['error'] = "Harap mengisi semua field!";
  } else {
    // Generate ID pembeli
    $id_pembeli = generateUserId($conn);

    // Cek jika ID pembeli tidak ada dalam database
    $result = mysqli_query($conn, "SELECT * FROM pembeli WHERE id_pembeli = '$id_pembeli'");
    $jumlahBaris = mysqli_num_rows($result);
    if ($jumlahBaris > 0) {
      $id_pembeli += 1;
    }
    $result->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database dengan prepared statement
    $stmt = $conn->prepare("INSERT INTO pembeli (id_pembeli, nama, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id_user, $nama, $email, $hashed_password);

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
<html lang="en">

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
          <form method="post" action="">
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