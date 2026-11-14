<?php
// config.php - Konfigurasi koneksi database MySQL & fungsi helper di Railway
session_start();


$host     = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$user     = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: 'yfjedrGuaUNWQJmwdykySHwQETAkZChy';
$database = getenv('MYSQLDATABASE') ?: 'railway';
$port     = (int)(getenv('MYSQLPORT') ?: 3306);

// Matikan laporan error otomatis agar tidak memicu Uncaught mysqli_sql_exception sebelum die()
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    http_response_code(500);
    die("Koneksi MySQL Gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Fungsi membersihkan input dari karakter berbahaya (untuk query & XSS)
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8'));
}

// Fungsi menghitung rata-rata rating dataset
function get_avg_rating($rating_sum, $rating_count) {
    if ($rating_count <= 0) {
        return "Belum ada rating";
    }
    $avg = round($rating_sum / $rating_count, 1);
    return $avg . " / 5 (" . $rating_count . " ulasan)";
}
?>
