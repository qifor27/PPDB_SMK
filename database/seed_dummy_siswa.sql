-- =====================================================
-- SEED DATA: 50 Siswa Pendaftar PPDB SMK
-- Generated: 2026-01-03
-- =====================================================

-- =====================================================
-- INFORMASI ATURAN TES MINAT BAKAT
-- =====================================================
-- 1. Pendaftar memilih 1 sekolah dengan 2 jurusan berbeda:
--    - Akan mengikuti 2 kali ujian yang berbeda sesuai jurusan
-- 
-- 2. Pendaftar memilih 2 sekolah berbeda:
--    - Tes minat bakat dilakukan di sekolah pilihan pertama
--    - Tetap melaksanakan 2 kali ujian sesuai pilihan jurusan
-- =====================================================

-- Hapus data siswa dan pendaftaran lama jika ada (untuk testing)
-- DELETE FROM `tb_dokumen`;
-- DELETE FROM `tb_pendaftaran`;
-- DELETE FROM `tb_siswa`;

-- =====================================================
-- INSERT 50 SISWA DUMMY
-- =====================================================
INSERT INTO `tb_siswa` (
    `nisn`, `username`, `password`, `email`, `nama_lengkap`, 
    `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`,
    `nik`, `no_kk`, `alamat`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `kode_pos`,
    `latitude`, `longitude`, `no_hp`,
    `nama_ayah`, `pekerjaan_ayah`, `nama_ibu`, `pekerjaan_ibu`, `no_hp_ortu`,
    `asal_sekolah`, `tahun_lulus`
) VALUES

-- Siswa 1-10
('0081234560001', 'ahmad.fadli', MD5('siswa123'), 'ahmad.fadli@gmail.com', 'Ahmad Fadli', 'L', 'Padang', '2010-03-15', 'Islam', '1371010150310001', '1371011234560001', 'Jl. Pemuda No. 10', '01', '02', 'Ulak Karang', 'Padang Utara', 'Padang', 'Sumatera Barat', '25134', -0.91234500, 100.35456700, '081234567001', 'Fadli Rahman', 'PNS', 'Siti Aminah', 'Ibu Rumah Tangga', '081234567101', 'SMPN 1 Padang', '2025'),

('0081234560002', 'dinda.permata', MD5('siswa123'), 'dinda.permata@gmail.com', 'Dinda Permata Sari', 'P', 'Padang', '2010-05-22', 'Islam', '1371012205100002', '1371011234560002', 'Jl. Sudirman No. 25', '03', '05', 'Jati Baru', 'Padang Timur', 'Padang', 'Sumatera Barat', '25129', -0.93456700, 100.36789000, '081234567002', 'Permata Jaya', 'Wiraswasta', 'Yuni Kartika', 'Guru', '081234567102', 'SMPN 2 Padang', '2025'),

('0081234560003', 'reza.pratama', MD5('siswa123'), 'reza.pratama@gmail.com', 'Reza Pratama Putra', 'L', 'Padang', '2010-01-08', 'Islam', '1371010801100003', '1371011234560003', 'Jl. Veteran No. 5', '02', '03', 'Lolong Belanti', 'Padang Barat', 'Padang', 'Sumatera Barat', '25136', -0.92100000, 100.34500000, '081234567003', 'Pratama Putra', 'Pedagang', 'Dewi Sartika', 'PNS', '081234567103', 'SMPN 3 Padang', '2025'),

('0081234560004', 'annisa.zahra', MD5('siswa123'), 'annisa.zahra@gmail.com', 'Annisa Zahra', 'P', 'Padang', '2010-07-14', 'Islam', '1371011407100004', '1371011234560004', 'Jl. Khatib Sulaiman No. 12', '04', '06', 'Kampung Kalawi', 'Padang Timur', 'Padang', 'Sumatera Barat', '25153', -0.92500000, 100.38000000, '081234567004', 'Zahra Ahmad', 'Dokter', 'Nurhayati', 'Bidan', '081234567104', 'SMPN 4 Padang', '2025'),

('0081234560005', 'budi.santoso', MD5('siswa123'), 'budi.santoso@gmail.com', 'Budi Santoso', 'L', 'Padang', '2010-11-30', 'Islam', '1371013011100005', '1371011234560005', 'Jl. Rasuna Said No. 8', '01', '01', 'Simpang Haru', 'Padang Timur', 'Padang', 'Sumatera Barat', '25123', -0.94200000, 100.37800000, '081234567005', 'Santoso Budi', 'TNI', 'Lestari', 'Ibu Rumah Tangga', '081234567105', 'SMPN 5 Padang', '2025'),

('0081234560006', 'citra.dewi', MD5('siswa123'), 'citra.dewi@gmail.com', 'Citra Dewi Lestari', 'P', 'Bukittinggi', '2010-02-28', 'Islam', '1371012802100006', '1371011234560006', 'Jl. Andalas No. 20', '02', '04', 'Andalas', 'Padang Timur', 'Padang', 'Sumatera Barat', '25147', -0.93800000, 100.36200000, '081234567006', 'Dewi Ahmad', 'Polri', 'Kartini', 'Guru', '081234567106', 'SMPN 6 Padang', '2025'),

