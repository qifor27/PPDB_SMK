-- =====================================================
-- SPMB 2025/2026 - SMK Schema Updates
-- Migration file for SMK-specific requirements
-- =====================================================

-- =====================================================
-- 1. UPDATE tb_pendaftaran dengan kolom baru untuk SMK
-- =====================================================

ALTER TABLE `tb_pendaftaran`
ADD COLUMN `tahap_pendaftaran` ENUM('1','2') DEFAULT '1' AFTER `id_jalur`,
ADD COLUMN `pilihan_mode` ENUM('satu_sekolah_dua_jurusan','dua_sekolah_satu_jurusan') DEFAULT NULL AFTER `tahap_pendaftaran`,
ADD COLUMN `nilai_rapor_semester1` DECIMAL(5,2) DEFAULT NULL AFTER `pilihan_mode`,
ADD COLUMN `nilai_rapor_semester2` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_rapor_semester1`,
ADD COLUMN `nilai_rapor_semester3` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_rapor_semester2`,
ADD COLUMN `nilai_rapor_semester4` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_rapor_semester3`,
ADD COLUMN `nilai_rapor_semester5` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_rapor_semester4`,
ADD COLUMN `rerata_nilai_rapor` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_rapor_semester5`,
ADD COLUMN `bobot_nilai_rapor` DECIMAL(5,2) DEFAULT NULL AFTER `rerata_nilai_rapor`,
ADD COLUMN `peringkat_paralel` INT DEFAULT NULL AFTER `bobot_nilai_rapor`,
ADD COLUMN `nilai_tes_bakat_minat` DECIMAL(5,2) DEFAULT NULL AFTER `peringkat_paralel`,
ADD COLUMN `nilai_akhir_seleksi` DECIMAL(5,2) DEFAULT NULL AFTER `nilai_tes_bakat_minat`,
ADD COLUMN `kelompok_seleksi` ENUM('afirmasi','domisili','prestasi','nilai_akhir') DEFAULT NULL AFTER `nilai_akhir_seleksi`,
ADD COLUMN `status_tes` ENUM('belum','sudah') DEFAULT 'belum' AFTER `kelompok_seleksi`,
ADD COLUMN `tanggal_tes` TIMESTAMP NULL DEFAULT NULL AFTER `status_tes`,
ADD COLUMN `is_dibatalkan` TINYINT(1) DEFAULT 0 AFTER `status`,
ADD COLUMN `tanggal_batal` TIMESTAMP NULL DEFAULT NULL AFTER `is_dibatalkan`;

-- =====================================================
-- 2. UPDATE tb_siswa dengan kolom afirmasi dan syarat SMK
-- =====================================================

ALTER TABLE `tb_siswa`
ADD COLUMN `is_keluarga_tidak_mampu` TINYINT(1) DEFAULT 0 AFTER `foto`,
ADD COLUMN `jenis_kartu_bantuan` ENUM('PIP','DTSEN','PKH','KIS','Lainnya') DEFAULT NULL AFTER `is_keluarga_tidak_mampu`,
ADD COLUMN `nomor_kartu_bantuan` VARCHAR(50) DEFAULT NULL AFTER `jenis_kartu_bantuan`,
ADD COLUMN `is_disabilitas` TINYINT(1) DEFAULT 0 AFTER `nomor_kartu_bantuan`,
ADD COLUMN `jenis_disabilitas` VARCHAR(100) DEFAULT NULL AFTER `is_disabilitas`,
ADD COLUMN `nomor_kartu_disabilitas` VARCHAR(50) DEFAULT NULL AFTER `jenis_disabilitas`,
ADD COLUMN `is_panti_asuhan` TINYINT(1) DEFAULT 0 AFTER `nomor_kartu_disabilitas`,
ADD COLUMN `nama_panti` VARCHAR(200) DEFAULT NULL AFTER `is_panti_asuhan`,
ADD COLUMN `status_buta_warna` ENUM('tidak','parsial','total') DEFAULT 'tidak' AFTER `nama_panti`,
ADD COLUMN `file_surat_tidak_buta_warna` VARCHAR(255) DEFAULT NULL AFTER `status_buta_warna`;

-- =====================================================
-- 3. BUAT tabel tb_tes_bakat_minat
-- =====================================================

CREATE TABLE IF NOT EXISTS `tb_tes_bakat_minat` (
    `id_tes` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pendaftaran` INT NOT NULL,
    `id_kejuruan` INT NOT NULL,
    `tanggal_tes` DATE NOT NULL,
    `jam_mulai` TIME DEFAULT NULL,
    `jam_selesai` TIME DEFAULT NULL,
    `nilai_tes` DECIMAL(5,2) DEFAULT NULL,
    `penguji` VARCHAR(100) DEFAULT NULL,
    `catatan` TEXT DEFAULT NULL,
    `status` ENUM('terjadwal','selesai','tidak_hadir') DEFAULT 'terjadwal',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_pendaftaran` (`id_pendaftaran`),
    INDEX `idx_kejuruan` (`id_kejuruan`),
    INDEX `idx_tanggal` (`tanggal_tes`),
    FOREIGN KEY (`id_pendaftaran`) REFERENCES `tb_pendaftaran`(`id_pendaftaran`) ON DELETE CASCADE,
    FOREIGN KEY (`id_kejuruan`) REFERENCES `tb_kejuruan`(`id_program`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 4. BUAT tabel tb_pembatalan
-- =====================================================

CREATE TABLE IF NOT EXISTS `tb_pembatalan` (
    `id_pembatalan` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pendaftaran` INT NOT NULL,
    `alasan_pembatalan` TEXT,
    `dibatalkan_oleh` ENUM('siswa','admin') DEFAULT 'siswa',
    `id_admin_pembatal` INT DEFAULT NULL,
    `tanggal_pembatalan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('diajukan','disetujui','ditolak') DEFAULT 'diajukan',
    `catatan_admin` TEXT DEFAULT NULL,
    INDEX `idx_pendaftaran` (`id_pendaftaran`),
    INDEX `idx_status` (`status`),
    FOREIGN KEY (`id_pendaftaran`) REFERENCES `tb_pendaftaran`(`id_pendaftaran`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 5. UPDATE tb_kejuruan dengan syarat buta warna
-- =====================================================

ALTER TABLE `tb_kejuruan`
ADD COLUMN `syarat_tidak_buta_warna` TINYINT(1) DEFAULT 0 AFTER `deskripsi`,
ADD COLUMN `kuota` INT DEFAULT 36 AFTER `syarat_tidak_buta_warna`,
ADD COLUMN `kuota_terisi` INT DEFAULT 0 AFTER `kuota`;

-- Update jurusan yang memerlukan syarat tidak buta warna
-- Berdasarkan Juknis: Teknologi Rekayasa, Teknologi Informatika, Industri/Kimia/Farmasi, Kemaritiman
UPDATE `tb_kejuruan` SET `syarat_tidak_buta_warna` = 1 
WHERE `nama_kejuruan` LIKE '%Teknik%' 
   OR `nama_kejuruan` LIKE '%Kimia%'
   OR `nama_kejuruan` LIKE '%Farmasi%'
   OR `nama_kejuruan` LIKE '%Nautika%'
   OR `nama_kejuruan` LIKE '%Teknika%'
   OR `nama_kejuruan` LIKE '%Komputer%'
   OR `nama_kejuruan` LIKE '%Jaringan%'
   OR `nama_kejuruan` LIKE '%Elektronika%'
   OR `nama_kejuruan` LIKE '%Otomasi%'
   OR `nama_kejuruan` LIKE '%Mekatronika%'
   OR `nama_kejuruan` LIKE '%Rekayasa%';

-- =====================================================
-- 6. UPDATE tb_jalur untuk kelompok seleksi SMK
-- =====================================================

-- Tambah kolom untuk bobot seleksi
ALTER TABLE `tb_jalur`
ADD COLUMN `bobot_rapor` DECIMAL(5,2) DEFAULT 30.00 AFTER `kuota_persen`,
ADD COLUMN `bobot_tes` DECIMAL(5,2) DEFAULT 70.00 AFTER `bobot_rapor`,
ADD COLUMN `is_smk` TINYINT(1) DEFAULT 1 AFTER `bobot_tes`;

-- Insert kelompok seleksi SMK jika belum ada
INSERT IGNORE INTO `tb_jalur` (`nama_jalur`, `kode_jalur`, `deskripsi`, `kuota_persen`, `bobot_rapor`, `bobot_tes`, `is_smk`, `icon`, `warna`, `urutan`) VALUES
('Kelompok Afirmasi SMK', 'afirmasi', 'Prioritas untuk siswa dari keluarga tidak mampu, penyandang disabilitas, atau panti asuhan', 15.00, 30.00, 70.00, 1, 'bi-heart-fill', '#C084FC', 1);

-- =====================================================
-- 7. BUAT tabel tb_kuota_kejuruan (kuota per jurusan per kelompok)
-- =====================================================

CREATE TABLE IF NOT EXISTS `tb_kuota_kejuruan` (
    `id_kuota` INT AUTO_INCREMENT PRIMARY KEY,
    `id_kejuruan` INT NOT NULL,
    `tahun_ajaran` VARCHAR(10) NOT NULL,
    `kuota_afirmasi` INT DEFAULT 0,
    `kuota_domisili` INT DEFAULT 0,
    `kuota_prestasi` INT DEFAULT 0,
    `kuota_nilai_akhir` INT DEFAULT 0,
    `terisi_afirmasi` INT DEFAULT 0,
    `terisi_domisili` INT DEFAULT 0,
    `terisi_prestasi` INT DEFAULT 0,
    `terisi_nilai_akhir` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_kuota` (`id_kejuruan`, `tahun_ajaran`),
    FOREIGN KEY (`id_kejuruan`) REFERENCES `tb_kejuruan`(`id_program`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Generate kuota default untuk semua jurusan
INSERT IGNORE INTO `tb_kuota_kejuruan` (`id_kejuruan`, `tahun_ajaran`, `kuota_afirmasi`, `kuota_domisili`, `kuota_prestasi`, `kuota_nilai_akhir`)
SELECT 
    `id_program`,
    '2025/2026',
    CEIL(36 * 0.15) as kuota_afirmasi,    -- 15% = 6
    CEIL(36 * 0.10) as kuota_domisili,    -- 10% = 4
    CEIL(36 * 0.20) as kuota_prestasi,    -- 20% = 8
    CEIL(36 * 0.55) as kuota_nilai_akhir  -- 55% = 20
FROM `tb_kejuruan`;
