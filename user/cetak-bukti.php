<?php
/**
 * User - Cetak Bukti Pendaftaran
 */
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';

// Require login
Session::requireRole(ROLE_SISWA, SITE_URL . '/login.php');

$userId = Session::getUserId();
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);

// Get pendaftaran data
$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur, s1.nama_sekolah as sekolah_pilihan1, s2.nama_sekolah as sekolah_pilihan2
     FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk s1 ON p.id_smk_pilihan1 = s1.id_smk
     LEFT JOIN tb_smk s2 ON p.id_smk_pilihan2 = s2.id_smk
     WHERE p.id_siswa = ?
     ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

if (!$pendaftaran) {
    header("Location: index.php");
    exit;
}

// Get prestasi if exists
$prestasiList = [];
if ($pendaftaran['kode_jalur'] === 'prestasi') {
    $prestasiList = getPrestasiByPendaftaran($pendaftaran['id_pendaftaran']);
    $totalPoin = getTotalPrestasiPoin($pendaftaran['id_pendaftaran']);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - <?= $pendaftaran['nomor_pendaftaran'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .header-print {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .print-title {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .print-subtitle {
            font-size: 14pt;
            text-align: center;
            margin-bottom: 5px;
        }

        .data-table td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .label-cell {
            width: 180px;
            font-weight: bold;
        }

        .separator {
            width: 20px;
            text-align: center;
        }

        .photo-box {
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 20px;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container-fluid p-4 bg-white">
        <!-- Action Buttons -->
        <div class="no-print mb-4 text-center">
            <button onclick="window.print()" class="btn btn-primary me-2">Cetak Bukti</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
            <div class="alert alert-info mt-3">
                <small>Pastikan kertas ukuran A4 saat mencetak.</small>
            </div>
        </div>

        <!-- Header -->
        <div class="header-print d-flex align-items-center justify-content-center mb-4">
            <div class="me-4 text-center">
                <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" class="logo"
                    onerror="this.src='https://via.placeholder.com/80?text=LOGO'">
            </div>
            <div>
                <div class="print-title">PEMERINTAH PROVINSI SUMATERA BARAT</div>
                <div class="print-title">DINAS PENDIDIKAN</div>
                <div class="print-subtitle">PENERIMAAN PESERTA DIDIK BARU (PPDB) SMK</div>
                <div class="text-center">Tahun Ajaran <?= $pendaftaran['tahun_ajaran'] ?></div>
            </div>
        </div>

        <h4 class="text-center font-weight-bold mb-4" style="text-decoration: underline;">TANDA BUKTI PENDAFTARAN</h4>

        <!-- Data Siswa -->
        <div class="row mb-4">
            <div class="col-8">
                <table class="data-table w-100">
                    <tr>
                        <td class="label-cell">Nomor Pendaftaran</td>
                        <td class="separator">:</td>
                        <td class="fw-bold"><?= $pendaftaran['nomor_pendaftaran'] ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">Nama Lengkap</td>
                        <td class="separator">:</td>
                        <td><?= strtoupper($siswa['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">NISN</td>
                        <td class="separator">:</td>
                        <td><?= $siswa['nisn'] ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">Tempat, Tanggal Lahir</td>
                        <td class="separator">:</td>
                        <td><?= $siswa['tempat_lahir'] ?>, <?= date('d-m-Y', strtotime($siswa['tanggal_lahir'])) ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">Asal Sekolah</td>
                        <td class="separator">:</td>
                        <td><?= $siswa['asal_sekolah'] ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">Jalur Pendaftaran</td>
                        <td class="separator">:</td>
                        <td><?= $pendaftaran['nama_jalur'] ?></td>
                    </tr>
                    <tr>
                        <td class="label-cell">Tanggal Daftar</td>
                        <td class="separator">:</td>
                        <td><?= date('d F Y', strtotime($pendaftaran['tanggal_daftar'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-4 text-center">
                <div class="mx-auto photo-box">
                    <small class="text-muted">FOTO 3x4</small>
                </div>
            </div>
        </div>

        <!-- Pilihan Sekolah -->
        <div class="mb-4">
            <h5 class="border-bottom pb-2 mb-3">Pilihan Sekolah</h5>
            <table class="data-table w-100">
                <tr>
                    <td class="label-cell">Pilihan 1</td>
                    <td class="separator">:</td>
                    <td><strong><?= $pendaftaran['sekolah_pilihan1'] ?></strong></td>
                </tr>
                <?php if (!empty($pendaftaran['sekolah_pilihan2'])): ?>
                    <tr>
                        <td class="label-cell">Pilihan 2</td>
                        <td class="separator">:</td>
                        <td><?= $pendaftaran['sekolah_pilihan2'] ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Data Prestasi (Khusus Jalur Prestasi) -->
        <?php if ($pendaftaran['kode_jalur'] === 'prestasi' && !empty($prestasiList)): ?>
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">Data Prestasi</h5>
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Prestasi</th>
                            <th>Tingkat</th>
                            <th>Peringkat</th>
                            <th>Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestasiList as $i => $p): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($p['nama_prestasi']) ?></td>
                                <td><?= htmlspecialchars($p['tingkat']) ?></td>
                                <td><?= htmlspecialchars($p['peringkat']) ?></td>
                                <td class="text-center"><?= $p['poin'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Poin</td>
                            <td class="text-center fw-bold"><?= $totalPoin ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Pernyataan -->
        <div class="mt-5">
            <p>Saya menyatakan bahwa data yang saya isikan dalam formulir pendaftaran ini adalah benar dan dapat
                dipertanggungjawabkan. Apabila dikemudian hari ditemukan ketidaksesuaian data, saya bersedia menerima
                sanksi sesuai ketentuan yang berlaku.</p>

            <div class="row mt-5">
                <div class="col-6">
                    <p>Mengetahui,<br>Orang Tua/Wali</p>
                    <br><br><br>
                    <p>( ..................................................... )</p>
                </div>
                <div class="col-6 text-end">
                    <p>................., <?= date('d F Y') ?><br>Calon Peserta Didik Baru</p>
                    <br><br><br>
                    <p>( <?= strtoupper($siswa['nama_lengkap']) ?> )</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>