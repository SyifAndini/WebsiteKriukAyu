document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      passwordInput.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  });

// Untuk upload foto profil
document.querySelector('.profile-pic-upload').addEventListener('click', function() {
  document.getElementById('profilePic').click();
  });
        
document.getElementById('profilePic').addEventListener('change', function(e) {
  const fileName = e.target.files[0]?.name || 'Tidak ada file yang dipilih';
  this.previousElementSibling.textContent = fileName;
  });