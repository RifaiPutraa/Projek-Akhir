<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Kelola User';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id_user'];
    $conn->query("DELETE FROM users WHERE id_user=$id");
    setFlash('warning', 'User dihapus.');
    redirect(SITE_URL . '/admin/user.php');
}

$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';
$where = $search ? "WHERE username LIKE '%$search%' OR email LIKE '%$search%'" : '';
$users = $conn->query("SELECT * FROM users $where ORDER BY created_at DESC");

include 'includes/admin_header.php';
?>

<h5 class="fw-bold mb-3">Kelola User</h5>

<form method="GET" class="mb-3">
    <div class="d-flex gap-2" style="max-width:400px;">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari username / email..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($search): ?><a href="user.php" class="btn btn-light btn-sm">Reset</a><?php endif; ?>
    </div>
</form>

<div class="bg-white border rounded p-3">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr><th>ID</th><th>Username</th><th>Email</th><th>Bergabung</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if ($users->num_rows === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada user.</td></tr>
            <?php endif; ?>
            <?php while ($u = $users->fetch_assoc()): ?>
                <tr>
                    <td>#<?= str_pad($u['id_user'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_user" value="<?= $u['id_user'] ?>">
                            <button class="btn btn-outline-danger btn-sm" style="font-size:0.78rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
