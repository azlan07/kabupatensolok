<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
}
include "../koneksi.php";
// Ambil id_admin dari session
$username = $_SESSION['username'];
$query_admin = "SELECT id FROM admin WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $query_admin);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result_admin = mysqli_stmt_get_result($stmt);
$admin_data = mysqli_fetch_assoc($result_admin);
$id_admin = $admin_data['id'];

// Query untuk mengambil data berita dengan join ke tabel admin
$query = "SELECT berita.*, admin.nama as nama_admin 
          FROM berita 
          JOIN admin ON berita.id_admin = admin.id 
          ORDER BY tanggal_posting DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <!-- Teks di sebelah kiri -->
                            <h6 class="m-0 font-weight-bold text-primary">Data Berita</h6>

                            <!-- Tombol di sebelah kanan -->
                            <a href="tambah_berita.php" class="btn btn-primary btn-sm">
                                Tambah Data
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Judul Berita</th>
                                            <th>Tanggal Posting</th>
                                            <th>Author</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)):
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <b><a href="detail_berita.php?id=<?= $row['id_berita'] ?>">
                                                            <?= htmlspecialchars($row['judul_berita']) ?>
                                                        </a></b>
                                                </td>
                                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_posting'])) ?></td>
                                                <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                                                <td>
                                                    <?php if ($row['foto_berita']): ?>
                                                        <img src="uploads/berita/<?= $row['foto_berita'] ?>"
                                                            alt="Foto Berita"
                                                            style="max-width: 100px;"
                                                            class="img-thumbnail">
                                                    <?php else: ?>
                                                        <span class="text-muted">Tidak ada foto</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="detail_berita.php?id=<?= $row['id_berita'] ?>"
                                                        class="btn-sm btn-success">
                                                        <span class="fas fa-eye">
                                                    </a>
                                                    <a href="edit_berita.php?id=<?= $row['id_berita'] ?>"
                                                        class="btn-sm btn-primary">
                                                        <span class="fas fa-edit">
                                                    </a>
                                                    <a href="hapus_berita.php?id=<?= $row['id_berita'] ?>"
                                                        class="btn-sm btn-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                                        <span class="fas fa-trash">
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "footer.php"; ?>

        </div>
        <!-- End of Page Wrapper -->