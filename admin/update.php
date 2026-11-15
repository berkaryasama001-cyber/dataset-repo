<?php
require_once '../config.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

// Ambil data dataset yang akan diedit
$res = $conn->query("SELECT * FROM datasets WHERE id = $id");
if ($res->num_rows == 0) {
    header("Location: dashboard.php");
    exit;
}
$data = $res->fetch_assoc();

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = sanitize($_POST['title']);
    $category    = sanitize($_POST['category']);
    $file_format = sanitize($_POST['file_format']);
    $row_count   = intval($_POST['row_count']);
    $description = sanitize($_POST['description']);

    $new_filename = $data['file_path'];
    $file_size    = $data['file_size'];
    $upload_ok    = true;

    // Cek apakah admin mengunggah file pengganti baru
    if (isset($_FILES['dataset_file']) && $_FILES['dataset_file']['error'] == UPLOAD_ERR_OK) {
        $file_name       = $_FILES['dataset_file']['name'];
        $file_tmp        = $_FILES['dataset_file']['tmp_name'];
        $file_size_bytes = $_FILES['dataset_file']['size'];

        $ext     = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = array('csv', 'sql', 'zip');

        if (in_array($ext, $allowed)) {
            $upload_folder = "../uploads";
            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }

            $generated_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);
            $target_dir     = $upload_folder . "/" . $generated_name;

            if (move_uploaded_file($file_tmp, $target_dir)) {
                // Hapus file lama dari folder uploads jika ada
                $old_file = $upload_folder . "/" . $data['file_path'];
                if (!empty($data['file_path']) && file_exists($old_file)) {
                    unlink($old_file);
                }

                $new_filename = $generated_name;
                $file_size    = round($file_size_bytes / 1024 / 1024, 2) . ' MB';
                if ($file_size_bytes < 1048576) {
                    $file_size = round($file_size_bytes / 1024, 2) . ' KB';
                }
            } else {
                $error     = "Gagal memindahkan file baru ke folder uploads.";
                $upload_ok = false;
            }
        } else {
            $error     = "Format file baru harus .csv, .sql, atau .zip";
            $upload_ok = false;
        }
    }

    if ($upload_ok) {
        $sql = "UPDATE datasets SET 
                    title = '$title',
                    description = '$description',
                    category = '$category',
                    file_format = '$file_format',
                    file_path = '$new_filename',
                    file_size = '$file_size',
                    row_count = $row_count
                WHERE id = $id";

        if ($conn->query($sql)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Gagal memperbarui data ke database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Dataset</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
    <h1>Edit Dataset</h1>
    <a href="dashboard.php" style="color:white;">← Kembali ke Dashboard</a>
</header>
<div class="container" style="max-width: 600px;">
    <div class="filter-card">
        <?php if($error): ?><p style="color:red; margin-bottom:1rem;"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Dataset</label>
                <input type="text" name="title" required value="<?php echo htmlspecialchars($data['title']); ?>">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" required value="<?php echo htmlspecialchars($data['category']); ?>">
            </div>
            <div class="form-group">
                <label>Format Dataset</label>
                <select name="file_format" required>
                    <option value="CSV" <?php echo ($data['file_format'] == 'CSV') ? 'selected' : ''; ?>>CSV</option>
                    <option value="SQL" <?php echo ($data['file_format'] == 'SQL') ? 'selected' : ''; ?>>SQL</option>
                    <option value="CSV & SQL" <?php echo ($data['file_format'] == 'CSV & SQL') ? 'selected' : ''; ?>>CSV & SQL</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estimasi Jumlah Baris (Row Count)</label>
                <input type="number" name="row_count" value="<?php echo intval($data['row_count']); ?>">
            </div>
            <div class="form-group">
                <label>Ganti File Dataset (Opsional)</label>
                <input type="file" name="dataset_file">
                <small style="color: #6c757d; display: block; margin-top: 4px;">
                    File saat ini: <b><?php echo htmlspecialchars($data['file_path']); ?></b> (<?php echo $data['file_size']; ?>). Kosongkan jika tidak ingin mengganti file.
                </small>
            </div>
            <div class="form-group">
                <label>Deskripsi & Metadata Detail</label>
                <textarea name="description" rows="5" required><?php echo htmlspecialchars($data['description']); ?></textarea>
            </div>
            <button type="submit" class="btn" style="width:100%;">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>