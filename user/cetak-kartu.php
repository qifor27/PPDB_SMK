<?php

/**
 * PPDB SMK - Cetak Kartu Pendaftaran
 * Format sesuai SPMB 2025/2026
 */

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';

// Require login
Session::requireRole(ROLE_SISWA, SITE_URL . '/login.php');

$userId = Session::getUserId();
$pilihan = (int)($_GET['pilihan'] ?? 1);

// Get siswa data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);

// Get pendaftaran
$pendaftaran = db()->fetch("
    SELECT p.*, j.nama_jalur, j.kode_jalur
    FROM tb_pendaftaran p
    LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
    WHERE p.id_siswa = ?
    ORDER BY p.id_pendaftaran DESC LIMIT 1
", [$userId]);

if (!$pendaftaran) {
    die('Pendaftaran tidak ditemukan');
}

// Get sekolah dan jurusan berdasarkan pilihan
if ($pilihan === 1) {
    $sekolah = db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$pendaftaran['id_smk_pilihan1']]);
    $kejuruan = null;
    if (!empty($pendaftaran['id_kejuruan_pilihan1'])) {
        $kejuruan = db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$pendaftaran['id_kejuruan_pilihan1']]);
    }
    $nomorPendaftaran = $pendaftaran['nomor_pendaftaran'];
} else {
    // Pilihan 2
    $idSmk = $pendaftaran['id_smk_pilihan2'] ?: $pendaftaran['id_smk_pilihan1'];
    $idKejuruan = $pendaftaran['id_kejuruan_pilihan2'] ?: $pendaftaran['id_kejuruan_pilihan1'];

    $sekolah = db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$idSmk]);
    $kejuruan = null;
    if (!empty($idKejuruan)) {
        $kejuruan = db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$idKejuruan]);
    }
    $nomorPendaftaran = $pendaftaran['nomor_pendaftaran'] . '-P2';
}

