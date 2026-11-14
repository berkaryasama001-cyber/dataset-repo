<!-- <?php -->
// import.php - Skrip import database otomatis menggunakan mysqli

// require_once 'config.php';
// $sql_file = 'db_dataset_repo.sql';

// if (!file_exists($sql_file)) {
//     die("<h3 style='color: red;'>Gagal:</h3> File SQL <strong>{$sql_file}</strong> tidak ditemukan di direktori project.");
// }

// $sql_content = file_get_contents($sql_file);

// if (empty(trim($sql_content))) {
//     die("<h3 style='color: red;'>Gagal:</h3> File SQL <strong>{$sql_file}</strong> kosong.");
// }

// if ($conn->multi_query($sql_content)) {
//     // Memproses dan membersihkan seluruh antrean query dalam file SQL
//     do {
//         if ($result = $conn->store_result()) {
//             $result->free();
//         }
//     } while ($conn->more_results() && $conn->next_result());

//     // Cek jika ada error di salah satu query bagian tengah/akhir file SQL
//     if ($conn->errno) {
//         echo "<h2 style='color: red;'>Gagal di Tengah Proses Import:</h2>";
//         echo "<p>" . htmlspecialchars($conn->error) . "</p>";
//     } else {
//         echo "<h2 style='color: green;'>SUKSES: Database berhasil di-import ke Railway!</h2>";
//         echo "<hr>";
//         echo "<p><b>Langkah Terakhir:</b> Segera hapus file <b>import.php</b> ini dari project Anda demi keamanan.</p>";
//     }
// } else {
//     echo "<h2 style='color: red;'>Gagal Mengimport Database:</h2>";
//     echo "<p>" . htmlspecialchars($conn->error) . "</p>";
// }

// $conn->close();
// ?>
