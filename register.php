<?php
/**
 * PPDB SMK - Register Page (Siswa)
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

// Redirect if already logged in
if (Session::isLoggedIn()) {
    redirect(SITE_URL . '/user/');
}

// Check if registration is open
if (!isPPDBOpen()) {
    Session::flash('error', 'Maaf, pendaftaran belum dibuka.');
    redirect(SITE_URL . '/login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token keamanan tidak valid.';
    } else {
        $nisn = sanitize($_POST['nisn'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $namaLengkap = sanitize($_POST['nama_lengkap'] ?? '');
        $password = $_POST['password'] ?? '';
        $konfirmPassword = $_POST['konfirm_password'] ?? '';
        $jenisKelamin = sanitize($_POST['jenis_kelamin'] ?? '');
        $tempatLahir = sanitize($_POST['tempat_lahir'] ?? '');
        $tanggalLahir = sanitize($_POST['tanggal_lahir'] ?? '');
        $noHp = sanitize($_POST['no_hp'] ?? '');
        
        // Validation
        if (!validateNISN($nisn)) {
            $error = 'Format NISN tidak valid (harus 10 digit angka).';
        } elseif (!validateEmail($email)) {
            $error = 'Format email tidak valid.';
        } elseif (strlen($password) < 6) {
            $error = 'Password minimal 6 karakter.';
        } elseif ($password !== $konfirmPassword) {
            $error = 'Konfirmasi password tidak cocok.';
        } elseif (empty($namaLengkap) || empty($jenisKelamin) || empty($tempatLahir) || empty($tanggalLahir)) {
            $error = 'Semua field wajib harus diisi.';
        } else {
            // Check existing NISN
            if (db()->exists('tb_siswa', 'nisn = ?', [$nisn])) {
                $error = 'NISN sudah terdaftar.';
            } elseif (db()->exists('tb_siswa', 'email = ?', [$email])) {
                $error = 'Email sudah terdaftar.';
            } else {
                try {
                    $username = $nisn; // Use NISN as username
                    
                    db()->insert('tb_siswa', [
                        'nisn' => $nisn,
                        'username' => $username,
                        'password' => hashPassword($password),
                        'email' => $email,
                        'nama_lengkap' => $namaLengkap,
                        'jenis_kelamin' => $jenisKelamin,
                        'tempat_lahir' => $tempatLahir,
                        'tanggal_lahir' => $tanggalLahir,
                        'no_hp' => $noHp,
                        'agama' => 'Islam',
                        'alamat' => '-'
                    ]);
                    
                    logActivity('register', "New student registered: {$nisn}");
                    Session::flash('success', 'Registrasi berhasil! Silakan login.');
                    redirect(SITE_URL . '/login.php');
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center mb-4">
                    <a href="index.php" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                        <i class="bi bi-mortarboard-fill text-primary fs-1"></i>
                        <span class="fs-4 fw-bold text-white">PPDB SMK</span>
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Buat Akun Baru</h4>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="needs-validation" novalidate>
                            <?= Session::csrfField() ?>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nisn" name="nisn" 
                                           pattern="\d{10}" maxlength="10" required
                                           placeholder="10 digit NISN"
                                           value="<?= htmlspecialchars($_POST['nisn'] ?? '') ?>">
                                    <div class="form-text">NISN akan digunakan sebagai username</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           placeholder="email@contoh.com"
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                                
                                <div class="col-12">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required
                                           placeholder="Sesuai ijazah/akta"
                                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                                   id="jenisL" value="L" required 
                                                   <?= ($_POST['jenis_kelamin'] ?? '') === 'L' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="jenisL">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                                   id="jenisP" value="P" 
                                                   <?= ($_POST['jenis_kelamin'] ?? '') === 'P' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="jenisP">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">No. HP / WhatsApp</label>
                                    <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                           placeholder="08xxxxxxxxxx"
                                           value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required
                                           placeholder="Kota/Kabupaten"
                                           value="<?= htmlspecialchars($_POST['tempat_lahir'] ?? '') ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                           value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               minlength="6" required placeholder="Minimal 6 karakter">
                                        <button type="button" class="input-group-text password-toggle" data-target="#password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="konfirm_password" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="konfirm_password" name="konfirm_password" 
                                           required placeholder="Ulangi password">
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agree" required>
                                        <label class="form-check-label text-muted" for="agree">
                                            Saya menyetujui <a href="#" class="text-primary">syarat dan ketentuan</a> yang berlaku
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-person-plus me-2"></i>Daftar
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Sudah punya akun? 
                                <a href="login.php" class="text-primary">Masuk di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
