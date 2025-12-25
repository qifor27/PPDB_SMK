-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Des 2025 pada 07.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbesemka`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_Lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`, `nama_Lengkap`, `email`, `no_telepon`, `foto`) VALUES
(1, 'rofiq', '7b791f5362125321a32604b204f70381', 'Muhammad Rofiqul Islamy', 'rofiqislamy88@gmail.com', '0895337252897', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_galeri`
--

CREATE TABLE `tb_galeri` (
  `id_galeri` int(11) NOT NULL,
  `id_smk` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_galeri`
--

INSERT INTO `tb_galeri` (`id_galeri`, `id_smk`, `foto`, `keterangan`, `urutan`, `created_at`, `updated_at`) VALUES
(5, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c081ca.jpg', '', 1, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(6, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c095a0.jpg', '', 2, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(7, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0abb6.jpg', '', 3, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(8, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0bb01.jpg', '', 4, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(9, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0c549.jpg', '', 5, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(10, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0df0c.jpg', '', 6, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(11, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0ed6f.jpg', '', 7, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(12, 1, 'uploads/galeri/smk_1/foto_1764448140_692b578c0f4e6.jpg', '', 8, '2025-11-29 20:29:00', '2025-11-29 20:29:00'),
(13, 6, 'uploads/galeri/smk_6/foto_1764448279_692b58176d616.jpg', '', 1, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(14, 6, 'uploads/galeri/smk_6/foto_1764448279_692b58176e433.jpg', '', 2, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(15, 6, 'uploads/galeri/smk_6/foto_1764448279_692b58176ed20.jpg', '', 3, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(16, 6, 'uploads/galeri/smk_6/foto_1764448279_692b5817715b1.jpg', '', 4, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(17, 6, 'uploads/galeri/smk_6/foto_1764448279_692b5817725de.jpg', '', 5, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(18, 6, 'uploads/galeri/smk_6/foto_1764448279_692b581772e85.jpg', '', 6, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(19, 6, 'uploads/galeri/smk_6/foto_1764448279_692b581773b35.jpg', '', 7, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(20, 6, 'uploads/galeri/smk_6/foto_1764448279_692b581774507.jpg', '', 8, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(21, 6, 'uploads/galeri/smk_6/foto_1764448279_692b581775379.jpg', '', 9, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(22, 6, 'uploads/galeri/smk_6/foto_1764448279_692b5817762ca.jpg', '', 10, '2025-11-29 20:31:19', '2025-11-29 20:31:19'),
(23, 2, 'uploads/galeri/smk_2/foto_1764448301_692b582d545ae.jpg', '', 1, '2025-11-29 20:31:41', '2025-11-29 20:31:41'),
(24, 2, 'uploads/galeri/smk_2/foto_1764448301_692b582d55195.jpg', '', 2, '2025-11-29 20:31:41', '2025-11-29 20:31:41'),
(25, 2, 'uploads/galeri/smk_2/foto_1764448301_692b582d57d5c.jpg', '', 3, '2025-11-29 20:31:41', '2025-11-29 20:31:41'),
(26, 2, 'uploads/galeri/smk_2/foto_1764448301_692b582d58cd2.jpg', '', 4, '2025-11-29 20:31:41', '2025-11-29 20:31:41'),
(27, 3, 'uploads/galeri/smk_3/foto_1764448319_692b583f935a5.jpg', '', 1, '2025-11-29 20:31:59', '2025-11-29 20:31:59'),
(28, 3, 'uploads/galeri/smk_3/foto_1764448319_692b583f944b7.jpg', '', 2, '2025-11-29 20:31:59', '2025-11-29 20:31:59'),
(29, 4, 'uploads/galeri/smk_4/foto_1764448339_692b5853be5c7.jpg', '', 1, '2025-11-29 20:32:19', '2025-11-29 20:32:19'),
(30, 4, 'uploads/galeri/smk_4/foto_1764448339_692b5853c0f0c.jpg', '', 2, '2025-11-29 20:32:19', '2025-11-29 20:32:19'),
(31, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868cb420.jpg', '', 3, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(32, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868cc0ad.jpg', '', 4, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(33, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868ccb8e.jpg', '', 5, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(34, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868cdfc5.jpg', '', 6, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(35, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868ceece.jpg', '', 7, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(36, 4, 'uploads/galeri/smk_4/foto_1764448360_692b5868cfd8a.jpg', '', 8, '2025-11-29 20:32:40', '2025-11-29 20:32:40'),
(37, 7, 'uploads/galeri/smk_7/foto_1764448384_692b588053d24.jpg', '', 1, '2025-11-29 20:33:04', '2025-11-29 20:33:04'),
(38, 7, 'uploads/galeri/smk_7/foto_1764448384_692b588054861.jpg', '', 2, '2025-11-29 20:33:04', '2025-11-29 20:33:04'),
(39, 7, 'uploads/galeri/smk_7/foto_1764448384_692b588055457.jpg', '', 3, '2025-11-29 20:33:04', '2025-11-29 20:33:04'),
(40, 7, 'uploads/galeri/smk_7/foto_1764448384_692b588057851.jpg', '', 4, '2025-11-29 20:33:04', '2025-11-29 20:33:04'),
(41, 11, 'uploads/galeri/smk_11/foto_1764448410_692b589a26ae6.jpg', '', 1, '2025-11-29 20:33:30', '2025-11-29 20:33:30'),
(42, 11, 'uploads/galeri/smk_11/foto_1764448410_692b589a28a36.jpg', '', 2, '2025-11-29 20:33:30', '2025-11-29 20:33:30'),
(43, 11, 'uploads/galeri/smk_11/foto_1764448410_692b589a29af8.jpg', '', 3, '2025-11-29 20:33:30', '2025-11-29 20:33:30'),
(44, 10, 'uploads/galeri/smk_10/foto_1764448449_692b58c191df8.jpg', '', 1, '2025-11-29 20:34:09', '2025-11-29 20:34:09'),
(45, 10, 'uploads/galeri/smk_10/foto_1764448449_692b58c19292d.jpg', '', 2, '2025-11-29 20:34:09', '2025-11-29 20:34:09'),
(46, 10, 'uploads/galeri/smk_10/foto_1764448449_692b58c193386.jpg', '', 3, '2025-11-29 20:34:09', '2025-11-29 20:34:09'),
(47, 10, 'uploads/galeri/smk_10/foto_1764448449_692b58c196a01.jpg', '', 4, '2025-11-29 20:34:09', '2025-11-29 20:34:09'),
(48, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d7338a5.jpg', '', 1, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(49, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d73469f.jpg', '', 2, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(50, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d7366a6.jpg', '', 3, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(51, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d736f5f.jpg', '', 4, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(52, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d7377fb.jpg', '', 5, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(53, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d7381fc.jpg', '', 6, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(54, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d7395f9.jpg', '', 7, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(55, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d73a406.jpg', '', 8, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(56, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d73af4b.jpg', '', 9, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(57, 9, 'uploads/galeri/smk_9/foto_1764448471_692b58d73b6a5.jpg', '', 10, '2025-11-29 20:34:31', '2025-11-29 20:34:31'),
(58, 8, 'uploads/galeri/smk_8/foto_1764448509_692b58fd0439e.jpg', '', 1, '2025-11-29 20:35:09', '2025-11-29 20:35:09'),
(59, 8, 'uploads/galeri/smk_8/foto_1764448509_692b58fd04ed3.jpg', '', 2, '2025-11-29 20:35:09', '2025-11-29 20:35:09'),
(60, 8, 'uploads/galeri/smk_8/foto_1764448509_692b58fd079da.jpg', '', 3, '2025-11-29 20:35:09', '2025-11-29 20:35:09'),
(61, 12, 'uploads/galeri/smk_12/foto_1765149096_693609a8b8f10.jpg', '', 1, '2025-12-07 23:11:36', '2025-12-07 23:11:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kejuruan`
--

CREATE TABLE `tb_kejuruan` (
  `id_program` int(11) NOT NULL,
  `id_smk` int(11) NOT NULL,
  `nama_kejuruan` varchar(200) NOT NULL,
  `kode_kejuruan` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kejuruan`
--

INSERT INTO `tb_kejuruan` (`id_program`, `id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(1, 1, 'Kimia Analisis', 'KA', 'menganalisis kimia'),
(2, 11, 'Teknik Komputer dan Jaringan', 'TKJ', 'Mempelajari jaringan komputer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_prestasi`
--

CREATE TABLE `tb_prestasi` (
  `id_prestasi` int(11) NOT NULL,
  `id_smk` int(11) NOT NULL,
  `nama_prestasi` varchar(200) NOT NULL,
  `jenis_prestasi` enum('Akademik','Non-Akademik') NOT NULL,
  `tahun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_smk`
--

CREATE TABLE `tb_smk` (
  `id_smk` int(11) NOT NULL,
  `npsn` varchar(20) NOT NULL,
  `nama_sekolah` varchar(200) NOT NULL,
  `alamat` text NOT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `jumlah_siswa` int(11) NOT NULL,
  `jumlah_guru` int(11) NOT NULL,
  `nama_kepsek` varchar(100) NOT NULL,
  `foto_utama` varchar(225) NOT NULL,
  `foto_tambahan` varchar(225) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_smk`
--

INSERT INTO `tb_smk` (`id_smk`, `npsn`, `nama_sekolah`, `alamat`, `kelurahan`, `kecamatan`, `kode_pos`, `latitude`, `longitude`, `telepon`, `email`, `website`, `jumlah_siswa`, `jumlah_guru`, `nama_kepsek`, `foto_utama`, `foto_tambahan`, `deskripsi`) VALUES
(1, '', 'SMAK PADANG', 'Jalan Alai Pauh V No. 13, Kelurahan Kapalo Koto, Kecamatan Pauh', 'Kapalo Koto\r\n', 'Pauh\r\n', ' 25163', -0.93305127, 100.44104181, '', 'smakpadang@gmail.com\r\n', 'https://www.smk-smakpa.sch.id/\r\n', 1075, 69, ' Drs. Nasir', '', '', 'SMK-SMAK Padang adalah sekolah menengah kejuruan 	negeri yang berfokus pada pendidikan dan pelatihan analis kimia. Sekolah 	ini berada di bawah Kementerian Perindustrian dan memiliki reputasi 	sebagai sekolah unggulan yang mempersiapkan lulusannya untuk bersaing di 	dunia industri global. Selain kurikulum nasional, SMK-SMAK Padang 	menekankan pada kedisiplinan, integritas, dan karakter. '),
(2, '10304850', 'SMK Negeri 4 Padang', 'Jl. Raya Padang - Indarung', 'Cangkeh Nan XX', 'Lubuk Begalung', ' 25159', -0.94953670, 100.41287590, '0751711654', 'smkn4padang@gmail.com 	', 'https://smk4-padang.sch.id/', 917, 67, 'Marnetti Yuniengsih B', '', '', 'SMK Negeri 4 Padang adalah sekolah kejuruan negeri yang berlokasi di Lubuk Begalung, Kota Padang, Sumatera Barat, dan dikenal sebagai sekolah seni rupa dengan sejarah panjang sejak tahun 1955. Sekolah ini memiliki berbagai jurusan kreatif seperti Seni Rupa, Desain Komunikasi Visual, Animasi, Teknik Furnitur, Desain & Produksi Kriya (Batik dan Tekstil), Broadcasting & Produksi Film (Multimedia), serta Bisnis Daring dan Pemasaran. '),
(3, '10304188', 'SMK Negeri 7 Padang', 'JL. CENGKEH LUBUK BEGALUNG', 'Cengkeh Nan XX', 'Lubuk Begalung', '25225', -0.94953670, 100.41287590, '075171576', 'smk7padang@ymail.com', 'https://www.smk7padang.sch.id/', 535, 54, 'Dra. Evy Fitriana M.M', '', '', 'SMK Negeri 7 Padang adalah sekolah kejuruan di bidang seni pertunjukan etnis Minang, yang didirikan pada tahun 1965. Sekolah ini berlokasi di Jalan Cengkeh, Lubuk Begalung, Padang, dan memiliki empat program keahlian: Seni Karawitan Minang, Seni Tari Minang, Seni Teater, dan Seni Musik Non-Klasik. '),
(4, '10307616', 'SMK Negeri 8 Padang', 'Jln. Raya Padang - Indarung', 'Cengkeh Nan XX', 'Lubuk Begalung', '25143', -0.95175900, 100.41541700, '075171815', 'smknegeri8pdg@yahoo.com\r\n', 'www.smk-8padang.sch.id', 1000, 81, 'Desnatalia S.Pd, M.Pd', '', '', 'SMK Negeri 8 Padang merupakan salah satu sekolah menengah kejuruan negeri di Kota Padang yang berlokasi di kawasan Lubuk Begalung. Sekolah ini berfokus pada pengembangan kompetensi di bidang Teknologi, Bisnis, dan Industri Kreatif,dengan tujuan mencetak lulusan yang terampil, mandiri, serta mampu beradaptasi dengan perkembangan dunia kerja modern.'),
(6, '10304848', 'SMKN 1 padang', 'Jl. M. Yunus', 'Kampung Kalawi', NULL, '25153', -0.92297950, 100.38776800, '0751-27917', 'smkn1pdgsumbar@yahoo.com', 'http://smkn1padang.wordpress.com', 0, 107, 'Delfauzul, S.Pd., M.Pd.', '', '', ''),
(7, '10307617', 'SMKN 10 Padang', 'Jl. Padang – Bengkulu Km. 15, Balai Gadang', 'Balai Gadang', NULL, '25171', -0.82146210, 100.30256470, '0751-484305', 'smkn10padang@gmail.com', 'http://www.smkn10padang.id1945.com', 495, 40, 'Herawaty', '', '', ''),
(8, '10308148', 'SMTI', 'JL. IR. H. JUANDA NO. 2, Kec. Padang Barat, Kota Padang, Prov. 	Sumatera Barat', 'Rimbo Kaluang', NULL, '25115', -0.92991440, 100.35027570, '07517053522', 'smtipadang@gmail.com', 'http://smtipadang.sch.id', 788, 49, 'Sylvi, S.T, M.Si', '', '', ''),
(9, '69734159', 'SMKN PP', 'Jl. Pertanian', 'koto tangah', NULL, '25175', -0.84524920, 100.38184950, '0751495809', 'smkppnegeripadang@gmail.com', 'https://sppn-padang.sch.id/', 415, 39, 'Dra. Evy Fitriana, M.M', '', '', ''),
(10, '10304853', 'SMKN 9 Padang', ' Jl. S. Parman No. 230\r\n', 'Ulak Karang Selatan', NULL, '25134', -0.95412770, 100.35707040, '075134719', 'smknegeri9padang@gmail.com', ' https://smk9pdg.sch.id/', 1, 68, 'Syamsul Mardan', '', '', ''),
(11, '10304849', 'SMKN 3 Padang', 'Jl. Dr. Sutomo No. 1\r\n', 'Ujung Gurun', NULL, '25118', -0.94519860, 100.35973500, '075134373', 'esemka3pdg@yahoo.co.id', 'https://smk3-padang.sch.id/', 1354, 81, 'Lily Sumeri ', '', '', ''),
(12, '10304847', 'SMKN 2 Padang', ' Jl. Dr. Sutomo No. 5, Simpang Haru, Padang Timur, Kota Padang, Sumatera Barat.', 'Simpang Haru', NULL, '25123', -0.94510000, 100.37870000, ' 0751-21930', 'smkn2_padang@yahoo.co.id', '(belum)', 1250, 85, 'Sahfalefi, M.Pd', '', '', ''),
(13, '10304851', 'SMKN 5 Padang', 'Jl. Beringin No. 4', 'Lolong Belanti', NULL, ' 25136', -0.92166270, 100.34920630, '7053201', 'smkn5padang@ymail.com', 'smkn5padang@ymail.com', 1331, 93, 'izka Fauzi Yosfi', '', '', ''),
(14, '10303507', 'SMKN 6 Padang', 'Jln. Suliki No. 1, Padang', ' Jati Baru', NULL, ' 25129', -0.93699890, 100.36214570, '075121907 ', 'smksixpdg@gmail.com', 'https://www.smk6-padang.sch.id/', 1096, 89, 'R D S Deta Mahendra ', '', '', ''),
(15, ' 10310780', 'SMKN 1 Sumbar', 'JL. M YUNUS', 'Kampung Kalawi', NULL, '100.383454', -0.93011440, 100.38345470, '(0751) 26761', 'smkn1.sumbar@yahoo.com', 'https://smkn1sumbar.sch.id/', 648, 102, ' Zulkifli, S.Pd', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `tb_galeri`
--
ALTER TABLE `tb_galeri`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_smk` (`id_smk`);

--
-- Indeks untuk tabel `tb_kejuruan`
--
ALTER TABLE `tb_kejuruan`
  ADD PRIMARY KEY (`id_program`),
  ADD KEY `id_smk` (`id_smk`);

--
-- Indeks untuk tabel `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  ADD PRIMARY KEY (`id_prestasi`),
  ADD KEY `id_smk` (`id_smk`);

--
-- Indeks untuk tabel `tb_smk`
--
ALTER TABLE `tb_smk`
  ADD PRIMARY KEY (`id_smk`),
  ADD UNIQUE KEY `npsn` (`npsn`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_galeri`
--
ALTER TABLE `tb_galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `tb_kejuruan`
--
ALTER TABLE `tb_kejuruan`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  MODIFY `id_prestasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_smk`
--
ALTER TABLE `tb_smk`
  MODIFY `id_smk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_galeri`
--
ALTER TABLE `tb_galeri`
  ADD CONSTRAINT `tb_galeri_ibfk_1` FOREIGN KEY (`id_smk`) REFERENCES `tb_smk` (`id_smk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_kejuruan`
--
ALTER TABLE `tb_kejuruan`
  ADD CONSTRAINT `tb_kejuruan_ibfk_1` FOREIGN KEY (`id_smk`) REFERENCES `tb_smk` (`id_smk`);

--
-- Ketidakleluasaan untuk tabel `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  ADD CONSTRAINT `tb_prestasi_ibfk_1` FOREIGN KEY (`id_smk`) REFERENCES `tb_smk` (`id_smk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
