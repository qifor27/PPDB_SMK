-- =====================================================
-- SEED DATA JURUSAN/KOMPETENSI KEAHLIAN SMK KOTA PADANG
-- Generated: 2026-01-02
-- =====================================================

-- Hapus data lama jika ada
DELETE FROM `tb_kejuruan`;

-- Reset auto increment
ALTER TABLE `tb_kejuruan` AUTO_INCREMENT = 1;

-- =====================================================
-- 1. SMAK PADANG (id_smk = 1)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(1, 'Kimia Analisis', 'KA', 'Program keahlian yang mempelajari analisis kimia, penanganan laboratorium kimia, dan pengujian bahan kimia untuk industri.');

-- =====================================================
-- 2. SMKN 4 PADANG (id_smk = 2) - Seni Rupa
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(2, 'Seni Rupa', 'SR', 'Program keahlian yang mempelajari seni lukis, seni patung, dan berbagai teknik seni rupa tradisional maupun modern.'),
(2, 'Desain Komunikasi Visual', 'DKV', 'Program keahlian yang mempelajari perancangan visual untuk komunikasi, termasuk desain grafis, branding, dan media promosi.'),
(2, 'Animasi', 'ANI', 'Program keahlian yang mempelajari pembuatan animasi 2D dan 3D untuk industri kreatif dan hiburan.'),
(2, 'Teknik Furnitur', 'TF', 'Program keahlian yang mempelajari desain dan pembuatan furnitur dari berbagai material.'),
(2, 'Desain dan Produksi Kriya', 'DPK', 'Program keahlian yang mempelajari desain dan produksi kerajinan batik, tekstil, dan kriya tradisional.'),
(2, 'Broadcasting dan Produksi Film', 'BPF', 'Program keahlian yang mempelajari produksi film, videografi, dan penyiaran multimedia.'),
(2, 'Bisnis Daring dan Pemasaran', 'BDP', 'Program keahlian yang mempelajari pemasaran digital dan pengelolaan bisnis online.');

-- =====================================================
-- 3. SMKN 7 PADANG (id_smk = 3) - Seni Pertunjukan Minang
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(3, 'Seni Karawitan Minang', 'SKM', 'Program keahlian yang mempelajari musik tradisional Minangkabau, termasuk talempong dan saluang.'),
(3, 'Seni Tari Minang', 'STM', 'Program keahlian yang mempelajari tari tradisional Minangkabau seperti tari piring, tari payung, dan randai.'),
(3, 'Seni Teater', 'ST', 'Program keahlian yang mempelajari seni peran, pementasan, dan produksi teater.'),
(3, 'Seni Musik Non-Klasik', 'SMN', 'Program keahlian yang mempelajari musik populer, band, dan berbagai genre musik modern.');

-- =====================================================
-- 4. SMKN 8 PADANG (id_smk = 4)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(4, 'Teknologi Industri Kreatif', 'TIK', 'Program keahlian yang mempelajari teknologi untuk industri kreatif dan bisnis digital.'),
(4, 'Bisnis dan Kewirausahaan', 'BK', 'Program keahlian yang mempelajari pengelolaan bisnis dan pengembangan kewirausahaan.');

-- =====================================================
-- 5. SMKN 1 PADANG (id_smk = 6)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(6, 'Teknik Konstruksi Kayu', 'TKK', 'Program keahlian yang mempelajari konstruksi bangunan berbahan dasar kayu.'),
(6, 'Teknik Konstruksi Batu dan Beton', 'TKBB', 'Program keahlian yang mempelajari konstruksi bangunan dari batu dan beton.'),
(6, 'Desain Pemodelan dan Informasi Bangunan', 'DPIB', 'Program keahlian yang mempelajari desain arsitektur dan Building Information Modeling (BIM).'),
(6, 'Teknik Instalasi Tenaga Listrik', 'TITL', 'Program keahlian yang mempelajari instalasi dan perawatan sistem kelistrikan.'),
(6, 'Teknik Jaringan Tenaga Listrik', 'TJTL', 'Program keahlian yang mempelajari jaringan distribusi tenaga listrik.'),
(6, 'Teknik Otomasi Industri', 'TOI', 'Program keahlian yang mempelajari sistem otomasi dan kendali industri.'),
(6, 'Teknik Pemesinan', 'TPM', 'Program keahlian yang mempelajari pengoperasian dan pemeliharaan mesin-mesin industri.'),
(6, 'Teknik Kendaraan Ringan Otomotif', 'TKRO', 'Program keahlian yang mempelajari perawatan dan perbaikan kendaraan ringan.'),
(6, 'Teknik Audio Video', 'TAV', 'Program keahlian yang mempelajari sistem audio video dan elektronika.'),
(6, 'Teknik Elektronika Industri', 'TEI', 'Program keahlian yang mempelajari elektronika untuk aplikasi industri.'),
(6, 'Bisnis Konstruksi dan Properti', 'BKP', 'Program keahlian yang mempelajari bisnis di bidang konstruksi dan properti.'),
(6, 'Akomodasi Perhotelan', 'APH', 'Program keahlian yang mempelajari manajemen hotel dan layanan akomodasi.'),
(6, 'Kuliner', 'KUL', 'Program keahlian yang mempelajari seni memasak dan pengelolaan dapur profesional.'),
(6, 'Patiseri', 'PAT', 'Program keahlian yang mempelajari pembuatan kue, roti, dan produk pastry.');

