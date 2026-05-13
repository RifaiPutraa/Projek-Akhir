<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Dashboard';

$total_transaksi = $conn->query("SELECT COUNT(*) as c FROM transactions")->fetch_assoc()['c'];
$total_user      = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$transaksi_hari  = $conn->query("SELECT COUNT(*) as c FROM transactions WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$total_pendapatan = $conn->query("SELECT COALESCE(SUM(total_harga),0) as t FROM transactions WHERE status='berhasil'")->fetch_assoc()['t'];

$recent = $conn->query("
    SELECT tr.*, g.nama_game, n.nama_nominal, u.username
    FROM transactions tr
    JOIN games g ON tr.id_game=g.id_game
    JOIN nominals n ON tr.id_nominal=n.id_nominal
    LEFT JOIN users u ON tr.id_user=u.id_user
    ORDER BY tr.created_at DESC LIMIT 10
");

include 'includes/admin_header.php';
?>

<h5 class="fw-bold mb-4">Dashboard</h5>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value"><?= number_format($total_transaksi) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total User</div>
            <div class="stat-value"><?= number_format($total_user) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= number_format($transaksi_hari) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" style="font-size:1.2rem;"><?= rupiah($total_pendapatan) ?></div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="bg-white border rounded p-3">
    <h6 class="fw-semibold mb-3">Transaksi Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>User</th><th>Game</th><th>Nominal</th><th>Metode</th><th>Status</th><th>Total</th><th>Waktu</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($r = $recent->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $r['id_transaksi'] ?></td>
                    <td><?= htmlspecialchars($r['username'] ?? 'Guest') ?></td>
                    <td><?= htmlspecialchars($r['nama_game']) ?></td>
                    <td><?= htmlspecialchars($r['nama_nominal']) ?></td>
                    <td><?= htmlspecialchars($r['metode_pembayaran']) ?></td>
                    <td><span class="badge badge-<?= $r['status'] ?> px-2 py-1"><?= ucfirst($r['status']) ?></span></td>
                    <td><?= rupiah($r['total_harga']) ?></td>
                    <td><?= date('d M, H:i', strtotime($r['created_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
