<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
    exit;
}

include '../koneksi.php';

// Current timestamp and user
$current_time = '2025-02-03 13:56:40';

// Fungsi untuk handle upload file
function handleFileUpload($file, $index)
{
    if ($file['error'] !== 0) {
        throw new Exception("Error pada upload foto " . ($index) . "!");
    }

    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $file_size = $file['size'];

    // Validasi ukuran file (max 2MB)
    if ($file_size > 2000000) {
        throw new Exception("Ukuran foto " . $index . " terlalu besar! Maksimal 2MB");
    }

    // Validasi tipe file
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("Tipe file foto " . $index . " tidak diizinkan! Gunakan JPG, JPEG, atau PNG");
    }

    // Generate unique filename
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $new_filename = 'fasilitas_' . date('YmdHis') . '_' . uniqid() . '_' . $index . '.' . $extension;

    // Upload file
    $upload_path = '../uploads/fasilitas/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    if (move_uploaded_file($tmp_name, $upload_path . $new_filename)) {
        return $new_filename;
    } else {
        throw new Exception("Gagal mengupload foto " . $index . "!");
    }
}

try {
    // Validasi input
    $required_fields = array(
        'nama_fasilitas' => 'Nama Fasilitas',
        'harga' => 'Harga',
        'alamat' => 'Alamat',
        'detail' => 'Detail',
        'banyak_pengunjung' => 'Banyak Pengunjung',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude'
    );

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            throw new Exception("Field $label harus diisi!");
        }
    }

    // Tangkap data dari form
    $nama_fasilitas = mysqli_real_escape_string($koneksi, $_POST['nama_fasilitas']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $detail = mysqli_real_escape_string($koneksi, $_POST['detail']);
    $banyak_pengunjung = mysqli_real_escape_string($koneksi, $_POST['banyak_pengunjung']);
    $latitude = mysqli_real_escape_string($koneksi, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($koneksi, $_POST['longitude']);

    // Handle upload foto
    $foto_fasilitas1 = handleFileUpload($_FILES['foto_fasilitas1'], 1);
    $foto_fasilitas2 = handleFileUpload($_FILES['foto_fasilitas2'], 2);
    $foto_fasilitas3 = handleFileUpload($_FILES['foto_fasilitas3'], 3);

    // Query insert
    $query = "INSERT INTO fasilitas (
                nama_fasilitas,
                harga,
                alamat,
                detail,
                banyak_pengunjung,
                latitude,
                longitude,
                foto_fasilitas1,
                foto_fasilitas2,
                foto_fasilitas3
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssss",
            $nama_fasilitas,
            $harga,
            $alamat,
            $detail,
            $banyak_pengunjung,
            $latitude,
            $longitude,
            $foto_fasilitas1,
            $foto_fasilitas2,
            $foto_fasilitas3
        );

        if (mysqli_stmt_execute($stmt)) {
            // Log aktivitas
            $log_message = "[" . $current_time . "] User " .
                " menambahkan fasilitas: " . $nama_fasilitas . "\n";
            error_log($log_message, 3, "logs/fasilitas.log");

            header("Location: tampil_data_fasilitas.php?status=success&message=Data fasilitas berhasil ditambahkan");
            exit;
        } else {
            throw new Exception("Gagal menyimpan data ke database: " . mysqli_error($koneksi));
        }
    } else {
        throw new Exception("Error dalam persiapan query: " . mysqli_error($koneksi));
    }
} catch (Exception $e) {
    // Hapus file yang sudah terupload jika ada error
    $upload_path = '../uploads/fasilitas/';
    foreach ([$foto_fasilitas1, $foto_fasilitas2, $foto_fasilitas3] as $foto) {
        if (!empty($foto) && file_exists($upload_path . $foto)) {
            unlink($upload_path . $foto);
        }
    }

    // Log error
    $error_message = "[" . $current_time . "] ERROR  "  .
        ": " . $e->getMessage() . "\n";
    error_log($error_message, 3, "logs/error.log");

    echo "<script>
            alert('" . $e->getMessage() . "');
            window.location.href='tambah_data_fasilitas.php';
          </script>";
}
