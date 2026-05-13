# RIVV STORE - Website Top Up Game

Website top up game sederhana berbasis PHP, MySQL, dan Bootstrap 5.

---

## 📁 Struktur Folder

```
rivv_store/
├── index.php               ← Halaman beranda
├── games.php               ← Daftar semua game
├── topup.php               ← Halaman top up per game
├── success.php             ← Konfirmasi transaksi
├── login.php               ← Login user
├── register.php            ← Daftar user
├── logout.php
├── database.sql            ← File database (import ke phpMyAdmin)
│
├── includes/
│   ├── config.php          ← Konfigurasi DB & helper functions
│   ├── header.php          ← Navbar
│   └── footer.php          ← Footer + kontak + tentang
│
├── assets/
│   ├── css/style.css       ← Custom CSS
│   ├── js/main.js          ← Custom JS
│   └── img/
│       ├── logo.png        ← ⬅ TARUH LOGO DI SINI
│       ├── games/          ← ⬅ TARUH GAMBAR GAME DI SINI
│       │   ├── ml.png
│       │   ├── ff.png
│       │   ├── pubg.png
│       │   └── ...
│       ├── banners/        ← ⬅ TARUH GAMBAR BANNER DI SINI
│       │   ├── banner_ml.png
│       │   ├── banner_ff.png
│       │   └── ...
│       └── icons/          ← ⬅ TARUH IKON PEMBAYARAN & DIAMOND DI SINI
│           ├── diamond.png
│           ├── gopay.png
│           ├── dana.png
│           ├── ovo.png
│           └── qris.png
│
└── admin/
    ├── login.php           ← Login admin
    ├── dashboard.php       ← Dashboard statistik
    ├── game.php            ← Kelola game
    ├── nominal.php         ← Kelola nominal/harga
    ├── transaksi.php       ← Lihat transaksi
    ├── banner.php          ← Kelola banner carousel
    ├── user.php            ← Lihat & hapus user
    ├── logout.php
    └── includes/
        ├── admin_header.php
        └── admin_footer.php
```

---

## ⚙️ Cara Setup

### 1. Import Database
- Buka **phpMyAdmin**
- Buat database baru bernama `rivv_store` (atau import langsung)
- Import file `database.sql`

### 2. Konfigurasi Koneksi
Edit file `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Username MySQL kamu
define('DB_PASS', '');           // Password MySQL kamu
define('DB_NAME', 'rivv_store');
define('SITE_URL', 'http://localhost/rivv_store');
```

### 3. Taruh di Server
- Copy folder `rivv_store` ke folder `htdocs` (XAMPP) atau `www` (WAMP)
- Akses di browser: `http://localhost/rivv_store`

---

## 🖼️ Cara Menambahkan Gambar

### Logo
- Taruh file `logo.png` di `assets/img/`
- Ukuran ideal: **200 x 60px** (format PNG transparan)

### Gambar Game
- Taruh di `assets/img/games/`
- Nama file harus sama dengan yang ada di database (kolom `gambar`)
- Contoh: `ml.png`, `ff.png`, `pubg.png`
- Ukuran ideal: **300 x 300px** (kotak)

### Banner Carousel
- Taruh di `assets/img/banners/`
- Nama file harus sama dengan kolom `gambar` di tabel `banners`
- Contoh: `banner_ml.png`, `banner_ff.png`
- Ukuran ideal: **1200 x 400px** (landscape)

### Ikon Pembayaran
- Taruh di `assets/img/icons/`
- File: `gopay.png`, `dana.png`, `ovo.png`, `qris.png`
- Ukuran ideal: **120 x 60px** (format PNG transparan)

### Ikon Diamond
- Taruh file `diamond.png` di `assets/img/icons/`
- Ukuran: **32 x 32px**

---

## 🔐 Akun Default

### User (Login: http://localhost/rivv_store/login.php)
| Username | Password |
|----------|----------|
| user1    | user123  |
| gamer99  | user123  |

### Admin (Login: http://localhost/rivv_store/admin/login.php)
| Username | Password |
|----------|----------|
| admin    | password |

> ⚠️ Ganti password admin setelah pertama kali login!

---

## 🎮 Game yang Tersedia (Default)
| Game | File Gambar | File Banner |
|------|------------|-------------|
| Mobile Legends | ml.png | banner_ml.png |
| Free Fire | ff.png | banner_ff.png |
| PUBG Mobile | pubg.png | banner_pubg.png |
| Stumble Guys | stumble.png | banner_stumble.png |
| EA Sport FC 25 | eafc.png | banner_eafc.png |
| Roblox | roblox.png | banner_roblox.png |

---

## ✨ Fitur
- ✅ Login & Register User
- ✅ Login Admin terpisah
- ✅ Carousel banner (bisa tambah/edit dari admin)
- ✅ Halaman beranda: banner, game populer, semua game, kenapa pilih kami, footer
- ✅ Top up: input user ID, pilih nominal, pilih metode pembayaran
- ✅ Notifikasi/ringkasan setelah transaksi
- ✅ Admin: dashboard statistik, kelola game, nominal, banner, user, transaksi
- ✅ Pencarian game
- ✅ Semua gambar punya placeholder otomatis jika file belum ada

---

## 🛠️ Teknologi
- **Frontend**: HTML5, CSS3, Bootstrap 5.3, Bootstrap Icons
- **Backend**: PHP 7.4+
- **Database**: MySQL / MariaDB
- **Font**: Google Fonts (Poppins)
