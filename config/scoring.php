<?php

/**
 * SPMB 2025/2026 - Scoring Functions
 * Fungsi pembobotan nilai rapor, prestasi, dan kalkulasi nilai akhir
 * Berdasarkan Juknis SPMB Provinsi Sumatera Barat
 */

/**
 * Get bobot nilai rapor berdasarkan tabel Juknis
 * @param float $rerataNilai Rerata nilai rapor semester 1-5
 * @return int Bobot/skor
 */
function getBobotRapor($rerataNilai)
{
    if ($rerataNilai >= 98) return 94;
    if ($rerataNilai >= 97) return 93;
    if ($rerataNilai >= 96) return 92;
    if ($rerataNilai >= 95) return 91;
    if ($rerataNilai >= 94) return 90;
    if ($rerataNilai >= 93) return 89;
    if ($rerataNilai >= 92) return 88;
    if ($rerataNilai >= 91) return 87;
    if ($rerataNilai >= 90) return 86;
    if ($rerataNilai >= 89) return 85;
    if ($rerataNilai >= 88) return 84;
    if ($rerataNilai >= 87) return 83;
    if ($rerataNilai >= 86) return 82;
    if ($rerataNilai >= 85) return 81;
    return 80;
}

/**
 * Get bobot prestasi akademik/nonakademik berdasarkan tingkat dan juara
 * @param string $tingkat Tingkat kompetisi: 'Kab/Kota', 'Provinsi', 'Nasional', 'Internasional'
 * @param string $juara Juara: 'I', 'II', 'III' atau 'Emas', 'Perak', 'Perunggu'
 * @return int Bobot/skor
 */
function getBobotPrestasi($tingkat, $juara)
{
    // Konversi nama juara ke format standar
    $juaraMap = [
        'I' => 1,
        '1' => 1,
        'Emas' => 1,
        'Medali Emas' => 1,
        'II' => 2,
        '2' => 2,
        'Perak' => 2,
        'Medali Perak' => 2,
        'III' => 3,
        '3' => 3,
        'Perunggu' => 3,
        'Medali Perunggu' => 3,
    ];

    $juaraIndex = $juaraMap[$juara] ?? 0;
    if ($juaraIndex === 0) return 0;

    // Tabel bobot berdasarkan Juknis
    $bobot = [
        'Internasional' => [1 => 100, 2 => 99, 3 => 98],
        'Nasional' => [1 => 97, 2 => 96, 3 => 95],
        'Provinsi' => [1 => 94, 2 => 93, 3 => 92],
        'Kab/Kota' => [1 => 91, 2 => 90, 3 => 89],
        'Kota/Kabupaten' => [1 => 91, 2 => 90, 3 => 89],
    ];

    return $bobot[$tingkat][$juaraIndex] ?? 0;
}

/**
 * Get bobot Hafidz Qur'an berdasarkan jumlah juz
 * @param int $jumlahJuz Jumlah juz yang dihafal
 * @return int Bobot/skor
 */
function getBobotHafidz($jumlahJuz)
{
    if ($jumlahJuz >= 13) return 100;
    if ($jumlahJuz >= 12) return 99;
    if ($jumlahJuz >= 11) return 98;
    if ($jumlahJuz >= 10) return 97;
    if ($jumlahJuz >= 9) return 96;
    if ($jumlahJuz >= 8) return 95;
    if ($jumlahJuz >= 7) return 94;
    if ($jumlahJuz >= 6) return 93;
    if ($jumlahJuz >= 5) return 92;
    if ($jumlahJuz >= 4) return 91;
    if ($jumlahJuz >= 3) return 90;
    if ($jumlahJuz >= 2) return 89;
    return 0;
}

/**
 * Get bobot Ketua OSIS/Pramuka berdasarkan tipe sekolah
 * @param int $jumlahRombel Jumlah rombongan belajar
 * @return int Bobot/skor
 */
