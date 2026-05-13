<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Kelola Nominal';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add' || $action === 'edit') {
        $id_game = (int)$_POST['id_game'];
        $nama    = $conn->real_escape_string(trim($_POST['nama_nominal']));
        $jumlah  = (int)$_POST['jumlah'];
        $bonus   = (int)($_POST['bonus'] ?? 0);
        $harga   = (float)$_POST['harga'];
        $status  = $_POST['status'];
        if ($action === 'add') {
            $conn->query("INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga, status) VALUES ($id_game,'$nama',$jumlah,$bonus,$harga,'$status')");
            setFlash('success', 'Nominal ditambahkan.');
        } else {
            $id = (int)$_POST['id_nominal'];
            $conn->query("UPDATE nominals SET id_game=$id_game, nama_nominal='$nama', jumlah=$jumlah, bonus=$bonus, harga=$harga, status='$status' WHERE id_nominal=$id");
            setFlash('success', 'Nominal diperbarui.');
        }
        redirect(SITE_URL . '/admin/nominal.php');
    }
    if ($action === 'delete') {
        $id = (int)$_POST['id_nominal'];
        $conn->query("DELETE FROM nominals WHERE id_nominal=$id");
        setFlash('warning', 'Nominal dihapus.');
        redirect(SITE_URL . '/admin/nominal.php');
    }
}

$filter_game = isset($_GET['id_game']) ? (int)$_GET['id_game'] : 0;
$where = $filter_game ? "WHERE n.id_game=$filter_game" : '';
$nominals = $conn->query("SELECT n.*, g.nama_game FROM nominals n JOIN games g ON n.id_game=g.id_game $where ORDER BY g.nama_game, n.harga ASC");
$games_list = $conn->query("SELECT * FROM games WHERE status='aktif' ORDER BY nama_game ASC");

$edit_nom = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $edit_nom = $conn->query("SELECT * FROM nominals WHERE id_nominal=$eid")->fetch_assoc();
}

include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Nominal</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#nomModal">
        <i class="bi bi-plus"></i> Tambah Nominal
    </button>
</div>

<!-- Filter -->
<form method="GET" class="mb-3">
    <div class="d-flex gap-2">
        <select name="id_game" class="form-select form-select-sm" style="max-width:200px;">
            <option value="">Semua Game</option>
            <?php $games_list->data_seek(0); while ($g = $games_list->fetch_assoc()): ?>
                <option value="<?= $g['id_game'] ?>" <?= $filter_game == $g['id_game'] ? 'selected' : '' ?>><?= htmlspecialchars($g['nama_game']) ?></option>
            <?php endwhile; ?>
        </select>
        <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
        <a href="nominal.php" class="btn btn-light btn-sm">Reset</a>
    </div>
</form>

<div class="bg-white border rounded p-3">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr><th>ID</th><th>Game</th><th>Nominal</th><th>Jumlah</th><th>Bonus</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if ($nominals->num_rows === 0): ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data.</td></tr>
            <?php endif; ?>
            <?php while ($nom = $nominals->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $nom['id_nominal'] ?></td>
                    <td><?= htmlspecialchars($nom['nama_game']) ?></td>
                    <td><?= htmlspecialchars($nom['nama_nominal']) ?></td>
                    <td><?= $nom['jumlah'] ?></td>
                    <td><?= $nom['bonus'] > 0 ? '+' . $nom['bonus'] : '-' ?></td>
                    <td><?= rupiah($nom['harga']) ?></td>
                    <td><span class="badge bg-<?= $nom['status']==='aktif' ? 'success' : 'secondary' ?>-subtle text-<?= $nom['status']==='aktif' ? 'success' : 'secondary' ?> border" style="font-size:0.72rem;"><?= ucfirst($nom['status']) ?></span></td>
                    <td>
                        <a href="nominal.php?edit=<?= $nom['id_nominal'] ?>" class="btn btn-xs btn-outline-primary" style="font-size:0.75rem;padding:2px 8px;">Edit</a>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Hapus nominal ini?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_nominal" value="<?= $nom['id_nominal'] ?>">
                            <button class="btn btn-outline-danger" style="font-size:0.75rem;padding:2px 8px;">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="nomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold"><?= $edit_nom ? 'Edit Nominal' : 'Tambah Nominal' ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $edit_nom ? 'edit' : 'add' ?>">
                    <?php if ($edit_nom): ?>
                        <input type="hidden" name="id_nominal" value="<?= $edit_nom['id_nominal'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Game *</label>
                        <?php $games_list->data_seek(0); ?>
                        <select name="id_game" class="form-select form-select-sm" required>
                            <?php while ($g = $games_list->fetch_assoc()): ?>
                                <option value="<?= $g['id_game'] ?>" <?= ($edit_nom['id_game'] ?? 0) == $g['id_game'] ? 'selected' : '' ?>><?= htmlspecialchars($g['nama_game']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label small fw-medium">Nama Nominal *</label>
                            <input type="text" name="nama_nominal" class="form-control form-control-sm" placeholder="contoh: 86 Diamond" required value="<?= htmlspecialchars($edit_nom['nama_nominal'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label small fw-medium">Jumlah *</label>
                            <input type="number" name="jumlah" class="form-control form-control-sm" required value="<?= $edit_nom['jumlah'] ?? '' ?>">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-medium">Bonus</label>
                            <input type="number" name="bonus" class="form-control form-control-sm" value="<?= $edit_nom['bonus'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Harga (Rp) *</label>
                        <input type="number" name="harga" class="form-control form-control-sm" required value="<?= $edit_nom['harga'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="aktif" <?= ($edit_nom['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($edit_nom['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><?= $edit_nom ? 'Simpan' : 'Tambah' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
<?php if ($edit_nom): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('nomModal')).show();
    });
</script>
<?php endif; ?>
