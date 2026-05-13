-- =============================================
-- RIVV STORE - Database Setup
-- =============================================

CREATE DATABASE IF NOT EXISTS rivv_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rivv_store;

-- =============================================
-- TABEL USERS (untuk login user biasa)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- TABEL ADMIN
-- =============================================
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- TABEL GAMES
-- =============================================
CREATE TABLE IF NOT EXISTS games (
    id_game INT AUTO_INCREMENT PRIMARY KEY,
    nama_game VARCHAR(100) NOT NULL,
    gambar VARCHAR(255) DEFAULT 'placeholder.png',
    banner VARCHAR(255) DEFAULT 'banner_placeholder.png',
    deskripsi TEXT,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    is_populer TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- TABEL NOMINALS
-- =============================================
CREATE TABLE IF NOT EXISTS nominals (
    id_nominal INT AUTO_INCREMENT PRIMARY KEY,
    id_game INT NOT NULL,
    nama_nominal VARCHAR(100) NOT NULL,
    jumlah INT NOT NULL,
    bonus INT DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    FOREIGN KEY (id_game) REFERENCES games(id_game) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- TABEL TRANSACTIONS
-- =============================================
CREATE TABLE IF NOT EXISTS transactions (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_game INT NOT NULL,
    id_nominal INT NOT NULL,
    user_game_id VARCHAR(100) NOT NULL,
    server_id VARCHAR(100) DEFAULT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    status ENUM('pending','berhasil','gagal') DEFAULT 'pending',
    total_harga DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL,
    FOREIGN KEY (id_game) REFERENCES games(id_game),
    FOREIGN KEY (id_nominal) REFERENCES nominals(id_nominal)
) ENGINE=InnoDB;

-- =============================================
-- TABEL BANNERS (untuk carousel di beranda)
-- =============================================
CREATE TABLE IF NOT EXISTS banners (
    id_banner INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    gambar VARCHAR(255) NOT NULL,
    link VARCHAR(255) DEFAULT '#',
    urutan INT DEFAULT 0,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB;

-- =============================================
-- METODE PEMBAYARAN (referensi)
-- =============================================
CREATE TABLE IF NOT EXISTS metode_pembayaran (
    id_metode INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    logo VARCHAR(255) DEFAULT 'payment_placeholder.png',
    status ENUM('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB;

-- =============================================
-- DATA AWAL
-- =============================================

-- Admin default (password: admin123)
INSERT INTO admin (username, email, password) VALUES
('admin', 'admin@rivvstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Users default (password: user123)
INSERT INTO users (username, email, password) VALUES
('user1', 'user1@example.com', '$2y$10$TKh8H1.PFgs/LMQfItuly.dvH0IPEXQTmGdO77jz3jJmVHSfHnr6'),
('gamer99', 'gamer99@example.com', '$2y$10$TKh8H1.PFgs/LMQfItuly.dvH0IPEXQTmGdO77jz3jJmVHSfHnr6');

-- Games
INSERT INTO games (nama_game, gambar, banner, deskripsi, is_populer) VALUES
('Mobile Legends', 'ml.png', 'banner_ml.png', 'Game MOBA 5v5 paling populer di Asia Tenggara.', 1),
('Free Fire', 'ff.png', 'banner_ff.png', 'Battle royale survival 50 pemain terpopuler.', 1),
('PUBG Mobile', 'pubg.png', 'banner_pubg.png', 'Battle royale realistis dengan grafis memukau.', 1),
('Stumble Guys', 'stumble.png', 'banner_stumble.png', 'Party game seru untuk dimainkan bersama.', 1),
('EA Sport FC 25', 'eafc.png', 'banner_eafc.png', 'Simulasi sepak bola terbaik dari EA Sports.', 0),
('Roblox', 'roblox.png', 'banner_roblox.png', 'Platform game kreatif dengan dunia tak terbatas.', 0);

-- Nominals Mobile Legends
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(1, '12 Diamond', 12, 0, 3000),
(1, '28 Diamond', 25, 3, 7500),
(1, '56 Diamond', 50, 6, 15000),
(1, '170 Diamond', 154, 16, 43000),
(1, '240 Diamond', 217, 23, 61000),
(1, '296 Diamond', 256, 40, 75000);

-- Nominals Free Fire
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(2, '70 Diamond', 70, 0, 18000),
(2, '140 Diamond', 140, 0, 36000),
(2, '355 Diamond', 355, 0, 90000),
(2, '720 Diamond', 720, 0, 180000),
(2, '1450 Diamond', 1450, 0, 360000);

-- Nominals PUBG
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(3, '60 UC', 60, 0, 15000),
(3, '325 UC', 325, 0, 75000),
(3, '660 UC', 660, 0, 150000),
(3, '1800 UC', 1800, 0, 375000);

-- Nominals Stumble Guys
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(4, '90 Gems', 90, 0, 15000),
(4, '180 Gems', 180, 0, 29000),
(4, '450 Gems', 450, 0, 75000);

-- Nominals EA Sport
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(5, '1050 FC Points', 1050, 0, 75000),
(5, '2800 FC Points', 2800, 0, 195000),
(5, '5900 FC Points', 5900, 0, 390000);

-- Nominals Roblox
INSERT INTO nominals (id_game, nama_nominal, jumlah, bonus, harga) VALUES
(6, '400 Robux', 400, 0, 70000),
(6, '800 Robux', 800, 0, 135000),
(6, '1700 Robux', 1700, 0, 280000);

-- Banners
INSERT INTO banners (judul, gambar, link, urutan) VALUES
('Mobile Legends Top Up', 'banner_ml.png', '#', 1),
('Free Fire Special', 'banner_ff.png', '#', 2),
('PUBG Mobile', 'banner_pubg.png', '#', 3);

-- Metode Pembayaran
INSERT INTO metode_pembayaran (nama, logo) VALUES
('GoPay', 'gopay.png'),
('Dana', 'dana.png'),
('OVO', 'ovo.png'),
('QRIS', 'qris.png'),
('Transfer Bank', 'bank.png');

-- Sample transactions
INSERT INTO transactions (id_user, id_game, id_nominal, user_game_id, server_id, metode_pembayaran, status, total_harga) VALUES
(1, 1, 4, '123456789', '2201', 'GoPay', 'berhasil', 43000),
(2, 2, 7, '987654321', NULL, 'Dana', 'berhasil', 18000),
(1, 3, 11, '555333111', NULL, 'QRIS', 'pending', 15000);
