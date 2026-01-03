# Panduan Kolaborasi Tim PPDB SMK

Dokumen ini berisi pembagian tugas terbaru dan panduan teknis git untuk menghindari konflik saat penggabungan (merge) kode.

## 👥 Pembagian Tugas

Setiap anggota tim memiliki tanggung jawab pada direktori dan fitur spesifik. **DILARANG** mengedit file di luar direktori tanggung jawab masing-masing kecuali sangat diperlukan dan sudah dikomunikasikan.

### 1. Mutia - User / Siswa
*   **Fokus Direktori**: `user/`
*   **Tugas Utama**:
    *   Mengurus fitur user/siswa.
    *   **Dropdown Wilayah**: Menambahkan dropdown otomatis untuk **Kota/Kabupaten**, **Kecamatan**, dan **Kelurahan** khusus wilayah **Sumatera Barat (Sumbar)** pada form pendaftaran atau profil siswa.
    *   **Pencarian SMK**: Memastikan fitur pencarian SMK bisa difilter berdasarkan Kecamatan. Saat memilih kecamatan, daftar SMK yang muncul hanya yang berada di kecamatan tersebut.

### 2. Suci - Superadmin
*   **Fokus Direktori**: `superadmin/`
*   **Tugas Utama**:
    *   Mengelola fitur dashboard Superadmin.
    *   **Improvement & Fixes**: Melakukan perbaikan (bug fixing), refactoring kode, dan peningkatan fitur (improvement) yang dirasa perlu pada sisi Superadmin.
    *   Memastikan data master (seperti data wilayah jika ada di database) valid.

### 3. Rofiq - Admin Sekolah
*   **Fokus Direktori**: `admin/`
*   **Tugas Utama**:
    *   Mengurus fitur dashboard Admin Sekolah.
    *   Verifikasi pendaftar, manajemen kuota, dan laporan di level sekolah masing-masing.

---

## � Persiapan Awal (Khusus Member Baru / Suci)
Jika Anda belum pernah menghubungkan folder proyek di laptop ke repository GitHub (Remote), lakukan langkah ini sekali saja di awal:

1.  **Buka Terminal** di VS Code.
2.  **Inisialisasi Git** (jika belum ada folder `.git`):
    ```bash
    git init
    ```
3.  **Tambahkan Remote Repository**:
    Minta URL repository ke ketua tim (biasanya berakhiran `.git`).
    ```bash
    git remote add origin https://github.com/USERNAME_KETUA/NAMA_REPO.git
    ```
4.  **Cek Apakah Berhasil**:
    ```bash
    git remote -v
    ```
    Jika muncul tulisan `origin ... (fetch)` dan `origin ... (push)`, berarti sudah aman.

---

## �🛠 Strategi Git & Merge (Anti-Konflik)

Karena kita sudah bekerja dengan branch masing-masing, ikuti langkah berikut untuk menggabungkan kode (merge) kembali ke branch utama (`main`) dengan aman.

### Aturan Utama
1.  **Fokus pada Folder Sendiri**: Mutia di `user/`, Suci di `superadmin/`, Rofiq di `admin/`. Konflik biasanya terjadi jika dua orang mengedit file yang sama (misal: `assets/style.css` atau `config/database.php`).
2.  **Komunikasi**: Jika harus mengedit file *shared* (seperti CSS global atau Config database), kabari grup dulu.

### Langkah Merge (Penggabungan)

Setiap anggota tim harus melakukan langkah ini dari laptop masing-masing:

#### 1. Simpan Pekerjaan (Commit)
Pastikan semua perubahan di branch Anda sudah di-commit.
```bash
git add .
git commit -m "Menyelesaikan fitur X"
```

#### 2. Ambil Kode Terbaru dari Main (Pull)
Pindah ke branch `main` dan update kodenya.
```bash
git checkout main
git pull origin main
```

#### 3. Kembali ke Branch Anda & Merge Main (Update Local Branch)
Ini langkah penting! Kita update branch kita dengan codingan terbaru dari `main` dulu untuk menyelesaikan konflik di branch sendiri (bukan di main).
```bash
git checkout <nama-branch-anda>
# Contoh: git checkout feature/mutia-user

git merge main
```

#### 4. Selesaikan Konflik (Jika Ada)
Jika muncul pesan `CONFLICT`, jangan panik.
*   Buka VS Code.
*   Lihat file yang merah (conflict).
*   Pilih *Accept Current Change* (punya Anda) atau *Accept Incoming Change* (dari main) atau *Accept Both* sesuai kebutuhan.
*   Setelah semua konflik bersih, save file.
*   Lakukan commit merge:
    ```bash
    git add .
    git commit -m "Fix merge conflict from main"
    ```

#### 5. Push ke Repository
Setelah aman, push branch Anda.
```bash
git push origin <nama-branch-anda>
```

#### 6. Buat Pull Request (PR) atau Merge ke Main
Cara paling aman adalah buat Pull Request di GitHub/GitLab agar bisa direview teman.
**ATAU** jika sepakat merge langsung:
```bash
git checkout main
git merge <nama-branch-anda>
git push origin main
```

---

## 📝 Catatan Khusus
*   **Data Wilayah**: Untuk Mutia, pastikan database memiliki tabel referensi wilayah (kota, kecamatan, kelurahan) atau gunakan API wilayah Indonesia untuk mengisi dropdown.
*   **Database**: Jika ada yang mengubah struktur database (`dbesemka.sql`), wajib share file SQL baru atau query `ALTER TABLE` ke grup.
