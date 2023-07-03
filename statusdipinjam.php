<?php
include 'koneksi.php';

$connection = getConnection();

$query = "SELECT * FROM peminjaman_master WHERE status_peminjaman = 'DIPINJAM'";
$result = $connection->query($query);

if ($result !== false && $result->rowCount() > 0) {
    $peminjaman = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($peminjaman);
} else {
    echo "Tidak ada data peminjaman dengan status DIPINJAM.";
}

$connection = null;
?>