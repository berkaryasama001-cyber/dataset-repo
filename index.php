<?php
require_once 'config.php';

// Ambil parameter filter dari GET
$search   = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$format   = isset($_GET['format']) ? sanitize($_GET['format']) : '';

// Build Query SQL
$query = "SELECT * FROM datasets WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}
if (!empty($category)) {
    $query .= " AND category = '$category'";
}
if (!empty($format)) {
    $query .= " AND file_format = '$format'";
}

$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);

// Ambil daftar kategori unik untuk dropdown filter
$cat_result = $conn->query("SELECT DISTINCT category FROM datasets");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Repositori Latihan Dataset FKK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1><a href="index.php">Repositori Latihan Dataset FKK</a></h1>
</header>

<div class="container">
    <!-- Form Filter & Pencarian -->
    <div class="filter-card">
        <form method="GET" action="index.php" class="filter-form">
            <input type="text" name="search" placeholder="Cari nama atau deskripsi dataset..." value="<?php echo htmlspecialchars($search); ?>">
            
            <select name="category">
                <option value="">-- Semua Kategori --</option>
                <?php while ($cat = $cat_result->fetch_assoc()): ?>
                    <option value="<?php echo $cat['category']; ?>" <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                        <?php echo $cat['category']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="format">
                <option value="">-- Semua Format --</option>
                <option value="CSV" <?php echo ($format == 'CSV') ? 'selected' : ''; ?>>CSV</option>
                <option value="SQL" <?php echo ($format == 'SQL') ? 'selected' : ''; ?>>SQL</option>
                <option value="CSV & SQL" <?php echo ($format == 'CSV & SQL') ? 'selected' : ''; ?>>CSV & SQL</option>
            </select>

            <button type="submit">Filter Dataset</button>
            <a href="index.php" class="btn btn-secondary" style="line-height: 2.2rem;">Reset</a>
        </form>
    </div>

    <!-- Grid List Dataset -->
    <div class="dataset-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div>
                        <span class="badge"><?php echo htmlspecialchars($row['category']); ?></span>
                        <span class="badge badge-format"><?php echo htmlspecialchars($row['file_format']); ?></span>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($row['description'], 0, 110)) . '...'; ?></p>
                    </div>
                    <div>
                        <div class="card-meta">
                            ⭐ <?php echo get_avg_rating($row['rating_sum'], $row['rating_count']); ?><br>
                            📥 <?php echo $row['downloads']; ?>x Diunduh | 📊 <?php echo number_format($row['row_count']); ?> Baris
                        </div>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn" style="width:100%; display:block;">Lihat Detail & Download</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada dataset yang ditemukan dengan kriteria filter tersebut.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>