# Manual Book - SPMB SMK Kota Padang

Panduan lengkap penggunaan Sistem Penerimaan Murid Baru (SPMB) SMK Kota Padang untuk Siswa, Admin Sekolah, dan Superadmin.

---

## 📚 PANDUAN SISWA

### 1. Registrasi Akun
1. Buka halaman utama
2. Klik **"Daftar Sekarang"** 
3. Isi formulir pendaftaran:
   - NISN (10 digit)
   - Username
   - Email aktif
   - Password (minimal 6 karakter)
   - Nama lengkap
   - Jenis kelamin
   - Tempat & tanggal lahir
4. Klik **"Daftar"**
5. Jika berhasil, Anda akan diarahkan ke halaman login

### 2. Login Siswa
1. Akan diarahkan ke halaman login
2. Masukkan **NISN** atau **Username**
3. Masukkan **Password**
4. Klik **"Masuk"**

### 3. Melengkapi Profil
1. Setelah login, klik menu **"Profil"**
2. Lengkapi data:
   - Data Pribadi (NIK, No. KK, Agama)
   - Alamat Domisili (RT/RW, Kelurahan, Kecamatan, Kota)
   - Data Orang Tua (Nama, Pekerjaan, Penghasilan)
   - Data Sekolah Asal (Nama Sekolah, NPSN, Tahun Lulus)
3. Upload **Foto Profil** (opsional)
4. Klik **"Simpan"**

### 4. Memilih Jalur Pendaftaran
Tersedia 4 jalur pendaftaran:

| Jalur | Deskripsi | Kuota |
|-------|-----------|-------|
| **Afirmasi** | Siswa dari keluarga kurang mampu (KIP/PKH/KIS/SKTM) | 15% |
| **Prestasi** | Siswa berprestasi akademik/non-akademik | 25% |
| **Zonasi** | Berdasarkan jarak domisili ke sekolah | 50% |
| **Kepindahan** | Siswa yang orang tuanya pindah tugas (ASN/TNI/POLRI) | 10% |

### 5. Melakukan Pendaftaran
1. Klik menu **"Pendaftaran"**
2. Pilih **Jalur Pendaftaran**
3. Pilih **SMK Pilihan 1** (wajib)
4. Pilih **Jurusan Pilihan 1**
5. Pilih **SMK Pilihan 2** (opsional)
6. Pilih **Jurusan Pilihan 2**
7. Klik **"Simpan"**

### 6. Upload Dokumen
1. Klik menu **"Dokumen"**
2. Upload dokumen sesuai jalur:
   - **Semua Jalur**: KK, Ijazah/SKL, Raport
   - **Afirmasi**: KIP/PKH/KIS/SKTM
   - **Prestasi**: Sertifikat/Piagam
   - **Kepindahan**: SK Pindah Tugas Orang Tua
3. Format file: PDF/JPG/PNG (Max 2MB)

### 7. Input Prestasi (Jalur Prestasi)
1. Klik menu **"Prestasi"**
2. Klik **"Tambah Prestasi"**
3. Isi:
   - Nama Prestasi
   - Jenis (Akademik/Non-Akademik/Olahraga/Seni)
   - Tingkat (Kota/Provinsi/Nasional/Internasional)
   - Tahun
   - Upload Sertifikat
4. Klik **"Simpan"**

### 8. Submit Pendaftaran
1. Pastikan semua data dan dokumen sudah lengkap
2. Klik menu **"Pendaftaran"**
3. Review data Anda
4. Klik **"Submit Pendaftaran"**
5. Status akan berubah dari "Draft" menjadi "Diajukan"

### 9. Melihat Status Pendaftaran
1. Klik menu **"Status"**
2. Lihat status pendaftaran:
   - **Draft**: Belum disubmit
   - **Diajukan**: Menunggu verifikasi admin
   - **Terverifikasi**: Dokumen valid, menunggu pengumuman
   - **Diterima**: Selamat! Anda diterima
   - **Ditolak**: Tidak lolos seleksi

---

## 🏫 PANDUAN ADMIN SEKOLAH

### 1. Login Admin Sekolah
1. Akses `http://localhost/PPDB_SMK/login.php?mode=admin`
2. Masukkan **Username** (contoh: `admin_smkn2`)
3. Masukkan **Password** (`admin123`)
4. Klik **"Masuk"**

### 2. Dashboard Admin
Setelah login, Anda akan melihat:
- Statistik pendaftar (Total, Diajukan, Terverifikasi, Diterima, Ditolak)
- Grafik pendaftar per jalur
- Daftar pendaftar terbaru

### 3. Verifikasi Pendaftar
1. Klik menu **"Pendaftar"**
2. Klik nama pendaftar untuk melihat detail
3. Review:
   - Data siswa
   - Dokumen yang diupload
   - Prestasi (jika jalur prestasi)
