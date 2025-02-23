<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
    exit;
}

include '../koneksi.php';

// Menangkap data dari form
$id = $_POST['id_fasilitas'];
$nama = $_POST['nama_fasilitas'];
$alamat = $_POST['alamat'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$harga = $_POST['harga'];
$detail = $_POST['detail'];
$banyak_pengunjung = $_POST['banyak_pengunjung'];

try {
    // Mulai transaction
    mysqli_begin_transaction($koneksi);

    // Fungsi untuk handle upload foto
    function handlePhotoUpload($file, $old_photo, $index) {
        global $koneksi, $id;
        
        // Jika tidak ada file baru diupload, gunakan foto lama
        if (!$file['size']) {
            return $old_photo;
        }

        // Validasi file
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("Tipe file foto " . $index . " tidak diizinkan. Gunakan JPG, JPEG, atau PNG");
        }

        if ($file['size'] > 2000000) {
            throw new Exception("Ukuran file foto " . $index . " terlalu besar (maksimal 2MB)");
        }

        // Generate nama file baru
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'fasilitas_' . $id . '_' . time() . '_' . $index . '.' . $ext;
        $upload_path = '../uploads/fasilitas/';

        // Hapus foto lama jika ada
        if ($old_photo && file_exists($upload_path . $old_photo)) {
            unlink($upload_path . $old_photo);
        }

        // Upload file baru
        if (!move_uploaded_file($file['tmp_name'], $upload_path . $new_filename)) {
            throw new Exception("Gagal mengupload foto " . $index);
        }

        return $new_filename;
    }

    // Query untuk mendapatkan data foto lama
    $query_old = mysqli_query($koneksi, "SELECT foto_fasilitas1, foto_fasilitas2, foto_fasilitas3 
                                        FROM fasilitas WHERE id_fasilitas='$id'");
    $data_old = mysqli_fetch_assoc($query_old);

    // Handle upload foto
    $foto1 = isset($_FILES['foto_fasilitas1']) ? 
             handlePhotoUpload($_FILES['foto_fasilitas1'], $data_old['foto_fasilitas1'], 1) : 
             $data_old['foto_fasilitas1'];

    $foto2 = isset($_FILES['foto_fasilitas2']) ? 
             handlePhotoUpload($_FILES['foto_fasilitas2'], $data_old['foto_fasilitas2'], 2) : 
             $data_old['foto_fasilitas2'];

    $foto3 = isset($_FILES['foto_fasilitas3']) ? 
             handlePhotoUpload($_FILES['foto_fasilitas3'], $data_old['foto_fasilitas3'], 3) : 
             $data_old['foto_fasilitas3'];

    // Update data ke database
    $query = "UPDATE fasilitas SET 
              nama_fasilitas = ?,
              alamat = ?,
              latitude = ?,
              longitude = ?,
              harga = ?,
              detail = ?,
              banyak_pengunjung = ?,
              foto_fasilitas1 = ?,
              foto_fasilitas2 = ?,
              foto_fasilitas3 = ?
              WHERE id_fasilitas = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", 
        $nama,
        $alamat,
        $latitude,
        $longitude,
        $harga,
        $detail,
        $banyak_pengunjung,
        $foto1,
        $foto2,
        $foto3,
        $id
    );

    // Eksekusi query
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal mengupdate data: " . mysqli_error($koneksi));
    }

    // Commit transaction
    mysqli_commit($koneksi);

    // Set session success message
    $_SESSION['success_message'] = "Data fasilitas berhasil diupdate!";
    
    // Redirect ke halaman tampil data
    header("location:tampil_data_fasilitas.php");
    exit;

} catch (Exception $e) {
    // Rollback jika terjadi error
    mysqli_rollback($koneksi);

    // Log error
    $error_message = "ERROR : " . $e->getMessage() . "\n";
    error_log($error_message, 3, "logs/error.log");

    // Set session error message
    $_SESSION['error_message'] = $e->getMessage();
    
    // Redirect kembali ke form edit
    header("location:edit_data_fasilitas.php?id_fasilitas=$id");
    exit;
}
?>