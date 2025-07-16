<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // plaintext
    $nama     = trim($_POST['nama_lengkap']);
    $email    = trim($_POST['email']);
    $no_hp    = trim($_POST['no_hp']);

    // Cek apakah ada yang kosong
    if ($username == '' || $password == '' || $nama == '' || $email == '' || $no_hp == '') {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah username sudah ada
        $cek = $conn->query("SELECT * FROM user WHERE username='$username'");
        if ($cek->num_rows > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO user (username, pass, nama_lengkap, email, no_hp) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $password, $nama, $email, $no_hp);

            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - Sistem Pakar Wisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background-color: #008080;
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      color: #333;
    }

    .register-box {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 450px;
    }

    .register-box h3 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #008080;
      text-align: center;
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

    .btn-register {
      background-color: #008080;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      margin-top: 10px;
      transition: 0.3s ease;
    }

    .btn-register:hover {
      background-color: #006666;
    }

    .alert {
      font-size: 0.9rem;
      padding: 8px 12px;
    }
  </style>
</head>
<body>

<div class="register-box" data-aos="zoom-in">
  <h3>Register</h3>

  <?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nama Lengkap</label>
      <input type="text" name="nama_lengkap" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="no_hp" class="form-control" required>
    </div>
    <button type="submit" name="register" class="btn btn-register w-100">Daftar</button>
    <div class="mt-3 text-center">
      <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
    </div>
  </form>
</div>

<?php if (isset($success)) : ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= $success ?>',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        window.location.href = "login.php";
    });
</script>
<?php endif; ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

</body>
</html>
