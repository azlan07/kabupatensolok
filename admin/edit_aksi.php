<?php
// koneksi database
include '../koneksi.php';

// menangkap data yang di kirim dari form
$id = $_POST['id_wisata'];
$nama = $_POST['nama_wisata'];
$alamat = $_POST['alamat'];
$deskripsi = $_POST['deskripsi'];
$harga_tiket = $_POST['harga_tiket'];
$banyak_pengunjung = $_POST['banyak_pengunjung'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// update data ke database
mysqli_query($koneksi, "update wisata set nama_wisata='$nama', alamat='$alamat', deskripsi='$deskripsi', harga_tiket='$harga_tiket', banyak_pengunjung='$banyak_pengunjung', latitude='$latitude', longitude='$longitude' where id_wisata='$id'");

// mengalihkan halaman kembali ke index.php
header("location:tampil_data.php");
