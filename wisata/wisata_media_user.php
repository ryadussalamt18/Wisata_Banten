<?php
require_once "config.php";

$media_query = $conn->query("
  SELECT wisata_media.*, wisata.nama_wisata, wisata.deskripsi 
  FROM wisata_media 
  INNER JOIN wisata ON wisata_media.id_wisata = wisata.id_wisata 
  ORDER BY wisata.nama_wisata ASC
");

function parse_youtube_id($url) {
    if (preg_match('/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    return '';
}

$manual_deskripsi = [
  1 => "AEON Mall BSD adalah pusat perbelanjaan dengan konsep khas Negeri Sakura yang menawarkan hypermarket, restoran, taman bermain dan bioskop.",
  2 => "Cagar Alam Pegunungan merupakan cagar alam di Banten yang juga satu-satunya ekosistem rawa pegunungan di Pulau Jawa.",
  3 => "Marcopolo Waterpark adalah taman rekreasi air keluarga yang memiliki berbagai wahana permainan air yang seru.",
  4 => "Perkampungan Baduy Luar Bertempat tinggal di wilayah Kanekes, seperti Cikadu, Kaduketuk, Cisagu, dan Gajeboh.",
  5 => "Trekking Baduy merupakan kelompok etnis masyarakat adat suku Banten di wilayah Kabupaten Lebak, Banten."
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Media Wisata - Sistem Pakar Wisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background-color: #00b4b4;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 30px;
      margin-bottom: 80px;
    }
    .media-card {
      margin-bottom: 20px;
      text-align: center;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 12px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      position: relative;
    }
    .media-wrapper img,
    .media-wrapper video,
    .media-wrapper iframe {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .media-wrapper:hover img,
    .media-wrapper:hover video,
    .media-wrapper:hover iframe {
      transform: scale(1.03);
      box-shadow: 0 0 20px rgba(0, 128, 128, 0.6);
    }
    .media-label {
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
      background-color: #007d7d;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: bold;
    }
    .btn-back {
      background-color: #008080;
      color: white;
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: bold;
      margin-bottom: 20px;
      text-decoration: none;
    }
    .btn-back:hover {
      background-color: #007d7d;
      text-decoration: none;
    }
    .footer {
      background-color: #008080;
      color: white;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container" data-aos="fade-up">
  <a href="dashboard_user.php" class="btn btn-back" data-aos="fade-right"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>
  <h3 class="text-center mb-4 text-white" data-aos="zoom-in">Media Wisata</h3>

  <div class="row">
    <?php
    $no = 1;
    $nama_sebelumnya = '';

    if ($media_query->num_rows > 0):
      while ($media = $media_query->fetch_assoc()):
        $nama = $media['nama_wisata'];
        $deskripsi = $manual_deskripsi[$no] ?? ($media['deskripsi'] ?? '-');
        $is_youtube = strpos($media['url'], 'youtube.com') !== false || strpos($media['url'], 'youtu.be') !== false;
        $youtube_id = parse_youtube_id($media['url']);

        if ($nama != $nama_sebelumnya):
    ?>
      <div class="col-12 mt-4 mb-2" data-aos="fade-up" data-aos-delay="<?= $no * 100 ?>">
        <div class="card shadow-sm border-0">
          <div class="card-body" style="background-color: #f9f9f9; border-left: 5px solid #008080;">
            <h5 class="mb-2 text-dark"><?= htmlspecialchars($nama) ?></h5>
            <p class="mb-0 text-muted" style="font-size: 15px; line-height: 1.7;">
              <?= nl2br(htmlspecialchars($deskripsi)) ?>
            </p>
          </div>
        </div>
      </div>
    <?php
        $nama_sebelumnya = $nama;
        $no++;
        endif;
    ?>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $no * 150 ?>">
      <div class="media-card">
        <div class="media-label">
          <?= $media['type'] == 'foto' ? 'Foto' : 'Video'; ?>
        </div>
        <div class="media-wrapper mt-4">
          <?php if ($is_youtube && $youtube_id): ?>
            <iframe 
              src="https://www.youtube.com/embed/<?= $youtube_id ?>?autoplay=1&mute=1&loop=1&playlist=<?= $youtube_id ?>"
              frameborder="0"
              allow="autoplay; encrypted-media"
              allowfullscreen
              class="w-100 rounded"
              style="height: 200px; object-fit: cover;">
            </iframe>
          <?php elseif ($media['type'] == 'foto'): ?>
            <img src="<?= $media['url'] ?>" alt="Foto Wisata" class="media-thumbnail" data-img="<?= $media['url'] ?>">
          <?php elseif ($media['type'] == 'video'): ?>
            <video autoplay muted loop playsinline class="media-thumbnail">
              <source src="<?= $media['url'] ?>" type="video/mp4">
              Browser tidak mendukung tag video.
            </video>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; else: ?>
      <div class="col-12">
        <div class="alert alert-warning">Tidak ada media untuk ditampilkan.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal Gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-white">
      <div class="modal-body text-center">
        <img src="" id="modalImage" class="img-fluid rounded" style="max-height: 80vh;" alt="Foto Wisata">
      </div>
    </div>
  </div>
</div>

<!-- Modal Video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe id="videoFrame" src="" frameborder="0" allowfullscreen allow="autoplay"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="footer">
  &copy; <?= date("Y") ?> Sistem Pakar Wisata Banten | Metode SAW
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });

  document.querySelectorAll('.media-thumbnail').forEach(el => {
    el.addEventListener('click', function () {
      const img = this.getAttribute('data-img');
      const video = this.getAttribute('data-video');

      if (img) {
        document.getElementById('modalImage').src = img;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
      } else if (video) {
        document.getElementById('videoFrame').src = video;
        const modalVideo = new bootstrap.Modal(document.getElementById('videoModal'));
        modalVideo.show();
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
          document.getElementById('videoFrame').src = '';
        });
      }
    });
  });
</script>

</body>
</html>
