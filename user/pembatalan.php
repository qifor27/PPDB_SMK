<?php

/**
 * PPDB SMK - Pembatalan Pendaftaran
 * Halaman informasi dan riwayat pembatalan
 * Sesuai Juknis SPMB 2025/2026
 */

$pageTitle = 'Pembatalan Pendaftaran';
require_once 'includes/header.php';

// Cek pendaftaran
if (!$pendaftaran) {
    Session::flash('error', 'Anda belum memiliki pendaftaran.');
    redirect(SITE_URL . '/user/');
}

// Cek apakah bisa dibatalkan
$bisaBatal = $pendaftaran['status'] === 'draft' || $pendaftaran['status'] === 'submitted';

// Get riwayat pembatalan
$riwayatPembatalan = db()->fetchAll("
    SELECT pb.*, p.nomor_pendaftaran
    FROM tb_pembatalan pb
    JOIN tb_pendaftaran p ON pb.id_pendaftaran = p.id_pendaftaran
    WHERE p.id_siswa = ?
    ORDER BY pb.tanggal_pembatalan DESC
", [$userId]);

// Handle form submission (request cancellation info)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Token keamanan tidak valid.');
    } else {
        // Log permintaan info pembatalan
        Session::flash('info', 'Silakan datang langsung ke sekolah yang dipilih untuk proses pembatalan.');
    }
}
?>

<div class="row g-4">
    <!-- Info Alert -->
    <div class="col-12">
        <div class="alert alert-warning">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Informasi Pembatalan</h5>
            <hr>
            <p class="mb-2">Pembatalan pendaftaran dapat dilakukan dengan ketentuan:</p>
            <ul class="mb-0">
                <li>Pembatalan hanya bisa dilakukan selama pilihan sekolah <strong>belum diverifikasi</strong> oleh operator</li>
                <li>Pembatalan dilakukan dengan <strong>datang langsung</strong> ke sekolah yang dipilih dan/atau melapor ke operator SMK</li>
                <li>Setelah pembatalan, Anda dapat <strong>mendaftar kembali</strong> selama jadwal pendaftaran masih dibuka</li>
            </ul>
        </div>
    </div>

    <!-- Status Pendaftaran -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Status Pendaftaran Saat Ini</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%">Nomor Pendaftaran</td>
                        <td><strong><?= $pendaftaran['nomor_pendaftaran'] ?></strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            <?php
                            $statusBadges = [
                                'draft' => '<span class="badge bg-secondary">Draft</span>',
                                'submitted' => '<span class="badge bg-info">Diajukan</span>',
                                'verified' => '<span class="badge bg-success">Terverifikasi</span>',
                                'accepted' => '<span class="badge bg-success">Diterima</span>',
                                'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                            ];
                            echo $statusBadges[$pendaftaran['status']] ?? '<span class="badge bg-secondary">-</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Dapat Dibatalkan</td>
                        <td>
                            <?php if ($bisaBatal): ?>
                                <span class="badge bg-success"><i class="bi bi-check me-1"></i>Ya</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Tidak</span>
                                <small class="text-muted d-block mt-1">Pendaftaran sudah diverifikasi</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Dibatalkan</td>
                        <td>
                            <?php if ($pendaftaran['is_dibatalkan']): ?>
                                <span class="badge bg-danger">Ya</span>
                                <small class="text-muted d-block">
                                    <?= date('d M Y H:i', strtotime($pendaftaran['tanggal_batal'])) ?>
                                </small>
                            <?php else: ?>
                                <span class="badge bg-success">Tidak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <?php if ($bisaBatal && !$pendaftaran['is_dibatalkan']): ?>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        Untuk membatalkan pendaftaran, silakan datang langsung ke:
                        <strong><?= htmlspecialchars($pendaftaran['sekolah_pilihan1'] ?? 'Sekolah yang Anda pilih') ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Prosedur Pembatalan -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Prosedur Pembatalan</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>1. Persiapan Dokumen</h6>
                            <p class="text-muted small mb-0">
                                Siapkan kartu pendaftaran dan kartu identitas (KTP/Kartu Pelajar)
                            </p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6>2. Kunjungi Sekolah</h6>
                            <p class="text-muted small mb-0">
                                Datang ke sekolah yang Anda pilih pada jam operasional
                            </p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6>3. Temui Operator PPDB</h6>
                            <p class="text-muted small mb-0">
                                Sampaikan maksud pembatalan kepada operator PPDB sekolah
                            </p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>4. Proses Pembatalan</h6>
                            <p class="text-muted small mb-0">
                                Operator akan memproses pembatalan dan memberikan bukti
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembatalan -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Pembatalan</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($riwayatPembatalan)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Pendaftaran</th>
                                    <th>Alasan</th>
                                    <th>Dibatalkan Oleh</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatPembatalan as $pb): ?>
                                    <tr>
                                        <td><?= date('d M Y H:i', strtotime($pb['tanggal_pembatalan'])) ?></td>
                                        <td><?= $pb['nomor_pendaftaran'] ?></td>
                                        <td><?= htmlspecialchars($pb['alasan_pembatalan'] ?? '-') ?></td>
                                        <td>
                                            <?= $pb['dibatalkan_oleh'] === 'admin' ?
                                                '<span class="badge bg-info">Operator</span>' :
                                                '<span class="badge bg-secondary">Siswa</span>' ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                'diajukan' => '<span class="badge bg-warning">Diajukan</span>',
                                                'disetujui' => '<span class="badge bg-success">Disetujui</span>',
                                                'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
                                            ];
                                            echo $statusBadges[$pb['status']] ?? '-';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="mb-0">Tidak ada riwayat pembatalan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -26px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px currentColor;
    }

    .timeline-content {
        padding-left: 10px;
    }
</style>

<?php require_once 'includes/footer.php'; ?>