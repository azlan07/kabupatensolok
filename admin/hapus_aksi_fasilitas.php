<?php
// koneksi database
include '../koneksi.php';

// menangkap data id yang di kirim dari url
$id = $_GET['id_fasilitas'];


// menghapus data dari database
$query = mysqli_query($koneksi, "delete from fasilitas where id_fasilitas='$id'");
if ($query) {
    echo "<script>alert('Data Berhasil Dihapus!'); window.location = 'tampil_data_fasilitas.php'</script>";
} else {
    echo "<script>alert('Data Gagal Dihapus!'); window.location = 'tampil_data_fasilitas.php'</script>";
}
