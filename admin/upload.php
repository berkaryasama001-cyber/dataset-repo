<?php
require_once '../config.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = sanitize($_POST['title']);
    $category    = sanitize($_POST['category']);
    $file_format = sanitize($_POST['file_format']);
    $row_count   = intval($_POST['row_count']);
    $description = sanitize($_POST['description']);

    // Handle Upload File
    $file_name       = $_FILES['dataset_file']['name'];
    $file_tmp        = $_FILES['dataset_file']['tmp_name'];
    $file_size_bytes = $_FILES['dataset_file']['size'];

    // Format ukuran file
    $file_size = round($file_size_bytes / 1024 / 1024, 2) . ' MB';
    if ($file_size_bytes < 1048576) {
        $file_size = round($file_size_bytes / 1024, 2) . ' KB';
    }

    $ext     = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = array('csv', 'sql', 'zip');

    if (in_array($ext, $allowed)) {
        // Pastikan folder uploads ada dan bisa ditulis (writable)
        $upload_folder = "../uploads";
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        $new_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);
        $target_dir   = $upload_folder . "/" . $new_filename;

        if (move_uploaded_file($file_tmp, $target_dir)) {
            $conn->query("INSERT INTO datasets (title, description, category, file_format, file_path, file_size, row_count) 
                          VALUES ('$title', '$description', '$category', '$file_format', '$new_filename', '$file_size', $row_count)");
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Gagal memindahkan file ke folder uploads.";
        }
    } else {
        $error = "Format file harus .csv, .sql, atau .zip";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Dataset Baru</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
    <h1>Upload Dataset Baru</h1>
    <a href="dashboard.php" style="color:white;">← Kembali ke Dashboard</a>
</header>
<div class="container" style="max-width: 600px;">
    <div class="filter-card">
        <?php if($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Dataset</label>
                <input type="text" name="title" required placeholder="Contoh: Log Sinyal ECG Pasien">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" required placeholder="Contoh: Kesehatan, Keuangan, SIMRS">
            </div>
            <div class="form-group">
                <label>Format Dataset</label>
                <select name="file_format" required>
                    <option value="CSV">CSV</option>
                    <option value="SQL">SQL</option>
                    <option value="CSV & SQL">CSV & SQL</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estimasi Jumlah Baris (Row Count)</label>
                <input type="number" name="row_count" placeholder="Contoh: 10000">
            </div>
            <div class="form-group">
                <label>File Dataset (.csv / .sql / .zip)</label>
                <input type="file" name="dataset_file" required>
            </div>
            <div class="form-group">
                <label>Deskripsi & Metadata Detail</label>
                <textarea name="description" rows="5" required placeholder="Jelaskan atribut/kolom pada dataset, konteks data, dan lisensi..."></textarea>
            </div>
            <button type="submit" class="btn" style="width:100%;">Upload & Publisikan</button>
        </form>
    </div>
</div>
</body>
</html>
