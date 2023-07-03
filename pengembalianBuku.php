<?php
include 'koneksi.php';

// Pengembalian buku
function pengembalianBuku($id_peminjaman, $tanggal_pengembalian, $durasi_keterlambatan)
{
    global $conn; // Gunakan variabel global $conn di dalam fungsi

    try {
        // Lakukan validasi dan proses pengembalian buku
        // ...

        // Update status peminjaman di tabel peminjaman_master
        $sql = "UPDATE peminjaman_master SET status_peminjaman = 'Dikembalikan', tanggal_pengembalian = ?, durasi_keterlambatan = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$tanggal_pengembalian, $durasi_keterlambatan, $id_peminjaman]);

        // Berikan respons API
        $response = [
            'status' => 'success',
            'message' => 'Pengembalian buku berhasil',
            'tanggal_pengembalian' => $tanggal_pengembalian,
            'durasi_keterlambatan' => $durasi_keterlambatan
        ];

        return $response;
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengembalikan buku: ' . $e->getMessage()
        ];
        return $response;
    }
}

$conn = getConnection();

// Mendapatkan data pengembalian dari request (misalnya melalui URL atau parameter POST)
$id_peminjaman = isset($_POST['id_peminjaman']) ? $_POST['id_peminjaman'] : null;
$tanggal_pengembalian = isset($_POST['tanggal_pengembalian']) ? $_POST['tanggal_pengembalian'] : "2023-07-06";
$durasi_keterlambatan = isset($_POST['durasi_keterlambatan']) ? $_POST['durasi_keterlambatan'] : "2 HARI";

if ($id_peminjaman !== 7 && $id_peminjaman !== "" && $tanggal_pengembalian !== null && $tanggal_pengembalian !== "" && $durasi_keterlambatan !== null && $durasi_keterlambatan !== "") {
    // Panggil fungsi dan kirimkan respons
    $response = pengembalianBuku($id_peminjaman, $tanggal_pengembalian, $durasi_keterlambatan);
} else {
    // Jika data pengembalian tidak valid, berikan respons error
    $response = [
        'status' => 'error',
        'message' => 'Data pengembalian tidak valid'
    ];
}

echo json_encode($response);

// Tutup koneksi
$conn = null;
?>