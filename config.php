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
// config.php - Konfigurasi koneksi database Railway & Localhost

$host     = $_ENV['MYSQLHOST'] ?? $_ENV['MYSQL_HOST'] ?? 'localhost';
$user     = $_ENV['MYSQLUSER'] ?? $_ENV['MYSQL_USER'] ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['MYSQL_PASSWORD'] ?? '';
$database = $_ENV['MYSQLDATABASE'] ?? $_ENV['MYSQL_DATABASE'] ?? 'railway';
$port     = (int)($_ENV['MYSQLPORT'] ?? $_ENV['MYSQL_PORT'] ?? 3306);

// Membuat koneksi menggunakan mysqli berorientasi objek
$conn = @new mysqli($host, $user, $password, $database, $port);

// Periksa apakah koneksi gagal
if ($conn->connect_error) {
    http_response_code(500);
    die("<h3>Koneksi Database Gagal</h3><p>Pastikan variabel environment di Railway sudah terhubung dengan benar.</p><p><strong>Detail Error:</strong> " . $conn->connect_error . "</p>");
}

// Atur charset UTF-8 agar data teks aman
$conn->set_charset("utf8mb4");
?> 