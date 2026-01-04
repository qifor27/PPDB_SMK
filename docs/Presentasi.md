# Panduan Presentasi Sistem PPDB SMK Kota Padang 2025

Dokumen ini berisi panduan teknis dan alur sistem untuk kebutuhan presentasi tim. Materi disesuaikan dengan update fitur terakhir aplikasi.

---

## 👥 1. User Siswa (Rafa & Mutia)
**Fokus:** Menjelaskan pengalaman pengguna (User Experience) siswa dari mendaftar sampai memantau hasil.

### Alur Presentasi:
1.  **Registrasi & Login**
    *   Tunjukkan halaman `Daftar Akun`.
    *   Isi NISN, Nama, dan Password.
    *   Login menggunakan NISN yang didaftarkan.

2.  **Lengkapi Profil (Fitur Baru: Peta Lokasi)**
    *   Masuk ke menu `Profil Saya`.
    *   Tunjukkan fitur **Maps/Peta**:
        *   Klik tombol **"Deteksi Lokasi Saya"** (Warna Ungu) untuk auto-detect GPS.
        *   Atau klik manual pada peta untuk set titik koordinat rumah.
    *   Pastikan Biodata terisi lengkap.

3.  **Input Nilai Rapor**
    *   Input nilai rata-rata pengetahuan semester 1-5.
    *   Upload foto rapor asli untuk verifikasi.

4.  **Pilih Sekolah (Fitur Baru: Wizard Pemilihan)**
    *   Jelaskan flow baru yang sesuai Juknis 2025.
    *   Siswa harus memilih **Mode Pendaftaran** terlebih dahulu:
        *   **Mode A (1 Sekolah, 2 Jurusan):** Pilih 1 SMK, lalu pilih 2 Jurusan berbeda di sekolah tersebut. Tes dilakukan di sekolah itu.
        *   **Mode B (2 Sekolah, 1 Jurusan):** Pilih 1 Jurusan dulu, lalu pilih 2 SMK berbeda yang punya jurusan tersebut. Tes dilakukan di SMK Pilihan 1.
    *   Tunjukkan tampilan baru yang menggunakan tema **Ungu Gradasi**.

5.  **Cek Status & Cetak Kartu**
    *   Masuk menu `Hasil Pendaftaran` atau `Status`.
    *   Lihat status verifikasi (Menunggu/Diverifikasi).
    *   Download Bukti Pendaftaran.

---

## 🏫 2. Admin Sekolah (Rofiq & Veli)
**Fokus:** Menjelaskan bagaimana operator sekolah memproses data siswa yang masuk.

### Alur Presentasi:
1.  **Login Admin Sekolah**
    *   Login dengan akun admin sekolah (misal: `admin_smkn2` / `admin123`).
    
2.  **Verifikasi Berkas**
    *   Masuk menu `Verifikasi Dokumen`.
    *   Cek kesesuaian data yang diinput siswa dengan file yang diupload (Rapor, KK, dll).
    *   Klik **Terima** atau **Tolak** (jika data salah).

3.  **Verifikasi Pilihan Jurusan**
    *   Pastikan siswa tidak buta warna untuk jurusan tertentu (DKV, Farmasi, dll).

4.  **Input Nilai Tes Bakat Minat**
    *   Ini adalah tugas krusial admin sekolah.
    *   Input nilai tes hasil seleksi offline ke sistem untuk digabungkan dengan nilai rapor.

5.  **Proses Kelulusan**
    *   Masuk menu `Proses Kelulusan`.
    *   Lihat daftar siswa yang sudah diverifikasi dan punya nilai lengkap.
    *   Sistem akan meranking otomatis berdasarkan **Nilai Akhir** (70% Tes + 30% Rapor).

---

## 🛠️ 3. Superadmin (Sabrina & Suci)
**Fokus:** Menjelaskan manajemen sistem secara global dan fitur CMS (Content Management System).

### Alur Presentasi:
1.  **Dashboard Utama**
    *   Tunjukkan grafik pendaftar, total SMK, dan statistik global.

2.  **Kelola Data Master**
    *   **Data SMK:** Tambah/Edit sekolah, set kuota, set lokasi sekolah.
    *   **Data Jurusan:** Atur jurusan apa saja yang ada di tiap SMK.
    *   **Akun Admin:** Reset password admin sekolah jika lupa.

3.  **Kelola Beranda (Fitur Baru)**
    *   Tunjukkan menu baru **"Kelola Beranda"** di sidebar.
    *   **Edit Hero Section:** Ubah judul, subtitle, dan gambar utama tanpa koding.
    *   **Upload Dokumen:** Upload file Juknis, Manual Book, dll agar bisa didownload user.
    *   **Update Jadwal:** Tambah/Hapus agenda PPDB yang tampil di halaman depan.
    *   **Kontak & Sosmed:** Update nomor telpon & link sosmed dinas.

4.  **Monitoring Kuota**
    *   Pantau keterisian kuota seluruh sekolah.

---

## 📜 4. Peraturan & Teknis PPDB (Azlan)
**Fokus:** Menjelaskan "Otak" atau Logika Bisnis aplikasi sesuai Juknis SPMB Sumbar 2025.

### Poin Penting untuk Dijelaskan:
1.  **Aturan Kombinasi Pilihan (Logic Validasi)**
    *   Sistem **MEMBLOKIR** jika siswa memilih: 2 Sekolah Berbeda dengan 2 Jurusan Berbeda.
    *   **Yang Diizinkan:**
        *   1 Sekolah, 2 Jurusan Beda.
        *   2 Sekolah Beda, 1 Jurusan Sama.

2.  **Rumus Perangkingan (Scoring System)**
    *   **Nilai Akhir (NA)** = (70% x Nilai Tes Bakat Minat) + (30% x Rerata Rapor).
    *   Jika NA sama, prioritas ditentukan oleh: 
        1.  Usia (lebih tua diprioritaskan).
        2.  Waktu mendaftar (lebih cepat diprioritaskan).

3.  **Aturan Lokasi Tes**
    *   Jika memilih Mode 2 Sekolah: Tes Bakat Minat **WAJIB** dilakukan di **Sekolah Pilihan 1**.
    *   Nilai tes tersebut akan dipakai juga untuk perangkingan di Sekolah Pilihan 2.

4.  **Syarat Khusus**
    *   Sistem memberi warning/block untuk jurusan Teknik/Seni/Kesehatan bagi siswa yang terindikasi buta warna (berdasarkan data kesehatan).

---

### 💡 Tips Tambahan untuk Tim:
*   **Tema Tampilan:** Tekankan bahwa aplikasi menggunakan UI modern dengan warna dominan **Ungu Gradasi** (sesuai request user terakhir) agar terlihat fresh dan professional.
*   **Responsif:** Aplikasi bisa dibuka di HP maupun Laptop dengan tampilan yang menyesuaikan.
