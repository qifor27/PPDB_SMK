-- =====================================================
-- PPDB SMK - Add Ranking Columns
-- Run this migration in phpMyAdmin
-- =====================================================

-- Add ranking-related columns to tb_pendaftaran
ALTER TABLE `tb_pendaftaran` 
ADD COLUMN IF NOT EXISTS `bobot_rapor` DECIMAL(5,2) DEFAULT NULL COMMENT 'Hasil konversi rata-rata rapor ke bobot',
ADD COLUMN IF NOT EXISTS `nilai_akumulasi` DECIMAL(5,2) DEFAULT NULL COMMENT '30% bobot rapor + 70% nilai tes',
ADD COLUMN IF NOT EXISTS `ranking_sekolah` INT DEFAULT NULL COMMENT 'Ranking di sekolah pilihan',
ADD COLUMN IF NOT EXISTS `umur_bulan` INT DEFAULT NULL COMMENT 'Umur dalam bulan untuk tie-breaker';

-- Add index for ranking queries
ALTER TABLE `tb_pendaftaran` 
ADD INDEX IF NOT EXISTS `idx_ranking` (`id_smk_pilihan1`, `id_kejuruan_pilihan1`, `tahap_pendaftaran`, `nilai_akumulasi`);

-- Verify columns added
DESCRIBE `tb_pendaftaran`;
