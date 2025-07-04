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
        header("Location: dashboardAdmin.php");
        exit();
      } else {
        echo "<script>alert('Password salah!')</script>";
      }
    } else {
      echo "<script>alert('Akun tidak ditemukan! Periksa kembali input Anda.')</script>";
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
  <title>Login Page</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../my_css/style.css">
</head>

<body class="login-bg">
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
                <button class="btn btn-outline-secondary toggle-password" type="button">
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
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../my_js/main.js"></script>
</body>

</html>