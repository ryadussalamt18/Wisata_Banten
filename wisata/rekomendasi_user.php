<?php
require_once "config.php";

// Kosongkan tabel rekomendasi
$conn->query("DELETE FROM rekomendasi");

// Ambil data kriteria (bobot & jenis)
$bobot = [];
$jenis = [];
$nilaiPerKriteria = [];

$kriteriaQuery = $conn->query("SELECT * FROM kriteria ORDER BY no");
while ($row = $kriteriaQuery->fetch_assoc()) {
    $no = $row['no'];
    $bobot[$no] = $row['bobot'];
    $jenis[$no] = strtolower($row['jenis']); // benefit/cost
    $nilaiPerKriteria[$no] = [];
}

// Ambil data penilaian
$nilaiMatrix = [];
$penilaianQuery = $conn->query("SELECT * FROM penilaian");
while ($row = $penilaianQuery->fetch_assoc()) {
    $idw = $row['id_wisata'];
    $idk = $row['id_kriteria'];
    $nilai = $row['nilai'];
    $nilaiMatrix[$idw][$idk] = $nilai;
    $nilaiPerKriteria[$idk][] = $nilai;
}

// Normalisasi
$normalisasi = [];
foreach ($nilaiMatrix as $idw => $nilaiKriteria) {
    foreach ($nilaiKriteria as $idk => $nilai) {
        if (!isset($jenis[$idk])) continue;

        if ($jenis[$idk] === 'cost') {
            $min = min($nilaiPerKriteria[$idk]);
            $normal = $nilai > 0 ? $min / $nilai : 0;
        } else {
            $max = max($nilaiPerKriteria[$idk]);
            $normal = $max > 0 ? $nilai / $max : 0;
        }

        $normalisasi[$idw][$idk] = $normal;
    }
}

// Hitung preferensi SAW
$hasilSAW = [];
foreach ($normalisasi as $idw => $nilaiNormal) {
    $total = 0;
    foreach ($nilaiNormal as $idk => $norm) {
        $total += $norm * $bobot[$idk];
    }
    $total = round($total, 2);
    $hasilSAW[$idw] = $total;

    // Simpan ke tabel rekomendasi
    $stmt = $conn->prepare("INSERT INTO rekomendasi (id_wisata, preferensi) VALUES (?, ?)");
    $stmt->bind_param("id", $idw, $total);
    $stmt->execute();
}
arsort($hasilSAW);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekomendasi Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #00b4b4, #ffffff);
            font-family: 'Segoe UI', sans-serif;
            padding: 50px 20px;
        }

        .recommendation-container {
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .recommendation-header {
            background: linear-gradient(to right, #00b4b4, #008080);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recommendation-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
        }

        .recommendation-body {
            padding: 30px;
        }

        .table thead th {
            background-color: #e0f7fa;
        }

        .badge-ranking {
            display: inline-block;
            background: linear-gradient(to right, #00b4b4, #008080);
            border-radius: 50px;
            color: white;
            font-weight: bold;
            padding: 8px 16px;
        }

        .footer-note {
            font-size: 14px;
            margin-top: 15px;
            color: #777;
        }

        .btn-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #008080;
            color: white;
            border-radius: 50px;
            padding: 12px 16px;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: none;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #008080;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="recommendation-container" data-aos="fade-up">
    <div class="recommendation-header" data-aos="fade-down">
        <h4><i class="bi bi-stars me-2"></i> Rekomendasi Tempat Wisata Terbaik</h4>
        <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
    </div>

    <div class="recommendation-body">
        <p class="mb-4 text-muted" data-aos="fade-up">
            Rekomendasi ini dihitung dengan metode SAW berdasarkan preferensi pengguna dan kriteria yang tersedia.
        </p>

        <a href="dashboard_user.php" class="back-link" data-aos="fade-right"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>

        <div class="table-responsive" data-aos="fade-up" data-aos-delay="100">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px;">#</th>
                        <th>Nama Tempat Wisata</th>
                        <th>Skor Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $peringkat = 1;
                    foreach ($hasilSAW as $id => $skor) {
                        $wisataQuery = $conn->query("SELECT nama_wisata FROM wisata WHERE id_wisata = '$id'");
                        $nama = $wisataQuery->fetch_assoc()['nama_wisata'];
                        echo "<tr>
                            <td><span class='badge-ranking'>{$peringkat}</span></td>
                            <td class='text-start fw-semibold'>{$nama}</td>
                            <td><span class='fw-bold text-success'>" . number_format($skor, 2) . "</span></td>
                        </tr>";
                        $peringkat++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="footer-note text-center mt-4" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-info-circle"></i> Rekomendasi ini bersifat dinamis dan bergantung pada data penilaian.
        </div>
    </div>
</div>

<!-- Scroll to top -->
<button onclick="scrollToTop()" id="btnTop" class="btn btn-top" title="Kembali ke Atas">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    window.onscroll = function () {
        const btn = document.getElementById("btnTop");
        btn.style.display = (document.documentElement.scrollTop > 200) ? "block" : "none";
    };
</script>

</body>
</html>
