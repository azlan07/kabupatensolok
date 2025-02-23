<?php
include '../koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_event = $_POST['nama_event'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $status = $_POST['status'];
    
    $error = false;
    $error_message = '';

    // Validasi input
    if (empty($nama_event) || empty($tanggal_mulai) || empty($tanggal_selesai) || 
        empty($alamat) || empty($deskripsi) || empty($latitude) || empty($longitude)) {
        $error = true;
        $error_message = "Semua field harus diisi!";
    }

    // Handle upload foto
    if (!$error && isset($_FILES['foto_event'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if ($_FILES['foto_event']['error'] !== 0) {
            $error = true;
            $error_message = "Harap pilih file foto!";
        } elseif (!in_array($_FILES['foto_event']['type'], $allowed_types)) {
            $error = true;
            $error_message = "Tipe file tidak diizinkan. Hanya JPEG, JPG, dan PNG yang diperbolehkan.";
        } elseif ($_FILES['foto_event']['size'] > $max_size) {
            $error = true;
            $error_message = "Ukuran file terlalu besar. Maksimal 5MB.";
        } else {
            $target_dir = "uploads/events/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['foto_event']['name'], PATHINFO_EXTENSION);
            $foto_event = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $foto_event;

            if (!move_uploaded_file($_FILES['foto_event']['tmp_name'], $target_file)) {
                $error = true;
                $error_message = "Gagal mengupload file.";
            }
        }
    } else {
        $error = true;
        $error_message = "Foto event harus diupload!";
    }

    // Proses simpan jika tidak ada error
    if (!$error) {
        // Perbaikan query SQL
        $query = "INSERT INTO event (nama_event, tanggal_mulai, tanggal_selesai, alamat, 
                                   deskripsi, latitude, longitude, foto_event, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = mysqli_prepare($koneksi, $query);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssssssss", 
                    $nama_event, 
                    $tanggal_mulai, 
                    $tanggal_selesai, 
                    $alamat, 
                    $deskripsi, 
                    $latitude, 
                    $longitude, 
                    $foto_event, 
                    $status
                );

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: event.php?status=success&message=Event berhasil ditambahkan");
                    exit();
                } else {
                    $error = true;
                    $error_message = "Gagal menyimpan event: " . mysqli_error($koneksi);
                }
            } else {
                $error = true;
                $error_message = "Error dalam persiapan query: " . mysqli_error($koneksi);
            }
        } catch (Exception $e) {
            $error = true;
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<head>
    <!-- Tambahkan stylesheet Leaflet -->
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Event Baru</h1>
                    </div>

                    <!-- Card Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Event</h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error) && $error): ?>
                                <div class="alert alert-danger">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Grid untuk form dan peta -->
                            <div class="row">
                                <!-- Form bagian kiri -->
                                <div class="col-md-6">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Nama Event</label>
                                            <input type="text" class="form-control" name="nama_event" 
                                                   value="<?= isset($_POST['nama_event']) ? htmlspecialchars($_POST['nama_event']) : '' ?>" 
                                                   required />
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" class="form-control" name="tanggal_mulai" 
                                                   value="<?= isset($_POST['tanggal_mulai']) ? $_POST['tanggal_mulai'] : '' ?>" 
                                                   required />
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Selesai</label>
                                            <input type="date" class="form-control" name="tanggal_selesai" 
                                                   value="<?= isset($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : '' ?>" 
                                                   required />
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" name="alamat" rows="3" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control" name="deskripsi" rows="3" required><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Upload Foto</label>
                                            <input type="file" name="foto_event" class="form-control" accept="image/*" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="aktif" selected>Aktif</option>
                                                <option value="selesai">Selesai</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Latitude</label>
                                            <input id="latitude" name="latitude" class="form-control" type="number" 
                                                   step="any" required readonly />
                                        </div>

                                        <div class="form-group">
                                            <label>Longitude</label>
                                            <input id="longitude" name="longitude" class="form-control" type="number" 
                                                   step="any" required readonly />
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="event.php" class="btn btn-secondary">Kembali</a>
                                        </div>
                                    </form>
                                </div>

                                <!-- Peta di sebelah kanan -->
                                <div class="col-md-6">
                                    <div id="map"></div>
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

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-0.9634, 100.8298], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        // Tambahkan marker default
        marker = L.marker([-0.9634, 100.8298], { draggable: true }).addTo(map);

        // Update form latitude dan longitude saat marker dipindahkan
        marker.on('dragend', function (e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Tambahkan event klik pada peta
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