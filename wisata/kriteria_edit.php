<?php
require_once "config.php";

$no = $_GET['no'] ?? '';
$notif = "";

// Ambil data berdasarkan `no`
$sql = $conn->query("SELECT * FROM kriteria WHERE no = '$no'");
$data = $sql->fetch_assoc();

if (!$data) {
  echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
  return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama_kriteria'];
  $bobot = round(floatval($_POST['bobot']), 2);
  $jenis = $_POST['jenis'];

  // Hitung total bobot baru (kecuali bobot lama dari data ini)
  $cek = $conn->query("SELECT SUM(bobot) as total FROM kriteria WHERE no != '$no'");
  $total_bobot = floatval($cek->fetch_assoc()['total']) + $bobot;

  if ($total_bobot > 1) {
    $notif = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Gagal!</strong> Total bobot tidak boleh lebih dari 1. Sisa tersedia: " . number_format(1 - ($total_bobot - $bobot), 2) . ".
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
  } else {
    $stmt = $conn->prepare("UPDATE kriteria SET nama_kriteria=?, bobot=?, jenis=? WHERE no=?");
    $stmt->bind_param("sdsi", $nama, $bobot, $jenis, $no);

    if ($stmt->execute()) {
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
      echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data berhasil diupdate',
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.href = '?page=kriteria';
        });
      </script>";
      exit;
    } else {
      $notif = "<div class='alert alert-danger'>Gagal menyimpan perubahan!</div>";
    }
  }
}
?>

<!-- SweetAlert2 (jika tidak muncul karena echo, ini cadangan di bawah) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card shadow-lg border-0">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-pen me-2"></i> Edit Kriteria</h5>
  </div>
  <div class="card-body">
    <?= $notif ?>
    <form method="POST" class="needs-validation" novalidate>
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Kriteria</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-tag"></i></span>
            <input type="text" name="nama_kriteria" class="form-control" value="<?= htmlspecialchars($data['nama_kriteria']) ?>" required>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Bobot</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
            <input type="number" name="bobot" step="0.01" min="0.01" max="1" class="form-control" value="<?= number_format($data['bobot'], 2, '.', '') ?>" required>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Jenis</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-filter"></i></span>
            <select name="jenis" class="form-select" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="benefit" <?= $data['jenis'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
              <option value="cost" <?= $data['jenis'] == 'cost' ? 'selected' : '' ?>>Cost</option>
            </select>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-4">
        <a href="?page=kriteria" class="btn btn-secondary me-2"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
      </div>
    </form>
  </div>
</div>
