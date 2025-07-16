<?php
require_once "config.php";

$notif = "";
$val_nama = $_POST['nama_kriteria'] ?? '';
$val_bobot = isset($_POST['bobot']) ? number_format((float)$_POST['bobot'], 2, '.', '') : '';
$val_jenis = $_POST['jenis'] ?? '';

$show_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $val_nama;
  $bobot = round(floatval($val_bobot), 2);
  $jenis = $val_jenis;

  // Cek total bobot
  $cek = $conn->query("SELECT SUM(bobot) as total FROM kriteria");
  $data = $cek->fetch_assoc();
  $total_bobot_sekarang = floatval($data['total']);
  $total_setelah_tambah = $total_bobot_sekarang + $bobot;

  if ($total_setelah_tambah > 1) {
    $notif = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Peringatan!</strong> Total bobot tidak boleh lebih dari 1. Sisa bobot: " . number_format(1 - $total_bobot_sekarang, 2) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
  } else {
    $stmt = $conn->prepare("INSERT INTO kriteria (nama_kriteria, bobot, jenis) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $nama, $bobot, $jenis);

    if ($stmt->execute()) {
      $show_success = true;
    } else {
      $notif = "<div class='alert alert-danger'>Gagal menambahkan data!</div>";
    }

    $stmt->close();
  }

  $conn->close();
}
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card shadow-lg border-0">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Form Tambah Kriteria</h5>
  </div>
  <div class="card-body">
    <?= $notif ?>
    <form method="POST" class="needs-validation" novalidate>
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Kriteria</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-tag"></i></span>
            <input type="text" name="nama_kriteria" class="form-control"
              value="<?= htmlspecialchars($val_nama) ?>"
              placeholder="Contoh: Harga Tiket" required>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Bobot</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
            <input type="number" step="0.01" min="0.01" max="1" name="bobot" class="form-control"
              value="<?= $val_bobot ?>" placeholder="Contoh: 0.10" required>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Jenis</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-filter"></i></span>
            <select name="jenis" class="form-select" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="benefit" <?= $val_jenis == 'benefit' ? 'selected' : '' ?>>Benefit</option>
              <option value="cost" <?= $val_jenis == 'cost' ? 'selected' : '' ?>>Cost</option>
            </select>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-4">
        <a href="?page=kriteria" class="btn btn-secondary me-2">
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

  <?php if ($show_success): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data berhasil ditambahkan.',
      timer: 2000,
      showConfirmButton: false
    }).then(() => {
      window.location.href = '?page=kriteria';
    });
  <?php endif; ?>
</script>
