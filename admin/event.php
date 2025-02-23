<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
}
include "../koneksi.php";
include "notifikasi.php";

$query = "SELECT * FROM event ORDER BY id_event DESC";
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
                            <h6 class="m-0 font-weight-bold text-primary">Data Event</h6>

                            <!-- Tombol di sebelah kanan -->
                            <a href="tambah_event.php" class="btn btn-primary btn-sm">
                                Tambah Data
                            </a>
                        </div>

                        <div class="card-body">
                            <?php tampilkan_notifikasi(); ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama Event</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <b><a href="detail_event.php?id_event=<?= $row['id_event'] ?>">
                                                            <?= htmlspecialchars($row['nama_event']) ?>
                                                        </a></b>
                                                </td>
                                                <td><?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?></td>
                                                <td><?= date('d-m-Y', strtotime($row['tanggal_selesai'])) ?></td>
                                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                                <td>
                                                    <span class="badge <?= $row['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row['foto_event']): ?>
                                                        <img src="uploads/events/<?= $row['foto_event'] ?>"
                                                            alt="Foto Event"
                                                            style="max-width: 100px;"
                                                            class="img-thumbnail">
                                                    <?php else: ?>
                                                        <span class="text-muted">Tidak ada foto</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="detail_event.php?id_event=<?= $row['id_event'] ?>"
                                                        class="btn-sm btn-success">
                                                        <span class="fas fa-eye">
                                                    </a>
                                                    <a href="edit_event.php?id_event=<?= $row['id_event'] ?>"
                                                        class="btn-sm btn-primary">
                                                        <span class="fas fa-edit">
                                                    </a>
                                                    <a href="hapus_event.php?id_event=<?= $row['id_event'] ?>"
                                                        class="btn-sm btn-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                        <span class="fas fa-trash">
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
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