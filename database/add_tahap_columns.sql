-- =====================================================
-- ADD MISSING COLUMNS FOR TAHAP PENDAFTARAN SYSTEM
-- Run this in phpMyAdmin
-- =====================================================

-- Tambah kolom tahap_pendaftaran jika belum ada
ALTER TABLE `tb_pendaftaran` 
ADD COLUMN IF NOT EXISTS `tahap_pendaftaran` TINYINT(1) DEFAULT 1 COMMENT 'Tahap pendaftaran: 1 atau 2';

-- Tambah kolom nilai_tes untuk input nilai tes minat bakat
ALTER TABLE `tb_pendaftaran` 
ADD COLUMN IF NOT EXISTS `nilai_tes` DECIMAL(5,2) DEFAULT NULL COMMENT 'Nilai Tes Minat dan Bakat (0-100)';

-- Update index untuk optimasi query
ALTER TABLE `tb_pendaftaran` 
ADD INDEX IF NOT EXISTS `idx_tahap` (`tahap_pendaftaran`);

-- Verifikasi kolom berhasil ditambahkan
DESCRIBE `tb_pendaftaran`;
