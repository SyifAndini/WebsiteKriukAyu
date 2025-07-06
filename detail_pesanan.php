<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['no_pesanan'])) {
    echo "Nomor pesanan tidak ditemukan.";
    exit();
}

$no_pesanan = $_GET['no_pesanan'];

// Ambil info pesanan + pembeli
$query = "SELECT p.no_pesanan, p.metode_pembayaran, p.bukti_bayar, 
                 p.biaya_pengiriman, p.total, pb.nama, pb.no_telp, pb.alamat
          FROM pesanan p
          JOIN pembeli pb ON p.id_pembeli = pb.id_pembeli
          WHERE p.no_pesanan = '$no_pesanan'";
$detail = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($detail);

if (!$data) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

// Ambil item pesanan
$itemQuery = "SELECT i.jumlah, pr.jenis_kriuk, pr.harga
              FROM item_pesanan i
              JOIN produk pr ON i.id_kriuk = pr.id_kriuk
              WHERE i.no_pesanan = '$no_pesanan'";
$items = mysqli_query($conn, $itemQuery);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesanan</title>
    <link rel="icon" href="assets/tortilla.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail Pesanan #<?= htmlspecialchars($data['no_pesanan']) ?></h5>
            </div>
            <div class="card-body">
                <!-- Informasi Pembeli -->
                <div class="mb-3">
                    <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
                    <p><strong>No. Telp:</strong> <?= htmlspecialchars($data['no_telp']) ?></p>
                    <p><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat']) ?></p>
                </div>

                <!-- Daftar Produk -->
                <h6 class="mt-4">Daftar Produk:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal = 0;
                            while ($item = mysqli_fetch_assoc($items)) {
                                $totalItem = $item['harga'] * $item['jumlah'];
                                $subtotal += $totalItem;
                                echo "<tr>
                          <td>" . htmlspecialchars($item['jenis_kriuk']) . "</td>
                          <td>Rp " . number_format($item['harga'], 0, ',', '.') . "</td>
                          <td>{$item['jumlah']}</td>
                          <td>Rp " . number_format($totalItem, 0, ',', '.') . "</td>
                        </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Ringkasan Biaya -->
                <div class="mt-3">
                    <p><strong>Subtotal:</strong> Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
                    <p><strong>Ongkir:</strong> Rp <?= number_format($data['biaya_pengiriman'], 0, ',', '.') ?></p>
                    <p><strong>Total Bayar:</strong> <span class="text-success fw-bold">Rp <?= number_format($data['total'], 0, ',', '.') ?></span></p>
                    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($data['metode_pembayaran']) ?></p>
                    <p><strong>Bukti Bayar:</strong><br>
                        <?php if (!empty($data['bukti_bayar'])): ?>
                            <img src="<?= htmlspecialchars($data['bukti_bayar']) ?>" alt="Bukti Bayar" class="img-thumbnail mt-2" width="250">
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>
                    </p>
                </div>

                <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>

</body>

</html>