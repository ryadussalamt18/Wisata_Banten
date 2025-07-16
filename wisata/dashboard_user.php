<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'user') {
    header("Location: login.php");
    exit;
}
require_once "config.php";

$username = $_SESSION['username'];
$query = $conn->query("SELECT * FROM user WHERE username='$username'");
$user = $query->fetch_assoc();

// Mengambil media wisata untuk referensi
$media_query = $conn->query("SELECT * FROM wisata_media INNER JOIN wisata ON wisata_media.id_wisata = wisata.id_wisata");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard User - Sistem Pakar Wisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-color: #008080;
      font-family: 'Segoe UI', sans-serif;
      padding: 60px 15px;
    }

    .dashboard-box {
      background: #ffffff;
      border-radius: 25px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      padding: 40px;
      max-width: 960px;
      margin: auto;
      text-align: center;
    }

    .profile-icon {
      width: 90px;
      margin-bottom: 15px;
    }

    h2 {
      color: #008080;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .btn-rekomendasi {
      background-color: #008080;
      color: #fff;
      font-weight: bold;
      border-radius: 12px;
      padding: 10px 20px;
    }

    .btn-rekomendasi:hover {
      background-color: #007d7d;
    }

    .btn-media {
      background-color: #008080;
      color: #fff;
      font-weight: bold;
      border-radius: 12px;
      padding: 10px 20px;
      margin-left: 10px;
    }

    .btn-media:hover {
      background-color: #007d7d;
    }

    .profile-card {
      background: #f8f9fa;
      border-radius: 15px;
      padding: 25px 30px;
      margin-top: 30px;
      text-align: left;
    }

    .profile-card h6 {
      font-weight: 600;
      color: #444;
    }

    .profile-card p {
      color: #555;
      margin-bottom: 12px;
    }

    .logout {
      margin-top: 30px;
      font-size: 15px;
    }

    .logout a {
      color: #dc3545;
      font-weight: 500;
      text-decoration: none;
    }

    .logout a:hover {
      text-decoration: underline;
    }

    .edit-profile {
      text-align: right;
      margin-top: 15px;
    }

    .media-card {
      margin-bottom: 20px;
    }

    .media-card img, .media-card video {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
    }

    @media (max-width: 576px) {
      .profile-card {
        text-align: center;
      }

      .edit-profile {
        text-align: center;
      }
    }
  </style>
</head>
<body>

<?php if (isset($_SESSION['success_update'])): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '<?= $_SESSION['success_update'] ?>',
      showConfirmButton: false,
      timer: 2000
    });
  });
</script>
<?php unset($_SESSION['success_update']); ?>
<?php endif; ?>

<div class="dashboard-box" data-aos="zoom-in">
  <img src="https://cdn-icons-png.flaticon.com/512/2202/2202112.png" alt="User Icon" class="profile-icon" data-aos="fade-down">
  <h2 data-aos="fade-up">Halo, <?= htmlspecialchars($user['nama_lengkap']) ?> 👋</h2>
  <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Selamat datang di Sistem Pakar Rekomendasi Tempat Wisata di Banten</p>

  <div data-aos="fade-up" data-aos-delay="200">
    <a href="rekomendasi_user.php" class="btn btn-rekomendasi mt-2">
      <i class="bi bi-person-fill me-1"></i> Lihat Rekomendasi Wisata
    </a>

    <a href="wisata_media_user.php" class="btn btn-media mt-2">
      <i class="bi bi-camera-video me-1"></i> Lihat Media Wisata
    </a>
  </div>

  <div class="profile-card mt-4" data-aos="fade-up" data-aos-delay="300">
    <div class="row">
      <div class="col-md-6 mb-3">
        <h6>Username</h6>
        <p><?= htmlspecialchars($user['username']) ?></p>
      </div>
      <div class="col-md-6 mb-3">
        <h6>Nama Lengkap</h6>
        <p><?= htmlspecialchars($user['nama_lengkap']) ?></p>
      </div>
      <div class="col-md-6 mb-3">
        <h6>Email</h6>
        <p><?= htmlspecialchars($user['email']) ?></p>
      </div>
      <div class="col-md-6 mb-3">
        <h6>No. HP</h6>
        <p><?= htmlspecialchars($user['no_hp']) ?></p>
      </div>
    </div>
    <div class="edit-profile" data-aos="fade-left">
      <a href="edit_profile.php" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil-square"></i> Edit Profil
      </a>
    </div>
  </div>

  <div class="logout" data-aos="fade-up" data-aos-delay="400">
    <p>Ingin keluar? <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

</body>
</html>
