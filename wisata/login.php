<?php
session_start();
include 'config.php';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
  if ($_SESSION['level'] == 'admin') {
    header("Location: index.php");
  } else {
    header("Location: dashboard_user.php");
  }
  exit;
}

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password_input = $_POST['password'];
  $role = $_POST['role'];

  if ($role === 'admin') {
    $password = md5($password_input);
    $query = $conn->query("SELECT * FROM users WHERE username='$username' AND pass='$password' AND level='admin'");
    if ($query->num_rows > 0) {
      $user = $query->fetch_assoc();
      $_SESSION['login'] = true;
      $_SESSION['username'] = $user['username'];
      $_SESSION['level'] = $user['level'];
      $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Username atau password salah!";
    }

  } else if ($role === 'user') {
    $query = $conn->query("SELECT * FROM user WHERE username='$username' AND pass='$password_input'");
    if ($query->num_rows > 0) {
      $user = $query->fetch_assoc();
      $_SESSION['login'] = true;
      $_SESSION['username'] = $user['username'];
      $_SESSION['level'] = 'user';
      $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
      header("Location: dashboard_user.php");
      exit;
    } else {
      $error = "Username atau password salah!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Sistem Pakar Wisata</title>
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
    }

    .login-box {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-box h3 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #008080;
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

    .btn-login {
      background-color: #008080;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      margin-top: 10px;
      transition: 0.3s ease;
    }

    .btn-login:hover {
      background-color: #006666;
    }

    .logo {
      width: 70px;
      margin-bottom: 15px;
    }

    .alert {
      font-size: 0.9rem;
      padding: 8px 12px;
    }

    select.form-control {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background: url('data:image/svg+xml;utf8,<svg fill="%23008080" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 1rem center;
      background-color: #f8f8f8;
      background-size: 16px;
      padding-right: 2.5rem;
      border: 1px solid #ccc;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 500;
    }

    #infoRole {
      font-size: 0.85rem;
      color: #555;
      text-align: left;
      margin-top: 5px;
      min-height: 20px;
    }

    @media (max-width: 480px) {
      .login-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-box" data-aos="zoom-in">
    <img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" class="logo" alt="Icon Wisata">
    <h3>Login Sistem</h3>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3 text-start">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
      </div>
      <div class="mb-3 text-start">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
      </div>
      <div class="mb-2 text-start">
        <label for="role">Login sebagai</label>
        <select name="role" id="role" class="form-control" onchange="tampilkanInfoRole()" required>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        <div id="infoRole">Masuk sebagai admin (akses penuh ke sistem)</div>
      </div>
      <button type="submit" name="login" class="btn btn-login w-100">Masuk</button>
    </form>

    <div class="mt-3">
      <small>Belum punya akun? <a href="register.php">Daftar di sini</a></small>
    </div>
  </div>

  <script>
    function tampilkanInfoRole() {
      const role = document.getElementById("role").value;
      const info = document.getElementById("infoRole");
      if (role === "admin") {
        info.textContent = "Masuk sebagai admin (akses penuh ke sistem)";
      } else {
        info.textContent = "Masuk sebagai user (untuk melihat rekomendasi wisata)";
      }
    }
  </script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>
</body>
</html>
