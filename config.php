<?php
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