function getBobotKetuaOSIS($jumlahRombel)
{
    if ($jumlahRombel >= 27) return 91; // Tipe A
    if ($jumlahRombel >= 24) return 90; // Tipe A1
    if ($jumlahRombel >= 21) return 89; // Tipe A2
    if ($jumlahRombel >= 18) return 88; // Tipe B
    if ($jumlahRombel >= 15) return 87; // Tipe B1
    if ($jumlahRombel >= 12) return 86; // Tipe B2
    return 85; // Tipe C (<=11)
}

/**
 * Hitung nilai akhir SMK = 30% Rapor + 70% Tes Bakat Minat
 * @param float $bobotRapor Bobot nilai rapor (hasil dari getBobotRapor)
 * @param float $nilaiTes Nilai tes bakat minat (0-100)
 * @return float Nilai akhir
 */
function hitungNilaiAkhirSMK($bobotRapor, $nilaiTes)
{
    return (0.3 * $bobotRapor) + (0.7 * $nilaiTes);
}

/**
 * Hitung rerata nilai rapor dari semester 1-5
 * @param array $nilaiSemester Array nilai [semester1, semester2, ..., semester5]
 * @return float Rerata nilai
 */
function hitungRerataRapor($nilaiSemester)
{
    $nilaiValid = array_filter($nilaiSemester, function ($nilai) {
        return $nilai !== null && $nilai > 0;
    });

    if (empty($nilaiValid)) return 0;

    return array_sum($nilaiValid) / count($nilaiValid);
}

/**
 * Tentukan kelompok seleksi berdasarkan data siswa
 * @param array $siswa Data siswa
 * @param array $prestasi Data prestasi siswa
 * @param float $jarak Jarak ke sekolah dalam km
 * @return string Kelompok seleksi: 'afirmasi', 'domisili', 'prestasi', 'nilai_akhir'
 */
function tentukanKelompokSeleksi($siswa, $prestasi = [], $jarak = 0)
{
    // 1. Cek afirmasi (prioritas pertama)
    if (
        $siswa['is_keluarga_tidak_mampu'] ||
        $siswa['is_disabilitas'] ||
        $siswa['is_panti_asuhan']
    ) {
        return 'afirmasi';
    }

    // 2. Cek prestasi (prioritas kedua setelah afirmasi)
    if (!empty($prestasi)) {
        foreach ($prestasi as $p) {
            if ($p['status_verifikasi'] === 'valid') {
                return 'prestasi';
            }
        }
    }

    // 3. Cek domisili terdekat (dalam radius 3km)
    if ($jarak > 0 && $jarak <= 3) {
        return 'domisili';
    }

    // 4. Default: seleksi nilai akhir
    return 'nilai_akhir';
}

/**
 * Cek apakah jurusan memerlukan syarat tidak buta warna
 * @param string $namaJurusan Nama jurusan/konsentrasi keahlian
 * @return bool
 */
function perluSyaratTidakButaWarna($namaJurusan)
{
    $keywords = [
        'Teknik',
        'Kimia',
        'Farmasi',
        'Nautika',
        'Teknika',
        'Komputer',
        'Jaringan',
        'Elektronika',
        'Otomasi',
        'Mekatronika',
        'Rekayasa',
        'Informatika'
    ];

    foreach ($keywords as $keyword) {
        if (stripos($namaJurusan, $keyword) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Generate nomor pendaftaran SMK sesuai format SPMB
 * Format: 25175579001 (contoh)
 * @param int $idSiswa ID siswa
 * @param int $pilihan Pilihan ke-1 atau ke-2
 * @return string
 */
function generateNomorPendaftaranSMK($idSiswa, $pilihan = 1)
{
    $prefix = date('y') . date('mdH'); // 25 + 0623 + 09 = 25062309
    $siswaId = str_pad($idSiswa, 3, '0', STR_PAD_LEFT);
    $pilihanSuffix = $pilihan;

    return $prefix . $siswaId . $pilihanSuffix;
}
