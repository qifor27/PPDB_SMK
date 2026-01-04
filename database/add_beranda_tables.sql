-- ============================================
-- Tabel untuk Kelola Beranda Superadmin
-- ============================================

-- Tabel Dokumen Beranda
CREATE TABLE IF NOT EXISTS `tb_dokumen_beranda` (
    `id_dokumen` INT(11) NOT NULL AUTO_INCREMENT,
    `tipe` VARCHAR(50) NOT NULL COMMENT 'juknis, manual, formulir, persyaratan',
    `judul` VARCHAR(255) NOT NULL,
    `filename` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_dokumen`),
    UNIQUE KEY `unique_tipe` (`tipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Jadwal SPMB
CREATE TABLE IF NOT EXISTS `tb_jadwal_spmb` (
    `id_jadwal` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_kegiatan` VARCHAR(255) NOT NULL,
    `tanggal_mulai` DATE NOT NULL,
    `tanggal_selesai` DATE DEFAULT NULL,
    `keterangan` TEXT DEFAULT NULL,
    `urutan` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_jadwal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default jadwal
INSERT INTO `tb_jadwal_spmb` (`nama_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `keterangan`, `urutan`) VALUES
('Pendaftaran Online Tahap 1', '2025-01-02', '2025-01-15', 'Pendaftaran melalui website SPMB', 1),
('Verifikasi Dokumen Tahap 1', '2025-01-16', '2025-01-20', 'Verifikasi oleh admin sekolah', 2),
('Tes Bakat Minat Tahap 1', '2025-01-21', '2025-01-25', 'Tes dilaksanakan di sekolah pilihan', 3),
('Pengumuman Tahap 1', '2025-01-27', '2025-01-27', 'Pengumuman hasil seleksi', 4),
('Daftar Ulang Tahap 1', '2025-01-28', '2025-02-01', 'Daftar ulang bagi yang diterima', 5),
('Pendaftaran Online Tahap 2', '2025-02-03', '2025-02-15', 'Pendaftaran tahap kedua', 6);

-- Tambah kolom kategori di tb_pengaturan jika belum ada
ALTER TABLE `tb_pengaturan` ADD COLUMN IF NOT EXISTS `kategori` VARCHAR(50) DEFAULT 'umum';

-- Insert default beranda settings
INSERT INTO `tb_pengaturan` (`kunci`, `nilai`, `kategori`) VALUES
('hero_title', 'Sistem Penerimaan Murid Baru SMK Kota Padang', 'beranda'),
('hero_subtitle', 'Selamat datang di SPMB SMK Kota Padang. Daftarkan diri Anda sekarang dan raih masa depan cerah bersama SMK terbaik di Kota Padang.', 'beranda'),
('hero_image', 'assets/img/hero-students.png', 'beranda'),
('contact_phone', '(0751) 123456', 'beranda'),
('contact_email', 'spmb@smk.padang.go.id', 'beranda'),
('contact_address', 'Dinas Pendidikan Kota Padang, Jl. Bagindo Aziz Chan, Padang', 'beranda')
ON DUPLICATE KEY UPDATE nilai = VALUES(nilai);
