# Panduan Sistem Perangkingan SPMB SMK Kota Padang

**Tahun Ajaran 2025/2026**

---

## 📊 Dasar Hukum & Regulasi

Sistem perangkingan SPMB SMK Kota Padang mengacu pada:
1. **Peraturan Menteri Pendidikan dan Kebudayaan** tentang PPDB
2. **Keputusan Kepala Dinas Pendidikan Kota Padang** tentang SPMB SMK
3. **Juknis SPMB SMK Tahun Ajaran 2025/2026**

---

## � Jalur Penerimaan Siswa Baru (PPDB)

Berdasarkan Juknis SPMB SMK tahun 2025/2026, penerimaan siswa dibagi menjadi **4 Jalur** dengan kuota masing-masing:

### 1️⃣ Jalur Afirmasi (Minimum 15%)

**Prioritas untuk:**
- ✅ Keluarga ekonomi tidak mampu
- ✅ Penyandang disabilitas
- ✅ Anak panti asuhan/panti sosial

**Syarat Kelengkapan Data:**
Siswa yang mendaftar jalur afirmasi **WAJIB** melengkapi salah satu dokumen berikut:
- Kartu Indonesia Pintar (KIP)
- Program Keluarga Harapan (PKH)
- Kartu Indonesia Sehat (KIS)
- Surat Keterangan Tidak Mampu dari Kelurahan
- Surat Keterangan Disabilitas
- Surat Keterangan dari Panti Asuhan/Sosial

**Peringkat berdasarkan:**
1. Kelengkapan dokumen pendukung
2. Nilai Akhir (30% Rapor + 70% Tes)
3. Umur (tertua)
4. Jarak ke sekolah (terdekat)

**⚠️ Catatan Penting:** Jika data tidak lengkap, pendaftaran jalur afirmasi akan **DITOLAK**!

---

### 2️⃣ Jalur Zonasi (Maksimal 10%)

**Prioritas untuk:**
- ✅ Calon murid yang berdomisili **terdekat** dengan Satuan Pendidikan

**Peringkat berdasarkan:**
1. **Jarak ke sekolah** (terdekat menang) ← Prioritas Utama
2. Nilai Akhir (30% Rapor + 70% Tes)
3. Umur (tertua)
4. Tanggal pendaftaran (terdahulu)

**📍 Cara hitung jarak:**
Sistem otomatis menghitung jarak dari **koordinat rumah siswa** (yang diinput di profil) ke **koordinat sekolah** menggunakan rumus _Haversine_.

---

### 3️⃣ Jalur Prestasi (Maksimal 20%)

**Prioritas untuk:**
- ✅ Calon murid dengan prestasi akademik
- ✅ Calon murid dengan prestasi non-akademik

**Bobot Prestasi:**

| Tingkat | Juara 1 | Juara 2 | Juara 3 | Peserta |
|---------|---------|---------|---------|---------|
| **Internasional** | 100 poin | 90 poin | 80 poin | 50 poin |
| **Nasional** | 80 poin | 70 poin | 60 poin | 30 poin |
| **Provinsi** | 60 poin | 50 poin | 40 poin | 20 poin |
| **Kota/Kabupaten** | 40 poin | 30 poin | 20 poin | 10 poin |

**Peringkat berdasarkan:**
1. **Total Poin Prestasi** (tertinggi) ← Prioritas Utama
2. Nilai Akhir (30% Rapor + 70% Tes)
Jika nilai akhir **sama persis**, maka yang **lebih tua** mendapat prioritas.

**Contoh:**
- Siswa A: NA = 90.00, Umur = 15 tahun 8 bulan
- Siswa B: NA = 90.00, Umur = 15 tahun 6 bulan
- **Ranking:** A (peringkat 1), B (peringkat 2)

**Catatan:** Umur dihitung dalam **bulan** untuk akurasi yang lebih presisi.

---

### 3️⃣ Prioritas Ketiga: Tanggal Pendaftaran (Terdahulu)
Jika nilai akhir **dan** umur **sama**, maka yang **lebih dahulu mendaftar** mendapat prioritas.

**Contoh:**
- Siswa A: NA = 90.00, Umur = 15 tahun 6 bulan, Daftar = 5 Januari 2025
- Siswa B: NA = 90.00, Umur = 15 tahun 6 bulan, Daftar = 10 Januari 2025
- **Ranking:** A (peringkat 1), B (peringkat 2)

**Penting:** Ini mendorong calon siswa untuk **segera mendaftar** jika sudah siap! ⏰

---

### 4️⃣ Prioritas Keempat: Jarak ke Sekolah (Terdekat)
Jika semua faktor di atas **sama**, maka yang **lebih dekat** dengan sekolah mendapat prioritas.

**Contoh:**
- Siswa A: NA = 90.00, Umur = sama, Tanggal = sama, Jarak = 2.5 km
- Siswa B: NA = 90.00, Umur = sama, Tanggal = sama, Jarak = 3.8 km
- **Ranking:** A (peringkat 1), B (peringkat 2)

**Catatan:** Jarak dihitung otomatis dari koordinat rumah siswa ke koordinat sekolah.

---

## 📐 Cara Perhitungan Detail

### Step 1: Hitung Nilai Rapor
```
Nilai Rapor = (Semester 1 + Semester 2 + Semester 3 + Semester 4 + Semester 5) / 5
```

