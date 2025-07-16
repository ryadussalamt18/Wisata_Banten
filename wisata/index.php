<?php
date_default_timezone_set("Asia/Jakarta");
require "config.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekomendasi Tempat Wisata di Banten</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/datatables.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap-chosen.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

  <style>
    body {
      padding-top: 70px;
      background-color: #fdfdfd;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      background-color: #008080;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.1rem;
    }

    .nav-link {
      color: #fff !important;
      font-weight: 500;
      padding: 8px 14px;
      border-radius: 6px;
      margin-right: 5px;
      transition: 0.2s ease-in-out;
    }

    .nav-link i {
      margin-right: 6px;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .container {
      margin-top: 30px;
      margin-bottom: 60px;
    }

    .footer {
      background-color: #008080;
      color: white;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 14px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-sm fixed-top navbar-dark">
    <div class="container-fluid px-4">
      <a class="navbar-brand text-white" href="#"><i class="fas fa-map-marked-alt me-1"></i> Tempat Wisata |</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
          <li class="nav-item"><a class="nav-link" href="?index.php"><i class="fas fa-home"></i> Home</a></li>
          <li class="nav-item"><a class="nav-link" href="?page=kriteria"><i class="fas fa-sliders-h"></i> Kriteria</a></li>
          <li class="nav-item"><a class="nav-link" href="?page=penilaian"><i class="fas fa-edit"></i> Penilaian</a></li>
          <li class="nav-item"><a class="nav-link" href="?page=rekomendasi"><i class="fas fa-lightbulb"></i> Lihat Rekomendasi</a></li>
          <li class="nav-item"><a class="nav-link" href="?page=user"><i class="fas fa-users"></i> User</a></li>
          <li class="nav-item"><a class="nav-link" href="?page=wisata_media"><i class="fas fa-mountain-sun"></i> Wisata</a></li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Konten Dinamis -->
  <div class="container" data-aos="fade-up" data-aos-duration="800">
    <?php
    $page = $_GET['page'] ?? "";

    if ($page == "") {
      include "welcome.php";
    } elseif ($page == "kriteria") {
      include "kriteria.php";
    } elseif ($page == "kriteria_tambah") {
      include "kriteria_tambah.php";
    } elseif ($page == 'kriteria_edit') {
      include "kriteria_edit.php";
    } elseif ($page == "penilaian") {
      include "penilaian.php";
    } elseif ($page == "penilaian_tambah") {
      include "penilaian_tambah.php";
    } elseif ($page == "penilaian_edit") {
      include "penilaian_edit.php";
    } elseif ($page == "rekomendasi") {
      include "rekomendasi.php";
    } elseif ($page == "user") {
      include "user.php";
    } elseif ($page == "user_tambah") {
      include "user_tambah.php";
    } elseif ($page == "user_edit") {
      include "user_edit.php";
    } elseif ($page == "wisata_media") {
      include "wisata_media.php";
    } elseif ($page == "logout") {
      include "logout.php";
      session_destroy();
      echo "<script>window.location='login.php';</script>";
    } else {
      echo "<div class='alert alert-warning'>Halaman tidak ditemukan!</div>";
    }
    ?>
  </div>

  <!-- Footer -->
  <div class="footer">
    &copy; <?= date("Y") ?> Sistem Pakar Wisata - Banten | Metode SAW
  </div>

  <!-- JS -->
  <script src="assets/js/jquery-3.7.0.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/datatables.min.js"></script>
  <script src="assets/js/chosen.jquery.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
      $('.chosen').chosen();
    });
    AOS.init();
  </script>
</body>
</html>
