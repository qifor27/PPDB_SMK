-- =====================================================
-- INSERT 1 AKUN SISWA TEST
-- Database: dbesemka
-- =====================================================

INSERT INTO `tb_siswa` (
    `nisn`,
    `username`,
    `password`,
    `email`,
    `nama_lengkap`,
    `jenis_kelamin`,
    `tempat_lahir`,
    `tanggal_lahir`,
    `agama`,
    `alamat`,
    `kelurahan`,
    `kecamatan`,
    `kota`,
    `provinsi`,
    `no_hp`,
    `asal_sekolah`,
    `tahun_lulus`,
    `is_verified`,
    `created_at`
) VALUES (
    '1234567890',
    'siswa_test',
    MD5('siswa123'),
    'siswa.test@gmail.com',
    'Ahmad Fauzi',
    'L',
    'Padang',
    '2009-05-15',
    'Islam',
    'Jl. Sudirman No. 10',
    'Padang Barat',
    'Padang Barat',
    'Padang',
    'Sumatera Barat',
    '081234567890',
    'SMP Negeri 1 Padang',
    '2025',
    1,
    NOW()
);

-- =====================================================
-- INFO LOGIN:
-- Username: siswa_test
-- Password: siswa123
-- =====================================================
