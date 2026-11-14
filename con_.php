
// cara 3
<?php
// config.php - String manual murni sesuai nilai asli dari dashboard Railway

$host     = 'mysql.railway.internal'; // atau host persis yang tercetak di variabel MYSQLHOST Railway Anda
$user     = 'root';                   // sesuai nilai MYSQLUSER di dashboard
$password = 'yfjedrGuaUNWQJmwdykySHwQETAkZChy'; // salin nilai dari MYSQLPASSWORD / MYSQL_ROOT_PASSWORD
$database = 'railway';                // sesuai nilai MYSQLDATABASE / MYSQL_DATABASE
$port     = (int)'3306';              // sesuai nilai MYSQLPORT

$conn = @new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    http_response_code(500);
    die("Koneksi MySQL Gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>


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
// session_start();

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