-- =====================================================
-- 6. SMKN 10 PADANG (id_smk = 7) - Pelayaran & Perikanan
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(7, 'Nautika Kapal Penangkap Ikan', 'NKPI', 'Program keahlian yang mempelajari navigasi dan pelayaran kapal penangkap ikan.'),
(7, 'Teknika Kapal Penangkap Ikan', 'TKPI', 'Program keahlian yang mempelajari permesinan dan teknik kapal penangkap ikan.'),
(7, 'Nautika Kapal Niaga', 'NKN', 'Program keahlian yang mempelajari navigasi dan pelayaran kapal niaga.'),
(7, 'Teknika Kapal Niaga', 'TKN', 'Program keahlian yang mempelajari permesinan dan teknik kapal niaga.'),
(7, 'Agribisnis Perikanan Air Tawar', 'APAT', 'Program keahlian yang mempelajari budidaya dan bisnis perikanan air tawar.');

-- =====================================================
-- 7. SMTI PADANG (id_smk = 8)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(8, 'Teknik Kimia Industri', 'TKI', 'Program keahlian yang mempelajari proses industri kimia, pengendalian mutu, dan pengelolaan limbah industri.'),
(8, 'Teknik Otomasi Industri', 'TOI', 'Program keahlian yang mempelajari sistem kendali otomatis berbasis PLC, SCADA, dan mikrokontroler.');

-- =====================================================
-- 8. SMK PP NEGERI PADANG (id_smk = 9) - Pertanian
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(9, 'Agribisnis Tanaman Pangan dan Hortikultura', 'ATPH', 'Program keahlian yang mempelajari budidaya tanaman pangan, sayuran, dan tanaman hias.'),
(9, 'Agribisnis Tanaman Perkebunan', 'ATP', 'Program keahlian yang mempelajari budidaya dan pengelolaan tanaman perkebunan.'),
(9, 'Lanskap dan Pertamanan', 'LP', 'Program keahlian yang mempelajari desain lanskap dan pengelolaan pertamanan.'),
(9, 'Pemuliaan dan Perbenihan Tanaman', 'PPT', 'Program keahlian yang mempelajari pengembangan varietas tanaman unggul dan produksi benih.'),
(9, 'Agribisnis Pengolahan Hasil Pertanian', 'APHP', 'Program keahlian yang mempelajari pengolahan hasil pertanian menjadi produk olahan.');

-- =====================================================
-- 9. SMKN 9 PADANG (id_smk = 10)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(10, 'Perhotelan', 'PH', 'Program keahlian yang mempelajari manajemen hotel, layanan tamu, dan operasional perhotelan.'),
(10, 'Kuliner', 'KUL', 'Program keahlian yang mempelajari seni memasak, tata boga, dan pengelolaan dapur profesional.');

-- =====================================================
-- 10. SMKN 3 PADANG (id_smk = 11)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(11, 'Akuntansi dan Keuangan Lembaga', 'AKL', 'Program keahlian yang mempelajari akuntansi, pembukuan, dan pengelolaan keuangan.'),
(11, 'Manajemen Perkantoran dan Layanan Bisnis', 'MPLB', 'Program keahlian yang mempelajari administrasi perkantoran dan layanan bisnis.'),
(11, 'Bisnis Daring dan Pemasaran', 'BDP', 'Program keahlian yang mempelajari pemasaran digital dan e-commerce.'),
(11, 'Teknik Jaringan Komputer dan Telekomunikasi', 'TJKT', 'Program keahlian yang mempelajari jaringan komputer dan sistem telekomunikasi.'),
(11, 'Usaha Layanan Pariwisata', 'ULP', 'Program keahlian yang mempelajari pengelolaan usaha pariwisata dan perjalanan.'),
(11, 'Perhotelan', 'PH', 'Program keahlian yang mempelajari manajemen hotel dan layanan akomodasi.'),
(11, 'Kuliner', 'KUL', 'Program keahlian yang mempelajari seni memasak dan tata boga.');

-- =====================================================
-- 11. SMKN 2 PADANG (id_smk = 12)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(12, 'Pengembangan Perangkat Lunak dan Gim', 'PPLG', 'Program keahlian yang mempelajari pemrograman, pengembangan aplikasi, dan pembuatan game.'),
(12, 'Teknik Jaringan Komputer dan Telekomunikasi', 'TJKT', 'Program keahlian yang mempelajari instalasi jaringan komputer dan sistem telekomunikasi.'),
(12, 'Usaha Layanan Wisata', 'ULW', 'Program keahlian yang mempelajari industri pariwisata dan biro perjalanan.'),
(12, 'Manajemen Perkantoran dan Layanan Bisnis', 'MPLB', 'Program keahlian yang mempelajari administrasi perkantoran modern.'),
(12, 'Akuntansi dan Keuangan Lembaga', 'AKL', 'Program keahlian yang mempelajari akuntansi dan pengelolaan keuangan.'),
(12, 'Bisnis Digital dan Retail', 'BDR', 'Program keahlian yang mempelajari bisnis online dan manajemen retail.');

