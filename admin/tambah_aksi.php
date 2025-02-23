<?php
// koneksi database
include '../koneksi.php';

// menangkap data yang dikirim dari form
$nama = $_POST['nama_wisata'];
$alamat = $_POST['alamat'];
$deskripsi = $_POST['deskripsi'];
$harga_tiket = $_POST['harga_tiket'];
$banyak_pengunjung = $_POST['banyak_pengunjung'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Handle file upload
$foto_wisata = "";
if(isset($_FILES['foto_wisata'])) {
    $file = $_FILES['foto_wisata'];
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    
    // Validasi ukuran file (max 2MB)
    if($file_size > 2000000) {
        echo "<script>
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                window.location.href='tambah_data.php';
              </script>";
        exit;
    }
    
    // Validasi tipe file
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
    if(!in_array($file['type'], $allowed_types)) {
        echo "<script>
                alert('Tipe file tidak diizinkan! Gunakan JPG, JPEG, atau PNG');
                window.location.href='tambah_data.php';
              </script>";
        exit;
    }
    
    // Generate unique filename
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $new_filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;
    
    // Upload file
    $upload_path = '../uploads/wisata/';
    if(!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }
    
    if(move_uploaded_file($tmp_name, $upload_path . $new_filename)) {
        $foto_wisata = $new_filename;
    } else {
        echo "<script>
                alert('Gagal mengupload file!');
                window.location.href='tambah_data.php';
              </script>";
        exit;
    }
}

// menginput data ke database
$query = "INSERT INTO wisata (nama_wisata, alamat, deskripsi, harga_tiket, banyak_pengunjung, latitude, longitude, foto_wisata) 
          VALUES ('$nama', '$alamat', '$deskripsi', '$harga_tiket', '$banyak_pengunjung', '$latitude', '$longitude', '$foto_wisata')";

if(mysqli_query($koneksi, $query)) {
    header("Location: tampil_data.php");
} else {
    echo "<script>
            alert('Gagal menyimpan data!');
            window.location.href='tambah_data.php';
          </script>";
}
exit;
?>