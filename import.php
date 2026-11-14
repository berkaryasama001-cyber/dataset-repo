<?php
// Tampilkan error secara transparan
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

$filename = 'db_dataset_repo.sql';

if (!file_exists($filename)) {
    die("<h3 style='color:red;'>Error: File $filename tidak ditemukan!</h3>");
}

// Baca isi file SQL
$content = file_get_contents($filename);

// Bersihkan komentar baris (-- ) dan blok komentar agar tidak mengganggu eksekusi
$content = preg_replace('/--.*?\n/', "\n", $content);
$content = preg_replace('/\/\*.*?\*\//s', '', $content);

// Pisahkan setiap perintah SQL berdasarkan titik koma (;)
$queries = array_filter(array_map('trim', explode(';', $content)));

$success = 0;
$failed = 0;
$errors = [];

foreach ($queries as $query) {
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            $success++;
        } else {
            $failed++;
            $errors[] = $conn->error;
        }
    }
}

echo "<h2>Hasil Import Database Railway:</h2>";
echo "<p>Total perintah SQL dieksekusi: <b>$success</b></p>";

if ($failed > 0) {
    echo "<p style='color:orange;'>Perhatian: Ada $failed perintah yang gagal (kemungkinan tabel/data sudah ada sebelumnya).</p>";
    echo "<details><summary>Lihat detail error</summary><pre>" . print_r($errors, true) . "</pre></details>";
} else {
    echo "<h3 style='color:green;'>SUKSES BESAR: Seluruh tabel (admin & datasets) beserta isinya berhasil di-import ke Railway!</h3>";
    echo "<p>Data sampel Breast Cancer Wisconsin berhasil dimasukkan ke database.</p>";
}

echo "<hr><p><b>Langkah Terakhir:</b> Segera hapus file <b>import.php</b> ini dari project Anda demi keamanan.</p>";
?>