-- =====================================================
-- 12. SMKN 5 PADANG (id_smk = 13)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(13, 'Teknik Instalasi Tenaga Listrik', 'TITL', 'Program keahlian yang mempelajari instalasi dan pemeliharaan sistem kelistrikan.'),
(13, 'Teknik Pemesinan', 'TPM', 'Program keahlian yang mempelajari pengoperasian dan pemeliharaan mesin industri.'),
(13, 'Teknik Kendaraan Ringan Otomotif', 'TKRO', 'Program keahlian yang mempelajari perawatan dan perbaikan mobil.'),
(13, 'Teknik dan Bisnis Sepeda Motor', 'TBSM', 'Program keahlian yang mempelajari perawatan sepeda motor dan bisnis bengkel.'),
(13, 'Teknik Audio Video', 'TAV', 'Program keahlian yang mempelajari sistem audio video dan elektronika.'),
(13, 'Teknik Elektronika Industri', 'TEI', 'Program keahlian yang mempelajari elektronika untuk aplikasi industri.'),
(13, 'Bisnis Konstruksi dan Properti', 'BKP', 'Program keahlian yang mempelajari bisnis konstruksi dan properti.'),
(13, 'Desain Pemodelan dan Informasi Bangunan', 'DPIB', 'Program keahlian yang mempelajari desain arsitektur dan BIM.'),
(13, 'Teknik Komputer dan Jaringan', 'TKJ', 'Program keahlian yang mempelajari perakitan komputer dan jaringan.');

-- =====================================================
-- 13. SMKN 6 PADANG (id_smk = 14)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(14, 'Kuliner', 'KUL', 'Program keahlian yang mempelajari seni memasak dan pengelolaan dapur profesional.'),
(14, 'Kecantikan dan Spa', 'KS', 'Program keahlian yang mempelajari tata rias, perawatan kulit, dan spa.'),
(14, 'Perhotelan', 'PH', 'Program keahlian yang mempelajari manajemen hotel dan layanan tamu.'),
(14, 'Usaha Layanan Wisata', 'ULW', 'Program keahlian yang mempelajari industri pariwisata dan perjalanan.'),
(14, 'Teknik Jaringan Komputer dan Telekomunikasi', 'TJKT', 'Program keahlian yang mempelajari jaringan komputer dan telekomunikasi.'),
(14, 'Busana', 'BUS', 'Program keahlian yang mempelajari desain dan pembuatan busana.');

-- =====================================================
-- 14. SMKN 1 SUMATERA BARAT (id_smk = 15)
-- =====================================================
INSERT INTO `tb_kejuruan` (`id_smk`, `nama_kejuruan`, `kode_kejuruan`, `deskripsi`) VALUES
(15, 'Teknik Mekatronika', 'TMK', 'Program keahlian yang mempelajari integrasi mekanik, elektronik, dan sistem kendali.'),
(15, 'Teknik Audio Video', 'TAV', 'Program keahlian yang mempelajari sistem audio video dan elektronika.'),
(15, 'Teknik Elektronika Industri', 'TEI', 'Program keahlian yang mempelajari elektronika untuk industri.'),
(15, 'Teknik Kendaraan Ringan Otomotif', 'TKRO', 'Program keahlian yang mempelajari perawatan dan perbaikan mobil.'),
(15, 'Teknik dan Bisnis Sepeda Motor', 'TBSM', 'Program keahlian yang mempelajari perawatan sepeda motor dan bisnis bengkel.'),
(15, 'Desain Pemodelan dan Informasi Bangunan', 'DPIB', 'Program keahlian yang mempelajari desain arsitektur dan BIM.'),
(15, 'Bisnis Konstruksi dan Properti', 'BKP', 'Program keahlian yang mempelajari bisnis konstruksi dan properti.'),
(15, 'Teknik Pemesinan', 'TPM', 'Program keahlian yang mempelajari pengoperasian mesin industri.'),
(15, 'Teknik Pengelasan', 'TPL', 'Program keahlian yang mempelajari teknik pengelasan logam.'),
(15, 'Teknik Mekanik Industri', 'TMI', 'Program keahlian yang mempelajari mekanik untuk aplikasi industri.'),
(15, 'Teknik Instalasi Tenaga Listrik', 'TITL', 'Program keahlian yang mempelajari instalasi sistem kelistrikan.'),
(15, 'Teknik Pendinginan dan Tata Udara', 'TPTU', 'Program keahlian yang mempelajari sistem AC dan refrigerasi.');

-- =====================================================
-- VERIFIKASI: Tampilkan jumlah data yang dimasukkan
-- =====================================================
SELECT 
    s.nama_sekolah,
    COUNT(k.id_program) as jumlah_jurusan
FROM tb_smk s
LEFT JOIN tb_kejuruan k ON s.id_smk = k.id_smk
GROUP BY s.id_smk, s.nama_sekolah
ORDER BY s.id_smk;
