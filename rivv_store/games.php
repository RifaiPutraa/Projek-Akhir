<?php
session_start();
require_once 'includes/config.php';
$page_title = 'Semua Games';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "WHERE g.status='aktif'";
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where .= " AND g.nama_game LIKE '%$s%'";
}
$games = $conn->query("SELECT * FROM games g $where ORDER BY g.nama_game ASC");

include 'includes/header.php';
?>  

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0"><i class="bi bi-grid text-primary"></i> Semua Games</h5>
    </div>

    <!-- Search -->
    <form action="" method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-sm-6 col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari game..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    <?php if ($search): ?>
                        <a href="games.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>

    <?php if ($games->num_rows === 0): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-emoji-frown fs-1"></i>
            <p class="mt-2">Game tidak ditemukan.</p>
        </div>
    <?php else: ?>
    <div class="row row-cols-3 row-cols-sm-4 row-cols-md-6 g-3">
        <?php while ($game = $games->fetch_assoc()):
            $is_ph = !file_exists(__DIR__ . '/assets/img/games/' . $game['gambar']);
        ?>
        <div class="col">
            <a href="topup.php?id=<?= $game['id_game'] ?>" class="game-card">
                <div class="game-img-wrapper">
                    <?php if ($is_ph): ?>
                        <div class="placeholder-img">
                            <i class="bi bi-controller"></i>
                            <span style="font-size:0.6rem;padding:0 4px;text-align:center;"><?= $game['gambar'] ?></span>
                        </div>
                    <?php else: ?>
                        <img src="<?= gameImg($game['gambar']) ?>" alt="<?= htmlspecialchars($game['nama_game']) ?>">
                    <?php endif; ?>
                </div>
                <div class="game-name"><?= htmlspecialchars($game['nama_game']) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
