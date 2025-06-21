<?php
require_once 'koneksi.php';
session_start();
$id_pembeli = $_SESSION['id_user'];
if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}
/// Handle Upload Foto Profil
if (isset($_POST['simpan_foto']) && isset($_FILES['fotoProfil'])) {
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
        $newFileName = uniqid('profile_', true) . '.' . $fileExt;
        $fileDestination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $fileDestination)) {
          // Ambil foto lama dari database
          $query = $conn->query("SELECT foto_profil FROM pembeli WHERE id_pembeli = '$id_pembeli'");
          $data = $query->fetch_assoc();

          if ($data['foto_profil'] && file_exists($data['foto_profil'])) {
            unlink($data['foto_profil']); // Hapus file lama
          }
          // Update database
          $stmt = $conn->prepare("UPDATE pembeli SET foto_profil = ? WHERE id_pembeli = ?");
          $stmt->bind_param("ss", $fileDestination, $id_pembeli);

          if ($stmt->execute()) {
            $_SESSION['foto_profil'] = $fileDestination;
            $_SESSION['success'] = "Foto profil berhasil diupdate!";
          } else {
            $_SESSION['error'] = "Gagal menyimpan ke database: " . $conn->error;
          }
          $stmt->close();
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
    $_SESSION['error'] = "Format file tidak didukung (hanya JPG, JPEG, PNG, GIF)";
  }

  // Redirect untuk menghindari resubmit form
  header("Location: profile.php");
  exit();
}


if (isset($_POST['simpan_info'])) {
  $nama = $_POST['nama'] ?? '';
  $email = $_POST['email'] ?? '';
  $no_telp = $_POST['no_telp'] ?? '';
  $alamat = $_POST['alamat'] ?? '';

  $query = "UPDATE pembeli SET nama = ?, email = ?, no_telp = ?, alamat = ?
              WHERE id_pembeli = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssssi", $nama, $email, $no_telp, $alamat, $id_pembeli);
  $stmt->execute();
  if ($stmt->execute()) {
    echo "<script>alert('Data berhasil diperbarui!')</script>";
    header("Location: profile.php");
  } else {
    echo "<script>alert('Error: " . addslashes($stmt->error) . "')</script>";
  }
}

if (isset($_POST['simpan_password'])) {
  $old_pass = $_POST['old_pass'] ?? '';
  $new_pass = $_POST['new_pass'] ?? '';
  $confirm_pass = $_POST['confirm_pass'] ?? '';

  if (password_verify($old_pass, $user['password'])) {
    if ($new_pass == $confirm_pass) {
      $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
      // Simpan ke database dengan prepared statement
      $stmt = $conn->prepare("UPDATE pembeli SET password = ? WHERE id_pembeli = ?");
      $stmt->bind_param("ss", $hashed_password, $id_pembeli);

      if ($stmt->execute()) {
        echo "<script>alert('Password Anda berhasil diubah!')</script>";
        header("Location: profile.php");
      } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "')</script>";
      }

      $stmt->close();
    }
  } else {
    echo "<script>alert('Password lama Anda tidak sesuai! Mohon cek kembali.')</script>";
  }
}

