-- =====================================================
-- PPDB SMK - Kolom Tambahan untuk Jalur Kepindahan
-- Tugas: Veli
-- =====================================================

-- Kolom untuk data kepindahan orang tua
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS jenis_instansi_ortu VARCHAR(50) DEFAULT NULL COMMENT 'ASN/TNI/POLRI/BUMN/Swasta';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS nama_instansi_asal VARCHAR(200) DEFAULT NULL COMMENT 'Nama instansi sebelum pindah';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS nama_instansi_tujuan VARCHAR(200) DEFAULT NULL COMMENT 'Nama instansi setelah pindah';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS nomor_sk_pindah VARCHAR(100) DEFAULT NULL COMMENT 'Nomor SK Pindah Tugas';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS tanggal_sk_pindah DATE DEFAULT NULL COMMENT 'Tanggal SK Pindah Tugas';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS kota_asal VARCHAR(100) DEFAULT NULL COMMENT 'Kota/Kabupaten sebelum pindah';
ALTER TABLE tb_siswa ADD COLUMN IF NOT EXISTS alasan_kepindahan TEXT DEFAULT NULL COMMENT 'Alasan/keterangan kepindahan';
