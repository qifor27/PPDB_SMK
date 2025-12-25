# 📚 Dokumentasi Jalur Afirmasi - PPDB SMK Kota Padang

## 📋 Informasi Umum

### Apa itu Jalur Afirmasi?
Jalur Afirmasi adalah jalur pendaftaran PPDB yang diperuntukkan bagi **siswa dari keluarga kurang mampu** yang memiliki bukti kepesertaan program bantuan pemerintah.

### Detail Jalur
| Aspek | Keterangan |
|-------|------------|
| **Kode Jalur** | `afirmasi` |
| **Kuota** | 15% dari total kuota |
| **Prioritas** | Siswa dengan dokumen bantuan lengkap dan terverifikasi |
| **Warna Identitas** | Ungu/Purple (#8B5CF6) |

---

## 📄 Dokumen Persyaratan

### Dokumen Wajib Umum
1. Kartu Keluarga (KK)
2. Akta Kelahiran
3. Ijazah/SKL SMP/MTs
4. Raport Semester Terakhir
5. Pas Foto 3x4

### Dokumen Khusus Afirmasi (Minimal 1)
| Dokumen | Keterangan |
|---------|------------|
| **KIP** | Kartu Indonesia Pintar |
| **KKS** | Kartu Keluarga Sejahtera |
| **PKH** | Program Keluarga Harapan |
| **KIS** | Kartu Indonesia Sehat (BPJS PBI) |
| **SKTM** | Surat Keterangan Tidak Mampu dari Kelurahan/Desa |

> ⚠️ **Penting:** Pendaftar WAJIB mengupload minimal 1 dokumen bantuan (KIP/PKH/KIS/SKTM) untuk dapat diproses.

---

## 🔧 Fitur Sistem untuk Jalur Afirmasi

### 1. Form Pendaftaran
**Lokasi:** `user/pendaftaran.php`

Form khusus yang muncul hanya untuk jalur afirmasi:
- **Jenis Bantuan Pemerintah** - Dropdown pilihan (KIP/PKH/KIS/KKS/Lainnya)
- **Nomor Kartu Bantuan** - Input text untuk nomor kartu
- **Penghasilan Orang Tua** - Range penghasilan per bulan
- **Jumlah Tanggungan Keluarga** - Jumlah anggota keluarga
- **Catatan Kondisi Ekonomi** - Textarea opsional
- **Checkbox Dokumen** - Centang dokumen yang dimiliki

### 2. Upload Dokumen
**Lokasi:** `user/dokumen.php`

- Info banner khusus jalur afirmasi
- Penjelasan dokumen yang wajib diupload
- Status real-time kelengkapan dokumen afirmasi
- Validasi minimal 1 dokumen bantuan

### 3. Status Pendaftaran
**Lokasi:** `user/status.php`

- Card status dokumen afirmasi
- Indikator dokumen bantuan sudah/belum diupload
- Status verifikasi dokumen

### 4. Verifikasi Admin
**Lokasi:** `admin/verifikasi.php`

Panduan verifikasi untuk setiap jenis dokumen:

#### KIP/KKS
- ✅ Nomor kartu terlihat jelas
- ✅ Nama sesuai dengan data siswa
- ✅ Kartu masih berlaku (tidak expired)
- ✅ Foto kartu tidak blur/rusak

#### PKH
- ✅ SK PKH dari Kemensos
- ✅ Nama kepala keluarga/anggota tertera
- ✅ Tahun anggaran masih berlaku
- ✅ Stempel/tanda tangan resmi ada

#### KIS
- ✅ Nomor kartu BPJS Kesehatan PBI
- ✅ Nama peserta tertera
- ✅ Status kepesertaan aktif
- ✅ Kelas rawat sesuai PBI

#### SKTM
- ✅ Dikeluarkan oleh Kelurahan/Desa
- ✅ Stempel dan tanda tangan Lurah/Kades
- ✅ Tanggal penerbitan tidak lebih dari 6 bulan
- ✅ Nama dan alamat sesuai KK

### 5. Filter Pendaftar Admin
**Lokasi:** `admin/pendaftar.php`

- Quick filter button "Jalur Afirmasi"
- Statistik jumlah pendaftar afirmasi
- Info banner saat filter aktif

---

## 🛠️ Fungsi Helper

**Lokasi:** `config/functions.php`

| Fungsi | Deskripsi |
|--------|-----------|
| `getAfirmasiDokumenTypes()` | Mengembalikan array jenis dokumen afirmasi |
| `getJenisBantuanOptions()` | Mengembalikan array opsi jenis bantuan |
| `getRangePenghasilan()` | Mengembalikan array range penghasilan |
| `validateAfirmasiDokumen($id)` | Validasi kelengkapan dokumen afirmasi |
| `getAfirmasiStatus($id)` | Mendapatkan status kelengkapan afirmasi |
| `countAfirmasiPendaftar($smkId)` | Menghitung jumlah pendaftar afirmasi |
| `getAfirmasiVerificationChecklist()` | Mendapatkan checklist verifikasi dokumen |

### Contoh Penggunaan

```php
// Validasi dokumen afirmasi
$status = validateAfirmasiDokumen($pendaftaran['id_pendaftaran']);
if ($status['is_complete']) {
    echo "Dokumen afirmasi lengkap!";
} else {
    echo $status['message']; // "Minimal upload 1 dokumen bantuan..."
}

// Get status afirmasi
$afirmasiStatus = getAfirmasiStatus($pendaftaran['id_pendaftaran']);
echo "Dokumen terverifikasi: " . $afirmasiStatus['verified_count'];
```

---

## 🎨 CSS Styling

**Lokasi:** `assets/css/style.css`

Class CSS khusus untuk jalur afirmasi:

```css
.text-purple          /* Warna teks ungu */
.bg-purple            /* Background ungu solid */
.bg-purple-soft       /* Background ungu lembut (gradient) */
.border-purple        /* Border ungu */
.btn-purple           /* Button ungu solid */
.btn-outline-purple   /* Button outline ungu */
.alert-purple         /* Alert box ungu */
```

### Contoh Penggunaan
```html
<div class="card border-purple">
    <div class="card-header bg-purple-soft">
        <h6>Data Afirmasi</h6>
    </div>
</div>

<button class="btn btn-purple">Simpan</button>
<span class="badge bg-purple-soft">Afirmasi</span>
```

---

## 📁 Struktur File

```
PPDB_SMK/
├── config/
│   └── functions.php          # Fungsi helper afirmasi (line 460+)
├── user/
│   ├── pendaftaran.php        # Form data ekonomi keluarga
│   ├── dokumen.php            # Info banner dokumen afirmasi
│   └── status.php             # Status dokumen afirmasi
├── admin/
│   ├── verifikasi.php         # Panduan verifikasi dokumen
│   └── pendaftar.php          # Quick filter afirmasi
├── assets/
│   └── css/
│       └── style.css          # Styling ungu/purple (akhir file)
└── docs/
    └── JALUR_AFIRMASI.md      # Dokumentasi ini
```

---

## 🔍 Penanda Kode

Semua kode jalur afirmasi ditandai dengan comment block:

```php
// === JALUR AFIRMASI START ===
// Kode untuk fitur afirmasi
// === JALUR AFIRMASI END ===
```

Gunakan pencarian ini untuk menemukan semua kode afirmasi:
```bash
grep -r "JALUR AFIRMASI" --include="*.php" .
```

---

## 📊 Database

### Tabel yang Digunakan
| Tabel | Kolom Relevan |
|-------|---------------|
| `tb_jalur` | `kode_jalur = 'afirmasi'` |
| `tb_pendaftaran` | `id_jalur` (FK ke tb_jalur) |
| `tb_dokumen` | `jenis_dokumen` (KIP/PKH/KIS/SKTM) |
| `tb_siswa` | Data siswa pendaftar |
| `tb_kuota` | Kuota per jalur per SMK |

### Query Penting

```sql
-- Ambil semua pendaftar jalur afirmasi
SELECT p.*, s.nama_lengkap 
FROM tb_pendaftaran p
JOIN tb_siswa s ON p.id_siswa = s.id_siswa
JOIN tb_jalur j ON p.id_jalur = j.id_jalur
WHERE j.kode_jalur = 'afirmasi';

-- Cek dokumen afirmasi
SELECT * FROM tb_dokumen 
WHERE id_pendaftaran = ? 
AND (jenis_dokumen LIKE '%KIP%' 
     OR jenis_dokumen LIKE '%PKH%' 
     OR jenis_dokumen LIKE '%KIS%' 
     OR jenis_dokumen LIKE '%SKTM%');
```

---

## 👩‍💻 Pengembang

| Nama | Tugas | Branch |
|------|-------|--------|
| **Sabrina** | Jalur Afirmasi | `feature/sabrina-jalur-afirmasi` |

---

## 📅 Riwayat Perubahan

| Tanggal | Perubahan |
|---------|-----------|
| 25 Des 2025 | Implementasi awal jalur afirmasi |
| 25 Des 2025 | Fix error mixed parameters PDO |
| 25 Des 2025 | Fix warning headers already sent |

---

**© 2025 PPDB SMK Kota Padang**
