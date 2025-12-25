# 📚 Panduan Kolaborasi Project PPDB SMK

## 🔗 Clone Project

```bash
# Clone repository dari GitHub
git clone https://github.com/qifor27/PPDB_SMK.git

# Masuk ke folder project
cd PPDB_SMK
```

---

## 🚀 Cara Menjalankan Project

### Prerequisites
1. **XAMPP** dengan Apache dan MySQL aktif
2. **PHP 8.0+**
3. **Git** terinstall

### Langkah-langkah Setup

1. **Copy folder ke htdocs**
   ```bash
   # Jika clone langsung ke htdocs
   cd C:\xampp\htdocs\
   git clone https://github.com/[USERNAME]/PPDB_SMK.git
   ```

2. **Import Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru dengan nama `dbesemka`
   - Import file `dbesemka.sql` terlebih dahulu
   - Kemudian import file `database/ppdb_tables.sql`

3. **Konfigurasi Database**
   - Buka file `config/config.php`
   - Sesuaikan konfigurasi jika diperlukan:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'dbesemka');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Akses Website**
   - Buka browser dan akses: `http://localhost/PPDB_SMK`
   - Login Superadmin: `superadmin` / `superadmin123`

---

## 👥 Pembagian Tugas Tim

### 📋 Struktur Tim

| No | Nama | Tugas | Folder Utama |
|----|------|-------|--------------|
| 1 | **Rofiq** | CRUD Admin & Superadmin | `admin/`, `superadmin/`, `config/` |
| 2 | **Sabrina** | Jalur Afirmasi | `user/`, `admin/` (related to afirmasi) |
| 3 | **Mutia** | Jalur Prestasi | `user/`, `admin/` (related to prestasi) |
| 4 | **Rafa** | Jalur Zonasi | `user/`, `admin/` (related to zonasi) |
| 5 | **Veli** | Jalur Kepindahan | `user/`, `admin/` (related to kepindahan) |

---

## 📁 Detail Tugas Masing-masing

### 1️⃣ ROFIQ - CRUD Admin & Superadmin

**Fokus Area:**
- Manajemen admin sekolah (tb_admin_sekolah)
- Manajemen superadmin (tb_superadmin)
- Dashboard admin & superadmin
- Login/logout system
- Manajemen user roles

**File yang Dikerjakan:**
```
├── admin/
│   ├── index.php (dashboard admin)
│   ├── includes/
│   │   ├── header.php
│   │   └── sidebar.php (jika ada)
├── superadmin/
│   ├── index.php (dashboard superadmin)
│   ├── admin-sekolah.php (CRUD admin sekolah)
│   ├── pengaturan.php (pengaturan sistem)
│   ├── sekolah.php (manajemen sekolah)
│   ├── includes/
├── config/
│   ├── functions.php (fungsi umum admin)
│   ├── session.php (manajemen session)
├── login.php
├── logout.php
```

**Tabel Database:**
- `tb_admin`
- `tb_superadmin`
- `tb_admin_sekolah`
- `tb_pengaturan`
- `tb_log_aktivitas`

---

### 2️⃣ SABRINA - Jalur Afirmasi

**Fokus Area:**
- Form pendaftaran jalur afirmasi
- Validasi dokumen afirmasi (KIP, PKH, SKTM)
- Verifikasi pendaftar jalur afirmasi
- Logika penilaian/ranking afirmasi

**File/Fitur yang Dikerjakan:**
```
├── user/
│   ├── pendaftaran.php (bagian afirmasi)
│   ├── dokumen.php (upload dokumen afirmasi)
│   ├── status.php (status pendaftaran)
├── admin/
│   ├── verifikasi.php (verifikasi afirmasi)
│   ├── pendaftar.php (filter jalur afirmasi)
│   ├── detail-siswa.php (detail siswa afirmasi)
```

**Tabel Database yang Digunakan:**
- `tb_jalur` (kode_jalur = 'afirmasi')
- `tb_pendaftaran` (where id_jalur = afirmasi)
- `tb_dokumen` (dokumen afirmasi: KIP, PKH, SKTM)
- `tb_kuota` (kuota jalur afirmasi)

**Kode Jalur:** `afirmasi`
**Persyaratan Khusus:**
- KIP/KKS
- SKTM
- PKH/KIS

---

### 3️⃣ MUTIA - Jalur Prestasi

**Fokus Area:**
- Form pendaftaran jalur prestasi
- Upload & manajemen sertifikat prestasi
- Sistem poin prestasi berdasarkan tingkat
- Ranking berdasarkan akumulasi poin

**File/Fitur yang Dikerjakan:**
```
├── user/
│   ├── pendaftaran.php (bagian prestasi)
│   ├── dokumen.php (upload sertifikat)
├── admin/
│   ├── verifikasi.php (verifikasi prestasi)
│   ├── pendaftar.php (filter jalur prestasi)
```

**Tabel Database yang Digunakan:**
- `tb_jalur` (kode_jalur = 'prestasi')
- `tb_pendaftaran`
- `tb_prestasi_siswa` (TABEL KHUSUS PRESTASI)
- `tb_dokumen` (sertifikat prestasi)
- `tb_kuota`

