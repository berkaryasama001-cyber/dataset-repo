<?php
require_once '../config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Login bawaan cepat (bisa diatur manual atau via DB)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Developer</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container" style="max-width:400px; margin-top: 5rem;">
    <div class="filter-card">
        <h2>Login Developer</h2>
        <?php if($error): ?><p style="color:red; font-size:0.9rem;"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width:100%;">Login</button>
        </form>
    </div>
</div>
</body>
</html>