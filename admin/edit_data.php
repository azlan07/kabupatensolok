<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<!-- Tambahkan CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

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
                        <h1 class="h3 mb-0 text-gray-800">Edit Data Tempat Wisata</h1>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
                        </div>
                        <div class="card-body">

                            <?php
                            include '../koneksi.php';
                            $id = $_GET['id_wisata'];
                            $query = mysqli_query($koneksi, "select * from wisata where id_wisata='$id'");
                            $data  = mysqli_fetch_array($query);
                            ?>

                            <!-- </div> -->
                            <div class="panel-body">
                                <form class="form-horizontal style-form" style="margin-top: 20px;" action="edit_aksi.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">ID Wisata</label>
                                        <div class="col-sm-8">
                                            <input name="id_wisata" type="text" id="id_wisata" class="form-control" value="<?php echo $data['id_wisata']; ?>" readonly />
                                            <!--<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Nama Wisata</label>
                                        <div class="col-sm-8">
                                            <input name="nama_wisata" type="text" id="nama_wisata" class="form-control" value="<?php echo $data['nama_wisata']; ?>" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Alamat</label>
                                        <div class="col-sm-8">
                                            <input name="alamat" class="form-control" id="alamat" type="text" value="<?php echo $data['alamat']; ?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Deskripsi</label>
                                        <div class="col-sm-8">
                                            <input name="deskripsi" class="form-control" id="deskripsi" type="text" value="<?php echo $data['deskripsi']; ?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Harga Tiket</label>
                                        <div class="col-sm-8">
                                            <input name="harga_tiket" class="form-control" type="text" id="harga_tiket" type="text" value="<?php echo $data['harga_tiket']; ?>" required />
                                        </div>
                                    </div>
                                    <!-- Tambahkan form banyak pengunjung -->
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Pengunjung per-Bulan</label>
                                        <div class="col-sm-8">
                                            <input name="banyak_pengunjung" class="form-control" id="banyak_pengunjung"
                                                type="number" min="0"
                                                value="<?php echo $data['banyak_pengunjung']; ?>" required />
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Masukkan rata-rata jumlah pengunjung per bulan
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Card untuk Maps -->
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Lokasi di Peta</label>
                                        <div class="col-sm-8">
                                            <div id="map" style="height: 400px; margin-bottom: 10px;" class="border rounded"></div>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i>
                                                Klik pada peta untuk memilih lokasi
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Koordinat -->
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Latitude</label>
                                        <div class="col-sm-8">
                                            <input name="latitude" class="form-control" id="latitude" type="text"
                                                value="<?php echo $data['latitude']; ?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label">Longitude</label>
                                        <div class="col-sm-8">
                                            <input name="longitude" class="form-control" id="longitude" type="text"
                                                value="<?php echo $data['longitude']; ?>" required />
                                        </div>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="col-sm-2 col-sm-2 control-label"></label>
                                        <div class="col-sm-8">
                                            <input type="submit" value="Simpan" class="btn btn-primary" />&nbsp;
                                            <a href="wisata.php" class="btn btn-secondary">Kembali</a>
                                        </div>
                                    </div>
                                    <div style="margin-top: 20px;"></div>
                                </form>
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
    <!-- Tambahkan JavaScript Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker yang bisa di-drag
        var marker = L.marker([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], {
            draggable: true
        }).addTo(map);

        // Update koordinat saat marker di-drag
        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Update koordinat saat peta diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>

    <style>
        #map {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .leaflet-container {
            border-radius: 5px;
        }
    </style>
</body>

</html>