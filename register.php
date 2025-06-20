<?php
session_start();
include_once 'koneksi.php';

// Fungsi untuk generate ID
function generateUserId($conn) {
    $sql = "SELECT COUNT(*) AS total FROM pembeli";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return 'P' . str_pad(($row['total'] + 1), 4, '0', STR_PAD_LEFT);
}

if (isset($_POST['daftar'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $foto_profil = null;

    if (empty($nama) || empty($email) || empty($password) || empty($alamat) || empty($no_telp)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid!');</script>";
    } else {
        // Cek duplikat email
        $cek = $conn->prepare("SELECT email FROM pembeli WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            echo "<script>alert('Email sudah digunakan.');</script>";
        } else {
            $id_user = generateUserId($conn);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Baca isi file gambar
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['foto_profil']['tmp_name'];
                $foto_profil = file_get_contents($tmp);
            }

            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO pembeli (id_pembeli, nama, email, password, foto_profil, alamat, no_telp) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $id_user, $nama, $email, $hashed_password, $foto_profil, $alamat, $no_telp);
            $stmt->send_long_data(4, $foto_profil); // posisi ke-5 (index 4) = foto_profil

            if ($stmt->execute()) {
                $_SESSION['sukses_register'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('Gagal menyimpan data.');</script>";
            }

            $stmt->close();
        }

        $cek->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - Kriuk Ayu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
</head>
<body class="login-bg">
  <div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-container w-100 mx-3 mx-md-auto">
      <div class="row align-items-center">
        <div class="col-md-6 text-center mb-4">
          <img src="assets/sign-up.png" alt="Ilustrasi" class="login-image">
        </div>
        <div class="col-md-6">
          <h3 class="mb-4 text-center">Buat Akun Baru</h3>
          <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Lengkap</label>
              <input type="text" id="nama" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="foto_profil" class="form-label">Foto Profil</label>
              <input type="file" id="foto_profil" name="foto_profil" class="hidden">
            </div>
            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat</label>
              <textarea id="alamat" name="alamat" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
              <label for="no_telp" class="form-label">No. Telepon</label>
              <input type="tel" id="no_telp" name="no_telp" class="form-control" required>
            </div>
            <button type="submit" name="daftar" class="btn btn-primary w-100">Daftar</button>
            <p class="text-center mt-2">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
