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

if (isset($_POST['update'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    
    $update_query = $conn->prepare("UPDATE user SET nama_lengkap = ?, email = ?, no_hp = ? WHERE username = ?");
    $update_query->bind_param("ssss", $nama_lengkap, $email, $no_hp, $username);
    
    if ($update_query->execute()) {
       $_SESSION['success_update'] = "Profil berhasil diperbarui!";
echo "<script>window.location='dashboard_user.php';</script>";
exit;

    } else {
        $error = "Gagal memperbarui profil. Silakan coba lagi.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil - Sistem Pakar Wisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    body {
      background-color: #00b4b4;
      font-family: 'Segoe UI', sans-serif;
      padding: 60px 15px;
    }

    .edit-profile-box {
      background: #ffffff;
      border-radius: 25px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      padding: 40px;
      max-width: 960px;
      margin: auto;
    }

    h2 {
      color: #008080;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .form-control {
      background-color: #f8f8f8;
      border-radius: 10px;
      border: 1px solid #ccc;
      color: #333;
    }

    .form-control:focus {
      border-color: #008080;
      box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.25);
    }

    .btn-update {
      background-color: #008080;
      color: white;
      font-weight: bold;
      border-radius: 12px;
      margin-top: 10px;
      transition: 0.3s ease;
    }

    .btn-update:hover {
      background-color: #006666;
    }

    .alert {
      font-size: 0.9rem;
      padding: 8px 12px;
    }

    .back-link {
      margin-top: 20px;
    }

    @media (max-width: 576px) {
      .edit-profile-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="edit-profile-box" data-aos="fade-up">
  <h2 data-aos="fade-down">Edit Profil</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger" data-aos="fade-right"><?= $error ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" data-aos="fade-right"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3" data-aos="fade-up" data-aos-delay="100">
      <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
      <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
    </div>
    <div class="mb-3" data-aos="fade-up" data-aos-delay="200">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="mb-3" data-aos="fade-up" data-aos-delay="300">
      <label for="no_hp" class="form-label">No. HP</label>
      <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
    </div>
    <button type="submit" name="update" class="btn btn-update w-100" data-aos="zoom-in" data-aos-delay="400">Update Profil</button>
  </form>

  <div class="back-link" data-aos="fade-left" data-aos-delay="500">
    <a href="dashboard_user.php" class="btn btn-outline-primary btn-sm mt-3">
      <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