// Fallback kejuruan name
$namaKejuruan = $kejuruan['nama_kejuruan'] ?? 'Belum dipilih';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pendaftaran - <?= $nomorPendaftaran ?></title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            background: #f5f5f5;
        }

        .kartu {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #1a5276;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            background: #1a5276;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .header-title {
            text-align: center;
            flex: 1;
            padding: 0 15px;
        }

        .header-title h1 {
            font-size: 14pt;
            color: #1a5276;
            margin-bottom: 2px;
        }

        .header-title p {
            font-size: 10pt;
            color: #666;
        }

        .content {
            display: flex;
            gap: 20px;
        }

        .content-left {
            flex: 1;
        }

        .content-right {
            width: 150px;
            text-align: center;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .data-table td:first-child {
            width: 140px;
            color: #666;
        }

        .data-table td:nth-child(2) {
            width: 10px;
        }

        .data-table td:last-child {
            font-weight: 500;
        }

        .foto-container {
            width: 120px;
            height: 160px;
            border: 2px solid #1a5276;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 9pt;
            background: #f9f9f9;
        }

        .foto-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .qr-container {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
        }

        .section-title {
            background: #1a5276;
            color: white;
            padding: 8px 12px;
            font-weight: 600;
            margin: 15px 0 10px;
            font-size: 10pt;
        }

        .pilihan-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .pilihan-box h4 {
            color: #1a5276;
            font-size: 11pt;
            margin-bottom: 5px;
        }

        .info-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
        }

        .info-box h4 {
            color: #856404;
            margin-bottom: 5px;
        }

        .pernyataan {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
        }

        .pernyataan h4 {
            color: #1a5276;
            margin-bottom: 8px;
        }

        .ttd-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .ttd-box {
            width: 45%;
            text-align: center;
        }

        .ttd-line {
            border-bottom: 1px solid #333;
            margin: 50px 20px 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #1a5276;
            font-size: 9pt;
            color: #666;
        }

        @media print {
            body {
                background: white;
            }

            .kartu {
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="padding: 10px; text-align: center; background: #1a5276; color: white;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14pt; cursor: pointer;">
            🖨️ Cetak Kartu
        </button>
    </div>

    <div class="kartu">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">SB</div>
            <div class="header-title">
                <h1>KARTU TANDA PESERTA</h1>
                <h1>SPMB ONLINE</h1>
                <p>PROVINSI SUMATERA BARAT</p>
                <p>TAHUN AJARAN <?= getPengaturan('tahun_ajaran') ?></p>
            </div>
            <div class="header-logo">🎓</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="content-left">
                <table class="data-table">
                    <tr>
                        <td>NOMOR PENDAFTARAN</td>
                        <td>:</td>
                        <td><strong style="font-size: 12pt;"><?= $nomorPendaftaran ?></strong></td>
                    </tr>
                    <tr>
                        <td>NAMA PESERTA</td>
                        <td>:</td>
                        <td><?= htmlspecialchars(strtoupper($siswa['nama_lengkap'])) ?></td>
                    </tr>
                    <tr>
                        <td>TEMPAT/TGL LAHIR</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= date('d F Y', strtotime($siswa['tanggal_lahir'])) ?></td>
                    </tr>
                    <tr>
                        <td>JENIS KELAMIN</td>
                        <td>:</td>
                        <td><?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nik'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>NISN</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="content-right">
                <div class="foto-container">
                    <?php if ($siswa['foto']): ?>
                        <img src="<?= SITE_URL ?>/uploads/foto/<?= $siswa['foto'] ?>" alt="Foto">
                    <?php else: ?>
                        Pas Foto<br>3x4
                    <?php endif; ?>
                </div>
                <div class="qr-container">
                    QR Code
                </div>
            </div>
        </div>

        <!-- Pilihan Sekolah -->
        <div class="section-title">PILIHAN SEKOLAH</div>
        <div class="pilihan-box">
            <table class="data-table">
                <tr>
                    <td>JALUR SELEKSI NILAI RAPOR / PILIHAN <?= $pilihan ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>SMK PENDAFTARAN</td>
                    <td>:</td>
                    <td><strong><?= htmlspecialchars($sekolah['nama_sekolah'] ?? '-') ?></strong></td>
                </tr>
                <tr>
                    <td>KONSENTRASI KEAHLIAN</td>
                    <td>:</td>
                    <td><strong><?= htmlspecialchars($namaKejuruan) ?></strong></td>
                </tr>
                <tr>
                    <td>TANGGAL DAFTAR</td>
                    <td>:</td>
                    <td><?= date('d-m-Y H:i', strtotime($pendaftaran['tanggal_daftar'])) ?></td>
                </tr>
            </table>
        </div>

        <!-- Informasi Penting -->
        <div class="info-box">
            <h4>INFORMASI PENTING</h4>
            <p>Satu Peserta Ini adalah Bukti Resmi yang dinyatakan telah pelaksanaan pendaftaran di satuan pendidikan.
                Berkenaan dengan keabsahan-keabsahan di atas dan pernyataan-pernyataan yang ada.</p>
        </div>

        <!-- Pernyataan -->
        <div class="pernyataan">
            <h4>PERNYATAAN KESEPAKATAN YANG TELAH DISETUJUI</h4>
            <p>Dengan ini menyatakan telah diminta dan melaksanakan pengisian data dan pengajuan pendaftaran sesuai isian diatas
                dinyatakan dengan Sebenar-benarnya dan dapat dibuktikan jika diperlukan serta Pendaftaran yang sudah diberi
                Untuk dapat mengganti Lain-Lainnya sebelum diterima dalam masa diterima.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd-container">
            <div class="ttd-box">
                <p>Orang Tua / Wali,</p>
                <div class="ttd-line"></div>
                <p><strong>................................</strong></p>
            </div>
            <div class="ttd-box">
                <p>Peserta,</p>
                <div class="ttd-line"></div>
                <p><strong><?= htmlspecialchars($siswa['nama_lengkap']) ?></strong></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>SIMPANLAH LEMBAR PENDAFTARAN INI SEBAGAI BUKTI PENDAFTARAN ANDA</strong>
            <p>Dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
        </div>
    </div>
</body>

</html>