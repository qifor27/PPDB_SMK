<?php
/**
 * API - Prestasi CRUD Operations
 * Endpoint untuk mengelola data prestasi siswa
 * 
 * === JALUR PRESTASI - MUTIA ===
 */

require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/../config/session.php';

// Start session
Session::start();

// Check authentication
if (!Session::isLoggedIn() || Session::getRole() !== 'siswa') {
    jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
}

$userId = Session::getUserId();

// Get user's pendaftaran
$pendaftaran = db()->fetch(
    "SELECT p.*, j.kode_jalur FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_siswa = ? ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

if (!$pendaftaran || $pendaftaran['kode_jalur'] !== 'prestasi') {
    jsonResponse(['success' => false, 'error' => 'Tidak memiliki pendaftaran jalur prestasi'], 400);
}

$idPendaftaran = $pendaftaran['id_pendaftaran'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all prestasi for this pendaftaran
        $prestasiList = getPrestasiByPendaftaran($idPendaftaran);
        $totalPoin = 0;
        foreach ($prestasiList as $p) {
            $totalPoin += $p['poin'];
        }

        jsonResponse([
            'success' => true,
            'data' => $prestasiList,
            'total_poin' => $totalPoin,
            'count' => count($prestasiList)
        ]);
        break;

    case 'POST':
        // Add new prestasi
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input)) {
            $input = $_POST;
        }

        // Validate required fields
        $required = ['nama_prestasi', 'jenis_prestasi', 'tingkat', 'peringkat', 'tahun'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                jsonResponse(['success' => false, 'error' => "Field {$field} wajib diisi"], 400);
            }
        }

        // Validate enums
        $jenisValid = ['Akademik', 'Non-Akademik', 'Olahraga', 'Seni', 'Lainnya'];
        $tingkatValid = ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];
        $peringkatValid = ['Juara 1', 'Juara 2', 'Juara 3', 'Peserta'];

        if (!in_array($input['jenis_prestasi'], $jenisValid)) {
            jsonResponse(['success' => false, 'error' => 'Jenis prestasi tidak valid'], 400);
        }
        if (!in_array($input['tingkat'], $tingkatValid)) {
            jsonResponse(['success' => false, 'error' => 'Tingkat tidak valid'], 400);
        }
        if (!in_array($input['peringkat'], $peringkatValid)) {
            jsonResponse(['success' => false, 'error' => 'Peringkat tidak valid'], 400);
        }

        // Validate year
        $tahun = (int) $input['tahun'];
        $currentYear = (int) date('Y');
        if ($tahun < ($currentYear - 3) || $tahun > $currentYear) {
            jsonResponse(['success' => false, 'error' => 'Tahun harus dalam 3 tahun terakhir'], 400);
        }

        // Save prestasi
        $data = [
            'id_pendaftaran' => $idPendaftaran,
            'nama_prestasi' => $input['nama_prestasi'],
            'jenis_prestasi' => $input['jenis_prestasi'],
            'tingkat' => $input['tingkat'],
            'peringkat' => $input['peringkat'],
            'tahun' => $tahun,
            'penyelenggara' => $input['penyelenggara'] ?? ''
        ];

        $insertId = savePrestasiSiswa($data);

        if ($insertId) {
            // Get the inserted prestasi
            $prestasi = db()->fetch(
                "SELECT * FROM tb_prestasi_siswa WHERE id_prestasi_siswa = ?",
                [$insertId]
            );

            jsonResponse([
                'success' => true,
                'message' => 'Prestasi berhasil ditambahkan',
                'data' => $prestasi,
                'poin' => $prestasi['poin']
            ]);
        } else {
            jsonResponse(['success' => false, 'error' => 'Gagal menyimpan prestasi'], 500);
        }
        break;

    case 'DELETE':
        // Delete prestasi
        $idPrestasi = $_GET['id'] ?? null;

        if (!$idPrestasi) {
            jsonResponse(['success' => false, 'error' => 'ID prestasi tidak ditemukan'], 400);
        }

        // Check if pendaftaran is still in draft/submitted status
        if (!in_array($pendaftaran['status'], ['draft', 'submitted'])) {
            jsonResponse(['success' => false, 'error' => 'Tidak dapat menghapus prestasi, pendaftaran sudah diverifikasi'], 400);
        }

        $result = deletePrestasiSiswa($idPrestasi, $idPendaftaran);

        if ($result) {
            jsonResponse(['success' => true, 'message' => 'Prestasi berhasil dihapus']);
        } else {
            jsonResponse(['success' => false, 'error' => 'Gagal menghapus prestasi'], 500);
        }
        break;

    default:
        jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}
