<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data_fasilitas.php?pesan=belum_login");
}
include "../koneksi.php";
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
                            <h6 class="m-0 font-weight-bold text-primary">Data Fasilitas Wisata Kabupaten Solok</h6>

                            <!-- Tombol di sebelah kanan -->
                            <a href="tambah_data_fasilitas.php" class="btn btn-primary btn-sm">
                                Tambah Data
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Harga</th>
                                            <th>Alamat</th>
                                            <th>Pengunjung pe-Bulan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $data = mysqli_query($koneksi, "select * from fasilitas ORDER by id_fasilitas DESC");
                                        while ($d = mysqli_fetch_array($data)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no ?></td>
                                                <td><b><a href="detail_data_fasilitas.php?id_fasilitas=<?php echo $d['id_fasilitas']; ?> "> <?php echo $d['nama_fasilitas']; ?> </a> </b></td>
                                                <td><?php echo $d['harga']; ?></td>
                                                <td><?php echo $d['alamat']; ?></td>
                                                <td><?php echo $d['banyak_pengunjung']; ?></td>
                                                <td>
                                                    <a href="detail_data_fasilitas.php?id_fasilitas=<?php echo $d['id_fasilitas']; ?> " class="btn-sm btn-success"><span class="fas fa-eye"></a>
                                                    <a href="edit_data_fasilitas.php?id_fasilitas=<?php echo $d['id_fasilitas']; ?> " class="btn-sm btn-primary"><span class="fas fa-edit"></a>
                                                    <a href="hapus_aksi_fasilitas.php?id_fasilitas=<?php echo $d['id_fasilitas']; ?>" class="btn-sm btn-danger"><span class="fas fa-trash"></a>
                                                </td>
                                            </tr>
                            </div>
                        <?php
                                        }
                        ?>
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