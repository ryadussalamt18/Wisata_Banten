<?php
require_once "config.php";

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$success = $_GET['success'] ?? '';

// Routing TAMBAH
if ($action === 'tambah') {
  include 'penilaian_tambah.php';
  return;
}

// Hapus data penilaian dan wisata
if ($action === 'hapus' && $id !== '') {
  $conn->query("DELETE FROM penilaian WHERE id_wisata = '$id'");
  $conn->query("DELETE FROM wisata WHERE id_wisata = '$id'");
  echo "<script>window.location = '?page=penilaian&success=hapus';</script>";
  exit;
}

$kriteria = $conn->query("SELECT * FROM kriteria ORDER BY no")->fetch_all(MYSQLI_ASSOC);
$wisata = $conn->query("SELECT * FROM wisata ORDER BY id_wisata")->fetch_all(MYSQLI_ASSOC);
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<?php if ($success === 'hapus'): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data penilaian dan wisata berhasil dihapus.',
      timer: 2000,
      showConfirmButton: false
    }).then(() => {
      history.replaceState(null, '', '?page=penilaian');
    });
  </script>
<?php endif; ?>

<!-- Tambahan CSS -->
<style>
  .btn-action {
    margin-right: 8px;
  }
</style>

<div class="card shadow-sm">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Data Penilaian Wisata</h5>
      <a href="?page=penilaian&action=tambah" class="btn btn-light btn-sm text-primary fw-bold">
        <i class="bi bi-plus-circle me-1"></i> Tambah Penilaian
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center" id="myTable">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Wisata</th>
            <?php foreach ($kriteria as $k): ?>
              <th><?= htmlspecialchars($k['nama_kriteria']) ?></th>
            <?php endforeach; ?>
            <th style="width: 15%;"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          foreach ($wisata as $w):
            $idw = $w['id_wisata'];
            $nilai = [];
            $q = $conn->query("SELECT * FROM penilaian WHERE id_wisata = '$idw'");
            while ($r = $q->fetch_assoc()) {
              $nilai[$r['id_kriteria']] = $r['nilai'];
            }
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td class="text-start"><?= htmlspecialchars($w['nama_wisata']) ?></td>
              <?php foreach ($kriteria as $k): ?>
                <td><?= $nilai[$k['no']] ?? '-' ?></td>
              <?php endforeach; ?>
              <td class="text-center">
                <div class="d-flex justify-content-center">
                  <a href="?page=penilaian_edit&id=<?= $idw ?>" class="btn btn-warning btn-sm btn-action px-2">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="#" onclick="hapusPenilaian('<?= $idw ?>')" class="btn btn-danger btn-sm px-2">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function hapusPenilaian(id) {
    Swal.fire({
      title: 'Hapus Data?',
      text: 'Data penilaian dan wisata terkait akan dihapus permanen.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '?page=penilaian&action=hapus&id=' + id;
      }
    });
  }
</script>
