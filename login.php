<?php
require_once 'koneksi.php';

if (isset($_POST['masuk'])) {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Harap mengisi semua field!";
  } else {
    $stmt = $conn->prepare("SELECT * FROM pembeli WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['id_user'] = $user['id_pembeli'];
        $_SESSION['nama_user'] = $user['nama'];
        $_SESSION['email_user'] = $user['email'];
        $_SESSION['foto_profil'] = $user['foto_profil'];
        $_SESSION['logged_in'] = true;
        $_SESSION['success'] = "Anda berhasil masuk! Selamat memesan kriuk.";
      } else {
        $_SESSION['error'] = "Password Anda tidak sesuai. Periksa kembali input Anda.";
      }
    } else {
      $_SESSION['error'] = "Akun dengan email tersebut tidak terdaftar. Periksa kembali input Anda";
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
  <title>Masuk Ke Akun</title>
  <link rel="icon" href="assets/tortilla.png" type="image/x-icon">
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
          title: "Login Berhasil!",
          text: <?= json_encode($_SESSION['success']) ?>,
          icon: "success",
          confirmButtonText: 'OK',
          allowOutsideClick: false
        }).then(() => window.location.href = 'dashboard.php');
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
          <h3 class="mb-4 text-center">Selamat Datang di Kriuk Ayu!</h3>
          <form method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="namasaya@mail.com">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="password_anda@123">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <button type="submit" name="masuk" class="btn login-btn w-100 mb-2">Login</button>
            <div class="text-center small-text">
              Belum punya akun? <a href="register.php">Daftar disini</a>
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