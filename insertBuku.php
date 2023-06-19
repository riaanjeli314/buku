<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

// Mendapatkan data yang dikirim melalui metode POST
$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
$kode_kategori = isset($_POST['kode_kategori']) ? $_POST['kode_kategori'] : '';
$judul = isset($_POST['judul']) ? $_POST['judul'] : '';
$pengarang = isset($_POST['pengarang']) ? $_POST['pengarang'] : '';
$penerbit = isset($_POST['penerbit']) ? $_POST['penerbit'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$tanggal_input = isset($_POST['tanggal_input']) ? $_POST['tanggal_input'] : '';
$harga = isset($_POST['harga']) ? $_POST['harga'] : '';

try {
    // Establish database connection
    $conn = getConnection();

    // Query SQL untuk melakukan insert data buku
    $query = "INSERT INTO buku (kode, kode_kategori, judul, pengarang, penerbit, tahun, tanggal_input, harga) 
              VALUES (:kode, :kode_kategori, :judul, :pengarang, :penerbit, :tahun, :tanggal_input, :harga)";
    
    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);
    
    // Mengikat parameter dengan nilai yang sesuai
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
        'message' => 'Data buku berhasil ditambahkan'
    ];
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat menambahkan data buku: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>