**Kode Jalur:** `prestasi`
**Sistem Poin:**
- Internasional: Poin tinggi
- Nasional: Poin sedang
- Provinsi: Poin rendah
- Kota/Kabupaten: Poin terendah

---

### 4️⃣ RAFA - Jalur Zonasi

**Fokus Area:**
- Form pendaftaran jalur zonasi
- Integrasi perhitungan jarak (koordinat GPS)
- Validasi domisili berdasarkan KK
- Ranking berdasarkan jarak terdekat

**File/Fitur yang Dikerjakan:**
```
├── user/
│   ├── pendaftaran.php (bagian zonasi)
│   ├── dokumen.php (upload KK, bukti domisili)
├── admin/
│   ├── verifikasi.php (verifikasi zonasi)
│   ├── pendaftar.php (filter jalur zonasi)
├── api/
│   ├── get-smk.php (data koordinat sekolah)
```

**Tabel Database yang Digunakan:**
- `tb_jalur` (kode_jalur = 'zonasi')
- `tb_pendaftaran` (jarak_ke_sekolah, skor_zonasi)
- `tb_siswa` (latitude, longitude, alamat)
- `tb_smk` (latitude, longitude sekolah)
- `tb_kuota`

**Kode Jalur:** `zonasi`
**Fitur Khusus:**
- Perhitungan jarak Haversine
- Radius zonasi (default 3km)
- Mapping koordinat

---

### 5️⃣ VELI - Jalur Kepindahan Orang Tua

**Fokus Area:**
- Form pendaftaran jalur kepindahan
- Validasi dokumen kepindahan (SK Pindah Tugas)
- Verifikasi dokumen instansi
- Logika seleksi kepindahan

**File/Fitur yang Dikerjakan:**
```
├── user/
│   ├── pendaftaran.php (bagian kepindahan)
│   ├── dokumen.php (upload SK pindah, surat instansi)
├── admin/
│   ├── verifikasi.php (verifikasi kepindahan)
│   ├── pendaftar.php (filter jalur kepindahan)
```

**Tabel Database yang Digunakan:**
- `tb_jalur` (kode_jalur = 'kepindahan')
- `tb_pendaftaran`
- `tb_dokumen` (SK pindah, surat instansi)
- `tb_kuota`

**Kode Jalur:** `kepindahan`
**Persyaratan Khusus:**
- SK Pindah Tugas
- Surat Keterangan Instansi
- KK baru
- Surat pindah sekolah

---

## 🌿 Aturan Git (PENTING!)

### 1. Buat Branch Sendiri
```bash
# Rofiq
git checkout -b feature/rofiq-admin-crud

# Sabrina
git checkout -b feature/sabrina-jalur-afirmasi

# Mutia
git checkout -b feature/mutia-jalur-prestasi

# Rafa
git checkout -b feature/rafa-jalur-zonasi

# Veli
git checkout -b feature/veli-jalur-kepindahan
```

### 2. Hindari Merge Conflict
- ⚠️ **JANGAN** edit file yang bukan bagian tugas kamu
- ⚠️ **JANGAN** push langsung ke branch `main`
- ✅ Selalu pull dari main sebelum mulai kerja
- ✅ Komunikasi jika perlu edit file bersama

### 3. Workflow
```bash
# 1. Update dari main dulu
git checkout main
git pull origin main

# 2. Kembali ke branch sendiri & merge main
git checkout feature/[nama-branch-kamu]
git merge main

# 3. Kerjakan tugasmu
# ... coding ...

# 4. Commit dengan pesan yang jelas
git add .
git commit -m "[NAMA] - Deskripsi perubahan"

# 5. Push branch ke GitHub
git push origin feature/[nama-branch-kamu]

# 6. Buat Pull Request di GitHub untuk review
```

### 4. Format Commit Message
```
[ROFIQ] - Menambahkan fitur CRUD admin sekolah
[SABRINA] - Fix validasi dokumen KIP jalur afirmasi
[MUTIA] - Implementasi sistem poin prestasi
[RAFA] - Integrasi perhitungan jarak zonasi
[VELI] - Form upload SK pindah tugas
```

---

## ⚠️ File yang TIDAK BOLEH Diubah Sembarangan

File-file berikut adalah file bersama, koordinasikan jika perlu diubah:

| File | Penanggungjawab | Catatan |
|------|-----------------|---------|
| `config/database.php` | Rofiq | Core database |
| `config/config.php` | Rofiq | Konfigurasi utama |
| `dbesemka.sql` | Semua (koordinasi) | Backup database |
| `database/ppdb_tables.sql` | Semua (koordinasi) | Schema database |
| `assets/css/style.css` | Koordinasi | Styling global |
| `index.php` | Koordinasi | Landing page |

---

## 📞 Kontak & Komunikasi

Gunakan grup WhatsApp/Discord untuk:
- Koordinasi jika perlu edit file bersama
- Laporan progress harian
- Bantuan jika stuck
- Review code sebelum merge

---

## ✅ Checklist Sebelum Push

- [ ] Sudah test di localhost tanpa error
- [ ] Tidak ada syntax error
- [ ] Commit message sesuai format
- [ ] Hanya mengubah file sesuai tugas
- [ ] Sudah pull & merge main terbaru

---

**Selamat mengerjakan! 💪**
