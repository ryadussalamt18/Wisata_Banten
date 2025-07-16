<?php
require_once "config.php";

$notif = "";
$nama_wisata = $_POST['nama_wisata'] ?? '';
$nilai_input = $_POST['nilai'] ?? [];
$show_success = false;

$kriteria = $conn->query("SELECT * FROM kriteria ORDER BY no");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (empty($nama_wisata)) {
    $notif = "<div class='alert alert-warning'>Nama Wisata tidak boleh kosong! Harap lengkapi semua field.</div>";
  } elseif (array_filter($nilai_input, fn($n) => $n > 5)) {
    $notif = "<div class='alert alert-danger'>Nilai kriteria tidak boleh lebih dari 5!</div>";
  } else {
    $stmtWisata = $conn->prepare("INSERT INTO wisata (nama_wisata) VALUES (?)");
    $stmtWisata->bind_param("s", $nama_wisata);
    $berhasilWisata = $stmtWisata->execute();
    $id_wisata = $conn->insert_id;
    $stmtWisata->close();

    if ($berhasilWisata) {
      $berhasil = true;
      foreach ($nilai_input as $id_k => $nilai) {
        $stmt = $conn->prepare("INSERT INTO penilaian (id_wisata, id_kriteria, nilai) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $id_wisata, $id_k, $nilai);
        if (!$stmt->execute()) $berhasil = false;
        $stmt->close();
      }

      if ($berhasil) {
        $show_success = true; // Trigger untuk SweetAlert
      } else {
        $notif = "<div class='alert alert-danger'>Gagal menambahkan penilaian.</div>";
      }
    } else {
      $notif = "<div class='alert alert-danger'>Gagal menambahkan data wisata.</div>";
    }
  }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($show_success): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data berhasil ditambahkan',
      showConfirmButton: false,
      timer: 2000
    }).then(() => {
      window.location.href = '?page=penilaian';
    });
  </script>
<?php endif; ?>

<div class="card shadow-lg border-0">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah Penilaian Wisata Baru</h5>
  </div>
  <div class="card-body">
    <?= $notif ?>
    <form method="POST" class="needs-validation" novalidate>
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Wisata</label>
        <input type="text" name="nama_wisata" class="form-control" required value="<?= htmlspecialchars($nama_wisata) ?>">
      </div>

      <hr>
      <h6 class="fw-bold text-primary">Nilai Kriteria (1-5)</h6>

      <?php $kriteria->data_seek(0);
      while ($k = $kriteria->fetch_assoc()):
        $idk = $k['no'];
        $val_nilai = $nilai_input[$idk] ?? '';
      ?>
        <div class="mb-3">
          <label class="form-label"><?= htmlspecialchars($k['nama_kriteria']) ?> (<?= $k['jenis'] ?>)</label>
          <input type="number" name="nilai[<?= $idk ?>]" class="form-control" step="0.01" min="0" max="5" required value="<?= $val_nilai ?>">
        </div>
      <?php endwhile; ?>

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

<!-- Validasi Bootstrap -->
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
