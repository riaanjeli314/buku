<?php
include 'koneksi.php';

header('Content-Type: application/json');

// Handle CORS preflight quickly
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$pdo = getConnection();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $tanggalPeminjaman = $data['tanggal_peminjaman'] ?? null;
        $tanggalPengembalian = $data['tanggal_pengembalian'] ?? null;
        $nomorAnggota = $data['nomor_anggota'] ?? null;
        $kodeBuku = $data['kode_buku'] ?? null;

        if (!$tanggalPeminjaman || !$tanggalPengembalian || !$nomorAnggota || !$kodeBuku) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Field wajib tidak lengkap']);
            exit;
        }

        // Simpan data pinjaman (master + detail) dalam transaksi
        $pdo->beginTransaction();

        $stmtMaster = $pdo->prepare(
            "INSERT INTO peminjaman_master (tanggal_peminjaman, tanggal_pengembalian, nomor_anggota, status_peminjaman)
             VALUES (:tgl_pinjam, :tgl_kembali, :nomor_anggota, :status)"
        );
        // Gunakan status yang konsisten dengan endpoint status (DIPINJAM)
        $statusPinjam = 'DIPINJAM';
        $stmtMaster->execute([
            ':tgl_pinjam' => $tanggalPeminjaman,
            ':tgl_kembali' => $tanggalPengembalian,
            ':nomor_anggota' => $nomorAnggota,
            ':status' => $statusPinjam,
        ]);

        $idPeminjamanMaster = $pdo->lastInsertId();

        $stmtDetail = $pdo->prepare(
            "INSERT INTO peminjaman_detail (id_peminjaman_master, kode_buku) VALUES (:id_master, :kode_buku)"
        );
        $stmtDetail->execute([
            ':id_master' => $idPeminjamanMaster,
            ':kode_buku' => $kodeBuku,
        ]);

        $pdo->commit();

        echo json_encode(['status' => 'success', 'id_peminjaman' => (int)$idPeminjamanMaster]);
        exit;
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $idPeminjaman = $data['id_peminjaman'] ?? null;
        $statusPeminjaman = $data['status_peminjaman'] ?? null;

        if (!$idPeminjaman || !$statusPeminjaman) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'id_peminjaman dan status_peminjaman wajib diisi']);
            exit;
        }

        $stmt = $pdo->prepare(
            "UPDATE peminjaman_master SET status_peminjaman = :status WHERE id = :id"
        );
        $stmt->execute([':status' => $statusPeminjaman, ':id' => $idPeminjaman]);

        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($method === 'GET') {
        $idPeminjaman = isset($_GET['id_peminjaman']) ? $_GET['id_peminjaman'] : null;
        if (!$idPeminjaman) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'id_peminjaman tidak diberikan']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM peminjaman_master WHERE id = :id");
        $stmt->execute([':id' => $idPeminjaman]);
        $peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$peminjaman) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Data peminjaman tidak ditemukan']);
            exit;
        }

        echo json_encode($peminjaman);
        exit;
    }

    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Kesalahan database: ' . $e->getMessage()]);
} finally {
    $pdo = null;
}
?>