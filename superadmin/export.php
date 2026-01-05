<?php

/**
 * Super Admin - Export Data
 * Export data pendaftar atau SMK ke format CSV
 */

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';

// Check login
Session::requireRole('superadmin');

$type = $_GET['type'] ?? '';

if ($type === 'pendaftar') {
    // Export semua pendaftar
    $data = db()->fetchAll(
        "SELECT p.nomor_pendaftaran, s.nama_lengkap, s.nisn, s.jenis_kelamin, s.tanggal_lahir,
                s.asal_sekolah, p.nilai_rata_rata, p.nilai_tes, p.nilai_akumulasi,
                smk.nama_sekolah as smk_pilihan1, k.nama_kejuruan as kejuruan_pilihan1,
                p.status, p.tanggal_daftar, j.nama_jalur
         FROM tb_pendaftaran p
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         LEFT JOIN tb_smk smk ON p.id_smk_pilihan1 = smk.id_smk
         LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
         LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
         ORDER BY p.tanggal_daftar DESC"
    );

    $filename = 'pendaftar_' . date('Y-m-d_His') . '.csv';
    $headers = [
        'No Pendaftaran',
        'Nama Lengkap',
        'NISN',
        'JK',
        'Tanggal Lahir',
        'Asal Sekolah',
        'Nilai Rapor',
        'Nilai Tes',
        'Nilai Akumulasi',
        'SMK Pilihan 1',
        'Kejuruan Pilihan 1',
        'Status',
        'Tanggal Daftar',
        'Jalur'
    ];
} elseif ($type === 'smk') {
    // Export data SMK
    $data = db()->fetchAll(
        "SELECT s.nama_sekolah, s.npsn, s.alamat, s.kecamatan, s.telepon, s.email,
                s.jumlah_siswa, s.jumlah_guru,
                (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_smk_pilihan1 = s.id_smk) as total_pendaftar,
                (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_smk_pilihan1 = s.id_smk AND p.status = 'accepted') as total_diterima
         FROM tb_smk s
         ORDER BY s.nama_sekolah"
    );

    $filename = 'data_smk_' . date('Y-m-d_His') . '.csv';
    $headers = [
        'Nama Sekolah',
        'NPSN',
        'Alamat',
        'Kecamatan',
        'Telepon',
        'Email',
        'Jumlah Siswa',
        'Jumlah Guru',
        'Total Pendaftar',
        'Total Diterima'
    ];
} else {
    Session::flash('error', 'Tipe export tidak valid.');
    redirect('laporan.php');
}

// Output CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Add BOM for Excel UTF-8 compatibility
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Write header
fputcsv($output, $headers);

// Write data
foreach ($data as $row) {
    fputcsv($output, array_values($row));
}

fclose($output);
exit;
