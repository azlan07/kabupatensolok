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

    <head>
        <!-- Leaflet CSS dan JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <!-- Swiper CSS dan JS untuk slider foto -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <style>
            #map {
                height: 400px;
                width: 100%;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .swiper {
                width: 100%;
                height: 400px;
                margin-bottom: 20px;
            }

            .swiper-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 8px;
            }

            .detail-card {
                background: #f8f9fc;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .info-label {
                font-weight: bold;
                color: #4e73df;
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

                    <?php
                    $id = $_GET['id_fasilitas'];
                    $query = mysqli_query($koneksi, "select * from fasilitas where id_fasilitas='$id'");
                    $data  = mysqli_fetch_array($query);
                    ?>

                <?php } ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Fasilitas Wisata <?php echo $data['nama_fasilitas']; ?></h1>
                    </div>
                    <!-- Photos Slider -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Foto Fasilitas</h6>
                        </div>
                        <div class="card-body">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <?php if ($data['foto_fasilitas1']) : ?>
                                        <div class="swiper-slide">
                                            <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas1']; ?>" alt="Foto 1">
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($data['foto_fasilitas2']) : ?>
                                        <div class="swiper-slide">
                                            <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas2']; ?>" alt="Foto 2">
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($data['foto_fasilitas3']) : ?>
                                        <div class="swiper-slide">
                                            <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas3']; ?>" alt="Foto 3">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Cards Row -->
                    <div class="row">
                        <!-- Harga Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Harga</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pengunjung Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pengunjung</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['banyak_pengunjung']); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Detail Info Column -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informasi Detail</h6>
                                </div>
                                <div class="card-body">
                                    <div class="detail-card">
                                        <p><span class="info-label">Nama Fasilitas:</span><br><?php echo $data['nama_fasilitas']; ?></p>
                                        <p><span class="info-label">Alamat:</span><br><?php echo $data['alamat']; ?></p>
                                        <p><span class="info-label">Detail:</span><br><?php echo nl2br($data['detail']); ?></p>
                                        <p><span class="info-label">Koordinat:</span><br>
                                            Latitude: <?php echo $data['latitude']; ?><br>
                                            Longitude: <?php echo $data['longitude']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Map Column -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Lokasi di Peta</h6>
                                </div>
                                <div class="card-body">
                                    <div id="map"></div>
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
        <script>
            // Inisialisasi Swiper
            const swiper = new Swiper('.swiper', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });

            // Inisialisasi Map
            var map = L.map('map').setView([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>])
                .addTo(map)
                .bindPopup("<?php echo $data['nama_fasilitas']; ?>");
        </script>
    </body>

    </html>