<?php
session_start();
if(isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="my_css/style.css">
</head>
<body>
    <!-- Bagi dua -->
<div class="container-fluid overflow-hidden">
    <div class="row vh-100 overflow-auto">
        <div class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 bg-light d-flex sticky-top">
            <aside class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-dark">
                <a href="/" class="d-flex align-items-center pb-sm-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <span class="fs-5">Kriuk Ayu<span class="d-none d-sm-inline"></span></span>
                </a>
                <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="home.php" class="nav-link px-sm-0 px-2">
                            <i class="fs-5 bi-house text-dark"></i><span class="ms-1 d-none d-sm-inline text-dark">Beranda</span>
                        </a>
                    </li>                    
                    <li class="nav-item">
                        <a href="pesanan.php" class="nav-link px-sm-0 px-2">
                            <i class="fs-5 bi-table text-dark"></i><span class="ms-1 d-none d-sm-inline active text-dark">Pesanan Saya</span></a>
                    </li>
                </ul>
                <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="hugenerd" width="28" height="28" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1"><?= $_SESSION['nama_user'] ?? 'Pengguna'?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-light text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item bi bi-person" href="#">Lihat Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                        <!-- Form Logout dalam Dropdown -->
                        <form method="post" class="px-3 py-2">
                            <button type="submit" name="logout" class="btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
        <div class="col d-flex flex-column h-sm-100">
            <main class="row overflow-auto">
                <div class="col pt-4">
                    <h2>Halo, <span><?= $_SESSION['nama_user'] ?? 'Pengguna'?></span>!</h2>
                    <hr />
                    <h3>Pesanan Saya</h3>
                    <button class="btn btn-primary">Buat Pesanan Baru</button>
                    <!-- Jika belum pernah memesan -->
                    <div class="alert alert-info text-center" role="alert">
                        <p>Anda belum pernah memesan kriuk.</p>
                        <p>Silakan klik tombol "Buat Pesanan".</p>
                    </div>
                    <footer class="row bg-light py-4 mt-auto">
                    <div class="col"> Footer content here... </div>
                    </footer> 
                </div>       
            </main> 
            <div>
                     <!-- Modal -->
      <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Tambah Pesanan Kriuk
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Kriuk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form>
                <div class="mb-3">
                <label for="jenisKriuk" class="form-label">Jenis Kriuk</label>
                <select name="jenisKriuk" id="jenisKriuk" class="form-control" required>
                    <option value="" disabled selected>Pilih Jenis Kriuk</option>
                    <option value="otak-otak">Otak-Otak</option>
                    <option value="makaroni">Makaroni</option>
                    <option value="kerupuk-seblak">Kerupuk Seblak</option>
                    <option value="kerupuk-seblak">Emping Jagung</option>
                </select>
                </div>
                <div class="mb-3">
                    <label for="rasaKriuk" class="form-label">Rasa Kriuk</label>
                    <select name="rasaKriuk" id="rasaKriuk" class="form-control" required>
                        <option value="" disabled selected>Pilih Rasa Kriuk</option>
                        <option value="jagung-bakar">Jagung Bakar</option>
                        <option value="pedas-manis">Pedas Manis</option>
                        <option value="ekstra-pedas">Ekstra Pedas</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jumlahKriuk" class="form-label">Jumlah Kriuk</label>
                    <input type="number" class="form-control" id="jumlahKriuk" placeholder="Jumlah Kriuk" required>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary">Tambah Kriuk</button>
        </div>
      </div>
    </div>
  </div>
            </div>        
        </div>
    </div>
</div>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>