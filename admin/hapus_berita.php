<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
}
include "../koneksi.php";

// Get berita ID
$id_berita = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_berita) {
    header("Location: berita.php?status=error&message=ID Berita tidak ditemukan");
    exit();
}

try {
    // Ambil nama file foto sebelum menghapus data
    $query_foto = "SELECT foto_berita FROM berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($koneksi, $query_foto);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_berita);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $foto_berita = $row['foto_berita'];
            
            // Hapus file foto jika ada
            if ($foto_berita && file_exists("uploads/berita/" . $foto_berita)) {
                unlink("uploads/berita/" . $foto_berita);
            }
        }
    }

    // Hapus data berita dari database
    $query_delete = "DELETE FROM berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($koneksi, $query_delete);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_berita);
        
        if (mysqli_stmt_execute($stmt)) {
            // Catat log penghapusan
            $current_user = 'azlan07'; // sesuai dengan user yang sedang login
            $current_time = '2025-02-03 10:05:26'; // sesuai dengan waktu saat ini
            $log_message = "Berita ID: $id_berita dihapus oleh $current_user pada $current_time";
            error_log($log_message, 3, "logs/berita_deletion.log");
            
            header("Location: berita.php?status=success&message=Berita berhasil dihapus");
        } else {
            throw new Exception("Gagal menghapus berita: " . mysqli_error($koneksi));
        }
    } else {
        throw new Exception("Error dalam persiapan query: " . mysqli_error($koneksi));
    }
} catch (Exception $e) {
    header("Location: berita.php?status=error&message=" . urlencode($e->getMessage()));
}
exit();
?>