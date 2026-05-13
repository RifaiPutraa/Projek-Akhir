<?php
session_start();
require_once 'includes/config.php';

$t = isset($_GET['t']) ? (int)$_GET['t'] : 0;
$trans = $conn->query("
    SELECT tr.*, g.nama_game, g.gambar, n.nama_nominal, n.harga
    FROM transactions tr
    JOIN games g ON tr.id_game = g.id_game
    JOIN nominals n ON tr.id_nominal = n.id_nominal
    WHERE tr.id_transaksi = $t
")->fetch_assoc();

if (!$trans) { redirect(SITE_URL); }

$page_title = 'Transaksi Berhasil';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="success-card">
        <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
        <h5 class="fw-bold mb-1">ORDER ITEM BERHASIL</h5>
        <p class="text-muted small mb-3">Diamond sudah ditambahkan ke akun game Anda</p>

        <!-- Ringkasan -->
        <div class="border rounded p-3 text-start mb-3">
            <p class="fw-semibold small mb-2">Ringkasan Pesanan :</p>
            <div class="order-summary-row">
                <span class="text-muted">Game</span>
                <span class="fw-semibold"><?= htmlspecialchars($trans['nama_game']) ?></span>
            </div>
            <div class="order-summary-row">
                <span class="text-muted">Nominal</span>
                <span class="fw-semibold"><?= htmlspecialchars($trans['nama_nominal']) ?></span>
            </div>
            <div class="order-summary-row">
                <span class="text-muted">Metode Pembayaran</span>
                <span class="fw-semibold"><?= htmlspecialchars($trans['metode_pembayaran']) ?></span>
            </div>
            <div class="order-summary-row">
                <span class="text-muted">ID User</span>
                <span class="fw-semibold"><?= htmlspecialchars($trans['user_game_id']) ?></span>
            </div>
            <?php if ($trans['server_id']): ?>
            <div class="order-summary-row">
                <span class="text-muted">Server ID</span>
                <span class="fw-semibold"><?= htmlspecialchars($trans['server_id']) ?></span>
            </div>
            <?php endif; ?>
            <div class="order-summary-row">
                <span class="text-muted">Status Pembayaran</span>
                <span class="badge badge-berhasil px-2 py-1">Berhasil</span>
            </div>
            <div class="order-summary-row">
                <span class="text-muted fw-semibold">Total Pembayaran</span>
                <span class="fw-bold text-primary"><?= rupiah($trans['total_harga']) ?></span>
            </div>
        </div>

        <a href="<?= SITE_URL ?>" class="btn btn-primary w-100">
            <i class="bi bi-house me-1"></i> Lakukan Pembelian Lainnya
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
