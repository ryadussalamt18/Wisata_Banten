<?php
require_once "config.php";

// Ambil data wisata
$wisata_result = $conn->query("SELECT * FROM wisata");
$wisata_list = [];
while ($row = $wisata_result->fetch_assoc()) {
    $wisata_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Media Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #00b4b4; /* Disamakan dengan welcome */
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.6s ease-in-out;
        }

        h3 {
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
            color: #00796b;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            padding: 12px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-control:focus, .form-select:focus {
            border-color: #26a69a;
            box-shadow: 0 0 0 0.2rem rgba(38, 166, 154, 0.25);
        }

        .btn-primary {
            background-color: #00b4b4;
            border-color: #00b4b4;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #008080;
            border-color: #008080;
        }

        #search-box {
            margin-bottom: 12px;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Tambah Media Wisata Banten</h3>

    <form id="mediaForm">
        <div class="mb-3">
            <label for="search-box" class="form-label">Cari Wisata</label>
            <input type="text" id="search-box" class="form-control" placeholder="Ketik nama wisata...">
        </div>

        <div class="mb-3">
            <label for="id_wisata" class="form-label">Pilih Wisata</label>
            <select class="form-select" name="id_wisata" id="id_wisata" required size="5">
                <?php foreach ($wisata_list as $wisata): ?>
                    <option value="<?= $wisata['id_wisata'] ?>"><?= $wisata['nama_wisata'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="url" class="form-label">URL Foto atau Video</label>
            <input type="url" class="form-control" name="url" id="url" placeholder="https://..." required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Tipe Media</label>
            <select class="form-select" name="type" id="type" required>
                <option value="foto">Foto</option>
                <option value="video">Video</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Media</button>
    </form>
</div>

<script>
    // Live search wisata
    const searchBox = document.getElementById('search-box');
    const wisataSelect = document.getElementById('id_wisata');

    searchBox.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const options = wisataSelect.options;

        for (let i = 0; i < options.length; i++) {
            const text = options[i].text.toLowerCase();
            options[i].style.display = text.includes(filter) ? '' : 'none';
        }
    });

    // AJAX form submit
    $('#mediaForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'wisata_media_proses.php',
            data: $(this).serialize(),
            success: function (response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Media berhasil ditambahkan ke wisata.',
                        confirmButtonColor: '#00b4b4'
                    });

                    // Reset form + filter
                    $('#mediaForm')[0].reset();
                    $('#id_wisata').prop('selectedIndex', 0);
                    $('#search-box').val('');
                    $('#id_wisata option').show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response,
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
