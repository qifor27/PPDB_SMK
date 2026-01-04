-- =====================================================
-- FIX FOREIGN KEY CONSTRAINT ERROR #1452
-- Database: dbesemka
-- =====================================================

-- Jalankan query ini SATU PER SATU di phpMyAdmin

-- =====================================================
-- LANGKAH 1: Cek data orphan (id_siswa tidak valid)
-- =====================================================
-- Query ini akan menampilkan data di tb_pendaftaran 
-- yang id_siswa-nya tidak ada di tb_siswa

SELECT 
    p.id_pendaftaran,
    p.nomor_pendaftaran,
    p.id_siswa,
    p.status,
    p.tanggal_daftar
FROM tb_pendaftaran p
WHERE p.id_siswa NOT IN (SELECT id_siswa FROM tb_siswa);

-- =====================================================
-- LANGKAH 2A: OPSI HAPUS - Hapus data orphan
-- =====================================================
-- Gunakan ini jika data yang tidak valid boleh dihapus

DELETE FROM tb_pendaftaran 
WHERE id_siswa NOT IN (SELECT id_siswa FROM tb_siswa);

-- =====================================================
-- LANGKAH 2B: OPSI UPDATE - Set id_siswa ke NULL (jika kolom nullable)
-- =====================================================
-- Alternatif jika tidak mau hapus data

-- UPDATE tb_pendaftaran 
-- SET id_siswa = NULL 
-- WHERE id_siswa NOT IN (SELECT id_siswa FROM tb_siswa);

-- =====================================================
-- LANGKAH 3: Cek juga untuk id_jalur yang tidak valid
-- =====================================================

SELECT 
    p.id_pendaftaran,
    p.nomor_pendaftaran,
    p.id_jalur
FROM tb_pendaftaran p
WHERE p.id_jalur NOT IN (SELECT id_jalur FROM tb_jalur);

-- Hapus jika ada data orphan untuk id_jalur
DELETE FROM tb_pendaftaran 
WHERE id_jalur NOT IN (SELECT id_jalur FROM tb_jalur);

-- =====================================================
-- LANGKAH 4: Setelah data bersih, tambahkan constraint
-- =====================================================
-- Jalankan ALTER TABLE setelah semua data orphan dihapus

ALTER TABLE `tb_pendaftaran`
    ADD CONSTRAINT `fk_pendaftaran_siswa` FOREIGN KEY (`id_siswa`) 
        REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_pendaftaran_jalur` FOREIGN KEY (`id_jalur`) 
        REFERENCES `tb_jalur` (`id_jalur`);

-- =====================================================
-- SELESAI!
-- =====================================================
