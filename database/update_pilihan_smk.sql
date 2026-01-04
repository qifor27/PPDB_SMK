-- =====================================================
-- Update Data Dummy Sesuai Aturan Juknis SPMB Sumbar 2025
-- =====================================================
-- Aturan:
-- 1. Opsi A: 1 sekolah dengan 2 jurusan berbeda (same_school)
-- 2. Opsi B: 2 sekolah berbeda dengan 1 jurusan sama (diff_school)
-- 3. Opsi C: 2 sekolah berbeda dengan jurusan berbeda = TIDAK VALID
-- =====================================================

-- Step 1: Reset semua ke single dulu
UPDATE tb_pendaftaran SET 
    id_smk_pilihan2 = NULL,
    id_kejuruan_pilihan2 = NULL,
    jenis_pilihan = 'single',
    lokasi_tes_smk = id_smk_pilihan1,
    pilihan_mode = NULL;

-- Step 2: 40% Siswa - Opsi A (1 Sekolah, 2 Jurusan Berbeda)
-- Ambil pendaftar dengan id ganjil modulo 5 < 2
UPDATE tb_pendaftaran p
SET 
    id_smk_pilihan2 = id_smk_pilihan1,
    id_kejuruan_pilihan2 = (
        SELECT k.id_program 
        FROM tb_kejuruan k 
        WHERE k.id_smk = p.id_smk_pilihan1 
          AND k.id_program != p.id_kejuruan_pilihan1 
        ORDER BY RAND() 
        LIMIT 1
    ),
    jenis_pilihan = 'same_school',
    pilihan_mode = 'satu_sekolah_dua_jurusan',
    lokasi_tes_smk = id_smk_pilihan1
WHERE MOD(id_pendaftaran, 5) IN (0, 1);

-- Step 3: 30% Siswa - Opsi B (2 Sekolah Berbeda, 1 Jurusan Sama)
-- Cari sekolah lain yang punya jurusan dengan nama sama
UPDATE tb_pendaftaran p
SET 
    id_smk_pilihan2 = (
        SELECT k2.id_smk 
        FROM tb_kejuruan k1
        JOIN tb_kejuruan k2 ON k1.nama_kejuruan = k2.nama_kejuruan AND k1.id_smk != k2.id_smk
        WHERE k1.id_program = p.id_kejuruan_pilihan1
        ORDER BY RAND() 
        LIMIT 1
    ),
    id_kejuruan_pilihan2 = (
        SELECT k2.id_program 
        FROM tb_kejuruan k1
        JOIN tb_kejuruan k2 ON k1.nama_kejuruan = k2.nama_kejuruan AND k1.id_smk != k2.id_smk
        WHERE k1.id_program = p.id_kejuruan_pilihan1
        ORDER BY RAND() 
        LIMIT 1
    ),
    jenis_pilihan = 'diff_school',
    pilihan_mode = 'dua_sekolah_satu_jurusan',
    lokasi_tes_smk = id_smk_pilihan1 -- Tes di pilihan 1
WHERE MOD(id_pendaftaran, 5) IN (2, 3)
  AND id_smk_pilihan2 IS NULL; -- Belum diupdate sebelumnya

-- Step 4: 30% Siswa tetap single (tidak ada pilihan 2)
-- Sudah default dari Step 1

-- Verifikasi hasil
SELECT 
    jenis_pilihan,
    COUNT(*) as jumlah,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tb_pendaftaran), 1) as persen
FROM tb_pendaftaran 
GROUP BY jenis_pilihan;

-- Verifikasi Opsi A (same_school): SMK sama, jurusan beda
SELECT 'OPSI A - Same School' as tipe, COUNT(*) as jumlah
FROM tb_pendaftaran 
WHERE jenis_pilihan = 'same_school'
  AND id_smk_pilihan1 = id_smk_pilihan2
  AND id_kejuruan_pilihan1 != id_kejuruan_pilihan2;

-- Verifikasi Opsi B (diff_school): SMK beda, jurusan sama (by name)
SELECT 'OPSI B - Diff School' as tipe, COUNT(*) as jumlah
FROM tb_pendaftaran p
JOIN tb_kejuruan k1 ON p.id_kejuruan_pilihan1 = k1.id_program
JOIN tb_kejuruan k2 ON p.id_kejuruan_pilihan2 = k2.id_program
WHERE p.jenis_pilihan = 'diff_school'
  AND p.id_smk_pilihan1 != p.id_smk_pilihan2
  AND k1.nama_kejuruan = k2.nama_kejuruan;
