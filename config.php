<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_dataset_repo";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Helper untuk sanitasi input
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Helper untuk hitung rata-rata rating
function get_avg_rating($sum, $count) {
    if ($count == 0) return "Belum ada rating";
    return number_format($sum / $count, 1) . " / 5.0 (" . $count . " ulasan)";
}
?>