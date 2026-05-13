<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Kelola Transaksi';

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

$where = "WHERE 1=1";
if ($status_filter && in_array($status_filter, ['pending','berhasil','gagal'])) {
    $where .= " AND tr.status='$status_filter'";
}
if ($search) {
    $where .= " AND (u.username LIKE '%$search%' OR g.nama_game LIKE '%$search%' OR tr.user_game_id LIKE '%$search%')";
}

$transaksi = $conn->query("
    SELECT tr.*, g.nama_game, n.nama_nominal, u.username
    FROM transactions tr
    JOIN games g ON tr.id_game=g.id_game
    JOIN nominals n ON tr.id_nominal=n.id_nominal
    LEFT JOIN users u ON tr.id_user=u.id_user
    $where ORDER BY tr.created_at DESC
");

include 'includes/admin_header.php';
?>

<h5 class="fw-bold mb-3">Kelola Transaksi</h5>

<!-- Filter & Search -->
<form method="GET" class="mb-3">
    <div class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari user / game / ID..." style="max-width:240px;" value="<?= htmlspecialchars($search) ?>">
        <select name="status" class="form-select form-select-sm" style="max-width:160px;">
            <option value="">Semua Status</option>
            <option value="pending"  <?= $status_filter==='pending'  ? 'selected' : '' ?>>Pending</option>
            <option value="berhasil" <?= $status_filter==='berhasil' ? 'selected' : '' ?>>Berhasil</option>
            <option value="gagal"    <?= $status_filter==='gagal'    ? 'selected' : '' ?>>Gagal</option>
        </select>
        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-filter"></i> Filter</button>
        <a href="transaksi.php" class="btn btn-light btn-sm">Reset</a>
    </div>
</form>

<div class="bg-white border rounded p-3">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size:0.84rem;">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>User</th><th>Game</th><th>Nominal</th><th>User Game ID</th><th>Metode</th><th>Status</th><th>Total</th><th>Waktu</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($transaksi->num_rows === 0): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada transaksi.</td></tr>
            <?php endif; ?>
            <?php while ($r = $transaksi->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $r['id_transaksi'] ?></td>
                    <td><?= htmlspecialchars($r['username'] ?? 'Guest') ?></td>
                    <td><?= htmlspecialchars($r['nama_game']) ?></td>
                    <td><?= htmlspecialchars($r['nama_nominal']) ?></td>
                    <td><?= htmlspecialchars($r['user_game_id']) ?></td>
                    <td><?= htmlspecialchars($r['metode_pembayaran']) ?></td>
                    <td><span class="badge badge-<?= $r['status'] ?> px-2 py-1"><?= ucfirst($r['status']) ?></span></td>
                    <td><?= rupiah($r['total_harga']) ?></td>
                    <td><?= date('d/m/y H:i', strtotime($r['created_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
