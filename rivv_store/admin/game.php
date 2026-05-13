<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Kelola Game';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $nama   = $conn->real_escape_string(trim($_POST['nama_game']));
        $desc   = $conn->real_escape_string(trim($_POST['deskripsi']));
        $populer = isset($_POST['is_populer']) ? 1 : 0;
        $gambar = $conn->real_escape_string(trim($_POST['gambar']));
        $banner = $conn->real_escape_string(trim($_POST['banner']));
        $status = $_POST['status'] === 'aktif' ? 'aktif' : 'nonaktif';

        if ($action === 'add') {
            $conn->query("INSERT INTO games (nama_game, gambar, banner, deskripsi, is_populer, status) VALUES ('$nama','$gambar','$banner','$desc',$populer,'$status')");
            setFlash('success', 'Game berhasil ditambahkan.');
        } else {
            $id = (int)$_POST['id_game'];
            $conn->query("UPDATE games SET nama_game='$nama', gambar='$gambar', banner='$banner', deskripsi='$desc', is_populer=$populer, status='$status' WHERE id_game=$id");
            setFlash('success', 'Game berhasil diperbarui.');
        }
        redirect(SITE_URL . '/admin/game.php');
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id_game'];
        $conn->query("DELETE FROM games WHERE id_game=$id");
        setFlash('warning', 'Game berhasil dihapus.');
        redirect(SITE_URL . '/admin/game.php');
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';
$where = $search ? "WHERE nama_game LIKE '%$search%'" : '';
$games = $conn->query("SELECT * FROM games $where ORDER BY id_game ASC");

// Edit mode
$edit_game = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $edit_game = $conn->query("SELECT * FROM games WHERE id_game=$eid")->fetch_assoc();
}

include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Game</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gameModal">
        <i class="bi bi-plus"></i> Tambah Game
    </button>
</div>

<!-- Search -->
<form method="GET" class="mb-3">
    <div class="d-flex gap-2" style="max-width:400px;">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari game..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($search): ?><a href="game.php" class="btn btn-sm btn-light">Reset</a><?php endif; ?>
    </div>
</form>

<div class="row g-3">
    <?php while ($game = $games->fetch_assoc()):
        $is_ph = !file_exists(__DIR__ . '/../assets/img/games/' . $game['gambar']);
    ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card border h-100">
            <div style="height:140px;overflow:hidden;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                <?php if ($is_ph): ?>
                    <div class="text-center text-muted" style="font-size:0.7rem;padding:8px;">
                        <i class="bi bi-controller" style="font-size:2rem;"></i><br>
                        Taruh <code><?= $game['gambar'] ?></code><br>di assets/img/games/
                    </div>
                <?php else: ?>
                    <img src="<?= gameImg($game['gambar']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
                <?php endif; ?>
            </div>
            <div class="card-body p-3">
                <h6 class="fw-semibold mb-1" style="font-size:0.9rem;"><?= htmlspecialchars($game['nama_game']) ?></h6>
                <div class="d-flex gap-1 mb-2">
                    <span class="badge bg-<?= $game['status']==='aktif' ? 'success' : 'secondary' ?>-subtle text-<?= $game['status']==='aktif' ? 'success' : 'secondary' ?> border" style="font-size:0.7rem;">
                        <?= ucfirst($game['status']) ?>
                    </span>
                    <?php if ($game['is_populer']): ?>
                        <span class="badge bg-warning-subtle text-warning border" style="font-size:0.7rem;"><i class="bi bi-fire"></i> Populer</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-1">
                    <a href="game.php?edit=<?= $game['id_game'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" onsubmit="return confirm('Hapus game ini?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_game" value="<?= $game['id_game'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="gameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold"><?= $edit_game ? 'Edit Game' : 'Tambah Game' ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $edit_game ? 'edit' : 'add' ?>">
                    <?php if ($edit_game): ?>
                        <input type="hidden" name="id_game" value="<?= $edit_game['id_game'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama Game *</label>
                        <input type="text" name="nama_game" class="form-control form-control-sm" required value="<?= htmlspecialchars($edit_game['nama_game'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama File Gambar
                            <span class="text-muted">(contoh: ml.png)</span>
                        </label>
                        <input type="text" name="gambar" class="form-control form-control-sm" placeholder="nama_file.png" value="<?= htmlspecialchars($edit_game['gambar'] ?? '') ?>">
                        <div class="form-text">Taruh file PNG di <code>assets/img/games/</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama File Banner
                            <span class="text-muted">(contoh: banner_ml.png)</span>
                        </label>
                        <input type="text" name="banner" class="form-control form-control-sm" placeholder="banner_nama.png" value="<?= htmlspecialchars($edit_game['banner'] ?? '') ?>">
                        <div class="form-text">Taruh file PNG di <code>assets/img/banners/</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($edit_game['deskripsi'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="aktif" <?= ($edit_game['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($edit_game['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_populer" id="is_populer" <?= ($edit_game['is_populer'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="is_populer">Tampilkan di Game Populer</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><?= $edit_game ? 'Simpan' : 'Tambah' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
<?php if ($edit_game): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('gameModal')).show();
    });
</script>
<?php endif; ?>
