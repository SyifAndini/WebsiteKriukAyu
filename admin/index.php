<?php
require_once '../koneksi.php';

if (isset($_POST['masuk'])) {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($email) || empty($password)) {
    echo "<script>alert('Semua field harus diisi!')</script>";
  } else {
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $admin = $result->fetch_assoc();
      if ($password == $admin['password']) {
        session_start();
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['nama_admin'] = $admin['nama'];
        $_SESSION['email_admin'] = $admin['email'];
        $_SESSION['foto_profil'] = $admin['foto_profil'];
        $_SESSION['logged_in'] = true;
        $_SESSION['success'] = "Anda berhasil masuk ke akun admin.";
      } else {
        $_SESSION['error'] = "Password Anda tidak sesuai. Periksa kembali input Anda.";
      }
    } else {
      $_SESSION['error'] = "Tidak ada akun admin dengan email tersebut. Periksa kembali input Anda";
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk Akun Admin</title>
  <link rel="icon" href="../assets/tortilla.png" type="image/x-icon">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../my_css/style.css">
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
        }).then(() => window.location.href = 'dashboardAdmin.php');
      });
    </script>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  <div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-container w-100 mx-3 mx-md-auto">
      <div class="row align-items-center">
        <!-- Gambar -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img src="../assets/sign-up.png" alt="Login Illustration" class="login-image">
        </div>
        <!-- Form -->
        <div class="col-md-6">
          <h3 class="mb-4 text-center">Selamat Datang Admin Kriuk Ayu!</h3>
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
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../my_js/main.js"></script>
</body>

</html>