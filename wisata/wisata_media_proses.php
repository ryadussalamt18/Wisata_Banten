<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_wisata = $_POST['id_wisata'] ?? '';
    $type = $_POST['type'] ?? '';
    $url = mysqli_real_escape_string($conn, $_POST['url'] ?? '');

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        echo "URL tidak valid!";
        exit;
    }

    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/?(\w+))|youtu\.be\/(\w+))/', $url, $matches);
        $youtube_id = $matches[1] ?? ($matches[2] ?? '');
        $url = "https://www.youtube.com/watch?v=" . $youtube_id;
        $type = 'video';
    }

    $stmt = $conn->prepare("INSERT INTO wisata_media (id_wisata, type, url, uploaded_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $id_wisata, $type, $url);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Gagal menambahkan media!";
    }
} else {
    echo "Akses tidak valid!";
}
