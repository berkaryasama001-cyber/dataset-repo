<?php
// import.php - Skrip otomatis import database ke Railway

require_once 'config.php';

// Nama file database SQL Anda
$sql_file = 'db_dataset_repo.sql'; 

if (!file_exists($sql_file)) {
    die("<h3 style='color: red;'>Gagal:</h3> File SQL <strong>{$sql_file}</strong> tidak ditemukan di direktori project.");
}

$sql_content = file_get_contents($sql_file);

// Eksekusi multi query SQL dengan aman
if (mysqli_multi_query($conn, $sql_content)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    echo "<h2 style='color: green;'>SUKSES: Database berhasil di-import ke Railway!</h2>";
    echo "<hr>";
    echo "<p><b>Langkah Terakhir:</b> Segera hapus file <b>import.php</b> ini dari project Anda demi keamanan.</p>";
    } else {
        echo "<h2 style='color: red;'>Gagal Mengimport Database:</h2>";
        echo "<p>" . mysqli_error($conn) . "</p>";
    }
?>