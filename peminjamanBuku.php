<?php
include 'koneksi.php';

$connection = getConnection();

// Endpoint untuk menambahkan data peminjaman buku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $tanggalPeminjaman = $data['tanggal_peminjaman'];
    $tanggalPengembalian = $data['tanggal_pengembalian'];
    $nomorAnggota = $data['nomor_anggota'];
    $kodeBuku = $data['kode_buku'];

    // Query untuk menyimpan data ke peminjaman_master
    $queryMaster = "INSERT INTO peminjaman_master (tanggal_peminjaman, tanggal_pengembalian, nomor_anggota, status_peminjaman) 
                    VALUES ('$tanggalPeminjaman','$tanggalPengembalian', '$nomorAnggota', 'pinjam')";
    if ($connection->query($queryMaster) === TRUE) {
        $idPeminjamanMaster = $connection->insert_id;

        // Query untuk menyimpan data ke peminjaman_detail
        $queryDetail = "INSERT INTO peminjaman_detail (id_peminjaman_master, kode_buku) 
                        VALUES ('$idPeminjamanMaster', '$kodeBuku')";
        if ($connection->query($queryDetail) === TRUE) {
            // Mengembalikan response berhasil
            $response = array('status' => 'success');
            echo json_encode($response);
        } else {
            // Mengembalikan response gagal
            $response = array('status' => 'error', 'message' => 'Gagal menyimpan data peminjaman detail');
            echo json_encode($response);
        }
    } else {
        // Mengembalikan response gagal
        $response = array('status' => 'error', 'message' => 'Gagal menyimpan data peminjaman master');
        echo json_encode($response);
    }
}

// Endpoint untuk mengubah status peminjaman buku
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $idPeminjaman = $data['id_peminjaman'];
    $statusPeminjaman = $data['status_peminjaman'];

    // Query untuk mengupdate status peminjaman
    $query = "UPDATE peminjaman_master 
              SET status_peminjaman = '$statusPeminjaman' 
              WHERE id = $idPeminjaman";
    if ($connection->query($query) === TRUE) {
        // Mengembalikan response berhasil
        $response = array('status' => 'success');
        echo json_encode($response);
    } else {
        // Mengembalikan response gagal
        $response = array('status' => 'error', 'message' => 'Gagal mengupdate status peminjaman');
        echo json_encode($response);
    }
}

// Endpoint untuk mengambil data peminjaman buku
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_peminjaman'])) {
        $idPeminjaman = $_GET['id_peminjaman'];

        // Query untuk mengambil data peminjaman buku
        $query = "SELECT * FROM peminjaman_master WHERE id = $idPeminjaman";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
            $peminjaman = $result->fetch_assoc();

            // Mengembalikan data dalam format JSON
            echo json_encode($peminjaman);
        } else {
            // Mengembalikan response gagal
            $response = array('status' => 'error', 'message' => 'Data peminjaman tidak ditemukan');
            echo json_encode($response);
        }
    } else {
        // Mengembalikan response gagal
        $response = array('status' => 'error', 'message' => 'ID peminjaman tidak diberikan');
        echo json_encode($response);
    }
}

// Menutup koneksi database
$connection = null;
?>