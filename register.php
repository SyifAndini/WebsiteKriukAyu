<?php
include_once 'koneksi.php';

// Function untuk generate ID user (P0001, P0002, dst)
function generateUserId($conn) {
    $sql = "SELECT COUNT(*) AS total FROM user";
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
        echo "<script>alert('Semua field harus diisi!')</script>";
    } else {
        // Generate ID user dan role
        $id_user = generateUserId($conn);
        $role = 'Pembeli'; // Default role
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan ke database dengan prepared statement
        $stmt = $conn->prepare("INSERT INTO user (id_user, nama, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id_user, $nama, $email, $hashed_password, $role);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Registrasi berhasil! ID Anda: $id_user');
                window.location.href = 'login.php'; // Redirect setelah registrasi
            </script>";
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "')</script>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
</head>
<body class="login-bg">
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
          <form method="post">
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
                    <input type="password" class="form-control" id="password" name="password" placeholder="password_anda@123">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="my_js/main.js"></script>
</html>
