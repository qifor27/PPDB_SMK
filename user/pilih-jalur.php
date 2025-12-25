<?php
/**
 * User - Pilih Jalur Pendaftaran
 */
$pageTitle = 'Pilih Jalur Pendaftaran';
require_once 'includes/header.php';

// Redirect if already has pendaftaran
if ($pendaftaran) {
    redirect('pendaftaran.php');
}

$jalurList = getAllJalur();
?>

<div class="row g-4">
    <?php foreach ($jalurList as $jalur): ?>
    <div class="col-md-6">
        <div class="card jalur-card <?= $jalur['kode_jalur'] ?> h-100">
            <div class="jalur-icon">
                <i class="bi <?= $jalur['icon'] ?? 'bi-bookmark-star' ?>"></i>
            </div>
            <h4 class="jalur-title"><?= htmlspecialchars($jalur['nama_jalur']) ?></h4>
            <p class="jalur-desc"><?= htmlspecialchars($jalur['deskripsi'] ?? '') ?></p>
            
            <div class="jalur-quota mb-3">
                <i class="bi bi-pie-chart-fill me-1"></i>
                Kuota <?= number_format($jalur['kuota_persen'], 0) ?>%
            </div>
            
            <?php if ($jalur['persyaratan']): ?>
            <div class="text-start mb-3">
                <strong class="small">Persyaratan:</strong>
                <ul class="small text-muted mt-1 mb-0">
                    <?php foreach (explode("\n", $jalur['persyaratan']) as $req): ?>
                    <li><?= htmlspecialchars(trim($req)) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <a href="pendaftaran.php?jalur=<?= $jalur['id_jalur'] ?>" class="btn btn-primary mt-auto">
                <i class="bi bi-arrow-right-circle me-2"></i>Pilih Jalur Ini
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