4. Klik **"Verifikasi"** atau **"Tolak"**
5. Jika menolak, isi alasan penolakan

### 4. Manajemen Kuota
1. Klik menu **"Kuota"**
2. Lihat kuota per jalur
3. Edit kuota jika diperlukan
4. Klik **"Simpan"**

### 5. Laporan
1. Klik menu **"Laporan"**
2. Pilih jenis laporan:
   - Rekap Pendaftar per Jalur
   - Daftar Siswa Diterima
   - Daftar Siswa Ditolak
3. Export ke PDF/Excel jika diperlukan

### 6. Edit Profil Admin
1. Klik menu **"Profil"**
2. Update informasi:
   - Nama Lengkap
   - Email
   - Password (opsional)
3. Klik **"Simpan"**

---

## 👑 PANDUAN SUPERADMIN

### 1. Login Superadmin
1. Akses `http://localhost/PPDB_SMK/login.php?mode=superadmin`
2. Masukkan **Username** (`superadmin`)
3. Masukkan **Password** (`superadmin123`)
4. Klik **"Masuk"**

### 2. Dashboard Superadmin
Dashboard menampilkan:
- Total SMK terdaftar
- Total Admin Sekolah
- Total Pendaftar seluruh kota
- Statistik per jalur

### 3. Kelola Data SMK
1. Klik menu **"Sekolah"**
2. **Tambah SMK**:
   - Klik "Tambah SMK"
   - Isi data sekolah (NPSN, Nama, Alamat, Koordinat, dll)
   - Klik "Simpan"
3. **Edit SMK**: Klik ikon Edit pada baris SMK
4. **Hapus SMK**: Klik ikon Hapus (hati-hati, ini permanen!)

### 4. Kelola Admin Sekolah
1. Klik menu **"Admin Sekolah"**
2. **Tambah Admin**:
   - Klik "Tambah Admin"
   - Pilih SMK
   - Isi Username, Password, Nama, Email
   - Klik "Simpan"
3. **Aktif/Non-aktifkan**: Klik tombol toggle
4. **Edit/Hapus**: Gunakan ikon aksi

### 5. Kelola Jalur Pendaftaran
1. Klik menu **"Jalur"**
2. Edit deskripsi, persyaratan, dan kuota persen
3. Aktif/Non-aktifkan jalur sesuai kebutuhan

### 6. Kelola Kuota
1. Klik menu **"Kuota"**
2. Atur kuota per SMK per jalur
3. Pastikan total kuota sesuai kapasitas sekolah

### 7. Lihat Semua Pendaftar
1. Klik menu **"Pendaftar"**
2. Filter berdasarkan:
   - SMK
   - Jalur
   - Status
3. Export data jika diperlukan

### 8. Laporan Keseluruhan
1. Klik menu **"Laporan"**
2. Lihat rekap seluruh kota:
   - Total pendaftar per SMK
   - Perbandingan kuota vs terisi
   - Statistik per kecamatan

### 9. Pengaturan Sistem
1. Klik menu **"Pengaturan"**
2. Atur:
   - Tahun Ajaran
   - Tanggal Buka/Tutup Pendaftaran
   - Tanggal Pengumuman
   - Radius Zonasi
   - Status Pendaftaran (Buka/Tutup)

---

## 📞 Informasi Kontak

Jika mengalami kendala, hubungi:
- **Email**: ppdb@smkpadang.id
- **Telepon**: 0751-123456
- **Alamat**: Jl. Pendidikan No. 1, Padang

---

## 📋 Daftar Akun Default

### Admin Sekolah
| Username | Password | Sekolah |
|----------|----------|---------|
| admin_smak | admin123 | SMAK Padang |
| admin_smkn1 | admin123 | SMKN 1 Padang |
| admin_smkn2 | admin123 | SMKN 2 Padang |
| admin_smkn3 | admin123 | SMKN 3 Padang |
| admin_smkn4 | admin123 | SMKN 4 Padang |
| admin_smkn5 | admin123 | SMKN 5 Padang |
| admin_smkn6 | admin123 | SMKN 6 Padang |
| admin_smkn7 | admin123 | SMKN 7 Padang |
| admin_smkn8 | admin123 | SMKN 8 Padang |
| admin_smkn9 | admin123 | SMKN 9 Padang |
| admin_smkn10 | admin123 | SMKN 10 Padang |
| admin_smti | admin123 | SMTI Padang |
| admin_smkpp | admin123 | SMK PP Negeri |
| admin_smkn1sb | admin123 | SMKN 1 Sumbar |

### Superadmin
| Username | Password |
|----------|----------|
| superadmin | superadmin123 |

> ⚠️ **PENTING**: Segera ganti password default setelah login pertama kali!
