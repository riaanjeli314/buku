<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode POST
$id = isset($_POST['id']) ? $_POST['id'] : '';
$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
$kode_kategori = isset($_POST['kode_kategori']) ? $_POST['kode_kategori'] : '';
$judul = isset($_POST['judul']) ? $_POST['judul'] : '';
$pengarang = isset($_POST['pengarang']) ? $_POST['pengarang'] : '';
$penerbit = isset($_POST['penerbit']) ? $_POST['penerbit'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$tanggal_input = isset($_POST['tanggal_input']) ? $_POST['tanggal_input'] : '';
$harga = isset($_POST['harga']) ? $_POST['harga'] : '';

try {
    // Query SQL untuk melakukan update data buku berdasarkan kode
    $query = "UPDATE buku SET id = :id, kode_kategori = :kode_kategori, judul = :judul, pengarang = :pengarang, penerbit = :penerbit, tahun = :tahun, tanggal_input = :tanggal_input, harga = :harga WHERE kode = :kode";
    
    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);
    
    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':id', $id);
    $statement->bindParam(':kode', $kode);
    $statement->bindParam(':kode_kategori', $kode_kategori);
    $statement->bindParam(':judul', $judul);
    $statement->bindParam(':pengarang', $pengarang);
    $statement->bindParam(':penerbit', $penerbit);
    $statement->bindParam(':tahun', $tahun);
    $statement->bindParam(':tanggal_input', $tanggal_input);
    $statement->bindParam(':harga', $harga);
    
    // Eksekusi statement
    $statement->execute();
    
    // Mengembalikan response sukses
    $response = [
        'status' => 'success',
        'message' => 'Data buku berhasil diperbarui'
    ];
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui data buku: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>