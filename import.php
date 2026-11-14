<?php
// import.php - Skrip import database otomatis menggunakan PDO

require_once 'config.php'; $sql_file = 'db_dataset_repo.sql'; 

if (!file_exists($sql_file)) {
    die("<h3 style='color: red;'>Gagal:</h3> File SQL <strong>{$sql_file}</strong> tidak ditemukan di direktori project.");
}

try {
    $sql_content = file_get_contents($sql_file);
    $conn->exec($sql_content);

    echo "<h2 style='color: green;'>SUKSES: Database berhasil di-import ke Railway!</h2>";
    echo "<hr>";
    echo "<p><b>Langkah Terakhir:</b> Segera hapus file <b>import.php</b> ini dari project Anda demi keamanan.</p>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Gagal Mengimport Database:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>