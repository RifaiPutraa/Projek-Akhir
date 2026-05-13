<?php
// admin/includes/admin_header.php
if (!isAdminLoggedIn()) {
    redirect(SITE_URL . '/admin/login.php');
}
$admin_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - Admin ' . SITE_NAME : 'Admin ' . SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="admin-sidebar">
    <div class="brand">
        <?php if (file_exists(__DIR__ . '/../../assets/img/logo.png')): ?>
            <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" height="30">
        <?php else: ?>
            <span class="text-white"><span class="text-primary">RIVV</span>STORE</span>
        <?php endif; ?>
    </div>
    <nav class="nav flex-column">
        <a href="dashboard.php"   class="nav-link <?= $admin_page=='dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="transaksi.php"   class="nav-link <?= $admin_page=='transaksi' ? 'active' : '' ?>"><i class="bi bi-receipt"></i> Transaksi</a>
        <a href="nominal.php"     class="nav-link <?= $admin_page=='nominal' ? 'active' : '' ?>"><i class="bi bi-gem"></i> Nominal</a>
        <a href="game.php"        class="nav-link <?= $admin_page=='game' ? 'active' : '' ?>"><i class="bi bi-controller"></i> Game</a>
        <a href="banner.php"      class="nav-link <?= $admin_page=='banner' ? 'active' : '' ?>"><i class="bi bi-image"></i> Banner</a>
        <a href="user.php"        class="nav-link <?= $admin_page=='user' ? 'active' : '' ?>"><i class="bi bi-people"></i> User</a>
        <hr style="border-color:#343a40;margin:0.5rem 1rem;">
        <a href="logout.php"      class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Log out</a>
    </nav>
</div>

<!-- TOPBAR -->
<div class="admin-topbar">
    <span class="fw-semibold small"><?= isset($page_title) ? $page_title : 'Dashboard' ?></span>
    <span class="small text-muted"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
</div>

<!-- CONTENT -->
<div class="admin-content">
<?php $flash = getFlash(); if ($flash): ?>
<div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show auto-hide" role="alert">
    <?= $flash['msg'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
