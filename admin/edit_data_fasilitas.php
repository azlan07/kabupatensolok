<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<!-- Tambahkan CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
        margin: 10px 0;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .current-image {
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fc;
        border-radius: 5px;
    }
</style>

<body id="page-top">
    <div id="wrapper">
        <?php include "menu_sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "menu_topbar.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Data Fasilitas Wisata</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
                        </div>
                        <div class="card-body">
                            <?php
                            include '../koneksi.php';
                            $id = $_GET['id_fasilitas'];
                            $query = mysqli_query($koneksi, "select * from fasilitas where id_fasilitas='$id'");
                            $data  = mysqli_fetch_array($query);
                            ?>

                            <form class="form-horizontal style-form" action="edit_aksi_fasilitas.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_fasilitas" value="<?php echo $data['id_fasilitas']; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Data Fasilitas -->
                                        <div class="form-group">
                                            <label>Nama Fasilitas</label>
                                            <input name="nama_fasilitas" type="text" class="form-control" value="<?php echo $data['nama_fasilitas']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input name="harga" type="number" class="form-control" value="<?php echo $data['harga']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="3" required><?php echo $data['alamat']; ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Detail</label>
                                            <textarea name="detail" class="form-control" rows="3" required><?php echo $data['detail']; ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Banyak Pengunjung</label>
                                            <input name="banyak_pengunjung" type="number" class="form-control" value="<?php echo $data['banyak_pengunjung']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Latitude</label>
                                            <input name="latitude" id="latitude" type="text" class="form-control" value="<?php echo $data['latitude']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Longitude</label>
                                            <input name="longitude" id="longitude" type="text" class="form-control" value="<?php echo $data['longitude']; ?>" required>
                                        </div>

                                        <!-- Map -->
                                        <div class="form-group">
                                            <label>Lokasi di Peta</label>
                                            <div id="map"></div>
                                            <small class="text-muted">Klik pada peta untuk mengubah lokasi</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Foto Fasilitas -->
                                        <div class="form-group">
                                            <label>Foto 1</label>
                                            <?php if ($data['foto_fasilitas1']): ?>
                                                <div class="current-image">
                                                    <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas1']; ?>" class="preview-image">
                                                    <p class="text-muted">Foto saat ini: <?php echo $data['foto_fasilitas1']; ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto_fasilitas1" class="form-control" accept="image/*">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto 2</label>
                                            <?php if ($data['foto_fasilitas2']): ?>
                                                <div class="current-image">
                                                    <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas2']; ?>" class="preview-image">
                                                    <p class="text-muted">Foto saat ini: <?php echo $data['foto_fasilitas2']; ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto_fasilitas2" class="form-control" accept="image/*">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto 3</label>
                                            <?php if ($data['foto_fasilitas3']): ?>
                                                <div class="current-image">
                                                    <img src="../uploads/fasilitas/<?php echo $data['foto_fasilitas3']; ?>" class="preview-image">
                                                    <p class="text-muted">Foto saat ini: <?php echo $data['foto_fasilitas3']; ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto_fasilitas3" class="form-control" accept="image/*">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <a href="tampil_data_fasilitas.php" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>

    <!-- Tambahkan Script Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker
        var marker = L.marker([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], {
            draggable: true
        }).addTo(map);

        // Update koordinat saat marker dipindahkan
        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Update marker saat mengklik peta
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // Preview foto yang akan diupload
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>