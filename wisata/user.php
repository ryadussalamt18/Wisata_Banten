<?php
require_once "config.php";
session_start();

// Hapus pengguna jika ada parameter `hapus`
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM user WHERE id = $id");

    $_SESSION['success'] = "Data pengguna berhasil dihapus.";
    header("Location: ?page=user");
    exit;
}

// Ambil semua data user
$dataUser = $conn->query("SELECT * FROM user ORDER BY id ASC");
?>

<!-- Link Bootstrap & Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Notifikasi sukses -->
<?php if (isset($_SESSION['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '<?= $_SESSION['success'] ?>',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Konfirmasi hapus -->
<script>
function hapusUser(id) {
  Swal.fire({
    title: 'Hapus Pengguna?',
    text: "Data pengguna akan dihapus permanen!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '?page=user&hapus=' + id;
    }
  });
}
</script>

<!-- Tampilan -->
<div class="card shadow-sm" style="max-width: 1000px; margin: auto;">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Data Pengguna</h5>
      <a href="?page=user_tambah" class="btn btn-light btn-sm text-primary fw-bold">
        <i class="bi bi-person-plus me-1"></i> Tambah Pengguna
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center" id="myTable">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No. HP</th>
            <th style="width: 100px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          while ($row = $dataUser->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['no_hp']) ?></td>
            <td>
              <a href="?page=user_edit&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm px-2">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="#" onclick="hapusUser(<?= $row['id'] ?>)" class="btn btn-danger btn-sm px-2">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




<script>
    function hapusUser(id) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "Data pengguna akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=user&hapus=' + id;
            }
        });
    }
</script>