<?php
require_once "config.php";

$id = $_GET['id'] ?? '';
if (empty($id)) {
  echo "<div class='alert alert-danger'>ID wisata tidak ditemukan!</div>";
  return;
}

$wisata = $conn->query("SELECT * FROM wisata WHERE id_wisata = '$id'")->fetch_assoc();
if (!$wisata) {
  echo "<div class='alert alert-danger'>Data wisata tidak ditemukan!</div>";
  return;
}

$kriteria = $conn->query("SELECT * FROM kriteria ORDER BY no")->fetch_all(MYSQLI_ASSOC);
$penilaian = [];
$q = $conn->query("SELECT * FROM penilaian WHERE id_wisata = '$id'");
while ($row = $q->fetch_assoc()) {
  $penilaian[$row['id_kriteria']] = $row['nilai'];
}

$notif = "";
$show_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_wisata = $_POST['nama_wisata'] ?? '';
  $nilai_input = $_POST['nilai'] ?? [];

  foreach ($nilai_input as $id_k => $nilai) {
    if ($nilai < 1 || $nilai > 5) {
      $notif = "<div class='alert alert-warning'>Nilai hanya boleh antara 1 sampai 5!</div>";
      break;
    }
  }

  if (!$notif) {
    $stmt = $conn->prepare("UPDATE wisata SET nama_wisata = ? WHERE id_wisata = ?");
    $stmt->bind_param("si", $nama_wisata, $id);
    $stmt->execute();
    $stmt->close();

    foreach ($nilai_input as $id_kriteria => $nilai) {
      $cek = $conn->query("SELECT * FROM penilaian WHERE id_wisata = '$id' AND id_kriteria = '$id_kriteria'");
      if ($cek->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE penilaian SET nilai = ? WHERE id_wisata = ? AND id_kriteria = ?");
        $stmt->bind_param("iii", $nilai, $id, $id_kriteria);
      } else {
        $stmt = $conn->prepare("INSERT INTO penilaian (id_wisata, id_kriteria, nilai) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $id, $id_kriteria, $nilai);
      }
      $stmt->execute();
      $stmt->close();
    }

    $show_success = true; // untuk trigger notifikasi SweetAlert
  }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($show_success): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data berhasil diupdate',
      showConfirmButton: false,
      timer: 2000
    }).then(() => {
      window.location.href = '?page=penilaian';
    });
  </script>
<?php endif; ?>

<!-- Form Edit -->
<div class="card shadow-lg border-0">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-pen me-2"></i> Edit Penilaian Wisata</h5>
  </div>
  <div class="card-body">
    <?= $notif ?>
    <form method="POST" class="needs-validation" novalidate>
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Wisata</label>
        <input type="text" name="nama_wisata" class="form-control" required value="<?= htmlspecialchars($wisata['nama_wisata']) ?>">
      </div>

      <hr>
      <h6 class="fw-bold text-primary">Nilai Kriteria (1–5)</h6>

      <?php foreach ($kriteria as $k): 
        $idk = $k['no'];
        $val_nilai = $penilaian[$idk] ?? 1;
      ?>
        <div class="mb-3">
          <label class="form-label"><?= htmlspecialchars($k['nama_kriteria']) ?> (<?= $k['jenis'] ?>)</label>
          <input type="number" name="nilai[<?= $idk ?>]" class="form-control" step="1" min="1" max="5" required value="<?= (int)$val_nilai ?>">
        </div>
      <?php endforeach; ?>

      <div class="d-flex justify-content-end">
        <a href="?page=penilaian" class="btn btn-secondary me-2">
          <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-success">
          <i class="fas fa-save me-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