// Ambil data user dari database
$query = "SELECT * FROM pembeli WHERE id_pembeli = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Hanya isi ulang session kalau belum ada (untuk cegah timpa data dari user login baru)
if (!isset($_SESSION['nama_user'])) {
  $_SESSION['nama_user'] = $user['nama'];
  $_SESSION['email_user'] = $user['email'];
  $_SESSION['foto_profil'] = $user['foto_profil'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="my_css/style.css">
</head>

<body>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger position-fixed" role="alert">
      <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success position-fixed" role="alert">
      <?= $_SESSION['success']; ?>
    </div>
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
          <?php if (!empty($_SESSION['foto_profil']) && file_exists($_SESSION['foto_profil'])): ?>
            <img src="<?= $_SESSION['foto_profil'] ?>" alt="Profil User" width="50" height="50" class="rounded-circle">
          <?php else: ?>
            <img src="assets/default-profile.svg" alt="Profil User" width="50" height="50" class="rounded-circle">
          <?php endif; ?>
          <div><strong><?= $_SESSION['nama_user'] ?></strong></div>
          <small><?= $_SESSION['email_user'] ?></small>
        </div>
        <nav class="nav flex-column mb-4">
          <a class="nav-link" href="index.php">
            <i class="bi bi-house"></i>
            <span>Beranda</span>
          </a>
          <a class="nav-link active" href="profile.php">
            <i class="bi bi-person"></i>
            <span>Profil Saya</span>
          </a>
          <a class="nav-link" href="dashboard.php">
            <i class="bi bi-cart"></i>
            <span>Pesanan Saya</span>
          </a>
          <a class="nav-link" href="order.php">
            <i class="bi bi-plus-circle"></i>
            <span>Buat Pesanan</span>
          </a>
        </nav>
        <div class="mt-auto">
          <form method="POST">
            <button type="submit" name="logout" class="btn btn-outline-danger w-100">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 px-4 py-3 justify-content-center">
        <!-- Mobile Menu Button -->
        <button class="btn btn-outline-secondary d-lg-none mb-3" onclick="toggleSidebar()">☰ Menu</button>

        <h4><strong>Halo, <?= $_SESSION['nama_user'] ?>!</strong></h4>
        <hr>
        <div class="profile-section">
          <h4><i class="bi bi-person me-2"></i> Foto Profil</h4>
          <p class="text-muted mb-4">Ekspresikan diri Anda dengan foto diri yang keren</p>
          <form method="post" action="" enctype="multipart/form-data">
            <div class="border rounded p-4 text-center mb-3" style="cursor: pointer;" onclick="document.getElementById('fotoProfil').click()">
              <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
              <h5 class="mt-2">Unggah Foto</h5>
              <small class="text-muted" id="fileName">Tidak ada file yang dipilih</small>
              <input type="file" id="fotoProfil" name="fotoProfil" class="d-none" accept="image/*">
            </div>

            <button class="btn btn-primary" name="simpan_foto">Simpan Perubahan</button>
          </form>
        </div>

        <!-- Informasi Profil Section -->
        <div class="profile-section">
          <h4><i class="bi bi-info-circle me-2"></i> Informasi Profil</h4>
          <p class="text-muted mb-4">Ubah informasi profil Anda</p>

          <form method="post">
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" name="nama" value=<?= $user['nama'] ?>>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value=<?= $user['email'] ?>>
            </div>
            <div class="mb-3">
              <label class="form-label">No. Telp</label>
              <input type="tel" class="form-control" name="no_telp" value=<?= $user['no_telp'] ?>>
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat Domisili</label>
              <textarea class="form-control" name="alamat" rows="3"><?= $user['alamat'] ?></textarea>
            </div>

            <button type="submit" name="simpan_info" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>

        <div class="profile-section">
          <h4><i class="bi bi-shield-lock me-2"></i> Ubah Kata Sandi</h4>
          <p class="text-muted">Gunakan kata sandi yang aman</p>

          <form method="post">
            <div class="mb-3">
              <label class="form-label">Kata Sandi Lama</label>
              <input type="password" name="old_pass" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Kata Sandi Baru</label>
              <input type="password" name="new_pass" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Konfirmasi Kata Sandi Baru</label>
              <input type="password" name="confirm_pass" class="form-control">
            </div>
            <button type="submit" name="simpan_password" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>

        <!-- Hapus Akun Section -->
        <div class="profile-section">
          <h4><i class="bi bi-trash me-2"></i> Hapus Akun</h4>
          <p class="text-muted mb-4">Setelah akun Anda dihapus, semua data Anda akan dihapus secara permanent.</p>

          <!-- Button trigger modal -->
          <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Hapus Akun
          </button>

          <!-- Modal -->
          <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                  <form method="post" action="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" name="hapus" class="btn btn-danger">Hapus Akun</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<!-- Bootstrap JS & Sidebar Toggle -->
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  setTimeout(function() {
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
      alertBox.classList.add('fade');
      alertBox.classList.remove('show');

      // Hapus elemen dari DOM setelah efek selesai (opsional)
      setTimeout(() => alertBox.remove(), 500);
    }
  }, 2000); // 2000ms = 2 detik

  document.getElementById('fotoProfil').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Tidak ada file yang dipilih';
    document.getElementById('fileName').textContent = fileName;
  });
  // Toggle sidebar on mobile       
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    sidebar.classList.toggle("show");
    overlay.classList.toggle("show");
  }
</script>