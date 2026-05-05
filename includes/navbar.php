<?php
// session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">📘 Absensi QR</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">🏠 Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="scan.php">📷 Scan QR</a></li>
        <li class="nav-item"><a class="nav-link" href="riwayat.php">📅 Riwayat</a></li>
        <li class="nav-item"><a class="nav-link" href="jadwal.php">🕓 Jadwal</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">👤 Profil</a></li>

        <?php if (isset($_SESSION['login_siswa'])): ?>
          <!-- Jika sudah login sebagai siswa -->
          <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Logout</a></li>
        <?php else: ?>
          <!-- Jika belum login -->
          <li class="nav-item"><a class="nav-link" href="login_siswa.php">🗝️ Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
