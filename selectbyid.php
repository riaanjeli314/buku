<?php
include 'koneksi.php';

// Fungsi untuk melakukan SELECT peminjaman berdasarkan ID
function getPeminjamanById($id_peminjaman)
{
    global $conn; // Gunakan variabel global $conn di dalam fungsi

    try {
        // Query untuk mendapatkan data peminjaman_master berdasarkan ID
        $sql = "SELECT * FROM peminjaman_master WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_peminjaman]);
        $peminjaman_master = $stmt->fetch(PDO::FETCH_ASSOC);

        // Query untuk mendapatkan data peminjaman_detail berdasarkan ID
        $sql = "SELECT * FROM peminjaman_detail WHERE id_peminjaman_master = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_peminjaman]);
        $peminjaman_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Gabungkan data peminjaman_master dan peminjaman_detail menjadi satu array
        $data = [
            'peminjaman_master' => $peminjaman_master,
            'peminjaman_detail' => $peminjaman_detail
        ];

        // Berikan respons API
        $response = [
            'status' => 'success',
            'message' => 'Data peminjaman ditemukan',
            'data' => $data
        ];

        return $response;
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengambil data peminjaman: ' . $e->getMessage()
        ];
        return $response;
    }
}

$conn = getConnection();

// Mendapatkan ID peminjaman dari request (misalnya melalui URL atau parameter GET)
$id_peminjaman = isset($_GET['id_peminjaman']) ? $_GET['id_peminjaman'] : 1;

if ($id_peminjaman !== null && $id_peminjaman !== "") {
    // Panggil fungsi dan kirimkan respons
    $response = getPeminjamanById($id_peminjaman);
} else {
    // Jika ID peminjaman tidak valid, berikan respons error
    $response = [
        'status' => 'error',
        'message' => 'ID peminjaman tidak valid'
    ];
}

echo json_encode($response);

// Tutup koneksi
$conn = null;
?>