-- =====================================================
-- SEED ADMIN SEKOLAH - Akun Admin untuk setiap SMK
-- Generated: 2026-01-03
-- Password default: admin123 (hashed with MD5)
-- =====================================================

-- Hapus data lama jika ada
DELETE FROM `tb_admin_sekolah`;
ALTER TABLE `tb_admin_sekolah` AUTO_INCREMENT = 1;

-- Password hash MD5 untuk 'admin123' = 0192023a7bbd73250516f069df18b500
SET @password_hash = '0192023a7bbd73250516f069df18b500';

-- =====================================================
-- AKUN ADMIN SEKOLAH
-- =====================================================
INSERT INTO `tb_admin_sekolah` (`id_smk`, `username`, `password`, `nama_lengkap`, `email`, `is_active`) VALUES
-- 1. SMAK Padang (id_smk = 1)
(1, 'admin_smak', @password_hash, 'Admin SMAK Padang', 'admin@smakpadang.sch.id', 1),

-- 2. SMKN 4 Padang - Seni Rupa (id_smk = 2)
(2, 'admin_smkn4', @password_hash, 'Admin SMKN 4 Padang', 'admin@smkn4padang.sch.id', 1),

-- 3. SMKN 7 Padang - Seni Pertunjukan (id_smk = 3)
(3, 'admin_smkn7', @password_hash, 'Admin SMKN 7 Padang', 'admin@smkn7padang.sch.id', 1),

-- 4. SMKN 8 Padang (id_smk = 4)
(4, 'admin_smkn8', @password_hash, 'Admin SMKN 8 Padang', 'admin@smkn8padang.sch.id', 1),

-- 5. SMKN 1 Padang (id_smk = 6)
(6, 'admin_smkn1', @password_hash, 'Admin SMKN 1 Padang', 'admin@smkn1padang.sch.id', 1),

-- 6. SMKN 10 Padang - Pelayaran (id_smk = 7)
(7, 'admin_smkn10', @password_hash, 'Admin SMKN 10 Padang', 'admin@smkn10padang.sch.id', 1),

-- 7. SMTI Padang (id_smk = 8)
(8, 'admin_smti', @password_hash, 'Admin SMTI Padang', 'admin@smtipadang.sch.id', 1),

-- 8. SMK PP Negeri Padang - Pertanian (id_smk = 9)
(9, 'admin_smkpp', @password_hash, 'Admin SMK PP Negeri Padang', 'admin@smkpppadang.sch.id', 1),

-- 9. SMKN 9 Padang (id_smk = 10)
(10, 'admin_smkn9', @password_hash, 'Admin SMKN 9 Padang', 'admin@smkn9padang.sch.id', 1),

-- 10. SMKN 3 Padang (id_smk = 11)
(11, 'admin_smkn3', @password_hash, 'Admin SMKN 3 Padang', 'admin@smkn3padang.sch.id', 1),

-- 11. SMKN 2 Padang (id_smk = 12)
(12, 'admin_smkn2', @password_hash, 'Admin SMKN 2 Padang', 'admin@smkn2padang.sch.id', 1),

-- 12. SMKN 5 Padang (id_smk = 13)
(13, 'admin_smkn5', @password_hash, 'Admin SMKN 5 Padang', 'admin@smkn5padang.sch.id', 1),

-- 13. SMKN 6 Padang (id_smk = 14)
(14, 'admin_smkn6', @password_hash, 'Admin SMKN 6 Padang', 'admin@smkn6padang.sch.id', 1),

-- 14. SMKN 1 Sumatera Barat (id_smk = 15)
(15, 'admin_smkn1sb', @password_hash, 'Admin SMKN 1 Sumatera Barat', 'admin@smkn1sumbar.sch.id', 1);

-- =====================================================
-- DAFTAR AKUN ADMIN SEKOLAH
-- =====================================================
-- | Username        | Password  | Sekolah               |
-- |-----------------|-----------|------------------------|
-- | admin_smak      | admin123  | SMAK Padang            |
-- | admin_smkn1     | admin123  | SMKN 1 Padang          |
-- | admin_smkn2     | admin123  | SMKN 2 Padang          |
-- | admin_smkn3     | admin123  | SMKN 3 Padang          |
-- | admin_smkn4     | admin123  | SMKN 4 Padang          |
-- | admin_smkn5     | admin123  | SMKN 5 Padang          |
-- | admin_smkn6     | admin123  | SMKN 6 Padang          |
-- | admin_smkn7     | admin123  | SMKN 7 Padang          |
-- | admin_smkn8     | admin123  | SMKN 8 Padang          |
-- | admin_smkn9     | admin123  | SMKN 9 Padang          |
-- | admin_smkn10    | admin123  | SMKN 10 Padang         |
-- | admin_smti      | admin123  | SMTI Padang            |
-- | admin_smkpp     | admin123  | SMK PP Negeri Padang   |
-- | admin_smkn1sb   | admin123  | SMKN 1 Sumatera Barat  |
-- =====================================================

-- Verifikasi jumlah akun
SELECT 
    a.username, 
    a.nama_lengkap, 
    s.nama_sekolah,
    a.is_active
FROM tb_admin_sekolah a
JOIN tb_smk s ON a.id_smk = s.id_smk
ORDER BY a.id_admin_sekolah;
