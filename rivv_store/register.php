<?php
session_start();
require_once 'includes/config.php';

if (isUserLoggedIn()) redirect(SITE_URL);

$page_title = 'Daftar Akun';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$username) $errors[] = 'Username wajib diisi.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok.';

    if (empty($errors)) {
        $u = $conn->real_escape_string($username);
        $e = $conn->real_escape_string($email);
        // Cek duplikat
        $exists = $conn->query("SELECT id_user FROM users WHERE username='$u' OR email='$e'")->num_rows;
        if ($exists > 0) {
            $errors[] = 'Username atau email sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (username, email, password) VALUES ('$u', '$e', '$hash')");
            setFlash('success', 'Akun berhasil dibuat! Silakan login.');
            redirect(SITE_URL . '/login.php');
        }
    }
}

include 'includes/header.php';
?>

<div class="login-wrapper">
    <div class="px-3 w-100">
        <div class="login-card mx-auto">
            <h5 class="fw-bold text-center mb-1">Daftar Akun</h5>
            <p class="text-muted text-center small mb-4">Buat akun RIVV STORE kamu</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 small">
                    <?php foreach ($errors as $e): ?><div><?= $e ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-medium">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-medium">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>
            </form>
            <hr class="my-3">
            <p class="text-center text-muted small mb-0">
                Sudah punya akun? <a href="login.php" class="text-decoration-none">Login</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
