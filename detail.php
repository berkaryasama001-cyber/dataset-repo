<?php
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Proses Download File
if (isset($_GET['action']) && $_GET['action'] == 'download') {
    $res = $conn->query("SELECT * FROM datasets WHERE id = $id");
    if ($res->num_rows > 0) {
        $dataset = $res->fetch_assoc();
        
        // Update statistik download
        $conn->query("UPDATE datasets SET downloads = downloads + 1 WHERE id = $id");
        
        $file_path = "uploads/" . $dataset['file_path'];
        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "<script>alert('File tidak ditemukan di server.'); window.location.href='detail.php?id=$id';</script>";
            exit;
        }
    }
}

// Fitur submit rating
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
    $rating = intval($_POST['rating']);
    if ($rating >= 1 && $rating <= 5) {
        $conn->query("UPDATE datasets SET rating_sum = rating_sum + $rating, rating_count = rating_count + 1 WHERE id = $id");
        $message = "Terima kasih atas rating Anda!";
    }
}

// Ambil data detail dataset
$res = $conn->query("SELECT * FROM datasets WHERE id = $id");
if ($res->num_rows == 0) {
    header("Location: index.php");
    exit;
}
$data = $res->fetch_assoc();

// Logika Preview Data CSV (Maksimal 5 baris sampel)
$csv_headers = [];
$csv_rows    = [];
$file_full_path = "uploads/" . $data['file_path'];
$file_ext       = strtolower(pathinfo($data['file_path'], PATHINFO_EXTENSION));

if ($file_ext === 'csv' && file_exists($file_full_path)) {
    if (($handle = fopen($file_full_path, "r")) !== FALSE) {
        // Deteksi otomatis delimiter (koma ',' atau titik koma ';') dari baris pertama
        $first_line = fgets($handle);
        $delimiter  = (substr_count($first_line, ';') > substr_count($first_line, ',')) ? ';' : ',';
        rewind($handle);

        // Ambil baris header
        $header_data = fgetcsv($handle, 0, $delimiter);
        if ($header_data !== FALSE) {
            // Bersihkan karakter BOM UTF-8 jika file berasal dari Excel
            $header_data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header_data[0]);
            $csv_headers = $header_data;

            // Ambil maksimal 5 baris data sampel
            $max_preview = 5;
            $count = 0;
            while (($row_data = fgetcsv($handle, 0, $delimiter)) !== FALSE && $count < $max_preview) {
                $csv_rows[] = $row_data;
                $count++;
            }
        }
        fclose($handle);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title']); ?> - Detail Dataset</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .preview-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-top: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        table.preview-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        table.preview-table th {
            background-color: #f8f9fa;
            color: #212529;
            font-weight: 600;
            text-align: left;
            padding: 0.65rem 0.9rem;
            border-bottom: 2px solid #dee2e6;
        }
        table.preview-table td {
            padding: 0.55rem 0.9rem;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        table.preview-table tr:nth-child(even) {
            background-color: #fcfcfd;
        }
        table.preview-table tr:hover {
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>

<header>
    <h1><a href="index.php">Detail Dataset</a></h1>
    <a href="index.php" style="color:white;">← Kembali ke Katalog</a>
</header>

<div class="container" style="max-width: 900px;">
    <?php if ($message): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="filter-card">
        <span class="badge"><?php echo htmlspecialchars($data['category']); ?></span>
        <span class="badge badge-format"><?php echo htmlspecialchars($data['file_format']); ?></span>
        <h2 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($data['title']); ?></h2>
        
        <p style="margin-top: 1rem; white-space: pre-line;"><?php echo htmlspecialchars($data['description']); ?></p>

        <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #eee;">

        <h3>Metadata Dataset</h3>
        <table style="width: 100%; margin-top: 0.5rem; border-collapse: collapse;">
            <tr><td style="padding: 0.4rem 0; width: 200px;"><b>Format File</b></td><td>: <?php echo $data['file_format']; ?></td></tr>
            <tr><td style="padding: 0.4rem 0;"><b>Ukuran File</b></td><td>: <?php echo $data['file_size']; ?></td></tr>
            <tr><td style="padding: 0.4rem 0;"><b>Jumlah Baris/Data</b></td><td>: <?php echo number_format($data['row_count']); ?> baris</td></tr>
            <tr><td style="padding: 0.4rem 0;"><b>Total Diunduh</b></td><td>: <?php echo $data['downloads']; ?> kali</td></tr>
            <tr><td style="padding: 0.4rem 0;"><b>Rating Saat Ini</b></td><td>: ⭐ <?php echo get_avg_rating($data['rating_sum'], $data['rating_count']); ?></td></tr>
            <tr><td style="padding: 0.4rem 0;"><b>Tanggal Diupload</b></td><td>: <?php echo date("d M Y", strtotime($data['created_at'])); ?></td></tr>
        </table>

        <div style="margin-top: 2rem;">
            <a href="detail.php?id=<?php echo $id; ?>&action=download" class="btn" style="padding: 0.8rem 1.5rem; font-size: 1rem;">Download Dataset</a>
        </div>
    </div>

    <!-- Fitur Preview Sampel Data CSV -->
    <?php if (!empty($csv_headers)): ?>
    <div class="filter-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Preview Sampel Data (5 Baris Pertama)</h3>
            <span style="font-size: 0.8rem; color: #6c757d;">Total Kolom: <b><?php echo count($csv_headers); ?></b></span>
        </div>
        <div class="preview-wrapper">
            <table class="preview-table">
                <thead>
                    <tr>
                        <?php foreach ($csv_headers as $col_name): ?>
                            <th><?php echo htmlspecialchars($col_name); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($csv_rows)): ?>
                        <?php foreach ($csv_rows as $row): ?>
                            <tr>
                                <?php foreach ($csv_headers as $index => $col_name): ?>
                                    <td><?php echo isset($row[$index]) ? htmlspecialchars($row[$index]) : ''; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo count($csv_headers); ?>" style="text-align: center; color: #6c757d;">File CSV hanya berisi header tanpa baris data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Beri Rating -->
    <div class="filter-card">
        <h3>Beri Rating untuk Dataset ini</h3>
        <form method="POST" style="margin-top: 1rem; display: flex; gap: 1rem; align-items: center;">
            <select name="rating" required style="padding: 0.5rem;">
                <option value="">-- Pilih Rating --</option>
                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                <option value="2">⭐⭐ (2 - Kurang)</option>
                <option value="1">⭐ (1 - Buruk)</option>
            </select>
            <button type="submit" name="submit_rating" class="btn btn-secondary">Kirim Rating</button>
        </form>
    </div>
</div>

</body>
</html>
