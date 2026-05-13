<?php
session_start();
require_once 'includes/config.php';

if (isUserLoggedIn()) redirect(SITE_URL);

$page_title = 'Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $u = $conn->real_escape_string($username);
        $row = $conn->query("SELECT * FROM users WHERE username='$u' OR email='$u' LIMIT 1")->fetch_assoc();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            setFlash('success', 'Login berhasil! Selamat datang, ' . $row['username'] . '.');
            redirect(SITE_URL);
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Semua field wajib diisi.';
    }
}

include 'includes/header.php';
?>

<div class="login-wrapper">
    <div class="px-3 w-100">
        <div class="login-card mx-auto">
            <h5 class="fw-bold text-center mb-1">Login</h5>
            <p class="text-muted text-center small mb-4">Masuk ke akun RIVV STORE kamu</p>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-sm py-2 small"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-medium">Username / Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username atau email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
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
                    <button type="submit" class="btn btn-primary">Masuk</button>
                </div>
            </form>

            <hr class="my-3">
            <p class="text-center text-muted small mb-0">
                Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
