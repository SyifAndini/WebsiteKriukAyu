document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.querySelectorAll(".toggle-password");

  togglePassword.forEach((btn) => {
    btn.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      const passwordInput = document.getElementById(targetId);
      const icon = this.querySelector("i");

      if (passwordInput) {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        icon.classList.toggle("bi-eye");
        icon.classList.toggle("bi-eye-slash");
      }
    });
  });
});
function tampilkanMetode() {
  // Ambil nilai yang dipilih
  const metode = document.getElementById("metode_pembayaran").value;

  //Mengatur tampilan metode
  document.getElementById("cardCOD").classList.add("d-none");
  document.getElementById("cardTransfer").classList.add("d-none");
  document.getElementById("cardEWallet").classList.add("d-none");

  // Tampilkan card sesuai metode
  if (metode === "Cash On Delivery (COD)") {
    document.getElementById("cardCOD").classList.remove("d-none");
    document.getElementById("uploadBukti").classList.add("d-none");
  } else if (metode === "Transfer Bank") {
    document.getElementById("cardTransfer").classList.remove("d-none");
    document.getElementById("uploadBukti").classList.remove("d-none");
  } else if (metode === "E-Wallet") {
    document.getElementById("cardEWallet").classList.remove("d-none");
    document.getElementById("uploadBukti").classList.remove("d-none");
  }
  document.querySelector("input[name='bukti_bayar']").required =
    metode !== "Cash On Delivery (COD)";
}

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  sidebar.classList.toggle("show");
  overlay.classList.toggle("show");
}

function konfirmasiTolak(noPesanan) {
  Swal.fire({
    title: "Tolak Pembayaran?",
    text: `Apakah Anda yakin ingin menolak pembayaran pesanan ${noPesanan}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Tolak",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `pembayaran.php?action=tolak&no_pesanan=${noPesanan}`;
    }
  });
}

function konfirmasiTerima(noPesanan) {
  Swal.fire({
    title: "Konfirmasi Pembayaran?",
    text: `Apakah Anda yakin ingin menerima pembayaran pesanan ${noPesanan}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#198754",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Terima",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `pembayaran.php?action=terima&no_pesanan=${noPesanan}`;
    }
  });
}

function konfirmasiSelesai(noPesanan) {
  Swal.fire({
    title: "Selesaikan Pesanan?",
    text: `Apakah Anda yakin untuk menyelesaikan pesanan ${noPesanan}?\nPastikan pembeli telah menerima pesanannya`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#198754",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Selesaikan",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `dashboardAdmin.php?action=selesai&no_pesanan=${noPesanan}`;
    }
  });
}

function konfirmasiPengiriman(noPesanan) {
  Swal.fire({
    title: "Konfirmasi Pembayaran?",
    text: `Apakah Anda yakin untuk mengantar pesanan ${noPesanan}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#ffc107",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Antar Pesanan",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `dashboardAdmin.php?action=antar&no_pesanan=${noPesanan}`;
    }
  });
}
function konfirmasiBatal(noPesanan) {
  Swal.fire({
    title: "Tolak Pembayaran?",
    text: `Apakah Anda yakin untuk membatalkan pesanan ${noPesanan}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Batalkan Pesanan",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `dashboardAdmin.php?action=batal&no_pesanan=${noPesanan}`;
    }
  });
}

function pesananSelesai(noPesanan) {
  Swal.fire({
    title: "Selesaikan Pesanan?",
    text: `Apakah Anda sudah menerima pesanan ${noPesanan}? Cek kembali pesanan Anda.`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#198754",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Selesaikan",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `dashboard.php?action=selesai&no_pesanan=${noPesanan}`;
    }
  });
}
