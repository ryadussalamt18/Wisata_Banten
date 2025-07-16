<?php
require_once "config.php";
session_start(); // Tambahkan untuk menggunakan session

$error_username = '';

if (isset($_POST['simpan'])) {
    $username = trim($_POST['username']);
    $nama     = trim($_POST['nama_lengkap']);
    $email    = trim($_POST['email']);
    $no_hp    = trim($_POST['no_hp']);
    $password = trim($_POST['password']);

    if ($username == '' || $nama == '' || $email == '' || $no_hp == '' || $password == '') {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire('Gagal!', 'Semua field harus diisi.', 'warning');
        </script>";
    } else {
        // Cek apakah username sudah digunakan
        $cek = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error_username = "⚠ Username sudah digunakan. Pilih yang lain.";
        } else {
            $stmt = $conn->prepare("INSERT INTO user (username, pass, nama_lengkap, email, no_hp) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $password, $nama, $email, $no_hp);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Data pengguna berhasil disimpan.";
                header("Location: ?page=user");
                exit;
            } else {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan.', 'error');
                </script>";
            }
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card shadow">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> Tambah Pengguna</h5>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        <?php if ($error_username): ?>
          <div class="text-danger mt-1"><?= $error_username ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?= isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : '' ?>" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
      </div>
      <div class="mb-3">
        <label>No. HP</label>
        <input type="text" name="no_hp" class="form-control" value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : '' ?>" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
      <a href="?page=user" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
