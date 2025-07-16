<?php
require_once "config.php";
session_start(); // untuk notifikasi lewat session

$id = $_GET['id'] ?? 0;
$query = $conn->query("SELECT * FROM user WHERE id = $id");
$data = $query->fetch_assoc();

if (!$data) {
    echo "<script>
        Swal.fire('Gagal!', 'Data tidak ditemukan.', 'error')
        .then(() => window.location.href='?page=user');
    </script>";
    exit;
}

$error_pass_lama = '';
$error_username = '';

if (isset($_POST['update'])) {
    $username       = trim($_POST['username']);
    $nama           = trim($_POST['nama_lengkap']);
    $email          = trim($_POST['email']);
    $no_hp          = trim($_POST['no_hp']);
    $password_lama  = trim($_POST['password_lama']);
    $password_baru  = trim($_POST['password_baru']);

    // Validasi field wajib (password_baru boleh kosong)
    if ($username == '' || $nama == '' || $email == '' || $no_hp == '' || $password_lama == '') {
        echo "<script>
            Swal.fire('Gagal!', 'Field tidak boleh kosong, kecuali password baru.', 'warning');
        </script>";
    } else {
        // Cek apakah username sudah digunakan oleh user lain
        $cekUsername = $conn->prepare("SELECT id FROM user WHERE username = ? AND id != ?");
        $cekUsername->bind_param("si", $username, $id);
        $cekUsername->execute();
        $cekUsername->store_result();

        if ($cekUsername->num_rows > 0) {
            $error_username = "⚠ Username sudah digunakan oleh pengguna lain.";
        } else {
            $pass_lama_db = $data['pass'];

            // Jika password lama tidak cocok
            if ($password_lama !== $pass_lama_db) {
                $error_pass_lama = "⚠ Password Anda salah.";
            } else {
                if (!empty($password_baru)) {
                    $stmt = $conn->prepare("UPDATE user SET username=?, nama_lengkap=?, email=?, no_hp=?, pass=? WHERE id=?");
                    $stmt->bind_param("sssssi", $username, $nama, $email, $no_hp, $password_baru, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE user SET username=?, nama_lengkap=?, email=?, no_hp=? WHERE id=?");
                    $stmt->bind_param("ssssi", $username, $nama, $email, $no_hp, $id);
                }

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Data berhasil diupdate.";
                    header("Location: ?page=user");
                    exit;
                } else {
                    echo "<script>
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan.', 'error');
                    </script>";
                }
            }
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<?php unset($_SESSION['success']); endif; ?>

<div class="card shadow">
  <div class="card-header text-white" style="background: linear-gradient(90deg, #00b4b4, #008080);">
    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Pengguna</h5>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
        <?php if ($error_username): ?>
          <div class="text-danger mt-1"><?= $error_username ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label>No. HP</label>
        <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Password Lama <span class="text-danger">*</span></label>
        <input type="password" name="password_lama" class="form-control" required>
        <?php if ($error_pass_lama): ?>
          <div class="text-danger mt-1"><?= $error_pass_lama ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label>Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti password)</small></label>
        <input type="password" name="password_baru" class="form-control">
      </div>
      <button type="submit" name="update" class="btn btn-success"><i class="fas fa-save me-1"></i> Update</button>
      <a href="?page=user" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
