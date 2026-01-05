# Buku Panduan Sistem PPDB SMK Kota Padang

**Versi 1.0 | Januari 2026**

Dokumen ini berisi panduan lengkap untuk menggunakan Sistem Penerimaan Peserta Didik Baru (PPDB) SMK Kota Padang. Panduan disusun berdasarkan tiga jenis pengguna: **Calon Siswa**, **Admin Sekolah**, dan **Super Administrator**.

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Panduan Calon Siswa](#panduan-calon-siswa)
3. [Panduan Admin Sekolah](#panduan-admin-sekolah)
4. [Panduan Super Administrator](#panduan-super-administrator)

---

## Pendahuluan

Sistem PPDB SMK Kota Padang adalah platform daring yang memudahkan calon siswa mendaftar ke sekolah menengah kejuruan di Kota Padang. Sebelum memulai, pastikan perangkat Anda terhubung ke internet dan menggunakan browser modern seperti Chrome, Firefox, atau Edge.

### Akses Sistem

Buka browser dan kunjungi: **http://localhost/PPDB_SMK** (atau alamat sesuai konfigurasi)

![Halaman Beranda Sistem PPDB SMK](../screenshoot/Screenshot%20(397).png)

---

## Panduan Calon Siswa

Bagian ini menjelaskan langkah-langkah lengkap bagi calon siswa untuk mendaftar ke SMK.

### 1. Membuat Akun Baru

Jika belum memiliki akun, klik tombol **Daftar** pada halaman utama.

![Halaman Registrasi Akun Baru](../screenshoot/Screenshot%20(401).png)

Isi formulir pendaftaran dengan data berikut:
- **NISN** - Nomor Induk Siswa Nasional
- **Email** - Email aktif untuk konfirmasi
- **Password** - Minimal 6 karakter
- **Konfirmasi Password** - Ulangi password yang sama

Setelah mengisi data, klik **Buat Akun**. Anda akan diarahkan ke halaman login.

### 2. Login ke Sistem

Buka halaman login dengan menekan tombol **Masuk** di halaman utama.

![Halaman Login Siswa](../screenshoot/Screenshot%20(398).png)

Masukkan **NISN** dan **Password** yang telah didaftarkan, lalu klik **Masuk**.

### 3. Melengkapi Data Pribadi

Setelah login berhasil, Anda akan masuk ke **Dashboard Siswa**. Langkah pertama adalah melengkapi data pribadi.

![Dashboard Siswa](../screenshoot/Screenshot%20(402).png)

Klik menu **Data Pribadi** di sidebar kiri untuk membuka formulir data diri.

![Formulir Data Pribadi](../screenshoot/Screenshot%20(403).png)

Lengkapi seluruh informasi yang diminta:
- Nama lengkap sesuai ijazah
- Tempat dan tanggal lahir
- Jenis kelamin
- Agama
- Alamat lengkap
- Nama dan pekerjaan orang tua
- Nomor telepon orang tua

Setelah semua field terisi, klik **Simpan Data**.

### 4. Mengunggah Dokumen

Selanjutnya, unggah dokumen pendukung melalui menu **Dokumen**.

![Halaman Upload Dokumen](../screenshoot/Screenshot%20(404).png)

Dokumen yang perlu diunggah:
- **Kartu Keluarga** - Format JPG/PNG/PDF, maksimal 2MB
- **Akta Kelahiran** - Format JPG/PNG/PDF, maksimal 2MB  
- **Foto diri ukuran 3x4** - Format JPG/PNG, maksimal 1MB
- **Rapor Semester Terakhir** - Format PDF, maksimal 5MB (jika jalur prestasi)

Klik tombol **Upload** pada setiap jenis dokumen dan pilih file dari perangkat Anda.

### 5. Melakukan Pendaftaran

Setelah data dan dokumen lengkap, lakukan pendaftaran melalui menu **Pendaftaran**.

![Halaman Form Pendaftaran](../screenshoot/Screenshot%20(405).png)

Pada halaman ini, Anda perlu:
1. **Memilih Jalur Pendaftaran** - Afirmasi, Prestasi, Zonasi, atau Kepindahan

2. **Memilih SMK Pilihan 1** - Sekolah utama yang diinginkan
3. **Memilih SMK Pilihan 2** - Sekolah cadangan (opsional)
4. **Memilih Jurusan** - Program keahlian yang diminati

![Memilih Sekolah dan Jurusan](../screenshoot/Screenshot%20(406).png)

Periksa kembali pilihan Anda, lalu klik **Kirim Pendaftaran**.

### 6. Melihat Hasil Pendaftaran

Setelah pendaftaran berhasil, Anda dapat melihat status melalui menu **Hasil Pendaftaran**.

![Halaman Hasil Pendaftaran](../screenshoot/Screenshot%20(407).png)

Halaman ini menampilkan:
- Status pendaftaran (Menunggu Verifikasi / Terverifikasi / Diterima / Ditolak)
- Informasi sekolah yang dipilih
- Peringkat sementara (jika sudah diproses)
- Tombol cetak bukti pendaftaran

### 7. Mengunduh Bukti Pendaftaran

Untuk mencetak bukti pendaftaran sebagai arsip, klik tombol **Cetak Bukti Pendaftaran** di halaman hasil.

![Bukti Pendaftaran](../screenshoot/Screenshot%20(408).png)

Simpan file PDF atau langsung cetak dokumen tersebut.

---

## Panduan Admin Sekolah

Admin Sekolah bertugas memverifikasi dan mengelola pendaftar di SMK masing-masing.

### 1. Login Admin Sekolah

Akses halaman login khusus admin: **http://localhost/PPDB_SMK/login.php?mode=admin**

![Halaman Login Admin Sekolah](../screenshoot/Screenshot%20(399).png)

Masukkan **Username** dan **Password** yang diberikan superadmin.

### 2. Dashboard Admin

Setelah login, Anda akan melihat dashboard dengan statistik sekolah.

![Dashboard Admin Sekolah](../screenshoot/Screenshot%20(412).png)

Dashboard menampilkan:
- Jumlah total pendaftar
- Jumlah pendaftar per jalur
- Pendaftar menunggu verifikasi
- Kuota tersisa tiap jurusan

### 3. Melihat Daftar Pendaftar

Klik menu **Pendaftar** untuk melihat semua siswa yang mendaftar ke sekolah Anda.

![Daftar Pendaftar](../screenshoot/Screenshot%20(413).png)

Fitur yang tersedia:
- **Filter** berdasarkan jalur, status, atau jurusan
- **Pencarian** berdasarkan nama atau NISN
- **Urutkan** berdasarkan tanggal daftar atau nilai

### 4. Verifikasi Dokumen Pendaftar

Klik nama pendaftar untuk membuka halaman detail.

![Detail Pendaftar](../screenshoot/Screenshot%20(414).png)

Periksa kelengkapan data dan dokumen yang diunggah:
- Pastikan semua dokumen terbaca jelas
- Periksa kecocokkan data dengan dokumen
- Validasi NISN melalui sistem Dapodik (jika tersedia)

![Verifikasi Dokumen](../screenshoot/Screenshot%20(415).png)

Setelah verifikasi, ubah status pendaftar:
- **Terverifikasi** - Jika dokumen lengkap dan valid
- **Revisi** - Jika ada dokumen yang perlu diperbaiki
- **Ditolak** - Jika tidak memenuhi syarat

### 5. Mengelola Kuota Jurusan

Klik menu **Jurusan** untuk mengatur kuota penerimaan tiap program keahlian.

![Pengaturan Kuota Jurusan](../screenshoot/Screenshot%20(416).png)

Anda dapat mengubah:
- Kuota total per jurusan
- Kuota per jalur pendaftaran
- Status aktif/nonaktif jurusan

### 6. Perangkingan Siswa

Setelah periode pendaftaran ditutup, lakukan perangkingan melalui menu **Perangkingan**.

![Halaman Perangkingan](../screenshoot/Screenshot%20(417).png)

Sistem akan secara otomatis menyusun peringkat berdasarkan:
- **Jalur Zonasi**: Jarak rumah ke sekolah
- **Jalur Afirmasi**: Status ekonomi keluarga
- **Jalur Prestasi**: Nilai rapor dan prestasi
- **Jalur Perpindahan**: Prioritas perpindahan orang tua

### 7. Mengumumkan Hasil Seleksi

Setelah perangkingan selesai, klik **Umumkan Hasil** untuk mempublikasikan keputusan.

![Pengumuman Hasil](../screenshoot/Screenshot%20(418).png)

Siswa akan dapat melihat status kelulusan mereka di akun masing-masing.

---

## Panduan Super Administrator

Super Administrator memiliki akses penuh untuk mengelola seluruh sistem PPDB.

### 1. Login Superadmin

Akses halaman: **http://localhost/PPDB_SMK/login.php?mode=superadmin**

![Login Superadmin](../screenshoot/Screenshot%20(400).png)

Gunakan kredensial:
- **Username**: super
- **Password**: super123

### 2. Dashboard Superadmin

Setelah login, dashboard menampilkan statistik keseluruhan sistem.

![Dashboard Superadmin](../screenshoot/Screenshot%20(470).png)

Informasi yang ditampilkan:
- Total SMK terdaftar
- Total pendaftar seluruh SMK
- Statistik per jalur pendaftaran
- Grafik pendaftaran harian

### 3. Mengelola Data SMK

Klik menu **Data SMK** untuk menambah atau mengubah informasi sekolah.

![Daftar SMK](../screenshoot/Screenshot%20(422).png)

Untuk menambah SMK baru:
1. Klik tombol **Tambah SMK**
2. Isi formulir data sekolah (NPSN, Nama, Alamat, dll)
3. Tentukan lokasi koordinat untuk sistem zonasi
4. Klik **Simpan**

![Form Tambah SMK](../screenshoot/Screenshot%20(423).png)

### 4. Mengelola Admin Sekolah

Klik menu **Admin Sekolah** untuk mengatur akun admin tiap SMK.

![Daftar Admin Sekolah](../screenshoot/Screenshot%20(424).png)

Untuk membuat akun admin baru:
1. Klik **Tambah Admin**
2. Pilih SMK yang akan dikelola
3. Isi username dan password
4. Klik **Simpan**

### 5. Mengelola Tahap Seleksi

Klik menu **Tahap Seleksi** untuk mengatur jadwal dan jalur pendaftaran.

![Pengaturan Tahap Seleksi](../screenshoot/Screenshot%20(471).png)

Anda dapat:
- Mengaktifkan/menonaktifkan jalur tertentu
- Mengatur tanggal buka-tutup tiap tahap
- Menentukan bobot penilaian jalur prestasi

### 6. Melihat Semua Pendaftar

Menu **Semua Pendaftar** menampilkan data pendaftar dari seluruh SMK.

![Semua Pendaftar](../screenshoot/Screenshot%20(472).png)

Fitur:
- Filter berdasarkan sekolah, jalur, atau status
- Export data ke CSV/Excel
- Statistik pendaftaran real-time

### 7. Pengaturan Sistem

Menu **Pengaturan Sistem** untuk mengkonfigurasi parameter umum PPDB.

![Pengaturan Sistem](../screenshoot/Screenshot%20(473).png)

Pengaturan yang tersedia:
- **Jadwal PPDB** - Tanggal mulai, akhir, pengumuman
- **Status Pendaftaran** - Buka/tutup pendaftaran
- **Informasi Kontak** - Nama situs, email, telepon
- **Pengaturan Zonasi** - Radius dalam meter

### 8. Kelola Beranda

Menu **Kelola Beranda** untuk mengatur tampilan halaman utama website.

![Kelola Beranda](../screenshoot/Screenshot%20(474).png)

Anda dapat mengubah:
- Judul dan deskripsi hero section
- Jadwal SPMB yang ditampilkan
- Informasi kontak di footer

### 9. Laporan dan Export

Menu **Laporan** menyediakan berbagai laporan dan fitur export data.

![Halaman Laporan](../screenshoot/Screenshot%20(475).png)

Laporan yang tersedia:
- Statistik pendaftaran per sekolah
- Rekapitulasi per jalur
- Data pendaftar lengkap
- Export ke format CSV

---

## Tips dan Bantuan

### Untuk Calon Siswa:
- Siapkan semua dokumen dalam format digital sebelum mendaftar
- Pastikan foto yang diunggah terlihat jelas
- Simpan NISN dan password di tempat aman
- Pantau status pendaftaran secara berkala

### Untuk Admin Sekolah:
- Verifikasi dokumen secara teliti
- Hubungi siswa jika ada dokumen yang kurang jelas
- Backup data pendaftar secara berkala

### Untuk Superadmin:
- Pastikan jadwal PPDB sudah diatur sebelum pendaftaran dibuka
- Monitor kuota sekolah secara berkala
- Lakukan backup database mingguan

---

## Kontak Bantuan

Jika mengalami kendala dalam menggunakan sistem, hubungi:

- **Email**: ppdb@diknas-padang.go.id
- **Telepon**: (0751) 123456
- **WhatsApp**: 0812-xxxx-xxxx

---

*Dokumen ini disusun oleh Tim Pengembang PPDB SMK Kota Padang © 2026*
