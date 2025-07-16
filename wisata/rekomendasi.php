<?php
require_once "config.php";

// Kosongkan tabel rekomendasi agar tidak terjadi duplikasi
$conn->query("DELETE FROM rekomendasi");

// Ambil semua kriteria dan siapkan struktur bobot dan jenis
$kriteriaQuery = $conn->query("SELECT * FROM kriteria ORDER BY no");
$bobot = [];
$jenis = [];
$nilaiPerKriteria = [];

while ($row = $kriteriaQuery->fetch_assoc()) {
  $no = $row['no'];
  $bobot[$no] = $row['bobot'];
  $jenis[$no] = strtolower($row['jenis']); // 'benefit' atau 'cost'
  $nilaiPerKriteria[$no] = [];
}

// Ambil seluruh data penilaian
$penilaianQuery = $conn->query("SELECT * FROM penilaian");
$nilaiMatrix = [];

while ($row = $penilaianQuery->fetch_assoc()) {
  $idw = $row['id_wisata'];
  $idk = $row['id_kriteria'];
  $nilai = $row['nilai'];

  $nilaiMatrix[$idw][$idk] = $nilai;
  $nilaiPerKriteria[$idk][] = $nilai;
}

// Lakukan normalisasi
$normalisasi = [];

foreach ($nilaiMatrix as $idw => $nilaiKriteria) {
  foreach ($nilaiKriteria as $idk => $nilai) {
    if (!isset($jenis[$idk])) continue;

    if ($jenis[$idk] === 'cost') {
      $min = min($nilaiPerKriteria[$idk]);
      $normal = ($nilai > 0) ? $min / $nilai : 0;
    } else {
      $max = max($nilaiPerKriteria[$idk]);
      $normal = ($max > 0) ? $nilai / $max : 0;
    }

    $normalisasi[$idw][$idk] = $normal;
  }
}

// Hitung skor preferensi SAW
$preferensi = [];

foreach ($normalisasi as $idw => $nilaiNormal) {
  $total = 0;
  foreach ($nilaiNormal as $idk => $norm) {
    $total += $norm * $bobot[$idk];
  }
  $total = round($total, 2); // hanya 2 digit di belakang koma
  $preferensi[$idw] = $total;

  // Simpan ke tabel rekomendasi
  $stmt = $conn->prepare("INSERT INTO rekomendasi (id_wisata, preferensi) VALUES (?, ?)");
  $stmt->bind_param("id", $idw, $total);
  $stmt->execute();
}

// Urutkan dari yang tertinggi
arsort($preferensi);
?>

<!-- Tampilan Tabel Rekomendasi -->
<div class="card shadow-lg">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-star me-2"></i> Hasil Rekomendasi Tempat Wisata (Metode SAW)</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center">
        <thead class="table-success">
          <tr>
            <th>Peringkat</th>
            <th>Nama Tempat Wisata</th>
            <th>Skor Preferensi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          foreach ($preferensi as $idw => $skor):
            $wisata = $conn->query("SELECT nama_wisata FROM wisata WHERE id_wisata = '$idw'")->fetch_assoc();
            echo "<tr>
              <td>$no</td>
              <td class='text-start'>" . htmlspecialchars($wisata['nama_wisata']) . "</td>
              <td>" . number_format($skor, 2) . "</td>
            </tr>";
            $no++;
          endforeach;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
