<?php
require_once 'config.php'; // Menggunakan koneksi yang sudah terhubung ke Railway

$sql_file = 'db_dataset_repo.sql'; // Ganti dengan nama file sql Anda jika berbeda

if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);

    // Jalankan multi query SQL
    if ($conn->multi_query($sql_content)) {
        do {
            // Kosongkan hasil query agar tidak nyangkut
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());

        echo "<h2>Sukses! Database berhasil di-import ke Railway.</h2>";
        echo "<p>Silakan hapus file import.php ini setelah selesai demi keamanan.</p>";
    } else {
        echo "Gagal meng-import database: " . $conn->error;
    }
} else {
    echo "File SQL tidak ditemukan di folder project.";
}
?>