**Contoh:**
- Semester 1: 85
- Semester 2: 87
- Semester 3: 86
- Semester 4: 88
- Semester 5: 89

```
Nilai Rapor = (85 + 87 + 86 + 88 + 89) / 5 = 87.00
```

### Step 2: Hitung Bobot Rapor (30%)
```
Bobot Rapor = Nilai Rapor × 0.30
```

**Contoh:**
```
Bobot Rapor = 87.00 × 0.30 = 26.10
```

### Step 3: Hitung Bobot Tes Minat Bakat (70%)
```
Bobot TMB = Nilai TMB × 0.70
```

**Contoh (Nilai TMB = 95):**
```
Bobot TMB = 95.00 × 0.70 = 66.50
```

### Step 4: Hitung Nilai Akhir
```
Nilai Akhir = Bobot Rapor + Bobot TMB
```

**Contoh:**
```
Nilai Akhir = 26.10 + 66.50 = 92.60
```

---

## 🏆 Contoh Kasus Lengkap

### Kasus: 5 Siswa dengan Berbagai Kondisi

| Rank | Nama | NA | TMB | Rapor | Umur (bulan) | Tanggal Daftar | Jarak (km) |
|------|------|-----|-----|-------|-------------|----------------|------------|
| 1 | Andi | 94.00 | 100.0 | 80.0 | 186 | 5 Jan 2025 | 2.1 |
| 2 | Budi | 94.00 | 100.0 | 80.0 | 185 | 5 Jan 2025 | 1.5 |
| 3 | Citra | 92.50 | 95.0 | 85.0 | 188 | 3 Jan 2025 | 0.8 |
| 4 | Dina | 92.50 | 95.0 | 85.0 | 188 | 10 Jan 2025 | 3.2 |
| 5 | Eka | 90.00 | 90.0 | 90.0 | 190 | 1 Jan 2025 | 5.0 |

**Analisis:**
- **Andi vs Budi**: NA sama (94.00), tapi Andi **lebih tua** (186 vs 185 bulan) → Andi Rank 1
- **Citra vs Dina**: NA sama (92.50), umur sama (188), tapi Citra **daftar lebih dulu** (3 Jan vs 10 Jan) → Citra Rank 3
- **Eka**: NA lebih rendah (90.00) → Rank 5, meski paling tua dan paling awal daftar

---

## ⚖️ Prinsip Keadilan

### 1. **Objektif & Transparan**
Semua kriteria dan bobot penilaian **terbuka** untuk umum dan **dapat diverifikasi**.

### 2. **Berbasis Kompetensi**
Nilai tes bakat minat memiliki bobot **70%** karena lebih relevan dengan kompetensi kejuruan.

### 3. **First Come First Serve (Terbatas)**
Tanggal pendaftaran hanya berlaku sebagai **tiebreaker ketiga**, bukan prioritas utama.

### 4. **Zonasi Berimbang**
Jarak ke sekolah menjadi **tiebreaker terakhir** untuk mendukung prinsip zonasi.

---

## 🔄 Proses Perhitungan Ranking

### Tahapan Sistem

1. **Input Data**
   - Siswa melengkapi profil dan koordinat lokasi
   - Admin input nilai tes minat bakat
   - Sistem otomatis ambil nilai rapor dari database

2. **Perhitungan Otomatis**
   - Sistem hitung bobot rapor (30%)
   - Sistem hitung bobot TMB (70%)
   - Sistem hitung nilai akhir (NA)
   - Sistem hitung umur dalam bulan
   - Sistem hitung jarak ke sekolah (jika ada koordinat)

3. **Sorting & Ranking**
   - Sistem sort berdasarkan 4 kriteria prioritas
   - Assign ranking untuk setiap siswa per jurusan per tahap

4. **Publikasi**
   - Ranking dipublikasikan di halaman publik
   - Admin dan siswa dapat melihat posisi masing-masing

---

## ❓ FAQ Perangkingan

### Q: Apakah nilai rapor lebih penting dari tes?
**A:** Tidak. Nilai tes minat bakat memiliki bobot **70%**, sedangkan rapor hanya **30%**.

### Q: Bagaimana kalau siswa tidak mengisi koordinat rumah?
**A:** Jarak akan bernilai NULL dan tidak digunakan dalam tiebreaker. Sangat disarankan untuk mengisi!

### Q: Apakah tanggal daftar sangat menentukan?
**A:** Hanya jika nilai akhir DAN umur sama persis. Namun tetap disarankan daftar segera.

### Q: Apakah ranking bisa berubah?
**A:** Ya, jika:
- Ada siswa baru yang mendaftar dengan nilai lebih tinggi
- Ada perubahan nilai tes yang di-update admin
- Ada pembatalan pendaftaran

### Q: Berapa kuota per jurusan?
**A:** Setiap jurusan memiliki kuota berbeda. Cek di halaman detail SMK atau tanya admin sekolah.

---

## 📞 Informasi & Bantuan

Jika ada pertanyaan lebih lanjut tentang sistem perangkingan:

| Channel | Kontak |
|---------|--------|
| 📧 Email | spmb@smkpadang.go.id |
| 📞 Telepon | (0751) 123456 |
| 💬 WhatsApp | 0821-1234-5678 |

---

**© 2025 Dinas Pendidikan Kota Padang**

*Sistem Penerimaan Murid Baru SMK - Transparan, Adil, dan Akuntabel*
