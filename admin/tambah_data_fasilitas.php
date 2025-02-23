<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<head>
    <!-- Tambahkan stylesheet dan script Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 400px;
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Data Fasilitas Wisata</h1>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                        </div>
                        <div class="card-body">
                            <!-- Grid untuk form dan peta -->
                            <div class="row">
                                <!-- Formulir -->
                                <div class="col-md-6">
                                    <form class="form-horizontal style-form" style="margin-top: 10px;" action="tambah_aksi_fasilitas.php" method="post" enctype="multipart/form-data" name="form2" id="form2">
                                        <div class="form-group">
                                            <label>Nama Fasilitas</label>
                                            <input name="nama_fasilitas" type="text" class="form-control" placeholder="Nama Fasilitas" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input name="harga" class="form-control" type="number" placeholder="Harga" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <input name="alamat" class="form-control" type="text" placeholder="Alamat" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Detail</label>
                                            <input name="detail" class="form-control" type="text" placeholder="Detail" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Banyak Pengunjung</label>
                                            <input name="banyak_pengunjung" class="form-control" type="number" placeholder="Banyak Pengunjung" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Latitude</label>
                                            <input id="latitude" name="latitude" class="form-control" type="number" step="any" placeholder="-7.3811577" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Longitude</label>
                                            <input id="longitude" name="longitude" class="form-control" type="number" step="any" placeholder="109.2550945" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto 1</label>
                                            <input name="foto_fasilitas1" type="file" class="form-control" accept="image/*" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto 2</label>
                                            <input name="foto_fasilitas2" type="file" class="form-control" accept="image/*" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto 3</label>
                                            <input name="foto_fasilitas3" type="file" class="form-control" accept="image/*" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" value="Simpan" class="btn btn-sm btn-primary" />
                                        </div>
                                    </form>
                                </div>

                                <!-- Peta -->
                                <div class="col-md-6">
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
        // Inisialisasi peta
        var map = L.map('map').setView([-0.9632193533203104, 100.78149209828896], 10);

        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker default
        var marker = L.marker([-0.9632193533203104, 100.78149209828896], { draggable: true }).addTo(map);

        // Update form latitude dan longitude saat marker dipindahkan
        marker.on('dragend', function (e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Tambahkan event klik pada peta untuk menambahkan marker baru
        map.on('click', function (e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Update posisi marker
            marker.setLatLng([lat, lng]);

            // Update form latitude dan longitude
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    </script>
</body>

</html>
