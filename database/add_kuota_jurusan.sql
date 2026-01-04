-- =====================================================
-- Add Kuota per Jurusan Table
-- PPDB SMK - Sistem Penentuan Kelulusan
-- =====================================================

-- Tabel kuota per jurusan per tahun
CREATE TABLE IF NOT EXISTS `tb_kuota_jurusan` (
    `id_kuota_jurusan` INT(11) NOT NULL AUTO_INCREMENT,
    `id_smk` INT(11) NOT NULL,
    `id_kejuruan` INT(11) NOT NULL,
    `tahun_ajaran` VARCHAR(10) NOT NULL DEFAULT '2025/2026',
    `kuota` INT(11) NOT NULL DEFAULT 36,
    `terisi` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_kuota_jurusan`),
    UNIQUE KEY `unique_kuota` (`id_smk`, `id_kejuruan`, `tahun_ajaran`),
    KEY `idx_smk` (`id_smk`),
    KEY `idx_kejuruan` (`id_kejuruan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default kuota 36 per jurusan dari tb_kejuruan
INSERT IGNORE INTO tb_kuota_jurusan (id_smk, id_kejuruan, tahun_ajaran, kuota)
SELECT id_smk, id_program, '2025/2026', 36 FROM tb_kejuruan;

-- Verifikasi
SELECT 
    s.nama_sekolah,
    k.nama_kejuruan,
    q.kuota,
    q.terisi
FROM tb_kuota_jurusan q
JOIN tb_smk s ON q.id_smk = s.id_smk
JOIN tb_kejuruan k ON q.id_kejuruan = k.id_program
ORDER BY s.nama_sekolah, k.nama_kejuruan;
