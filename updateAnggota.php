<?php

include 'koneksi.php';

$connection = getConnection();


$id = isset($_POST['id']) ? $_POST['id'] : '';
$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
$tanggal_terdaftar = isset($_POST['tanggal_terdaftar']) ? $_POST['tanggal_terdaftar'] : '';

try {

    $query = "UPDATE anggota SET id = :id, nomor = :nomor, nama = :nama, jenis_kelamin = :jenis_kelamin, alamat = :alamat, no_hp = :no_hp, tanggal_terdaftar = :tanggal_terdaftar WHERE nomor = :nomor";


    $statement = $connection->prepare($query);


    $statement->bindParam(':id', $id);
    $statement->bindParam(':nomor', $nomor);
    $statement->bindParam(':nama', $nama);
    $statement->bindParam(':jenis_kelamin', $jenis_kelamin);
    $statement->bindParam(':alamat', $alamat);
    $statement->bindParam(':no_hp', $no_hp);
    $statement->bindParam(':tanggal_terdaftar', $tanggal_terdaftar);


    $statement->execute();


    $response = [
        'status' => 'success',
        'message' => 'Data anggota berhasil diupdate'
    ];
} catch (PDOException $e) {

    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui data anggota: ' . $e->getMessage()
    ];
}


echo json_encode($response);


$connection = null;
?>