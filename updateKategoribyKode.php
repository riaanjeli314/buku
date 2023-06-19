<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode POST
$id = isset($_POST['id']) ? $_POST['id'] : '';
$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';

try {
    // Query SQL untuk memperbarui data kategori berdasarkan kode
    $query = "UPDATE kategori SET kode = :kode, kategori = :kategori WHERE id = :id";
    
    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);
    
    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':id', $id);
    $statement->bindParam(':kode', $kode);
    $statement->bindParam(':kategori', $kategori);
    
    // Eksekusi statement
    $statement->execute();
    
    // Mengirimkan response dalam format JSON
    $response = [
        'status' => 'success',
        'message' => 'Data kategori berhasil diperbarui'
    ];
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui data kategori: ' . $e->getMessage()
    ];
}

// Mengirimkan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>