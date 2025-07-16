<?php
require_once "config.php";

// Ambil ID media dari parameter
$id = $_GET['id'] ?? '';
if (!$id) {
  echo "<script>alert('ID media tidak ditemukan');history.back();</script>";
  exit;
}

// Ambil data media berdasarkan ID
$result = $conn->query("SELECT * FROM wisata_media WHERE id = '$id'");
$media = $result->fetch_assoc();
if (!$media) {
  echo "<script>alert('Data tidak ditemukan');history.back();</script>";
  exit;
}

$wisata = $conn->query("SELECT * FROM wisata ORDER BY nama_wisata ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_wisata = $_POST['id_wisata'];
  $type = $_POST['type'];
  $url = mysqli_real_escape_string($conn, $_POST['url']);

  $update = $conn->query("UPDATE wisata_media SET id_wisata='$id_wisata', type='$type', url='$url' WHERE id='$id'");

  if ($update) {
    echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Media berhasil diperbarui'
      }).then(() => {
        window.location.href = 'wisata_media_user.php';
      });
    </script>";
    exit;
  } else {
    $error = "Gagal memperbarui data!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Media Wisata</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">Edit Media Wisata</h5>
    </div>
    <div class="card-body">
      <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Wisata</label>
          <select name="id_wisata" class="form-select" required>
            <?php while ($w = $wisata->fetch_assoc()): ?>
              <option value="<?= $w['id_wisata'] ?>" <?= $w['id_wisata'] == $media['id_wisata'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($w['nama_wisata']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Tipe Media</label>
          <select name="type" class="form-select" required>
            <option value="foto" <?= $media['type'] === 'foto' ? 'selected' : '' ?>>Foto</option>
            <option value="video" <?= $media['type'] === 'video' ? 'selected' : '' ?>>Video</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">URL Media</label>
          <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($media['url']) ?>" required>
        </div>

        <div class="d-flex justify-content-end">
          <a href="wisata_media_user.php" class="btn btn-secondary me-2">Kembali</a>
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
