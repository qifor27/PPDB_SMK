<?php
/**
 * PPDB SMK - Login Page
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

// Redirect if already logged in
if (Session::isLoggedIn()) {
    $redirect = match(Session::getRole()) {
        ROLE_SUPERADMIN => 'superadmin/',
        ROLE_ADMIN => 'admin/',
        default => 'user/'
    };
    redirect(SITE_URL . '/' . $redirect);
}

$error = '';
$success = Session::getFlash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'siswa');
    
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token keamanan tidak valid. Silakan coba lagi.';
    } elseif (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        $hashedPassword = hashPassword($password);
        $user = null;
        $userId = null;
        $userData = [];
        
        switch ($role) {
            case 'superadmin':
                $user = db()->fetch(
                    "SELECT * FROM tb_superadmin WHERE username = ? AND password = ?",
                    [$username, $hashedPassword]
                );
                if ($user) {
                    $userId = $user['id_superadmin'];
                    $userData = [
                        'nama' => $user['nama_lengkap'],
                        'email' => $user['email']
                    ];
                    $role = ROLE_SUPERADMIN;
                }
                break;
                
            case 'admin':
                $user = db()->fetch(
                    "SELECT a.*, s.nama_sekolah FROM tb_admin_sekolah a 
                     LEFT JOIN tb_smk s ON a.id_smk = s.id_smk 
                     WHERE a.username = ? AND a.password = ? AND a.is_active = 1",
                    [$username, $hashedPassword]
                );
                if ($user) {
                    $userId = $user['id_admin_sekolah'];
                    $userData = [
                        'nama' => $user['nama_lengkap'],
                        'email' => $user['email'],
                        'id_smk' => $user['id_smk'],
                        'nama_sekolah' => $user['nama_sekolah']
                    ];
                    $role = ROLE_ADMIN;
                }
                break;
                
            default: // siswa
                $user = db()->fetch(
                    "SELECT * FROM tb_siswa WHERE (username = ? OR nisn = ?) AND password = ?",
                    [$username, $username, $hashedPassword]
                );
                if ($user) {
                    $userId = $user['id_siswa'];
                    $userData = [
                        'nama' => $user['nama_lengkap'],
                        'nisn' => $user['nisn'],
                        'email' => $user['email']
                    ];
                    $role = ROLE_SISWA;
                }
        }
        
        if ($user) {
            Session::login($userId, $role, $userData);
            logActivity('login', "User {$username} logged in as {$role}");
            
            $redirect = match($role) {
                ROLE_SUPERADMIN => 'superadmin/',
                ROLE_ADMIN => 'admin/',
                default => 'user/'
            };
            redirect(SITE_URL . '/' . $redirect);
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <a href="index.php" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                        <img src="assets/img/sumbar.png" alt="Logo" style="height: 40px;">
                        <span class="fs-4 fw-bold text-primary">SPMB SMK</span>
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Masuk ke Akun</h4>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i><?= $success ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Check if admin or superadmin login mode - handle both GET and POST
                        $loginMode = $_POST['role'] ?? $_GET['mode'] ?? 'siswa';
                        $isAdminMode = $loginMode === 'admin';
                        $isSuperMode = $loginMode === 'superadmin';
                        ?>
                        
                        <form method="POST" action="login.php?mode=<?= $loginMode ?>" class="needs-validation" novalidate>
                            <?= Session::csrfField() ?>
                            <input type="hidden" name="role" value="<?= $loginMode ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <?php if ($isSuperMode): ?>
                                        Username Superadmin
                                    <?php elseif ($isAdminMode): ?>
                                        Username Admin
                                    <?php else: ?>
                                        NISN / Username
                                    <?php endif; ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="<?= $isSuperMode || $isAdminMode ? 'Masukkan username' : 'Masukkan NISN atau username' ?>" 
                                           required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Masukkan password" required>
                                    <button type="button" class="input-group-text password-toggle" data-target="#password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </button>
                        </form>
                        
                        <?php if (!$isAdminMode && !$isSuperMode): ?>
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Belum punya akun? 
                                <a href="register.php" class="text-primary">Daftar sekarang</a>
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="text-center">
                            <a href="login.php" class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke login siswa
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!$isAdminMode && !$isSuperMode): ?>
                <div class="text-center mt-4">
                    <p class="text-muted small mb-2">
                        Anda Admin Sekolah? 
                        <a href="login.php?mode=admin" class="text-primary">Klik disini</a>
                    </p>
                    <p class="text-muted small mb-0">
                        Superadmin? 
                        <a href="login.php?mode=superadmin" class="text-primary">Klik disini</a>
                    </p>
                </div>
                <?php endif; ?>
                
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
