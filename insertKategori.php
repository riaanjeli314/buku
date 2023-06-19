<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

// Mendapatkan data yang dikirim melalui metode POST
$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';

    // Establish database connection
    $conn = getConnection();

try {
    // Mengecek apakah data POST telah diterima
    if (!empty($kode) && !empty($kategori)) {
        // Query SQL untuk melakukan insert data kategori
        $query = "INSERT INTO kategori (kode, kategori) VALUES (:kode, :kategori)";
        
        // Mempersiapkan statement PDO untuk eksekusi query
        $statement = $conn->prepare($query);
        
        // Mengikat parameter dengan nilai yang sesuai
        $statement->bindParam(':kode', $kode);
        $statement->bindParam(':kategori', $kategori);
        
        // Eksekusi statement
        $statement->execute();
        
        // Mengembalikan response sukses
        $response = [
            'status' => 'success',
            'message' => 'Data kategori berhasil ditambahkan'
        ];
    } else {
        // Mengembalikan response jika data POST tidak lengkap
        $response = [
            'status' => 'error',
            'message' => 'Data kategori tidak lengkap'
        ];
    }
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat menambahkan data kategori: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>