# 🎯 Prompt Mutia - Jalur Prestasi

Saya adalah Mutia, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani Jalur Prestasi.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tentang Jalur Prestasi:
- Kode Jalur: 'prestasi'
- Kuota: 25% dari total
- Ditujukan untuk siswa berprestasi
- Dinilai berdasarkan akumulasi poin dari sertifikat

## Sistem Poin Prestasi:
| Tingkat | Juara 1 | Juara 2 | Juara 3 | Peserta |
|---------|---------|---------|---------|---------|
| Internasional | 100 | 90 | 80 | 50 |
| Nasional | 80 | 70 | 60 | 30 |
| Provinsi | 60 | 50 | 40 | 20 |
| Kota/Kabupaten | 40 | 30 | 20 | 10 |

## Tugas Saya Secara Spesifik:
1. Form Pendaftaran Jalur Prestasi
   - Input data prestasi (multiple)
   - Kategori: Akademik, Non-Akademik, Olahraga, Seni
   
2. Upload Sertifikat Prestasi
   - Multiple upload untuk banyak prestasi
   - Preview sertifikat
   
3. Sistem Perhitungan Poin
   - Hitung poin berdasarkan tingkat & peringkat
   - Akumulasi total poin
   
4. Ranking Jalur Prestasi
   - Urutkan berdasarkan total poin
   - Tie-breaker jika poin sama

## File yang Saya Kerjakan:
- user/pendaftaran.php (HANYA bagian kode if jalur == prestasi)
- user/dokumen.php (HANYA bagian upload sertifikat)
- admin/verifikasi.php (HANYA bagian verifikasi prestasi)
- admin/pendaftar.php (HANYA filter jalur prestasi)

## Tabel Database KHUSUS PRESTASI:
```sql
-- TABEL UTAMA SAYA: tb_prestasi_siswa
CREATE TABLE tb_prestasi_siswa (
    id_prestasi_siswa INT PRIMARY KEY,
    id_pendaftaran INT,
    nama_prestasi VARCHAR(200),
    jenis_prestasi ENUM('Akademik','Non-Akademik','Olahraga','Seni','Lainnya'),
    tingkat ENUM('Kota/Kabupaten','Provinsi','Nasional','Internasional'),
    tahun INT,
    penyelenggara VARCHAR(200),
    peringkat VARCHAR(50),
    file_sertifikat VARCHAR(255),
    poin INT,
    status_verifikasi ENUM('pending','valid','invalid')
);
```

## Query Penting:
```sql
-- Total poin prestasi per pendaftar
SELECT p.id_pendaftaran, SUM(ps.poin) as total_poin
FROM tb_pendaftaran p
JOIN tb_prestasi_siswa ps ON p.id_pendaftaran = ps.id_pendaftaran
WHERE p.id_jalur = (SELECT id_jalur FROM tb_jalur WHERE kode_jalur = 'prestasi')
GROUP BY p.id_pendaftaran
ORDER BY total_poin DESC
```

## PENTING - Hindari Conflict:
- ❌ JANGAN edit bagian jalur lain (afirmasi, zonasi, kepindahan)
- ❌ JANGAN ubah file admin utama (dashboard, CRUD admin)
- ❌ JANGAN edit perhitungan jarak zonasi
- ❌ JANGAN edit config/database.php atau config/config.php
- ✅ Fokus HANYA pada logic prestasi dan tabel tb_prestasi_siswa

## Penanda Code Block untuk Prestasi:
```php
// === JALUR PRESTASI START ===
// kode untuk prestasi
// === JALUR PRESTASI END ===
```

## Branch Git Saya:
```bash
git checkout -b feature/mutia-jalur-prestasi
```

---

## 🚀 Langkah Memulai

1. **Checkout ke branch sendiri:**
   ```bash
   git checkout -b feature/mutia-jalur-prestasi
   ```

2. **Cek struktur tabel tb_prestasi_siswa sudah ada di database**

3. **Mulai kerjakan fitur pertama:**
   - Form input prestasi di `user/pendaftaran.php`
   - Upload sertifikat di `user/dokumen.php`

---

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
