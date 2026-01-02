-- =====================================================
-- SEED ADMIN SEKOLAH - Akun Admin untuk setiap SMK
-- Generated: 2026-01-02
-- Password default: admin123 (hashed with password_hash)
-- =====================================================

-- Hapus data lama jika ada
DELETE FROM `tb_admin_sekolah`;
ALTER TABLE `tb_admin_sekolah` AUTO_INCREMENT = 1;

-- Password hash untuk 'admin123'
SET @password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- =====================================================
-- AKUN ADMIN SEKOLAH
-- =====================================================
INSERT INTO `tb_admin_sekolah` (`id_smk`, `username`, `password`, `nama_lengkap`, `email`, `status`) VALUES
-- 1. SMAK Padang
(1, 'admin_smak', @password_hash, 'Admin SMAK Padang', 'admin@smakpadang.sch.id', 'aktif'),

-- 2. SMKN 4 Padang (Seni Rupa)
(2, 'admin_smkn4', @password_hash, 'Admin SMKN 4 Padang', 'admin@smkn4padang.sch.id', 'aktif'),

-- 3. SMKN 7 Padang (Seni Pertunjukan)
(3, 'admin_smkn7', @password_hash, 'Admin SMKN 7 Padang', 'admin@smkn7padang.sch.id', 'aktif'),

-- 4. SMKN 8 Padang
(4, 'admin_smkn8', @password_hash, 'Admin SMKN 8 Padang', 'admin@smkn8padang.sch.id', 'aktif'),

-- 5. SMKN 1 Padang
(6, 'admin_smkn1', @password_hash, 'Admin SMKN 1 Padang', 'admin@smkn1padang.sch.id', 'aktif'),

-- 6. SMKN 10 Padang (Pelayaran)
(7, 'admin_smkn10', @password_hash, 'Admin SMKN 10 Padang', 'admin@smkn10padang.sch.id', 'aktif'),

-- 7. SMTI Padang
(8, 'admin_smti', @password_hash, 'Admin SMTI Padang', 'admin@smtipadang.sch.id', 'aktif'),

-- 8. SMK PP Negeri Padang (Pertanian)
(9, 'admin_smkpp', @password_hash, 'Admin SMK PP Negeri Padang', 'admin@smkpppadang.sch.id', 'aktif'),

-- 9. SMKN 9 Padang
(10, 'admin_smkn9', @password_hash, 'Admin SMKN 9 Padang', 'admin@smkn9padang.sch.id', 'aktif'),

-- 10. SMKN 3 Padang
(11, 'admin_smkn3', @password_hash, 'Admin SMKN 3 Padang', 'admin@smkn3padang.sch.id', 'aktif'),

-- 11. SMKN 2 Padang
(12, 'admin_smkn2', @password_hash, 'Admin SMKN 2 Padang', 'admin@smkn2padang.sch.id', 'aktif'),

-- 12. SMKN 5 Padang
(13, 'admin_smkn5', @password_hash, 'Admin SMKN 5 Padang', 'admin@smkn5padang.sch.id', 'aktif'),

-- 13. SMKN 6 Padang
(14, 'admin_smkn6', @password_hash, 'Admin SMKN 6 Padang', 'admin@smkn6padang.sch.id', 'aktif'),

-- 14. SMKN 1 Sumatera Barat
(15, 'admin_smkn1sb', @password_hash, 'Admin SMKN 1 Sumatera Barat', 'admin@smkn1sumbar.sch.id', 'aktif');

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
    a.status
FROM tb_admin_sekolah a
JOIN tb_smk s ON a.id_smk = s.id_smk
ORDER BY a.id_admin_sekolah;
