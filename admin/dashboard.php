<?php
require_once '../config.php';

// Validasi session login admin
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

// Proses Hapus Dataset jika parameter 'delete' diklik
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Ambil nama file untuk dihapus fisik dari folder uploads
    $res = $conn->query("SELECT file_path FROM datasets WHERE id = $id");
    if ($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        $file_target = "../uploads/" . $data['file_path'];
        if (file_exists($file_target)) {
            unlink($file_target);
        }
        // Hapus record dari database
        $conn->query("DELETE FROM datasets WHERE id = $id");
    }
    header("Location: dashboard.php");
    exit;
}

// Ambil semua data dataset
$result = $conn->query("SELECT * FROM datasets ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Repositori Latihan Dataset FKK</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        table.admin-table th, table.admin-table td {
            padding: 0.8rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }
        table.admin-table th {
            background: #f1f3f5;
            font-weight: 600;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8rem;
        }
        .btn-danger:hover {
            background: #bb2d3b;
        }
    </style>
</head>
<body>

<header>
    <h1>Admin - Repositori Latihan Dataset FKK</h1>
    <div>
        <a href="../index.php" target="_blank" style="margin-right: 15px;">Lihat Website Utama ↗</a>
        <a href="logout.php" style="color: #ffcccc;">Logout</a>
    </div>
</header>

<div class="container" style="max-width: 1200px;">
    
    <div class="admin-header">
        <h2>Kelola Dataset</h2>
        <a href="upload.php" class="btn">+ Upload Dataset Baru</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Dataset</th>
                <th>Kategori</th>
                <th>Format</th>
                <th>Ukuran</th>
                <th>Download</th>
                <th>Rating</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><b><?php echo htmlspecialchars($row['title']); ?></b></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><span class="badge badge-format"><?php echo $row['file_format']; ?></span></td>
                        <td><?php echo $row['file_size']; ?></td>
                        <td><?php echo $row['downloads']; ?>x</td>
                        <td>⭐ <?php echo ($row['rating_count'] > 0) ? number_format($row['rating_sum'] / $row['rating_count'], 1) : '0.0'; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="dashboard.php?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dataset ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 2rem; color: #6c757d;">Belum ada dataset yang di-upload.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>