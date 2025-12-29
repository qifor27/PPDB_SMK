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
            
            <button type="button" class="btn btn-primary mt-auto btn-pilih-jalur" 
                    data-jalur-id="<?= $jalur['id_jalur'] ?>"
                    data-jalur-nama="<?= htmlspecialchars($jalur['nama_jalur']) ?>"
                    data-bs-toggle="modal" data-bs-target="#modalKonfirmasi">
                <i class="bi bi-arrow-right-circle me-2"></i>Pilih Jalur Ini
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="bi bi-question-circle text-primary me-2"></i>Konfirmasi Pilihan Jalur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-2">Anda akan memilih jalur pendaftaran:</p>
                <h4 class="text-primary mb-3" id="namaJalurTerpilih">-</h4>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Perhatian!</strong> Setelah memilih jalur, Anda <strong>tidak dapat mengubahnya</strong>. 
                    Pastikan pilihan Anda sudah benar.
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Batal
                </button>
                <a href="#" id="btnKonfirmasiPilih" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Ya, Pilih Jalur Ini
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-pilih-jalur').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const jalurId = this.getAttribute('data-jalur-id');
        const jalurNama = this.getAttribute('data-jalur-nama');
        
        document.getElementById('namaJalurTerpilih').textContent = jalurNama;
        document.getElementById('btnKonfirmasiPilih').href = 'pendaftaran.php?jalur=' + jalurId;
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
