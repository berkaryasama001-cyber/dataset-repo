<?php
// Tampilkan pesan error jika ada kendala
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

$filename = 'db_dataset_repo.sql';

if (!file_exists($filename)) {
    die("File SQL ($filename) tidak ditemukan di folder project!");
}

// Baca file SQL
$sql_content = file_get_contents($filename);

// Pisahkan query berdasarkan titik koma (;) agar dieksekusi satu per satu dengan aman
$queries = array_filter(array_map('trim', explode(';', $sql_content)));

$success_count = 0;
$fail_count = 0;

foreach ($queries as $query) {
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            $success_count++;
        } else {
            $fail_count++;
        }
    }
}

echo "<h1>Hasil Import Database ke Railway:</h1>";
echo "<p>Berhasil mengeksekusi: <b>$success_count</b> perintah SQL.</p>";
if ($fail_count > 0) {
    echo "<p style='color:orange;'>Catatan: Ada $fail_count perintah dilewati (kemungkinan tabel sudah ada sebelumnya).</p>";
}
echo "<h3 style='color:green;'>Status: Import Database Selesai!</h3>";
echo "<p>Sekarang Anda bisa menghapus file <b>import.php</b> ini.</p>";
?>