<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');
} else {
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

                    <?php
                    $id = $_GET['id_wisata'];
                    $query = mysqli_query($koneksi, "select * from wisata where id_wisata='$id'");
                    $data  = mysqli_fetch_array($query);
                    ?>

                <?php } ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Wisata <?php echo $data['nama_wisata']; ?></h1>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Detail Wisata</h6>
                        </div>
                        <div class="card-body">

                            <!-- </div> -->
                            <div class="panel-body">
                                <table id="example" class="table table-hover table-bordered">
                                    <tr>
                                        <td width="250">Nama Wisata</td>
                                        <td width="550"><?php echo $data['nama_wisata']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td><?php echo $data['alamat']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td><?php echo $data['deskripsi']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Harga Tiket</td>
                                        <td>Rp. <?php echo $data['harga_tiket']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Pengungjung per-Bulan</td>
                                        <td><?php echo $data['banyak_pengunjung']; ?> Orang</td>
                                    </tr>
                                    <tr>
                                        <td>Latitude</td>
                                        <td><?php echo $data['latitude']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Longitude</td>
                                        <td><?php echo $data['longitude']; ?></td>
                                    </tr>
                                </table>
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <!-- Foto Wisata Card -->
                                        <div class="card">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Foto Wisata</h6>
                                            </div>
                                            <div class="card-body">
                                                <?php if (!empty($data['foto_wisata'])): ?>
                                                    <img src="../uploads/wisata/<?= $data['foto_wisata'] ?>"
                                                        alt="Foto <?= htmlspecialchars($data['nama_wisata']) ?>"
                                                        class="img-fluid rounded shadow">
                                                    <div class="text-center mt-3">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center text-muted">
                                                        <i class="fas fa-image fa-3x mb-3"></i>
                                                        <p>Tidak ada foto tersedia</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Info Tambahan Card -->
                                        <div class="card mt-4">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Informasi Tambahan</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>
                                                            <i class="fas fa-clock"></i> Current Time:<br>
                                                            <span class="text-primary">2025-02-03 13:00:02</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="small">
                                                    <p><i class="fas fa-info-circle"></i> Last Updated:</p>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success"></i> Data: <?= date('Y-m-d H:i:s') ?></li>
                                                        <li><i class="fas fa-check text-success"></i> Foto: <?= date('Y-m-d H:i:s', filemtime('../uploads/wisata/' . $data['foto_wisata'])) ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Peta Lokasi Card -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Lokasi Wisata</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="map" style="height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->
                <?php include "footer.php"; ?>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Tambahkan ini sebelum closing body tag -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

        <script>
            // Inisialisasi peta
            var map = L.map('map').setView([<?= $data['latitude'] ?>, <?= $data['longitude'] ?>], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan marker
            L.marker([<?= $data['latitude'] ?>, <?= $data['longitude'] ?>])
                .addTo(map)
                .bindPopup("<?= htmlspecialchars($data['nama_wisata']) ?>");
        </script>

        <style>
            .img-fluid {
                max-height: 400px;
                width: auto;
                margin: 0 auto;
                display: block;
            }

            #map {
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </body>

    </html>