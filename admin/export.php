<?php

/**
 * Admin Sekolah - Export Data
 */
require_once 'includes/header.php';

$type = $_GET['type'] ?? 'pendaftar';

// Get all pendaftar for this school
$pendaftarList = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, s.jenis_kelamin, s.tempat_lahir, s.tanggal_lahir,
            s.alamat, s.no_hp, s.email, s.asal_sekolah,
            k1.nama_kejuruan as jurusan_pilihan1, k2.nama_kejuruan as jurusan_pilihan2
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_kejuruan k1 ON p.id_kejuruan_pilihan1 = k1.id_program
     LEFT JOIN tb_kejuruan k2 ON p.id_kejuruan_pilihan2 = k2.id_program
     WHERE p.id_smk_pilihan1 = ?
     ORDER BY p.tanggal_daftar DESC",
    [$smkId]
);

if ($type === 'pendaftar') {
    // Export as CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data_pendaftar_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    // Header row
    fputcsv($output, [
        'No',
        'No. Pendaftaran',
        'Nama Lengkap',
        'NISN',
        'L/P',
        'Tempat Lahir',
        'Tanggal Lahir',
        'Alamat',
        'No. HP',
        'Email',
        'Asal Sekolah',
        'Tahap',
        'Jurusan Pilihan 1',
        'Jurusan Pilihan 2',
        'Status',
        'Tanggal Daftar'
    ]);

    // Data rows
    $no = 1;
    foreach ($pendaftarList as $p) {
        fputcsv($output, [
            $no++,
            $p['nomor_pendaftaran'],
            $p['nama_lengkap'],
            $p['nisn'],
            $p['jenis_kelamin'],
            $p['tempat_lahir'],
            formatDate($p['tanggal_lahir'], 'd/m/Y'),
            $p['alamat'],
            $p['no_hp'],
            $p['email'],
            $p['asal_sekolah'],
            'Tahap ' . ($p['tahap_pendaftaran'] ?? 1),
            $p['jurusan_pilihan1'] ?? '-',
            $p['jurusan_pilihan2'] ?? '-',
            ucfirst($p['status']),
            formatDate($p['tanggal_daftar'], 'd/m/Y H:i')
        ]);
    }

    fclose($output);
    exit;
} elseif ($type === 'rekap') {
    // Generate rekap report HTML for print
    $stats = [
        'total' => count($pendaftarList),
        'tahap1' => 0,
        'tahap2' => 0,
        'draft' => 0,
        'submitted' => 0,
        'verified' => 0,
        'accepted' => 0,
        'rejected' => 0,
        'laki' => 0,
        'perempuan' => 0
    ];

    foreach ($pendaftarList as $p) {
        $tahap = $p['tahap_pendaftaran'] ?? 1;
        if ($tahap == 1) $stats['tahap1']++;
        else $stats['tahap2']++;

        $stats[$p['status']]++;

        if ($p['jenis_kelamin'] === 'L') $stats['laki']++;
        else $stats['perempuan']++;
    }

    // Get jurusan stats
    $jurusanStats = db()->fetchAll(
        "SELECT k.nama_kejuruan, COUNT(p.id_pendaftaran) as total
         FROM tb_kejuruan k
         LEFT JOIN tb_pendaftaran p ON p.id_kejuruan_pilihan1 = k.id_program AND p.id_smk_pilihan1 = ?
         WHERE k.id_smk = ?
         GROUP BY k.id_program
         ORDER BY total DESC",
        [$smkId, $smkId]
    );
?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Rekap Pendaftaran - <?= htmlspecialchars($admin['nama_sekolah']) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    font-size: 12pt;
                }
            }

            body {
                font-family: 'Segoe UI', sans-serif;
            }
        </style>
    </head>

    <body class="p-4">
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
            <a href="laporan.php" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="text-center mb-4">
            <h4>REKAP PENDAFTARAN PESERTA DIDIK BARU</h4>
            <h5><?= htmlspecialchars($admin['nama_sekolah']) ?></h5>
            <p>Periode: Tahun Ajaran 2025/2026</p>
            <p>Tanggal Cetak: <?= formatDate(date('Y-m-d'), 'd F Y') ?></p>
        </div>

        <h6>A. Statistik Umum</h6>
        <table class="table table-bordered mb-4">
            <tr>
                <td width="50%">Total Pendaftar</td>
                <td><strong><?= $stats['total'] ?></strong></td>
            </tr>
            <tr>
                <td>Tahap 1</td>
                <td><?= $stats['tahap1'] ?></td>
            </tr>
            <tr>
                <td>Tahap 2</td>
                <td><?= $stats['tahap2'] ?></td>
            </tr>
        </table>

        <h6>B. Berdasarkan Status</h6>
        <table class="table table-bordered mb-4">
            <tr>
                <td>Draft</td>
                <td><?= $stats['draft'] ?></td>
            </tr>
            <tr>
                <td>Submitted</td>
                <td><?= $stats['submitted'] ?></td>
            </tr>
            <tr>
                <td>Verified</td>
                <td><?= $stats['verified'] ?></td>
            </tr>
            <tr>
                <td>Diterima</td>
                <td><?= $stats['accepted'] ?></td>
            </tr>
            <tr>
                <td>Ditolak</td>
                <td><?= $stats['rejected'] ?></td>
            </tr>
        </table>

        <h6>C. Berdasarkan Jenis Kelamin</h6>
        <table class="table table-bordered mb-4">
            <tr>
                <td>Laki-laki</td>
                <td><?= $stats['laki'] ?></td>
            </tr>
            <tr>
                <td>Perempuan</td>
                <td><?= $stats['perempuan'] ?></td>
            </tr>
        </table>

        <h6>D. Berdasarkan Jurusan (Pilihan 1)</h6>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Jurusan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jurusanStats as $j): ?>
                    <tr>
                        <td><?= htmlspecialchars($j['nama_kejuruan']) ?></td>
                        <td><?= $j['total'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-5 text-end">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <br><br><br>
            <p>_______________________</p>
        </div>
    </body>

    </html>
<?php
    exit;
}

// Default: redirect back
redirect('laporan.php');
