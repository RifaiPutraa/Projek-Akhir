<?php
session_start();
require_once 'includes/config.php';

$page_title = 'Beranda';

// Ambil banners
$banners = $conn->query("SELECT * FROM banners WHERE status='aktif' ORDER BY urutan ASC");

// Ambil game populer (4 game)
$populer = $conn->query("SELECT * FROM games WHERE status='aktif' AND is_populer=1 ORDER BY id_game ASC LIMIT 4");

// Ambil semua game
$all_games = $conn->query("SELECT * FROM games WHERE status='aktif' ORDER BY id_game ASC");

include 'includes/header.php';
?>

<!-- ===== CAROUSEL BANNER ===== -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php
        $banners_arr = $banners->fetch_all(MYSQLI_ASSOC);
        foreach ($banners_arr as $i => $banner): ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>"
                <?= $i === 0 ? 'class="active"' : '' ?>></button>
        <?php endforeach; ?>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <?php if (empty($banners_arr)): ?>
            <!-- Placeholder saat belum ada banner -->
            <div class="carousel-item active">
                <div class="banner-placeholder">
                    <div class="banner-text">
                        <p class="text-muted mb-1" style="font-size:0.85rem;">
                            <i class="bi bi-image me-1"></i>Taruh file banner di <code>assets/img/banners/</code>
                        </p>
                        <h3>RIVV STORE</h3>
                        <p>Top Up Game Cepat, Murah & Aman</p>
                    </div>
                </div>
            </div>
        <?php else:
            foreach ($banners_arr as $i => $banner):
                $img_url = bannerImg($banner['gambar']);
                $is_placeholder = !file_exists(__DIR__ . '/assets/img/banners/' . $banner['gambar']);
        ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                <?php if ($is_placeholder): ?>
                    <div class="banner-placeholder">
                        <div class="banner-text">
                            <p class="text-muted mb-1" style="font-size:0.82rem;">
                                <i class="bi bi-image me-1"></i>Taruh <code><?= $banner['gambar'] ?></code> di <code>assets/img/banners/</code>
                            </p>
                            <h3><?= htmlspecialchars($banner['judul']) ?></h3>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $banner['link'] ?>">
                        <img src="<?= $img_url ?>" alt="<?= htmlspecialchars($banner['judul']) ?>">
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="container my-4">

    <!-- Search -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form action="games.php" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari game...">
                <button type="submit" class="btn btn-primary px-3">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- ===== GAME POPULER ===== -->
    <h5 class="section-title"><i class="bi bi-fire text-danger"></i> Game Populer</h5>
    <div class="row row-cols-2 row-cols-sm-4 g-3 mb-5">
        <?php
        $populer->data_seek(0);
        while ($game = $populer->fetch_assoc()):
            $img = gameImg($game['gambar']);
            $is_ph = !file_exists(__DIR__ . '/assets/img/games/' . $game['gambar']);
        ?>
        <div class="col">
            <a href="topup.php?id=<?= $game['id_game'] ?>" class="game-card game-card-populer">
                <div class="game-img-wrapper">
                    <?php if ($is_ph): ?>
                        <div class="placeholder-img">
                            <i class="bi bi-controller"></i>
                            <span><?= htmlspecialchars($game['nama_game']) ?></span>
                            <span class="text-center" style="font-size:0.65rem;padding:0 8px;">Taruh <b><?= $game['gambar'] ?></b><br>di assets/img/games/</span>
                        </div>
                    <?php else: ?>
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($game['nama_game']) ?>">
                    <?php endif; ?>
                </div>
                <div class="game-name"><?= htmlspecialchars($game['nama_game']) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- ===== SEMUA GAMES ===== -->
    <h5 class="section-title"><i class="bi bi-grid text-primary"></i> Games</h5>
    <div class="row row-cols-3 row-cols-sm-4 row-cols-md-6 g-3 mb-3">
        <?php
        while ($game = $all_games->fetch_assoc()):
            $img = gameImg($game['gambar']);
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
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($game['nama_game']) ?>">
                    <?php endif; ?>
                </div>
                <div class="game-name"><?= htmlspecialchars($game['nama_game']) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="text-center mb-5">
        <a href="games.php" class="btn btn-outline-primary btn-sm">Lihat Semua Game</a>
    </div>

    <!-- ===== KENAPA PILIH KAMI ===== -->
    <h5 class="section-title"><i class="bi bi-star text-warning"></i> Kenapa Pilih Kami?</h5>
    <div class="row g-3 mb-2">
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                <h6>Proses Cepat</h6>
                <p>Top up diproses secara otomatis, diamond langsung masuk ke akun game kamu.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-tag"></i></div>
                <h6>Harga Murah</h6>
                <p>Kami menawarkan harga terbaik dengan kualitas layanan yang terpercaya.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                <h6>Transaksi Aman</h6>
                <p>Setiap transaksi dilindungi dan tercatat agar kamu bisa memantau riwayat pembelian.</p>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
