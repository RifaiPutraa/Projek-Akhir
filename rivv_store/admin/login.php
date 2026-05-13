<?php
session_start();
require_once '../includes/config.php';

if (isAdminLoggedIn()) redirect(SITE_URL . '/admin/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $u = $conn->real_escape_string($username);
        $row = $conn->query("SELECT * FROM admin WHERE username='$u' OR email='$u' LIMIT 1")->fetch_assoc();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id_admin'];
            $_SESSION['admin_username'] = $row['username'];
            redirect(SITE_URL . '/admin/dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Semua field wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="bg-light">

<div class="login-wrapper">
    <div class="px-3 w-100">
        <div class="login-card mx-auto">
            <!-- Logo -->
            <div class="text-center mb-3">
                <?php if (file_exists(__DIR__ . '/../assets/img/logo.png')): ?>
                    <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" height="45" class="mb-2">
                <?php else: ?>
                    <h5 class="fw-bold mb-0"><span class="text-primary">RIVV</span>STORE</h5>
                <?php endif; ?>
            </div>
            <h6 class="fw-bold text-center mb-1">Login Admin</h6>
            <p class="text-muted text-center small mb-4">Masuk untuk mengakses dashboard</p>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-medium">Email / Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Email atau username admin" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            <hr class="my-3">
            <p class="text-center text-muted small mb-0">
                <a href="<?= SITE_URL ?>" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
