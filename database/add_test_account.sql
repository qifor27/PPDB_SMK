-- Akun Siswa Baru untuk Testing
-- Jalankan di phpMyAdmin atau MySQL client

INSERT INTO tb_siswa (nisn, username, password, email, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, alamat, is_verified, created_at) 
VALUES (
    '0011223344', 
    'siswa_test2', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'test2@siswa.com', 
    'Siswa Testing Dua', 
    'P', 
    'Padang', 
    '2008-08-20', 
    'Islam', 
    'Jl. Testing No. 456', 
    0, 
    NOW()
);

-- Akun sudah dibuat!
-- Login dengan:
--   NISN/Username: 0011223344 atau siswa_test2
--   Password: password
