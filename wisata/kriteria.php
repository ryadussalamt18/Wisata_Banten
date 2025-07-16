<?php
require_once "config.php";

$action = $_GET['action'] ?? '';
$no = $_GET['no'] ?? '';
$success = $_GET['success'] ?? '';

if ($action === 'hapus' && $no !== '') {
  $conn->query("DELETE FROM kriteria WHERE no = $no");
  echo "<script>window.location = '?page=kriteria&success=hapus';</script>";
  exit;
}
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
      text: 'Data berhasil dihapus.',
      timer: 2000,
      showConfirmButton: false
    }).then(() => {
      history.replaceState(null, '', '?page=kriteria'); // Hapus parameter dari URL
    });
  </script>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-header bg-gradient text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i> Data Kriteria</h5>
      <a href="?page=kriteria_tambah&action=tambah" class="btn btn-light btn-sm text-primary fw-bold">
        <i class="fas fa-plus me-1"></i> Tambah Kriteria
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle" id="myTable">
        <thead class="table-success text-center">
          <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Kriteria</th>
            <th>Bobot</th>
            <th>Jenis</th>
            <th style="width: 15%;"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $sql = "SELECT * FROM kriteria ORDER BY no ASC";
          $result = $conn->query($sql);

          while ($row = $result->fetch_assoc()) {
          ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['nama_kriteria']); ?></td>
              <td class="text-center"><?= number_format($row['bobot'], 2); ?></td>
              <td class="text-center">
                <?php if (strtolower($row['jenis']) == 'cost'): ?>
                  <span class="badge bg-danger">Cost</span>
                <?php else: ?>
                  <span class="badge bg-success">Benefit</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="?page=kriteria_edit&action=update&no=<?= $row['no']; ?>" class="btn btn-warning btn-sm px-2">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="#" onclick="hapusKriteria(<?= $row['no']; ?>)" class="btn btn-danger btn-sm px-2">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php }
          $conn->close(); ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function hapusKriteria(id) {
    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '?page=kriteria&action=hapus&no=' + id;
      }
    });
  }
</script>