('0081234560007', 'dani.wijaya', MD5('siswa123'), 'dani.wijaya@gmail.com', 'Dani Wijaya Kusuma', 'L', 'Padang', '2010-09-12', 'Islam', '1371011209100007', '1371011234560007', 'Jl. Alang Lawas No. 15', '03', '02', 'Alang Lawas', 'Padang Selatan', 'Padang', 'Sumatera Barat', '25212', -0.95600000, 100.36700000, '081234567007', 'Wijaya Putra', 'Arsitek', 'Susi Susanti', 'Apoteker', '081234567107', 'SMPN 7 Padang', '2025'),

('0081234560008', 'elsa.putri', MD5('siswa123'), 'elsa.putri@gmail.com', 'Elsa Putri Maharani', 'P', 'Padang', '2010-04-05', 'Islam', '1371010504100008', '1371011234560008', 'Jl. Proklamasi No. 30', '05', '07', 'Mata Air', 'Padang Selatan', 'Padang', 'Sumatera Barat', '25216', -0.96100000, 100.36100000, '081234567008', 'Maharani Putra', 'Pengacara', 'Rina Marlina', 'Notaris', '081234567108', 'SMPN 8 Padang', '2025'),

('0081234560009', 'farhan.rizky', MD5('siswa123'), 'farhan.rizky@gmail.com', 'Farhan Rizky Ramadhan', 'L', 'Solok', '2010-06-25', 'Islam', '1371012506100009', '1371011234560009', 'Jl. Ampera No. 7', '01', '03', 'Lubuk Begalung', 'Lubuk Begalung', 'Padang', 'Sumatera Barat', '25159', -0.94900000, 100.41200000, '081234567009', 'Rizky Akbar', 'Kontraktor', 'Maya Sari', 'Guru', '081234567109', 'SMPN 9 Padang', '2025'),

('0081234560010', 'gita.anggraini', MD5('siswa123'), 'gita.anggraini@gmail.com', 'Gita Anggraini Putri', 'P', 'Padang', '2010-08-18', 'Islam', '1371011808100010', '1371011234560010', 'Jl. Pattimura No. 45', '02', '05', 'Pauh', 'Pauh', 'Padang', 'Sumatera Barat', '25163', -0.93200000, 100.44000000, '081234567010', 'Anggraini Putra', 'Dosen', 'Sri Wahyuni', 'Dosen', '081234567110', 'SMPN 10 Padang', '2025'),

