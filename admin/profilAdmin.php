<?php
require_once '../koneksi.php';
session_start();
$id_admin = $_SESSION['id_admin'];
function getUser($conn, $id_admin)
{
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id_admin = ?");
    $stmt->bind_param("s", $id_admin);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$admin = getUser($conn, $id_admin);

// Handle Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


// Handle Upload Foto Profil
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
                $tanggalHariIni = date("Ymd");
                $uniq = uniqid(); // misalnya: 666034db408ec
                $newFileName = 'profile_' . $tanggalHariIni . '_' . $uniq . "." . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $fileDestination)) {
                    // Ambil foto lama dari database
                    $query = $conn->query("SELECT foto_profil FROM admin WHERE id_admin = '$id_admin'");
                    $data = $query->fetch_assoc();

                    if ($data['foto_profil'] && file_exists($data['foto_profil'])) {
                        unlink($data['foto_profil']); // Hapus file lama
                    }
                    // Update database
                    $stmt = $conn->prepare("UPDATE admin SET foto_profil = ? WHERE id_admin = ?");
                    $stmt->bind_param("ss", $fileDestination, $id_admin);

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
        $_SESSION['error'] = "Format file tidak didukung (hanya JPG, JPEG, PNG)";
    }

    // Redirect untuk menghindari resubmit form
    header("Location: profilAdmin.php");
    exit();
}


if (isset($_POST['simpan_info'])) {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $no_telp = $_POST['no_telp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    $query = "UPDATE admin SET nama = ?, email = ? WHERE id_admin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama, $email, $id_admin);
    $stmt->execute();
    if ($stmt->execute()) {
        $_SESSION['success'] = "Data berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Error: " . addslashes($stmt->error);
    }
    $stmt->close();
    header("Location: profilAdmin.php");
    exit();
}

if (isset($_POST['simpan_password'])) {
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    if ($old_pass == $user['password']) {
        if ($new_pass == $confirm_pass) {
            // Simpan ke database dengan prepared statement
            $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id_admin = ?");
            $stmt->bind_param("ss", $new_pass, $id_admin);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Kata sandi berhasil diubah!";
            } else {
                $_SESSION['error'] = "Error: " . addslashes($stmt->error);
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Konfirmasi kata sandi tidak sama. Mohon cek kembali!";
        }
    } else {
        $_SESSION['error'] = "Kata sandi lama Anda tidak sesuai. Mohon cek kembali!";
    }
    header("Location: profilAdmin.php");
    exit();
}

$_SESSION['nama_admin'] = $admin['nama'];
$_SESSION['email_admin'] = $admin['email'];
$_SESSION['foto_profil'] = $admin['foto_profil'];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya</title>
    <link rel="icon" href="assets/tortilla.png" type="image/x-icon">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../my_css/style.css">
</head>

<body>
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
                    title: "Berhasil",
                    text: <?= json_encode($_SESSION['success']) ?>,
                    icon: "success"
                });
            });
        </script>
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
                    <div><strong><?= $_SESSION['nama_admin'] ?></strong></div>
                    <small><?= $_SESSION['email_admin'] ?></small>
                </div>
                <nav class="nav flex-column mb-4">
                    <a class="nav-link active" href="profilAdmin.php">
                        <i class="bi bi-person"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a class="nav-link" href="dashboardAdmin.php">
                        <i class="bi bi-cart"></i>
                        <span>Daftar Pesanan</span>
                    </a>
                    <a class="nav-link" href="pembayaran.php">
                        <i class="bi bi-wallet2"></i>
                        <span>Pembayaran</span>
                    </a>
                    </a>
                </nav>
                <div class="mt-auto">
                    <form method="POST">
                        <button type="submit" name="logout" class="btn btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 px-4 py-3 justify-content-center">
                <!-- Mobile Menu Button -->
                <button class="btn btn-outline-secondary d-lg-none mb-3" onclick="toggleSidebar()">☰ Menu</button>

                <h4><strong>Halo, <?= $_SESSION['nama_admin'] ?>!</strong></h4>
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
                            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($admin['nama']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value=<?= htmlspecialchars($admin['email']) ?>>
                        </div>

                        <button type="submit" name="simpan_info" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>

                <div class="profile-section">
                    <h4><i class="bi bi-shield-lock me-2"></i> Ubah Kata Sandi</h4>
                    <p class="text-muted">Gunakan kata sandi yang aman</p>

                    <form method="post">
                        <div class="mb-3">
                            <label for="old_pass" class="form-label">Kata Sandi Lama</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Masukkan kata sandi lama Anda">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="old_pass">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_pass" class="form-label">Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Masukkan kata sandi baru Anda">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_pass">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_pass" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="Masukkan kata sandi baru Anda">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_pass">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" name="simpan_password" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../my_js/main.js"></script>
    <script>
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
</body>

</html>