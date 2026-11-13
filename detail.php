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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title']); ?> - Detail Dataset</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1><a href="index.php">Detail Dataset</a></h1>
    <a href="index.php" style="color:white;">← Kembali ke Katalog</a>
</header>

<div class="container" style="max-width: 800px;">
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
            <tr><td style="padding: 0.4rem 0;"><b>Format File</b></td><td>: <?php echo $data['file_format']; ?></td></tr>
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