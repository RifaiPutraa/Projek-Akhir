<?php
// =============================================
// RIVV STORE - Konfigurasi Database
// =============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Ganti sesuai user MySQL kamu
define('DB_PASS', '');           // Ganti sesuai password MySQL kamu
define('DB_NAME', 'rivv_store_2');

define('SITE_NAME', 'RIVV STORE');
define('SITE_URL', 'http://localhost/rivv_store');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Helper: redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Helper: flash message (session based)
function setFlash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// Helper: format rupiah
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Helper: cek login user
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper: cek login admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Helper: gambar game (fallback ke placeholder)
function gameImg($filename) {
    $path = __DIR__ . '/../assets/img/games/' . $filename;
    if (file_exists($path) && $filename !== 'placeholder.png') {
        return SITE_URL . '/assets/img/games/' . $filename;
    }
    return SITE_URL . '/assets/img/placeholder_game.svg';
}

// Helper: gambar banner (fallback ke placeholder)  
function bannerImg($filename) {
    $path = __DIR__ . '/../assets/img/banners/' . $filename;
    if (file_exists($path) && $filename !== 'banner_placeholder.png') {
        return SITE_URL . '/assets/img/banners/' . $filename;
    }
    return SITE_URL . '/assets/img/placeholder_banner.svg';
}
