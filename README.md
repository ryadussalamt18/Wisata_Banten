#  Sistem Pakar Rekomendasi Tempat Wisata – Metode SAW

Aplikasi web berbasis **PHP** dan **MySQL** yang digunakan untuk memberikan **rekomendasi tempat wisata di Banten** menggunakan metode **Simple Additive Weighting (SAW)**. Sistem ini menilai dan mengurutkan lokasi wisata berdasarkan berbagai kriteria untuk membantu pengguna memilih destinasi terbaik.

# Metode SAW
Metode **Simple Additive Weighting (SAW)** merupakan salah satu metode pengambilan keputusan berbasis multi-kriteria. Sistem ini menghitung bobot setiap alternatif (tempat wisata) berdasarkan penilaian dari beberapa kriteria, lalu menghasilkan rekomendasi dengan skor tertinggi.

# Fitur Utama
-  Lihat Daftar Tempat Wisata
-  Input dan Manajemen Kriteria Penilaian
-  Penilaian Alternatif Tempat Wisata
-  Perhitungan SAW Otomatis
-  Hasil Rekomendasi Terbaik
-  Manajemen Pengguna (Admin/User)
-  Antarmuka modern dan responsif (HTML + CSS)

# Teknologi yang Digunakan
- **PHP** (tanpa framework)
- **MySQL** (basis data)
- **HTML5**, **CSS3**, JavaScript
- **Bootstrap 5** (untuk styling)
- **Font Awesome**, **Google Fonts** (ikon & tampilan)

# Cara Menjalankan
1. **Clone Repository**
   ```bash
   git clone https://github.com/ryadussalamt18/Wisata_Banten.git
2. **Import Database**
   * Buka **phpMyAdmin**
   * Buat database baru, misalnya `wisata_banten`
   * Import file SQL dari folder `/database/`
3. **Atur Koneksi Database**
   * Buka file `koneksi.php` atau `config/database.php`
   * Sesuaikan `host`, `username`, `password`, dan `nama database`
4. **Jalankan Aplikasi**
   * Buka browser dan akses:
     `http://localhost/Wisata_Banten`   
# Lisensi
Proyek ini bersifat open-source dan dapat digunakan bebas untuk pembelajaran dan pengembangan lebih lanjut.