-- Siswa 11-20
('0081234560011', 'hadi.kurniawan', MD5('siswa123'), 'hadi.kurniawan@gmail.com', 'Hadi Kurniawan', 'L', 'Padang', '2010-12-03', 'Islam', '1371010312100011', '1371011234560011', 'Jl. Nipah No. 22', '04', '06', 'Koto Tangah', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25175', -0.84500000, 100.38100000, '081234567011', 'Kurniawan Jaya', 'Pilot', 'Anita', 'Pramugari', '081234567111', 'SMPN 11 Padang', '2025'),

('0081234560012', 'indah.permatasari', MD5('siswa123'), 'indah.permatasari@gmail.com', 'Indah Permatasari', 'P', 'Padang', '2010-10-07', 'Islam', '1371010710100012', '1371011234560012', 'Jl. Sawahan No. 33', '03', '04', 'Sawahan', 'Padang Timur', 'Padang', 'Sumatera Barat', '25122', -0.94800000, 100.35900000, '081234567012', 'Permatasari Ahmad', 'Bankir', 'Lina', 'Akuntan', '081234567112', 'SMPN 12 Padang', '2025'),

('0081234560013', 'jaka.purnama', MD5('siswa123'), 'jaka.purnama@gmail.com', 'Jaka Purnama Putra', 'L', 'Payakumbuh', '2010-03-22', 'Islam', '1371012203100013', '1371011234560013', 'Jl. Diponegoro No. 18', '02', '02', 'Gunung Pangilun', 'Padang Utara', 'Padang', 'Sumatera Barat', '25116', -0.90500000, 100.35200000, '081234567013', 'Purnama Jaya', 'Insinyur', 'Yanti', 'Dokter', '081234567113', 'SMPN 13 Padang', '2025'),

('0081234560014', 'kartika.sari', MD5('siswa123'), 'kartika.sari@gmail.com', 'Kartika Sari Dewi', 'P', 'Padang', '2010-05-11', 'Islam', '1371011105100014', '1371011234560014', 'Jl. Gajah Mada No. 9', '01', '01', 'Flamboyan', 'Padang Barat', 'Padang', 'Sumatera Barat', '25112', -0.94000000, 100.35000000, '081234567014', 'Sari Putra', 'Pengusaha', 'Diana', 'Desainer', '081234567114', 'SMPN 14 Padang', '2025'),

('0081234560015', 'lukman.hakim', MD5('siswa123'), 'lukman.hakim@gmail.com', 'Lukman Hakim', 'L', 'Padang', '2010-07-29', 'Islam', '1371012907100015', '1371011234560015', 'Jl. Hayam Wuruk No. 11', '05', '03', 'Rimbo Kaluang', 'Padang Barat', 'Padang', 'Sumatera Barat', '25115', -0.93000000, 100.35000000, '081234567015', 'Hakim Bakar', 'Wartawan', 'Fatimah', 'Reporter', '081234567115', 'SMPN 15 Padang', '2025'),

('0081234560016', 'maya.anggraini', MD5('siswa123'), 'maya.anggraini@gmail.com', 'Maya Anggraini', 'P', 'Padang', '2010-01-16', 'Islam', '1371011601100016', '1371011234560016', 'Jl. Ahmad Yani No. 55', '04', '05', 'Belakang Tangsi', 'Padang Barat', 'Padang', 'Sumatera Barat', '25111', -0.92700000, 100.36400000, '081234567016', 'Anggraini Jaya', 'Pilot', 'Santi', 'Bidan', '081234567116', 'SMPN 16 Padang', '2025'),

('0081234560017', 'naufal.aditya', MD5('siswa123'), 'naufal.aditya@gmail.com', 'Naufal Aditya Pratama', 'L', 'Padang', '2010-09-02', 'Islam', '1371010209100017', '1371011234560017', 'Jl. Jhoni Anwar No. 21', '02', '06', 'Ujung Gurun', 'Padang Barat', 'Padang', 'Sumatera Barat', '25118', -0.94500000, 100.35900000, '081234567017', 'Aditya Putra', 'PNS', 'Murni', 'Guru', '081234567117', 'SMPN 17 Padang', '2025'),

('0081234560018', 'oktavia.rahmawati', MD5('siswa123'), 'oktavia.rahmawati@gmail.com', 'Oktavia Rahmawati', 'P', 'Pariaman', '2010-11-14', 'Islam', '1371011411100018', '1371011234560018', 'Jl. Imam Bonjol No. 8', '03', '04', 'Padang Pasir', 'Padang Barat', 'Padang', 'Sumatera Barat', '25117', -0.93500000, 100.35500000, '081234567018', 'Rahmawati Ahmad', 'Pedagang', 'Ningsih', 'Pedagang', '081234567118', 'MTsN 1 Padang', '2025'),

('0081234560019', 'putra.mahendra', MD5('siswa123'), 'putra.mahendra@gmail.com', 'Putra Mahendra Jaya', 'L', 'Padang', '2010-02-08', 'Islam', '1371010802100019', '1371011234560019', 'Jl. Sutan Syahrir No. 17', '01', '02', 'Ranah Parak Rumbio', 'Padang Selatan', 'Padang', 'Sumatera Barat', '25214', -0.95000000, 100.36000000, '081234567019', 'Mahendra Putra', 'Tentara', 'Wati', 'Bidan', '081234567119', 'MTsN 2 Padang', '2025'),

('0081234560020', 'qori.hasanah', MD5('siswa123'), 'qori.hasanah@gmail.com', 'Qori Hasanah', 'P', 'Padang', '2010-04-20', 'Islam', '1371012004100020', '1371011234560020', 'Jl. Hang Tuah No. 39', '05', '07', 'Seberang Padang', 'Padang Selatan', 'Padang', 'Sumatera Barat', '25213', -0.95800000, 100.36300000, '081234567020', 'Hasanah Ahmad', 'Polisi', 'Zulaika', 'Perawat', '081234567120', 'MTsN 3 Padang', '2025'),

-- Siswa 21-30
('0081234560021', 'rizal.firmansyah', MD5('siswa123'), 'rizal.firmansyah@gmail.com', 'Rizal Firmansyah', 'L', 'Padang', '2010-06-13', 'Islam', '1371011306100021', '1371011234560021', 'Jl. Teuku Umar No. 23', '02', '03', 'Berok Nipah', 'Padang Barat', 'Padang', 'Sumatera Barat', '25119', -0.92800000, 100.35200000, '081234567021', 'Firmansyah Jaya', 'Pengacara', 'Aminah', 'Hakim', '081234567121', 'SMPN 18 Padang', '2025'),

('0081234560022', 'sinta.aulia', MD5('siswa123'), 'sinta.aulia@gmail.com', 'Sinta Aulia Ramadhani', 'P', 'Padang', '2010-08-27', 'Islam', '1371012708100022', '1371011234560022', 'Jl. Cut Nyak Dien No. 14', '04', '05', 'Purus', 'Padang Barat', 'Padang', 'Sumatera Barat', '25114', -0.94200000, 100.35300000, '081234567022', 'Aulia Rahman', 'Dokter', 'Ratna', 'Apoteker', '081234567122', 'SMPN 19 Padang', '2025'),

('0081234560023', 'taufik.hidayat', MD5('siswa123'), 'taufik.hidayat@gmail.com', 'Taufik Hidayat', 'L', 'Bukittinggi', '2010-10-19', 'Islam', '1371011910100023', '1371011234560023', 'Jl. RA Kartini No. 6', '01', '01', 'Balai Gadang', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25171', -0.82100000, 100.30200000, '081234567023', 'Hidayat Putra', 'Programmer', 'Dewi', 'Programmer', '081234567123', 'SMPN 20 Padang', '2025'),

('0081234560024', 'ulfa.nabila', MD5('siswa123'), 'ulfa.nabila@gmail.com', 'Ulfa Nabila Putri', 'P', 'Padang', '2010-12-11', 'Islam', '1371011112100024', '1371011234560024', 'Jl. Adinegoro No. 29', '03', '04', 'Bungo Pasang', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25174', -0.85000000, 100.35000000, '081234567024', 'Nabila Ahmad', 'Chef', 'Indah', 'Chef', '081234567124', 'SMPN 21 Padang', '2025'),

('0081234560025', 'vino.bastian', MD5('siswa123'), 'vino.bastian@gmail.com', 'Vino Bastian Pratama', 'L', 'Padang', '2010-03-06', 'Islam', '1371010603100025', '1371011234560025', 'Jl. Batang Arau No. 41', '02', '06', 'Batang Arau', 'Padang Selatan', 'Padang', 'Sumatera Barat', '25211', -0.98000000, 100.36800000, '081234567025', 'Bastian Jaya', 'Nelayan', 'Erni', 'Pedagang', '081234567125', 'SMPN 22 Padang', '2025'),

('0081234560026', 'winda.lestari', MD5('siswa123'), 'winda.lestari@gmail.com', 'Winda Lestari', 'P', 'Solok', '2010-05-24', 'Islam', '1371012405100026', '1371011234560026', 'Jl. Sungai Sapih No. 16', '05', '02', 'Dadok Tunggul Hitam', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25173', -0.87000000, 100.38000000, '081234567026', 'Lestari Putra', 'Petani', 'Sumarni', 'Petani', '081234567126', 'SMPN 23 Padang', '2025'),

('0081234560027', 'xavier.aldo', MD5('siswa123'), 'xavier.aldo@gmail.com', 'Xavier Aldo Saputra', 'L', 'Padang', '2010-07-17', 'Islam', '1371011707100027', '1371011234560027', 'Jl. Pasar Ambacang No. 3', '04', '03', 'Lubuk Lintah', 'Kuranji', 'Padang', 'Sumatera Barat', '25152', -0.90000000, 100.40000000, '081234567027', 'Aldo Saputra', 'Montir', 'Yuli', 'Penjahit', '081234567127', 'SMPN 24 Padang', '2025'),

('0081234560028', 'yolanda.safitri', MD5('siswa123'), 'yolanda.safitri@gmail.com', 'Yolanda Safitri', 'P', 'Padang', '2010-09-09', 'Islam', '1371010909100028', '1371011234560028', 'Jl. Kuranji No. 52', '01', '05', 'Ampang', 'Kuranji', 'Padang', 'Sumatera Barat', '25151', -0.89500000, 100.39000000, '081234567028', 'Safitri Jaya', 'Supir', 'Mariam', 'Ibu Rumah Tangga', '081234567128', 'SMPN 25 Padang', '2025'),

('0081234560029', 'zaki.maulana', MD5('siswa123'), 'zaki.maulana@gmail.com', 'Zaki Maulana Ibrahim', 'L', 'Padang', '2010-11-01', 'Islam', '1371010111100029', '1371011234560029', 'Jl. Bypass No. 77', '03', '07', 'Sungai Lareh', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25176', -0.83000000, 100.32000000, '081234567029', 'Ibrahim Ahmad', 'Satpam', 'Fatma', 'Pembantu', '081234567129', 'SMPN 26 Padang', '2025'),

('0081234560030', 'amelia.putri', MD5('siswa123'), 'amelia.putri@gmail.com', 'Amelia Putri Andini', 'P', 'Padang', '2010-01-28', 'Islam', '1371012801100030', '1371011234560030', 'Jl. Siteba No. 12', '02', '01', 'Surau Gadang', 'Nanggalo', 'Padang', 'Sumatera Barat', '25146', -0.91000000, 100.37000000, '081234567030', 'Andini Putra', 'Tukang', 'Suryani', 'Pedagang', '081234567130', 'SMPN 27 Padang', '2025'),

-- Siswa 31-40
('0081234560031', 'bram.satria', MD5('siswa123'), 'bram.satria@gmail.com', 'Bram Satria Nugraha', 'L', 'Payakumbuh', '2010-04-14', 'Islam', '1371011404100031', '1371011234560031', 'Jl. Lapai No. 9', '04', '04', 'Lapai', 'Nanggalo', 'Padang', 'Sumatera Barat', '25145', -0.90800000, 100.36500000, '081234567031', 'Nugraha Jaya', 'Buruh', 'Kasih', 'Buruh', '081234567131', 'SMPN 28 Padang', '2025'),

('0081234560032', 'chelsea.ananda', MD5('siswa123'), 'chelsea.ananda@gmail.com', 'Chelsea Ananda Putri', 'P', 'Padang', '2010-06-06', 'Kristen', '1371010606100032', '1371011234560032', 'Jl. Parak Jigarang No. 24', '05', '06', 'Kurao Pagang', 'Nanggalo', 'Padang', 'Sumatera Barat', '25144', -0.89000000, 100.36000000, '081234567032', 'Ananda Putra', 'Pastor', 'Maria', 'Biarawati', '081234567132', 'SMPN 29 Padang', '2025'),

('0081234560033', 'dimas.prasetya', MD5('siswa123'), 'dimas.prasetya@gmail.com', 'Dimas Prasetya Wibowo', 'L', 'Padang', '2010-08-23', 'Islam', '1371012308100033', '1371011234560033', 'Jl. Tarandam No. 37', '01', '02', 'Tarandam', 'Padang Barat', 'Padang', 'Sumatera Barat', '25113', -0.93700000, 100.35100000, '081234567033', 'Wibowo Jaya', 'Sopir', 'Tuti', 'Penjual', '081234567133', 'SMPN 30 Padang', '2025'),

('0081234560034', 'eva.maharani', MD5('siswa123'), 'eva.maharani@gmail.com', 'Eva Maharani Salsabila', 'P', 'Padang', '2010-10-30', 'Islam', '1371013010100034', '1371011234560034', 'Jl. Gaung No. 19', '03', '03', 'Gaung', 'Lubuk Begalung', 'Padang', 'Sumatera Barat', '25226', -0.96500000, 100.40000000, '081234567034', 'Salsabila Ahmad', 'Cleaning Service', 'Lastri', 'Cleaning Service', '081234567134', 'SMPN 31 Padang', '2025'),

('0081234560035', 'fajar.ramadhan', MD5('siswa123'), 'fajar.ramadhan@gmail.com', 'Fajar Ramadhan', 'L', 'Padang', '2010-12-25', 'Islam', '1371012512100035', '1371011234560035', 'Jl. Banuaran No. 46', '02', '05', 'Banuaran', 'Lubuk Begalung', 'Padang', 'Sumatera Barat', '25225', -0.95500000, 100.42000000, '081234567035', 'Ramadhan Jaya', 'Ojek', 'Sari', 'Pedagang', '081234567135', 'SMPN 32 Padang', '2025'),

('0081234560036', 'gina.aulia', MD5('siswa123'), 'gina.aulia@gmail.com', 'Gina Aulia Rahmah', 'P', 'Pariaman', '2010-02-18', 'Islam', '1371011802100036', '1371011234560036', 'Jl. Koto Baru No. 11', '04', '07', 'Cengkeh', 'Lubuk Begalung', 'Padang', 'Sumatera Barat', '25159', -0.94900000, 100.41300000, '081234567036', 'Rahmah Putra', 'Peternak', 'Ani', 'Peternak', '081234567136', 'SMPN 33 Padang', '2025'),

('0081234560037', 'helmi.fauzan', MD5('siswa123'), 'helmi.fauzan@gmail.com', 'Helmi Fauzan Akbar', 'L', 'Padang', '2010-04-09', 'Islam', '1371010904100037', '1371011234560037', 'Jl. Tanah Sirah No. 28', '05', '01', 'Pegambiran', 'Lubuk Begalung', 'Padang', 'Sumatera Barat', '25227', -0.96000000, 100.39000000, '081234567037', 'Akbar Ahmad', 'Pemulung', 'Neneng', 'Pemulung', '081234567137', 'SMPN 34 Padang', '2025'),

('0081234560038', 'intan.permata', MD5('siswa123'), 'intan.permata@gmail.com', 'Intan Permata Hati', 'P', 'Padang', '2010-06-21', 'Islam', '1371012106100038', '1371011234560038', 'Jl. Piai Tangah No. 5', '01', '04', 'Piai Tangah', 'Pauh', 'Padang', 'Sumatera Barat', '25164', -0.94000000, 100.45000000, '081234567038', 'Hati Putra', 'Pemandu Wisata', 'Lilis', 'Guru', '081234567138', 'SMPN 35 Padang', '2025'),

('0081234560039', 'julian.pratama', MD5('siswa123'), 'julian.pratama@gmail.com', 'Julian Pratama Putra', 'L', 'Padang', '2010-08-12', 'Islam', '1371011208100039', '1371011234560039', 'Jl. Limau Manis No. 33', '03', '06', 'Limau Manis', 'Pauh', 'Padang', 'Sumatera Barat', '25163', -0.93100000, 100.44500000, '081234567039', 'Pratama Jaya', 'Security', 'Dewi', 'Penjahit', '081234567139', 'SMPN 36 Padang', '2025'),

('0081234560040', 'kirana.ayu', MD5('siswa123'), 'kirana.ayu@gmail.com', 'Kirana Ayu Lestari', 'P', 'Bukittinggi', '2010-10-04', 'Islam', '1371010410100040', '1371011234560040', 'Jl. Cupak Tangah No. 7', '02', '02', 'Cupak Tangah', 'Pauh', 'Padang', 'Sumatera Barat', '25162', -0.92500000, 100.43000000, '081234567040', 'Lestari Ahmad', 'Sales', 'Yanti', 'Sales', '081234567140', 'SMPN 37 Padang', '2025'),

-- Siswa 41-50
('0081234560041', 'leo.alexander', MD5('siswa123'), 'leo.alexander@gmail.com', 'Leo Alexander Wijaya', 'L', 'Padang', '2010-01-07', 'Katolik', '1371010701100041', '1371011234560041', 'Jl. Pisang No. 15', '04', '03', 'Pisang', 'Pauh', 'Padang', 'Sumatera Barat', '25161', -0.91500000, 100.42000000, '081234567041', 'Wijaya Putra', 'Pastor', 'Catherine', 'Biarawati', '081234567141', 'SMPN 38 Padang', '2025'),

('0081234560042', 'mira.sabrina', MD5('siswa123'), 'mira.sabrina@gmail.com', 'Mira Sabrina Azzahra', 'P', 'Padang', '2010-03-19', 'Islam', '1371011903100042', '1371011234560042', 'Jl. Gurun Laweh No. 22', '05', '05', 'Gurun Laweh', 'Nanggalo', 'Padang', 'Sumatera Barat', '25143', -0.90500000, 100.37500000, '081234567042', 'Azzahra Ahmad', 'Karyawan', 'Rina', 'Karyawan', '081234567142', 'SMPN 39 Padang', '2025'),

('0081234560043', 'nanda.rusdi', MD5('siswa123'), 'nanda.rusdi@gmail.com', 'Nanda Rusdi Harahap', 'L', 'Medan', '2010-05-31', 'Islam', '1371013105100043', '1371011234560043', 'Jl. Binuang Kampung No. 8', '01', '07', 'Binuang Kampung', 'Pauh', 'Padang', 'Sumatera Barat', '25165', -0.93800000, 100.46000000, '081234567043', 'Harahap Jaya', 'TNI', 'Yuli', 'Ibu Rumah Tangga', '081234567143', 'SMPN 40 Padang', '2025'),

('0081234560044', 'olivia.grace', MD5('siswa123'), 'olivia.grace@gmail.com', 'Olivia Grace Putri', 'P', 'Padang', '2010-07-14', 'Kristen', '1371011407100044', '1371011234560044', 'Jl. Jati No. 29', '03', '04', 'Jati Baru', 'Padang Timur', 'Padang', 'Sumatera Barat', '25129', -0.93600000, 100.36700000, '081234567044', 'Grace Putra', 'Pendeta', 'Sarah', 'Pendeta', '081234567144', 'SMPN 41 Padang', '2025'),

('0081234560045', 'pandu.wijaksono', MD5('siswa123'), 'pandu.wijaksono@gmail.com', 'Pandu Wijaksono', 'L', 'Padang', '2010-09-26', 'Islam', '1371012609100045', '1371011234560045', 'Jl. Belimbing No. 48', '02', '01', 'Jl. Belimbing', 'Kuranji', 'Padang', 'Sumatera Barat', '25155', -0.91000000, 100.41000000, '081234567045', 'Wijaksono Jaya', 'Petani', 'Sri', 'Petani', '081234567145', 'SMPN 42 Padang', '2025'),

('0081234560046', 'queen.safira', MD5('siswa123'), 'queen.safira@gmail.com', 'Queen Safira Maharani', 'P', 'Padang', '2010-11-08', 'Islam', '1371010811100046', '1371011234560046', 'Jl. Sungai Bangek No. 13', '04', '06', 'Sungai Bangek', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25172', -0.86000000, 100.36000000, '081234567046', 'Maharani Ahmad', 'Pedagang', 'Anni', 'Pedagang', '081234567146', 'SMPN 43 Padang', '2025'),

('0081234560047', 'rendi.saputra', MD5('siswa123'), 'rendi.saputra@gmail.com', 'Rendi Saputra Efendi', 'L', 'Padang', '2010-02-03', 'Islam', '1371010302100047', '1371011234560047', 'Jl. Lubuk Minturun No. 35', '05', '02', 'Lubuk Minturun', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25177', -0.84000000, 100.33000000, '081234567047', 'Efendi Jaya', 'Penambang', 'Darmi', 'Petani', '081234567147', 'SMPN 44 Padang', '2025'),

('0081234560048', 'sarah.amalia', MD5('siswa123'), 'sarah.amalia@gmail.com', 'Sarah Amalia Putri', 'P', 'Solok', '2010-04-25', 'Islam', '1371012504100048', '1371011234560048', 'Jl. Air Pacah No. 21', '01', '03', 'Air Pacah', 'Koto Tangah', 'Padang', 'Sumatera Barat', '25178', -0.85500000, 100.34500000, '081234567048', 'Amalia Ahmad', 'Penjual', 'Tina', 'Penjual', '081234567148', 'SMPN 45 Padang', '2025'),

('0081234560049', 'tommy.hermawan', MD5('siswa123'), 'tommy.hermawan@gmail.com', 'Tommy Hermawan Putra', 'L', 'Padang', '2010-06-16', 'Buddha', '1371011606100049', '1371011234560049', 'Jl. Pasar Baru No. 6', '03', '05', 'Kampung Jao', 'Padang Barat', 'Padang', 'Sumatera Barat', '25121', -0.92300000, 100.36100000, '081234567049', 'Hermawan Jaya', 'Pengusaha', 'Mei Ling', 'Pengusaha', '081234567149', 'SMPN 46 Padang', '2025'),

('0081234560050', 'uma.kartika', MD5('siswa123'), 'uma.kartika@gmail.com', 'Uma Kartika Dewi', 'P', 'Padang', '2010-08-08', 'Hindu', '1371010808100050', '1371011234560050', 'Jl. Pasar Raya No. 50', '02', '07', 'Kampung Jawa', 'Padang Barat', 'Padang', 'Sumatera Barat', '25122', -0.93000000, 100.36300000, '081234567050', 'Dewi Putra', 'Brahmana', 'Shanti', 'Guru Yoga', '081234567150', 'SMPN 47 Padang', '2025');


-- =====================================================
-- INSERT PENDAFTARAN UNTUK 50 SISWA
-- Mencakup berbagai skenario:
-- 1. Siswa dengan 1 sekolah, 2 jurusan berbeda (2 kali ujian)
-- 2. Siswa dengan 2 sekolah berbeda (ujian di sekolah pertama)
-- =====================================================

INSERT INTO `tb_pendaftaran` (
    `nomor_pendaftaran`, `id_siswa`, `id_smk_pilihan1`, `id_smk_pilihan2`, 
    `id_kejuruan_pilihan1`, `id_kejuruan_pilihan2`, `id_jalur`,
    `jarak_ke_sekolah`, `nilai_rata_rata`, `status`, `tahun_ajaran`
) VALUES
-- Skenario 1: 1 Sekolah, 2 Jurusan Berbeda (Siswa 1-20)
('PPDB2026-0001', 1, 11, NULL, 1, 2, 3, 2.5, 85.50, 'submitted', '2025/2026'),
('PPDB2026-0002', 2, 11, NULL, 3, 4, 3, 1.8, 88.25, 'submitted', '2025/2026'),
('PPDB2026-0003', 3, 12, NULL, 1, 2, 3, 2.1, 82.00, 'verified', '2025/2026'),
('PPDB2026-0004', 4, 12, NULL, 3, 4, 2, 3.2, 90.75, 'submitted', '2025/2026'),
('PPDB2026-0005', 5, 6, NULL, 1, 2, 3, 1.5, 79.50, 'verified', '2025/2026'),
('PPDB2026-0006', 6, 6, NULL, 3, 4, 1, 4.5, 75.00, 'submitted', '2025/2026'),
('PPDB2026-0007', 7, 13, NULL, 1, 2, 3, 2.8, 86.25, 'submitted', '2025/2026'),
('PPDB2026-0008', 8, 13, NULL, 3, 4, 2, 3.0, 91.00, 'verified', '2025/2026'),
('PPDB2026-0009', 9, 14, NULL, 1, 2, 3, 1.2, 83.75, 'submitted', '2025/2026'),
('PPDB2026-0010', 10, 14, NULL, 3, 4, 3, 5.5, 80.25, 'submitted', '2025/2026'),
('PPDB2026-0011', 11, 1, NULL, 1, NULL, 3, 8.2, 87.00, 'verified', '2025/2026'),
('PPDB2026-0012', 12, 2, NULL, 1, 2, 2, 2.0, 92.50, 'submitted', '2025/2026'),
('PPDB2026-0013', 13, 3, NULL, 1, 2, 3, 3.5, 78.75, 'submitted', '2025/2026'),
('PPDB2026-0014', 14, 4, NULL, 1, 2, 3, 1.9, 84.00, 'verified', '2025/2026'),
('PPDB2026-0015', 15, 8, NULL, 1, 2, 2, 2.3, 89.25, 'submitted', '2025/2026'),
('PPDB2026-0016', 16, 9, NULL, 1, 2, 3, 4.1, 76.50, 'submitted', '2025/2026'),
('PPDB2026-0017', 17, 10, NULL, 1, 2, 3, 1.7, 81.75, 'verified', '2025/2026'),
('PPDB2026-0018', 18, 7, NULL, 1, 2, 1, 6.5, 73.00, 'submitted', '2025/2026'),
('PPDB2026-0019', 19, 15, NULL, 1, 2, 3, 2.9, 85.00, 'submitted', '2025/2026'),
('PPDB2026-0020', 20, 15, NULL, 3, 4, 2, 3.1, 88.75, 'verified', '2025/2026'),

-- Skenario 2: 2 Sekolah Berbeda (Ujian di sekolah pertama) (Siswa 21-40)
('PPDB2026-0021', 21, 11, 12, 1, 3, 3, 1.8, 86.50, 'submitted', '2025/2026'),
('PPDB2026-0022', 22, 12, 11, 2, 5, 3, 2.2, 89.00, 'submitted', '2025/2026'),
('PPDB2026-0023', 23, 6, 13, 1, 1, 4, 7.5, 77.25, 'verified', '2025/2026'),
('PPDB2026-0024', 24, 13, 6, 2, 3, 3, 3.8, 82.50, 'submitted', '2025/2026'),
('PPDB2026-0025', 25, 14, 10, 1, 1, 3, 5.2, 79.75, 'submitted', '2025/2026'),
('PPDB2026-0026', 26, 10, 14, 2, 3, 1, 4.8, 74.00, 'verified', '2025/2026'),
('PPDB2026-0027', 27, 1, 8, 1, 1, 3, 6.0, 84.25, 'submitted', '2025/2026'),
('PPDB2026-0028', 28, 8, 1, 1, 1, 2, 3.3, 90.50, 'submitted', '2025/2026'),
('PPDB2026-0029', 29, 2, 4, 1, 1, 3, 8.1, 78.00, 'verified', '2025/2026'),
('PPDB2026-0030', 30, 4, 2, 2, 4, 3, 2.7, 83.00, 'submitted', '2025/2026'),
('PPDB2026-0031', 31, 3, 7, 1, 1, 1, 5.9, 72.50, 'submitted', '2025/2026'),
('PPDB2026-0032', 32, 7, 3, 2, 2, 3, 4.4, 85.75, 'verified', '2025/2026'),
('PPDB2026-0033', 33, 9, 15, 1, 1, 3, 3.6, 80.00, 'submitted', '2025/2026'),
('PPDB2026-0034', 34, 15, 9, 2, 3, 1, 6.8, 71.25, 'submitted', '2025/2026'),
('PPDB2026-0035', 35, 11, 6, 2, 5, 3, 2.4, 87.50, 'verified', '2025/2026'),
('PPDB2026-0036', 36, 6, 11, 2, 4, 3, 1.6, 88.00, 'submitted', '2025/2026'),
('PPDB2026-0037', 37, 12, 14, 1, 2, 2, 3.9, 91.25, 'submitted', '2025/2026'),
('PPDB2026-0038', 38, 14, 12, 4, 6, 3, 5.7, 76.75, 'verified', '2025/2026'),
('PPDB2026-0039', 39, 13, 2, 3, 1, 3, 4.2, 82.25, 'submitted', '2025/2026'),
('PPDB2026-0040', 40, 2, 13, 5, 2, 4, 7.0, 79.00, 'submitted', '2025/2026'),

-- Skenario Campuran (Siswa 41-50)
('PPDB2026-0041', 41, 11, NULL, 5, 6, 3, 2.0, 84.50, 'verified', '2025/2026'),
('PPDB2026-0042', 42, 12, 11, 1, 1, 3, 1.4, 86.00, 'submitted', '2025/2026'),
('PPDB2026-0043', 43, 6, NULL, 7, 8, 4, 8.5, 75.50, 'submitted', '2025/2026'),
('PPDB2026-0044', 44, 13, 14, 5, 1, 3, 2.6, 89.75, 'verified', '2025/2026'),
('PPDB2026-0045', 45, 14, NULL, 2, 3, 3, 4.0, 81.00, 'submitted', '2025/2026'),
('PPDB2026-0046', 46, 10, 9, 1, 1, 1, 6.2, 70.25, 'submitted', '2025/2026'),
('PPDB2026-0047', 47, 9, NULL, 3, 4, 3, 7.8, 77.75, 'verified', '2025/2026'),
('PPDB2026-0048', 48, 15, 6, 5, 3, 3, 3.4, 83.25, 'submitted', '2025/2026'),
('PPDB2026-0049', 49, 11, 12, 3, 1, 2, 2.1, 92.00, 'submitted', '2025/2026'),
('PPDB2026-0050', 50, 12, NULL, 2, 4, 3, 1.9, 85.25, 'verified', '2025/2026');


-- =====================================================
-- VERIFIKASI DATA
-- =====================================================
SELECT 'Total Siswa:' as Info, COUNT(*) as Jumlah FROM tb_siswa WHERE nisn LIKE '008123456%';
SELECT 'Total Pendaftaran:' as Info, COUNT(*) as Jumlah FROM tb_pendaftaran WHERE nomor_pendaftaran LIKE 'PPDB2026%';
SELECT status, COUNT(*) as jumlah FROM tb_pendaftaran WHERE nomor_pendaftaran LIKE 'PPDB2026%' GROUP BY status;
