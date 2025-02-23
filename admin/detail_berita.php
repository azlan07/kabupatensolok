<?php
include '../koneksi.php';
session_start();

// Cek login
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit();
}

$id_berita = $_GET['id'];

// Query untuk mengambil detail berita dengan join ke tabel admin
$query = "SELECT berita.*, admin.nama as nama_admin 
          FROM berita 
          JOIN admin ON berita.id_admin = admin.id 
          WHERE berita.id_berita = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_berita);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$berita = mysqli_fetch_assoc($result);

if (!$berita) {
    header("Location: berita.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<head>
    <style>
        .berita-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .berita-content {
            font-size: 1.1em;
            line-height: 1.8;
        }
        .metadata {
            font-size: 0.9em;
            color: #6c757d;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "menu_sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include "menu_topbar.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Berita</h1>
                    </div>

                    <!-- Card Content -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Informasi Berita</h6>
                            <div class="action-buttons">
                                <!-- <a href="edit_berita.php?id=<?= $berita['id_berita'] ?>" 
                                   class="btn btn-sm btn-warning">
                                   <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus_berita.php?id=<?= $berita['id_berita'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                   <i class="fas fa-trash"></i> Hapus
                                </a> -->
                                <a href="berita.php" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Konten Berita -->
                                <div class="col-md-8">
                                    <h2 class="mb-3"><?= htmlspecialchars($berita['judul_berita']) ?></h2>
                                    
                                    <div class="metadata mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>
                                                    <i class="fas fa-user"></i> Author: 
                                                    <?= htmlspecialchars($berita['nama_admin']) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>
                                                    <i class="fas fa-clock"></i> Posted: 
                                                    <?= date('d-m-Y H:i', strtotime($berita['tanggal_posting'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="berita-content">
                                        <?= $berita['isi_berita'] ?>
                                    </div>
                                </div>

                                <!-- Sidebar Info -->
                                <div class="col-md-4">
                                    <?php if ($berita['foto_berita']): ?>
                                        <div class="card mb-4">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Foto Berita</h6>
                                            </div>
                                            <div class="card-body">
                                                <img src="uploads/berita/<?= $berita['foto_berita'] ?>" 
                                                     alt="Foto Berita" class="berita-image">
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Info Card -->
                                    <div class="card">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Detail Informasi</h6>
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <i class="fas fa-calendar"></i> Tanggal Posting:<br>
                                                <?= date('d F Y', strtotime($berita['tanggal_posting'])) ?>
                                            </p>
                                            <p>
                                                <i class="fas fa-clock"></i> Waktu Posting:<br>
                                                <?= date('H:i:s', strtotime($berita['tanggal_posting'])) ?> WIB
                                            </p>
                                            <p>
                                                <i class="fas fa-user-edit"></i> Terakhir Diubah:<br>
                                                <?= date('d-m-Y H:i:s') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <?php include "footer.php"; ?>
        </div>
    </div>
</body>
</html>