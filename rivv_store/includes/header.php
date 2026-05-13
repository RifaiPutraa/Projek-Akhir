<?php
// /includes/header.php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME ?></title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <?= isset($extra_css) ? $extra_css : '' ?>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <!-- LOGO - Taruh file logo.png di assets/img/ -->
       <a class="navbar-brand d-flex align-items-center gap-2" href="<?= SITE_URL ?>">

    <?php
    $logo_path = __DIR__ . '/../assets/img/logo.png';
    if (file_exists($logo_path)): ?>
        <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" height="36" class="me-1">
    <?php endif; ?>

    <span class="fw-bold fs-5 text-white">
        <span class="text-primary">RIVV</span> STORE
    </span>

</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>" href="<?= SITE_URL ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'games' ? 'active' : '' ?>" href="<?= SITE_URL ?>/games.php">Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>

            <div class="d-flex gap-2 align-items-center">
                <?php if (isUserLoggedIn()): ?>
                    <span class="text-light small me-1"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="<?= SITE_URL ?>/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/login.php" class="btn btn-sm btn-outline-light">Login</a>
                <?php endif; ?>
                <a href="<?= SITE_URL ?>/admin/login.php" class="btn btn-sm btn-primary">Login Admin</a>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Message -->
<?php $flash = getFlash(); if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= $flash['msg'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
