<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode GET
$kode = isset($_GET['kode']) ? $_GET['kode'] : '';

try {
    // Query SQL untuk memilih data buku berdasarkan kode
    $query = "SELECT * FROM kategori WHERE kode = :kode";
    
    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);
    
    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':kode', $kode);
    
    // Eksekusi statement
    $statement->execute();
    
    // Mendapatkan hasil seleksi
    $kategori = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Mengirimkan response dengan data buku
    if ($kategori) {
        $response = [
            'status' => 'success',
            'data' => $kategori
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Data kategori tidak ditemukan'
        ];
    }
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memilih data kategori: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>