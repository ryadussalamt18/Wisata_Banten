<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Selamat Datang - Sistem Pakar Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #00b4b4;
            font-family: 'Segoe UI', sans-serif;
            padding-top: 90px;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-weight: 600;
            font-size: 2.5rem;
            color: #008080;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #444;
        }

        .btn-primary {
            background-color: #008080;
            color: #fff;
            font-weight: bold;
            border: none;
        }

        .btn-primary:hover {
            background-color: #006666;
        }

        .btn-light {
            background-color: #eee;
            color: #008080;
            border: 1px solid #ccc;
            font-weight: 500;
        }

        .btn-light:hover {
            background-color: #ddd;
        }

        img {
            width: 90px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container shadow">
        <h1>Selamat Datang!</h1>
        <p>Sistem Pakar Rekomendasi Tempat Wisata di Banten<br>
            Berdasarkan Metode SAW</p>
        <img src="https://cdn-icons-png.flaticon.com/512/235/235861.png" alt="Icon Wisata">

        <div class="d-grid gap-2 col-6 mx-auto">
            <a href="?page=rekomendasi" class="btn btn-primary btn-lg">Lihat Rekomendasi</a>
        </div>
    </div>

</body>

</html>