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
