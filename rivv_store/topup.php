<?php
session_start();
require_once 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$game = $conn->query("SELECT * FROM games WHERE id_game=$id AND status='aktif'")->fetch_assoc();
if (!$game) { redirect(SITE_URL . '/games.php'); }

$page_title = 'Top Up ' . $game['nama_game'];
$nominals = $conn->query("SELECT * FROM nominals WHERE id_game=$id AND status='aktif' ORDER BY harga ASC");
$metodes  = $conn->query("SELECT * FROM metode_pembayaran WHERE status='aktif'");

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_nominal = (int)$_POST['id_nominal'];
    $user_game_id = trim($_POST['user_game_id']);
    $server_id = trim($_POST['server_id'] ?? '');
    $metode = trim($_POST['metode_pembayaran']);

    // Validasi
    $errors = [];
    if (!$user_game_id) $errors[] = 'User ID wajib diisi.';
    if (!$id_nominal)   $errors[] = 'Pilih nominal terlebih dahulu.';
    if (!$metode)       $errors[] = 'Pilih metode pembayaran.';

    if (empty($errors)) {
        $nominal = $conn->query("SELECT * FROM nominals WHERE id_nominal=$id_nominal AND id_game=$id")->fetch_assoc();
        if (!$nominal) { $errors[] = 'Nominal tidak valid.'; }
        else {
            $id_user = isUserLoggedIn() ? (int)$_SESSION['user_id'] : 'NULL';
            $user_game_id_esc = $conn->real_escape_string($user_game_id);
            $server_id_esc = $conn->real_escape_string($server_id);
            $metode_esc = $conn->real_escape_string($metode);
            $total = $nominal['harga'];

            $sql = "INSERT INTO transactions (id_user, id_game, id_nominal, user_game_id, server_id, metode_pembayaran, status, total_harga)
                    VALUES ($id_user, $id, $id_nominal, '$user_game_id_esc', " . ($server_id ? "'$server_id_esc'" : "NULL") . ", '$metode_esc', 'berhasil', $total)";
            $conn->query($sql);
            $id_trans = $conn->insert_id;
            redirect(SITE_URL . "/success.php?t=$id_trans");
        }
    }
}

include 'includes/header.php';
?>

<!-- Header game -->
<div class="topup-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <?php
            $is_ph = !file_exists(__DIR__ . '/assets/img/games/' . $game['gambar']);
            if ($is_ph): ?>
                <div style="width:70px;height:70px;background:#343a40;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px solid #495057;font-size:0.6rem;color:#adb5bd;text-align:center;padding:4px;">
                    <i class="bi bi-controller" style="font-size:1.5rem;"></i>
                    <span><?= $game['gambar'] ?></span>
                </div>
            <?php else: ?>
                <img src="<?= gameImg($game['gambar']) ?>" alt="<?= htmlspecialchars($game['nama_game']) ?>" class="game-logo">
            <?php endif; ?>
            <div>
                <h5 class="mb-0 fw-bold"><?= htmlspecialchars($game['nama_game']) ?></h5>
                <span class="badge bg-success small">Pembayaran yang aman</span>
            </div>
        </div>
    </div>
</div>

<div class="container" style="max-width: 720px;">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger auto-hide">
            <?php foreach ($errors as $e): ?><div><?= $e ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="topupForm">
        <input type="hidden" name="id_nominal" id="id_nominal" value="">
        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">

        <!-- Step 1: Masukkan ID -->
        <div class="topup-box">
            <h6><span class="step-badge">1</span> Masukkan ID</h6>
            <div class="row g-2">
                <div class="col-sm-7">
                    <label class="form-label small">User ID <span class="text-danger">*</span></label>
                    <input type="text" name="user_game_id" class="form-control form-control-sm" placeholder="Masukkan User ID" required value="<?= htmlspecialchars($_POST['user_game_id'] ?? '') ?>">
                </div>
                <div class="col-sm-5">
                    <label class="form-label small">Server ID (jika ada)</label>
                    <input type="text" name="server_id" class="form-control form-control-sm" placeholder="Server ID" value="<?= htmlspecialchars($_POST['server_id'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Step 2: Pilih Nominal -->
        <div class="topup-box">
            <h6><span class="step-badge">2</span> Pilih Nominal</h6>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-2">
                <?php
                $nominals->data_seek(0);
                while ($nom = $nominals->fetch_assoc()):
                    $selected_nom = isset($_POST['id_nominal']) && (int)$_POST['id_nominal'] === $nom['id_nominal'];
                ?>
                <div class="col">
                    <div class="nominal-card <?= $selected_nom ? 'selected' : '' ?>" data-id="<?= $nom['id_nominal'] ?>">
                        <div class="diamond-icon">
                            <!-- Taruh icon diamond di assets/img/icons/diamond.png -->
                            <?php if (file_exists(__DIR__ . '/assets/img/icons/diamond.png')): ?>
                                <img src="<?= SITE_URL ?>/assets/img/icons/diamond.png" width="20" height="20" alt="diamond">
                            <?php else: ?>
                                <i class="bi bi-gem"></i>
                            <?php endif; ?>
                        </div>
                        <div class="nominal-name"><?= htmlspecialchars($nom['nama_nominal']) ?></div>
                        <?php if ($nom['bonus'] > 0): ?>
                            <div class="nominal-bonus">+<?= $nom['bonus'] ?> bonus</div>
                        <?php endif; ?>
                        <div class="nominal-price"><?= rupiah($nom['harga']) ?></div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Step 3: Metode Pembayaran -->
        <div class="topup-box">
            <h6><span class="step-badge">3</span> Pilih Metode Pembayaran</h6>
            <div class="row row-cols-3 row-cols-sm-5 g-2">
                <?php while ($met = $metodes->fetch_assoc()):
                    $met_logo_path = __DIR__ . '/assets/img/icons/' . $met['logo'];
                ?>
                <div class="col">
                    <div class="metode-card" data-nama="<?= htmlspecialchars($met['nama']) ?>">
                        <?php if (file_exists($met_logo_path)): ?>
                            <img src="<?= SITE_URL ?>/assets/img/icons/<?= $met['logo'] ?>" alt="<?= $met['nama'] ?>">
                        <?php else: ?>
                            <i class="bi bi-credit-card" style="font-size:1.5rem;color:#6c757d;"></i>
                        <?php endif; ?>
                        <div class="metode-name"><?= htmlspecialchars($met['nama']) ?></div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Tombol Beli -->
        <div class="d-grid mb-5">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-cart-check me-1"></i> Beli Sekarang
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Validasi sebelum submit
document.getElementById('topupForm').addEventListener('submit', function(e) {
    const nominal = document.getElementById('id_nominal').value;
    const metode  = document.getElementById('metode_pembayaran').value;
    if (!nominal) { alert('Silakan pilih nominal terlebih dahulu.'); e.preventDefault(); return; }
    if (!metode)  { alert('Silakan pilih metode pembayaran.'); e.preventDefault(); return; }
});
</script>
