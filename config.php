<?php
// Hanya localhost
// session_start();

// $host = "localhost";
// $user = "root";
// $pass = "";
// $dbname = "db_dataset_repo";

// $conn = new mysqli($host, $user, $pass, $dbname);

// if ($conn->connect_error) {
//     die("Koneksi Database Gagal: " . $conn->connect_error);
// }

// // Helper untuk sanitasi input
// function sanitize($data) {
//     global $conn;
//     return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
// }

// // Helper untuk hitung rata-rata rating
// function get_avg_rating($sum, $count) {
//     if ($count == 0) return "Belum ada rating";
//     return number_format($sum / $count, 1) . " / 5.0 (" . $count . " ulasan)";
// }

// Railway & localhost
session_start();

// Deteksi apakah sedang di Railway atau di Localhost XAMPP
// if (getenv('MYSQLHOST')) {
//     // Kredensial dari Environment Variables Railway
//     $host = getenv('MYSQLHOST');
//     $user = getenv('MYSQLUSER');
//     $pass = getenv('MYSQLPASSWORD');
//     $dbname = getenv('MYSQLDATABASE');
//     $port = getenv('MYSQLPORT');
// } else {
//     // Kredensial untuk Localhost XAMPP
//     $host = "localhost";
//     $user = "root";
//     $pass = "";
//     $dbname = "db_dataset_repo";
//     $port = "3306";
// }

// $conn = new mysqli($host, $user, $pass, $dbname, $port);

// if ($conn->connect_error) {
//     die("Koneksi Database Gagal: " . $conn->connect_error);
// }

// function sanitize($data) {
//     global $conn;
//     return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
// }

// function get_avg_rating($sum, $count) {
//     if ($count == 0) return "Belum ada rating";
//     return number_format($sum / $count, 1) . " / 5.0 (" . $count . " ulasan)";
// }

// cara 3

// config.php - Konversi koneksi database otomatis untuk Railway & Localhost

$host     = $_ENV['MYSQLHOST'] ?? $_ENV['MYSQL_HOST'] ?? 'localhost';
$user     = $_ENV['MYSQLUSER'] ?? $_ENV['MYSQL_USER'] ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['MYSQL_PASSWORD'] ?? '';
$database = $_ENV['MYSQLDATABASE'] ?? $_ENV['MYSQL_DATABASE'] ?? 'dataset_db';
$port     = $_ENV['MYSQLPORT'] ?? $_ENV['MYSQL_PORT'] ?? 3306;

// Matikan error reporting bawaan agar pesan error tampil bersih jika gagal
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_init();

// Menghubungkan ke MySQL dengan port dinamis
$connected = @mysqli_real_connect($conn, $host, $user, $password, $database, (int)$port);

if (!$connected) {
    // Jika koneksi gagal, tampilkan informasi diagnostik yang jelas
    http_response_code(500);
    die("<h3>Koneksi Database Gagal</h3>" .
        "<p><strong>Error:</strong> " . mysqli_connect_error() . "</p>" .
        "<p>Pastikan variabel environment di Railway sudah terhubung dengan benar ke service MySQL.</p>");
}

// Set charset UTF-8
mysqli_set_charset($conn, "utf8mb4");
?> 