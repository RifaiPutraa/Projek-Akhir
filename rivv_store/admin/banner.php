<?php
session_start();
require_once '../includes/config.php';
$page_title = 'Kelola Banner';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add' || $action === 'edit') {
        $judul  = $conn->real_escape_string(trim($_POST['judul']));
        $gambar = $conn->real_escape_string(trim($_POST['gambar']));
        $link   = $conn->real_escape_string(trim($_POST['link'] ?: '#'));
        $urutan = (int)$_POST['urutan'];
        $status = $_POST['status'];
        if ($action === 'add') {
            $conn->query("INSERT INTO banners (judul, gambar, link, urutan, status) VALUES ('$judul','$gambar','$link',$urutan,'$status')");
            setFlash('success', 'Banner ditambahkan.');
        } else {
            $id = (int)$_POST['id_banner'];
            $conn->query("UPDATE banners SET judul='$judul', gambar='$gambar', link='$link', urutan=$urutan, status='$status' WHERE id_banner=$id");
            setFlash('success', 'Banner diperbarui.');
        }
        redirect(SITE_URL . '/admin/banner.php');
    }
    if ($action === 'delete') {
        $id = (int)$_POST['id_banner'];
        $conn->query("DELETE FROM banners WHERE id_banner=$id");
        setFlash('warning', 'Banner dihapus.');
        redirect(SITE_URL . '/admin/banner.php');
    }
}

$banners = $conn->query("SELECT * FROM banners ORDER BY urutan ASC");
$edit_banner = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $edit_banner = $conn->query("SELECT * FROM banners WHERE id_banner=$eid")->fetch_assoc();
}

include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Banner</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bannerModal">
        <i class="bi bi-plus"></i> Tambah Banner
    </button>
</div>
<p class="text-muted small mb-3">
    <i class="bi bi-info-circle"></i> Taruh file gambar banner di folder <code>assets/img/banners/</code> lalu daftarkan nama filenya di sini.
    Ukuran ideal: <strong>1200 x 400px</strong>.
</p>

<div class="bg-white border rounded p-3">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr><th>ID</th><th>Judul</th><th>Gambar</th><th>Preview</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if ($banners->num_rows === 0): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada banner.</td></tr>
            <?php endif; ?>
            <?php while ($b = $banners->fetch_assoc()):
                $img_exists = file_exists(__DIR__ . '/../assets/img/banners/' . $b['gambar']);
            ?>
                <tr>
                    <td>#<?= $b['id_banner'] ?></td>
                    <td><?= htmlspecialchars($b['judul']) ?></td>
                    <td><code style="font-size:0.78rem;"><?= htmlspecialchars($b['gambar']) ?></code></td>
                    <td>
                        <?php if ($img_exists): ?>
                            <img src="<?= SITE_URL ?>/assets/img/banners/<?= $b['gambar'] ?>" style="height:40px;border-radius:4px;object-fit:cover;width:80px;">
                        <?php else: ?>
                            <span class="text-muted small"><i class="bi bi-image"></i> Belum ada</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $b['urutan'] ?></td>
                    <td><span class="badge bg-<?= $b['status']==='aktif'?'success':'secondary' ?>-subtle text-<?= $b['status']==='aktif'?'success':'secondary' ?> border" style="font-size:0.72rem;"><?= ucfirst($b['status']) ?></span></td>
                    <td>
                        <a href="banner.php?edit=<?= $b['id_banner'] ?>" class="btn btn-outline-primary btn-sm" style="font-size:0.75rem;">Edit</a>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Hapus banner?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_banner" value="<?= $b['id_banner'] ?>">
                            <button class="btn btn-outline-danger btn-sm" style="font-size:0.75rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="bannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold"><?= $edit_banner ? 'Edit Banner' : 'Tambah Banner' ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $edit_banner ? 'edit' : 'add' ?>">
                    <?php if ($edit_banner): ?>
                        <input type="hidden" name="id_banner" value="<?= $edit_banner['id_banner'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Judul Banner</label>
                        <input type="text" name="judul" class="form-control form-control-sm" value="<?= htmlspecialchars($edit_banner['judul'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama File Gambar *</label>
                        <input type="text" name="gambar" class="form-control form-control-sm" placeholder="contoh: banner_ml.png" required value="<?= htmlspecialchars($edit_banner['gambar'] ?? '') ?>">
                        <div class="form-text">Taruh di <code>assets/img/banners/</code> | Ukuran: 1200x400px</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Link (opsional)</label>
                        <input type="text" name="link" class="form-control form-control-sm" placeholder="#" value="<?= htmlspecialchars($edit_banner['link'] ?? '#') ?>">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label small fw-medium">Urutan</label>
                            <input type="number" name="urutan" class="form-control form-control-sm" value="<?= $edit_banner['urutan'] ?? 0 ?>">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-medium">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="aktif" <?= ($edit_banner['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= ($edit_banner['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><?= $edit_banner ? 'Simpan' : 'Tambah' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
<?php if ($edit_banner): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('bannerModal')).show();
    });
</script>
<?php endif; ?>
