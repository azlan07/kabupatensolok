<?php
include '../koneksi.php';
session_start();

// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

if (isset($_GET['id_event'])) {
    $id_event = $_GET['id_event'];
    
    // Ambil informasi foto sebelum menghapus data
    $query = "SELECT foto_event FROM event WHERE id_event = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_event);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($result);

    // Hapus foto dari direktori jika ada
    if ($event['foto_event']) {
        $file_path = "uploads/events/" . $event['foto_event'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Hapus data event dari database
    $query = "DELETE FROM event WHERE id_event = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_event);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: event.php?status=success&message=Event berhasil dihapus");
    } else {
        header("Location: event.php?status=error&message=Gagal menghapus event");
    }
} else {
    header("Location: event.php");
}
exit();
?>