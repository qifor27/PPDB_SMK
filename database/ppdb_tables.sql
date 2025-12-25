-- =====================================================
-- PPDB SMK - Additional Tables
-- Database: dbesemka
-- =====================================================

-- Tabel Superadmin (terpisah dari admin biasa)
CREATE TABLE IF NOT EXISTS `tb_superadmin` (
    `id_superadmin` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `nama_lengkap` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_superadmin`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Admin Sekolah
CREATE TABLE IF NOT EXISTS `tb_admin_sekolah` (
    `id_admin_sekolah` int(11) NOT NULL AUTO_INCREMENT,
    `id_smk` int(11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `nama_lengkap` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `no_telepon` varchar(20) DEFAULT NULL,
    `jabatan` varchar(50) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_admin_sekolah`),
    UNIQUE KEY `username` (`username`),
    KEY `id_smk` (`id_smk`),
    CONSTRAINT `fk_admin_smk` FOREIGN KEY (`id_smk`) REFERENCES `tb_smk` (`id_smk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Jalur Pendaftaran
CREATE TABLE IF NOT EXISTS `tb_jalur` (
    `id_jalur` int(11) NOT NULL AUTO_INCREMENT,
    `nama_jalur` varchar(50) NOT NULL,
    `kode_jalur` enum('afirmasi','prestasi','zonasi','kepindahan') NOT NULL,
    `deskripsi` text DEFAULT NULL,
    `persyaratan` text DEFAULT NULL,
    `kuota_persen` decimal(5,2) DEFAULT 0.00,
    `icon` varchar(50) DEFAULT NULL,
    `warna` varchar(20) DEFAULT NULL,
    `urutan` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_jalur`),
    UNIQUE KEY `kode_jalur` (`kode_jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel User/Siswa
CREATE TABLE IF NOT EXISTS `tb_siswa` (
    `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
    `nisn` varchar(20) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(100) NOT NULL,
    `nama_lengkap` varchar(150) NOT NULL,
    `jenis_kelamin` enum('L','P') NOT NULL,
    `tempat_lahir` varchar(100) NOT NULL,
    `tanggal_lahir` date NOT NULL,
    `agama` varchar(20) NOT NULL,
    `nik` varchar(20) DEFAULT NULL,
    `no_kk` varchar(20) DEFAULT NULL,
    `alamat` text NOT NULL,
    `rt` varchar(5) DEFAULT NULL,
    `rw` varchar(5) DEFAULT NULL,
    `kelurahan` varchar(100) DEFAULT NULL,
    `kecamatan` varchar(100) DEFAULT NULL,
    `kota` varchar(100) DEFAULT NULL,
    `provinsi` varchar(100) DEFAULT 'Sumatera Barat',
    `kode_pos` varchar(10) DEFAULT NULL,
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `no_telepon` varchar(20) DEFAULT NULL,
    `no_hp` varchar(20) DEFAULT NULL,
    `nama_ayah` varchar(100) DEFAULT NULL,
    `nik_ayah` varchar(20) DEFAULT NULL,
    `pekerjaan_ayah` varchar(100) DEFAULT NULL,
    `penghasilan_ayah` varchar(50) DEFAULT NULL,
    `nama_ibu` varchar(100) DEFAULT NULL,
    `nik_ibu` varchar(20) DEFAULT NULL,
    `pekerjaan_ibu` varchar(100) DEFAULT NULL,
    `penghasilan_ibu` varchar(50) DEFAULT NULL,
    `nama_wali` varchar(100) DEFAULT NULL,
    `no_hp_ortu` varchar(20) DEFAULT NULL,
    `asal_sekolah` varchar(200) DEFAULT NULL,
    `npsn_asal` varchar(20) DEFAULT NULL,
    `alamat_sekolah_asal` text DEFAULT NULL,
    `tahun_lulus` varchar(4) DEFAULT NULL,
    `no_ijazah` varchar(50) DEFAULT NULL,
    `no_skhun` varchar(50) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `is_verified` tinyint(1) DEFAULT 0,
    `verified_at` timestamp NULL DEFAULT NULL,
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_siswa`),
    UNIQUE KEY `nisn` (`nisn`),
    UNIQUE KEY `username` (`username`),
    KEY `idx_nama` (`nama_lengkap`),
    KEY `idx_asal_sekolah` (`asal_sekolah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Pendaftaran
CREATE TABLE IF NOT EXISTS `tb_pendaftaran` (
    `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT,
    `nomor_pendaftaran` varchar(30) NOT NULL,
    `id_siswa` int(11) NOT NULL,
    `id_smk_pilihan1` int(11) NOT NULL,
    `id_smk_pilihan2` int(11) DEFAULT NULL,
    `id_kejuruan_pilihan1` int(11) DEFAULT NULL,
    `id_kejuruan_pilihan2` int(11) DEFAULT NULL,
    `id_jalur` int(11) NOT NULL,
    `jarak_ke_sekolah` decimal(10,2) DEFAULT NULL,
    `skor_zonasi` decimal(10,4) DEFAULT NULL,
    `nilai_rata_rata` decimal(5,2) DEFAULT NULL,
    `nilai_un` decimal(5,2) DEFAULT NULL,
    `skor_prestasi` int(11) DEFAULT 0,
    `skor_akhir` decimal(10,4) DEFAULT NULL,
    `ranking` int(11) DEFAULT NULL,
    `status` enum('draft','submitted','verified','accepted','rejected','waiting') DEFAULT 'draft',
    `catatan_siswa` text DEFAULT NULL,
    `catatan_admin` text DEFAULT NULL,
    `alasan_penolakan` text DEFAULT NULL,
    `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
    `tanggal_submit` timestamp NULL DEFAULT NULL,
    `tanggal_verifikasi` timestamp NULL DEFAULT NULL,
    `tanggal_pengumuman` timestamp NULL DEFAULT NULL,
    `verified_by` int(11) DEFAULT NULL,
    `tahun_ajaran` varchar(10) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_pendaftaran`),
    UNIQUE KEY `nomor_pendaftaran` (`nomor_pendaftaran`),
    KEY `id_siswa` (`id_siswa`),
    KEY `id_smk_pilihan1` (`id_smk_pilihan1`),
    KEY `id_smk_pilihan2` (`id_smk_pilihan2`),
    KEY `id_jalur` (`id_jalur`),
    KEY `idx_status` (`status`),
    KEY `idx_tahun` (`tahun_ajaran`),
    CONSTRAINT `fk_pendaftaran_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id_siswa`) ON DELETE CASCADE,
    CONSTRAINT `fk_pendaftaran_smk1` FOREIGN KEY (`id_smk_pilihan1`) REFERENCES `tb_smk` (`id_smk`),
    CONSTRAINT `fk_pendaftaran_smk2` FOREIGN KEY (`id_smk_pilihan2`) REFERENCES `tb_smk` (`id_smk`),
    CONSTRAINT `fk_pendaftaran_jalur` FOREIGN KEY (`id_jalur`) REFERENCES `tb_jalur` (`id_jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Dokumen
CREATE TABLE IF NOT EXISTS `tb_dokumen` (
    `id_dokumen` int(11) NOT NULL AUTO_INCREMENT,
    `id_pendaftaran` int(11) NOT NULL,
    `jenis_dokumen` varchar(100) NOT NULL,
    `nama_file` varchar(255) NOT NULL,
    `path_file` varchar(500) NOT NULL,
    `ukuran_file` int(11) DEFAULT NULL,
    `status_verifikasi` enum('pending','valid','invalid') DEFAULT 'pending',
    `catatan` varchar(255) DEFAULT NULL,
    `verified_by` int(11) DEFAULT NULL,
    `verified_at` timestamp NULL DEFAULT NULL,
    `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_dokumen`),
    KEY `id_pendaftaran` (`id_pendaftaran`),
    KEY `idx_jenis` (`jenis_dokumen`),
    CONSTRAINT `fk_dokumen_pendaftaran` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tb_pendaftaran` (`id_pendaftaran`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Prestasi Siswa (untuk jalur prestasi)
CREATE TABLE IF NOT EXISTS `tb_prestasi_siswa` (
    `id_prestasi_siswa` int(11) NOT NULL AUTO_INCREMENT,
    `id_pendaftaran` int(11) NOT NULL,
    `nama_prestasi` varchar(200) NOT NULL,
    `jenis_prestasi` enum('Akademik','Non-Akademik','Olahraga','Seni','Lainnya') NOT NULL,
    `tingkat` enum('Kota/Kabupaten','Provinsi','Nasional','Internasional') NOT NULL,
    `tahun` int(11) NOT NULL,
    `penyelenggara` varchar(200) DEFAULT NULL,
    `peringkat` varchar(50) DEFAULT NULL,
    `file_sertifikat` varchar(255) DEFAULT NULL,
    `poin` int(11) DEFAULT 0,
    `status_verifikasi` enum('pending','valid','invalid') DEFAULT 'pending',
    `catatan` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_prestasi_siswa`),
    KEY `id_pendaftaran` (`id_pendaftaran`),
    CONSTRAINT `fk_prestasi_pendaftaran` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tb_pendaftaran` (`id_pendaftaran`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Kuota per SMK per Jalur
CREATE TABLE IF NOT EXISTS `tb_kuota` (
    `id_kuota` int(11) NOT NULL AUTO_INCREMENT,
    `id_smk` int(11) NOT NULL,
    `id_jalur` int(11) NOT NULL,
    `tahun_ajaran` varchar(10) NOT NULL,
    `kuota` int(11) NOT NULL DEFAULT 0,
    `terisi` int(11) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_kuota`),
    UNIQUE KEY `unique_kuota` (`id_smk`,`id_jalur`,`tahun_ajaran`),
    KEY `id_smk` (`id_smk`),
    KEY `id_jalur` (`id_jalur`),
    CONSTRAINT `fk_kuota_smk` FOREIGN KEY (`id_smk`) REFERENCES `tb_smk` (`id_smk`) ON DELETE CASCADE,
    CONSTRAINT `fk_kuota_jalur` FOREIGN KEY (`id_jalur`) REFERENCES `tb_jalur` (`id_jalur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Pengaturan Sistem
CREATE TABLE IF NOT EXISTS `tb_pengaturan` (
    `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT,
    `key_pengaturan` varchar(100) NOT NULL,
    `value_pengaturan` text DEFAULT NULL,
    `deskripsi` varchar(255) DEFAULT NULL,
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_pengaturan`),
    UNIQUE KEY `key_pengaturan` (`key_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Pengumuman
CREATE TABLE IF NOT EXISTS `tb_pengumuman` (
    `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT,
    `judul` varchar(200) NOT NULL,
    `isi` text NOT NULL,
    `jenis` enum('info','warning','important') DEFAULT 'info',
    `tanggal_mulai` date DEFAULT NULL,
    `tanggal_akhir` date DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_by` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_pengumuman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Log Aktivitas
CREATE TABLE IF NOT EXISTS `tb_log_aktivitas` (
    `id_log` int(11) NOT NULL AUTO_INCREMENT,
    `user_type` varchar(20) NOT NULL,
    `user_id` int(11) NOT NULL,
    `aksi` varchar(100) NOT NULL,
    `keterangan` text DEFAULT NULL,
    `ip_address` varchar(50) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_log`),
    KEY `idx_user` (`user_type`,`user_id`),
    KEY `idx_aksi` (`aksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert default superadmin
INSERT INTO `tb_superadmin` (`username`, `password`, `nama_lengkap`, `email`) VALUES
('superadmin', MD5('superadmin123'), 'Super Administrator', 'superadmin@ppdb-smk.id')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- Insert default jalur pendaftaran
INSERT INTO `tb_jalur` (`nama_jalur`, `kode_jalur`, `deskripsi`, `persyaratan`, `kuota_persen`, `icon`, `warna`, `urutan`) VALUES
('Jalur Afirmasi', 'afirmasi', 'Jalur khusus untuk siswa dari keluarga kurang mampu yang memiliki bukti kepesertaan program bantuan pemerintah seperti KIP, PKH, atau KIS.', 
'1. Kartu Indonesia Pintar (KIP) / Kartu Keluarga Sejahtera (KKS)\n2. Surat Keterangan Tidak Mampu (SKTM) dari Kelurahan\n3. Kartu PKH atau KIS yang masih berlaku\n4. Raport semester terakhir\n5. Ijazah/SKL SMP/MTs', 
15.00, 'bi-heart-fill', '#8B5CF6', 1),

('Jalur Prestasi', 'prestasi', 'Jalur untuk siswa berprestasi di bidang akademik maupun non-akademik dengan sertifikat/piagam kejuaraan minimal tingkat Kota/Kabupaten.', 
'1. Sertifikat/Piagam Prestasi asli\n2. Minimal prestasi tingkat Kota/Kabupaten\n3. Prestasi 3 tahun terakhir\n4. Raport semester terakhir\n5. Ijazah/SKL SMP/MTs\n6. Surat rekomendasi dari sekolah asal', 
25.00, 'bi-trophy-fill', '#F59E0B', 2),

('Jalur Zonasi', 'zonasi', 'Jalur berdasarkan jarak domisili ke sekolah tujuan. Prioritas diberikan kepada siswa yang tinggal dalam radius terdekat dari sekolah.', 
'1. Kartu Keluarga (KK) yang berdomisili di Kota Padang\n2. Minimal domisili 1 tahun sebelum pendaftaran\n3. Bukti kepemilikan/kontrak rumah (jika diperlukan)\n4. Raport semester terakhir\n5. Ijazah/SKL SMP/MTs', 
50.00, 'bi-geo-alt-fill', '#10B981', 3),

('Jalur Kepindahan Orang Tua', 'kepindahan', 'Jalur untuk siswa yang orang tuanya pindah tugas karena kedinasan (ASN, TNI, POLRI) atau alasan lain yang dapat dibuktikan.', 
'1. Surat Keputusan (SK) Pindah Tugas Orang Tua\n2. Surat Keterangan dari instansi terkait\n3. Kartu Keluarga baru\n4. Raport semester terakhir\n5. Ijazah/SKL SMP/MTs\n6. Surat pindah dari sekolah asal', 
10.00, 'bi-arrow-left-right', '#3B82F6', 4)
ON DUPLICATE KEY UPDATE `nama_jalur` = VALUES(`nama_jalur`);

-- Insert default pengaturan
INSERT INTO `tb_pengaturan` (`key_pengaturan`, `value_pengaturan`, `deskripsi`) VALUES
('tahun_ajaran', '2025/2026', 'Tahun ajaran aktif'),
('tanggal_mulai', '2025-01-15', 'Tanggal mulai pendaftaran'),
('tanggal_akhir', '2025-03-31', 'Tanggal akhir pendaftaran'),
('tanggal_pengumuman', '2025-04-15', 'Tanggal pengumuman hasil'),
('tanggal_daftar_ulang_mulai', '2025-04-20', 'Tanggal mulai daftar ulang'),
('tanggal_daftar_ulang_akhir', '2025-04-30', 'Tanggal akhir daftar ulang'),
('radius_zonasi', '3000', 'Radius zonasi dalam meter'),
('is_open', '1', 'Status pendaftaran dibuka/ditutup'),
('max_pilihan_sekolah', '2', 'Maksimal pilihan sekolah'),
('contact_email', 'ppdb@smkpadang.id', 'Email kontak'),
('contact_phone', '0751-123456', 'Telepon kontak'),
('contact_address', 'Jl. Pendidikan No. 1, Padang', 'Alamat kontak'),
('site_name', 'PPDB SMK Kota Padang', 'Nama situs'),
('site_description', 'Sistem Penerimaan Peserta Didik Baru SMK Kota Padang Tahun Ajaran 2025/2026', 'Deskripsi situs')
ON DUPLICATE KEY UPDATE `value_pengaturan` = VALUES(`value_pengaturan`);

-- Insert sample admin sekolah for SMAK Padang
INSERT INTO `tb_admin_sekolah` (`id_smk`, `username`, `password`, `nama_lengkap`, `email`, `jabatan`) VALUES
(1, 'admin_smak', MD5('admin123'), 'Admin SMAK Padang', 'admin@smakpadang.sch.id', 'Operator PPDB')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- Insert sample kuota for all SMK and jalur
INSERT IGNORE INTO `tb_kuota` (`id_smk`, `id_jalur`, `tahun_ajaran`, `kuota`)
SELECT s.id_smk, j.id_jalur, '2025/2026', 
       CASE j.kode_jalur
           WHEN 'afirmasi' THEN 30
           WHEN 'prestasi' THEN 50
           WHEN 'zonasi' THEN 100
           WHEN 'kepindahan' THEN 20
       END as kuota
FROM tb_smk s
CROSS JOIN tb_jalur j;

