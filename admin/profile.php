<?php
require_once '../koneksi.php';
session_start();
$id_admin = $_SESSION['id_admin'];

if(isset($_POST['tambah_kriuk'])) {
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

        echo "<script>
        alert('Kriuk berhasil ditambahkan!');
        document.location = 'order.php';
        </script>";
        exit();
      } 
}

if(isset($_GET['hal'])) {
    //Pengujian jika edit Data
    if($_GET['hal'] == "edit") {
        //Tampilkan Data yang akan diedit
        $tampil = mysqli_query($koneksi, "SELECT * FROM cart WHERE cart = '$_GET[id_cart]' ");
        $data = mysqli_fetch_array($tampil);

        if($data) {
            //Jika data ditemukan, maka jumlah kriuk akan ditampilkan dalam modal

          
        }
        
    } else if ($_GET['hal'] == "hapus") {
        //Persiapan hapus data
        $hapus = mysqli_query($conn, "DELETE FROM cart WHERE id_cart = '$_GET[id_cart]' ");
        if($hapus){
            echo "<script>
                    alert('Kriuk Berhasil Dihapus!');
                    document.location = 'order.php';
                 </script>";
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
  <link rel="stylesheet" href="../bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../my_css/style.css">
</head>
<body>
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
          <img src="assets/syifa.jpg" alt="Profil User"  width="50" height="50" class="rounded-circle">
          <div><strong><?= $_SESSION['nama_user']?></strong></div>
          <small><?= $_SESSION['email_user']?></small>
        </div>
        <nav class="nav flex-column mb-4">
          <!-- <a class="nav-link" href="home.php">
            <i class="bi bi-house"></i>
              <span>Beranda</span>
          </a> -->
          <a class="nav-link active rounded-4 mb-1 shadow-sm text-center" href="profile.php">
            <i class="bi bi-person"></i>
              <span>Profil Saya</span>
          </a>
          <a class="nav-link rounded-4 mt-1 shadow-sm text-center" href="dashboard.php">
            <i class="bi bi-cart"></i>
              <span>Info Pesanan</span>
          </a>
          <!-- <a class="nav-link" href="order.php">
            <i class="bi bi-plus-circle"></i>
              <span>Buat Pesanan</span>
          </a> -->
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
        <button class="btn btn-outline-secondary d-lg-none mb-3" onclick="toggleSidebar()">☰ Menu</button>

        <h4><strong>Halo, <?= $_SESSION['nama_user']?>!</strong></h4>
        <hr>
        <div class="profile-section">
                <h4><i class="bi bi-person me-2"></i> Foto Profil</h4>
                <p class="text-muted mb-4">Ekspresikan diri Anda dengan foto diri yang keren</p>
                
                <div class="border rounded p-4 text-center mb-3" style="cursor: pointer;" onclick="document.getElementById('fileUpload').click()">
                    <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                    <h5 class="mt-2">Unggah Foto</h5>
                    <small class="text-muted" id="fileName">Tidak ada file yang dipilih</small>
                    <input type="file" id="fileUpload" class="d-none" accept="image/*">
                </div>
                
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
            
            <!-- Informasi Profil Section -->
            <div class="profile-section">
                <h4><i class="bi bi-info-circle me-2"></i> Informasi Profil</h4>
                <p class="text-muted mb-4">Ubah informasi profil Anda</p>
                
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="Nama Pengguna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="namapengguna@gmail.com">
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label">No. Telp</label>
                        <input type="tel" class="form-control" value="08123456789">
                    </div> -->
                    <!-- <div class="mb-3">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea class="form-control" rows="3">Jl. Contoh No. 123, Kota</textarea>
                    </div> -->
                    
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-danger">Hapus Akun</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
  <!-- Bootstrap JS & Sidebar Toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const overlay = document.getElementById("overlay");
      sidebar.classList.toggle("show");
      overlay.classList.toggle("show");
    }
  </script>
</body>
</html>
