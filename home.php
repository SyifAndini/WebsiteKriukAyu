<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;;
if (isset($_POST['logout'])) {
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
    <title>Website Kriuk Ayu</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="my_css/style.css">
</head>
<body>
    <header>
        <!-- Navbar Kriuk Ayu -->
        <nav class="navbar navbar-expand-lg navbar-kriuk fixed-top" id="navbar-kriukAyu">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">
                <img src="assets/LogoKriukAyu.png" alt="Logo Kriuk Ayu" style="height: 40px; width: auto;" class="d-inline-block align-text-top"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav nav-underline mx-auto">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#home">Beranda</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#product">Produk</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#carapesan">Cara Pesan</a>
                  </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#review">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang Kami</a>
                      </li>
                </ul>
                <?php if(!$isLoggedIn): ?>
                <div class="d-flex gap-2 ms-auto">
                    <button class="btn btn-kuning" onclick="location.href='register.php'">Pesan Sekarang!</button>
                    <button class="btn btn-merah" onclick="location.href='login.php'">Masuk</button>
                  </div>
                <?php else: ?>
                  <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1" id="userProfile">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="hugenerd" width="28" height="28" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1"><?= $_SESSION['nama_user'] ?? 'Pengguna'?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item bi bi-person" href="#">Lihat Profil</a></li>
                        <li><a class="dropdown-item "href="dashboard.php">Dashboard</a></li>
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
                <?php endif; ?>
                </div>
              </div>
            </div>
          </nav>
    </header>
    <div data-bs-spy="scroll" data-bs-target="#navbar-kriukAyu" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
        <section id="home">   
            <!-- Carousel Foto Kriuk Ayu -->
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="assets/kriuk-basreng.jpg" class="d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="assets/kriuk-seblak-bungkus.jpg" class="d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="assets/otak-otak-jagung.jpg" class="d-block w-100" alt="...">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            <h2 class="text-center mt-5 mb-5 fw-bold">Selamat Datang di Kriuk Ayu</h2>
            <p class="text-center mb-5">Rumahnya aneka kriuk dengan berbagai rasa!</p>
        </section>
        <section id="product">
            <h2 class="text-center mb-5 fw-bold">Aneka Kriuk</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4 m-3">
                <div class="col">
                  <div class="card shadow p-3 mb-5 bg-body-tertiary rounded">
                    <img src="assets/kriuk-basreng.jpg" class="card-img-top" alt="Kriuk Otak-Otak">
                    <div class="card-body">
                      <h5 class="card-title">Otak-Otak</h5>
                      <h6 class="card-subtitle mb-2 text-body-secondary">Rp 7.000</h6>
                      <p class="card-text">Kriuk renyah yang dibuat dari otak-otak dengan metode deep fry.</p>
                      <!-- <div class="row row-cols">
                        <img src="assets/corn.png" alt="Corn" class="col-2">
                        <span>Jagung Bakar</span>
                      </div> -->
                    </div>
                  </div>
                </div>
                <div class="col">
                    <div class="card shadow p-3 mb-5 bg-body-tertiary rounded">
                      <img src="assets/kriuk-basreng.jpg" class="card-img-top" alt="...">
                      <div class="card-body">
                        <h5 class="card-title">Makaroni</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Rp 7.000</h6>
                        <p class="card-text">Makaroni yang digoreg dengan minyak panas menggunakan metode deep fry.</p>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card shadow p-3 mb-5 bg-body-tertiary rounded">
                      <img src="assets/kriuk-basreng.jpg" class="card-img-top" alt="...">
                      <div class="card-body">
                        <h5 class="card-title">Kerupuk Seblak</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Rp 7.000</h6>
                        <p class="card-text">Kriuk renyah yang dibuat dari otak-otak dengan metode deep fry</p>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card shadow p-3 mb-5 bg-body-tertiary rounded">
                      <img src="assets/kriuk-basreng.jpg" class="card-img-top" alt="...">
                      <div class="card-body">
                        <h5 class="card-title">Emping Jagung</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Rp 7.000</h6>
                        <p class="card-text">Kriuk renyah yang dibuat dari otak-otak dengan metode deep fry</p>
                      </div>
                    </div>
                  </div>
              </div>
        </section>
        <section id="carapesan">     
                <h2 class="text-center mb-5 fw-bold">Cara Pesan</h2>
                <p>Untuk melakukan pemesanan, silakan ikuti langkah-langkah berikut:</p>
                <ol>
                    <li>Pilih produk yang ingin dipesan.</li>
                    <li>Isi form pemesanan dengan lengkap.</li>
                    <li>Kirimkan form pemesanan.</li>
                    <li>Tunggu konfirmasi dari kami.</li>
                </ol>
        </section>
        <section class="py-5 bg-warning bg-opacity-25" id="review">
            <div class="container">
              <h2 class="text-center mb-5 fw-bold">Apa kata mereka?</h2>
              <div class="row g-4">
                
                <!-- Satu Testimoni -->
                <div class="col-md-4">
                  <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                    <div class="mb-2 text-warning fs-4">
                      ★★★★★
                    </div>
                    <p>Kriuknya enak banget, ada rasa rempah-rempahnya tapi gak menyengat. Cocok dicemilin pas nugas.</p>
                    <div class="mt-3">
                      <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                      <h6 class="fw-bold mb-0">Jennifer Kim</h6>
                    </div>
                  </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                      <div class="mb-2 text-warning fs-4">
                        ★★★★★
                      </div>
                      <p>Menurutku kriuk ini asinnya pas. Apalagi aku pribadi suka asin, jadinya suka banget! Oh iya, aku beli kriuk otak-otak rasa pedas manis ya. Kalian harus coba sih.</p>
                      <div class="mt-3">
                        <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                        <h6 class="fw-bold mb-0">Nadhil</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                      <div class="mb-2 text-warning fs-4">
                        ★★★★★
                      </div>
                      <p>IH!! KOK BISA SIH ADA KRIUK SEENAK INI WOY! Aku si anak kos ini kadang nyetok 3 bungkus kriuk buat camilan saat nugas. WORTH IT BGT! Mana kadang ada promo!</p>
                      <div class="mt-3">
                        <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                        <h6 class="fw-bold mb-0">Frincess Naibaho</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                      <div class="mb-2 text-warning fs-4">
                        ★★★★
                      </div>
                      <p>Aku suka banget kriuk otak-otak rasa pedas manis karena gurih udah gitu gak terlalu pedas, cocok sama lidahku. Mungkin next aku coba kerupuk seblak ya?</p>
                      <div class="mt-3">
                        <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                        <h6 class="fw-bold mb-0">Aulia</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                      <div class="mb-2 text-warning fs-4">
                        ★★★★
                      </div>
                      <p>Aku beli yang emping jagung pake bumbu jagung bakar, jadi double2 tuh rasanya. ENAK BGTT! tapi saran aja nih bumbunya dibanyakin ya jangan pelit-pelit.</p>
                      <div class="mt-3">
                        <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                        <h6 class="fw-bold mb-0">Kenneth</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card h-100 shadow-sm border rounded-4 p-3 text-center">
                      <div class="mb-2 text-warning fs-4">
                        ★★★
                      </div>
                      <p>Enak sih kriuknya. Saya beli yang otak-otak pedas manis karena ga terlalu suka pedas. Tapi kemahalan ah 7ribu, kasih diskon dong!</p>
                      <div class="mt-3">
                        <img src="https://picsum.photos/200/300" width="100" height="100" alt="avatar" class="rounded-circle mb-1" />
                        <h6 class="fw-bold mb-0">Mahesa</h6>
                      </div>
                    </div>
                  </div>
          
              </div>
            </div>
          </section>
    
        <!-- Tentang Kami -->
    <section id="tentang" class="py-5">
        <div class="container">
          <h2 class="text-center fw-bold mb-5">Tentang Kriuk Ayu</h2>
          <div class="row align-items-center">
            <div class="col-md-6 text-center">
              <img src="assets/otak-otak-jagung.jpg" alt="Foto Proses Pembuatan" class="img-fluid rounded shadow-sm mb-4" style="max-height: 300px;">
            </div>
            <div class="col-md-6">
              <p>
                Kriuk Ayu berdiri pada tahun 2024 karena adanya peluang pasar yang cukup besar dalam industri makanan ringan,
                khususnya camilan keripik yang digemari oleh berbagai kalangan. Berawal dari keinginan untuk menghadirkan produk
                camilan yang renyah, terjangkau, dan memiliki cita rasa khas Indonesia, Kriuk Ayu dikembangkan dengan mengutamakan
                kualitas bahan baku serta proses produksi yang higienis.
              </p>
              <a href="https://wa.me/6285882514744" target="_blank" class="btn btn-kuning mt-3">
                <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
              </a>
            </div>
          </div>
        </div>
      </section>
      
      <!-- Sosok Dibalik Kriuk Ayu -->
      <section class="bg-success bg-opacity-25 py-5">
        <div class="container">
          <h3 class="text-center fw-bold mb-5">Sosok dibalik Kriuk Ayu</h3>
          <div class="row justify-content-center text-center">
            <div class="col-md-4 mb-4">
              <img src="assets/mba-ayu.jpg" alt="Milvia Rahayu" class="rounded-circle mb-3" width="140" height="140">
              <h5 class="fw-bold">Milvia Rahayu</h5>
              <p>Pemilik Kriuk Ayu</p>
            </div>
            <div class="col-md-4 mb-4">
              <img src="assets/syifa.jpg" alt="Syifa Putri Andini" class="rounded-circle mb-3" width="140" height="140">
              <h5 class="fw-bold">Syifa Putri Andini</h5>
              <p>Pembuat Website Kriuk Ayu</p>
            </div>
          </div>
        </div>
    </div>
  </section>  
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body> 
  <!-- Footer -->
  <footer class="bg-success text-white text-center py-4">
    <p class="mb-1 fw-bold">Kriuk Ayu</p>
    <p class="mb-1">Warujaya, Parung</p>
    <p class="mb-0"><i class="bi bi-c-circle"></i> Syifa Putri Andini - 2025</p>
  </footer>
</html>