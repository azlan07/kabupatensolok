<?php
include '../koneksi.php';
session_start();

// Cek login
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id_event'])) {
    header("Location: event.php");
    exit();
}

$id_event = $_GET['id_event'];

// Ambil data event yang akan diedit
$query = "SELECT * FROM event WHERE id_event = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_event);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

// Jika event tidak ditemukan
if (!$event) {
    header("Location: event.php");
    exit();
}

// Proses form edit jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_event = $_POST['nama_event'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $status = $_POST['status'];
    $foto_lama = $event['foto_event'];

    $error = false;
    $error_message = '';

    // Validasi input
    if (empty($nama_event) || empty($tanggal_mulai) || empty($tanggal_selesai) || 
        empty($alamat) || empty($deskripsi) || empty($latitude) || empty($longitude)) {
        $error = true;
        $error_message = "Semua field harus diisi!";
    }

    // Validasi tanggal
    if ($tanggal_selesai < $tanggal_mulai) {
        $error = true;
        $error_message = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai!";
    }

    // Handle upload foto baru jika ada
    if (!$error && isset($_FILES['foto_event']) && $_FILES['foto_event']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['foto_event']['type'], $allowed_types)) {
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

            if (move_uploaded_file($_FILES['foto_event']['tmp_name'], $target_file)) {
                // Hapus foto lama jika ada
                if ($foto_lama && file_exists($target_dir . $foto_lama)) {
                    unlink($target_dir . $foto_lama);
                }
            } else {
                $error = true;
                $error_message = "Gagal mengupload file.";
            }
        }
    } else {
        $foto_event = $foto_lama;
    }

    // Proses update jika tidak ada error
    if (!$error) {
        $query = "UPDATE event SET 
                  nama_event = ?,
                  tanggal_mulai = ?,
                  tanggal_selesai = ?,
                  alamat = ?,
                  deskripsi = ?,
                  latitude = ?,
                  longitude = ?,
                  foto_event = ?,
                  status = ?
                  WHERE id_event = ?";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssi", 
            $nama_event, 
            $tanggal_mulai, 
            $tanggal_selesai, 
            $alamat, 
            $deskripsi, 
            $latitude, 
            $longitude, 
            $foto_event, 
            $status,
            $id_event
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: event.php?status=success&message=Event berhasil diupdate");
            exit();
        } else {
            $error = true;
            $error_message = "Gagal mengupdate event: " . mysqli_error($koneksi);
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
        .preview-image {
            max-width: 200px;
            margin-bottom: 10px;
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Event</h1>
                    </div>

                    <!-- Card Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Event</h6>
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
                                                   value="<?= htmlspecialchars($event['nama_event']) ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" class="form-control" name="tanggal_mulai" 
                                                   value="<?= $event['tanggal_mulai'] ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Selesai</label>
                                            <input type="date" class="form-control" name="tanggal_selesai" 
                                                   value="<?= $event['tanggal_selesai'] ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" name="alamat" rows="3" required><?= htmlspecialchars($event['alamat']) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control" name="deskripsi" rows="5" required><?= htmlspecialchars($event['deskripsi']) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="aktif" <?= $event['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                <option value="selesai" <?= $event['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto Event</label>
                                            <?php if ($event['foto_event']): ?>
                                                <div class="mb-2">
                                                    <img src="uploads/events/<?= $event['foto_event'] ?>" 
                                                         alt="Preview" class="preview-image img-thumbnail">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto_event" class="form-control" accept="image/*">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Latitude</label>
                                            <input id="latitude" name="latitude" class="form-control" type="number" 
                                                   step="any" value="<?= htmlspecialchars($event['latitude']) ?>" required readonly />
                                        </div>

                                        <div class="form-group">
                                            <label>Longitude</label>
                                            <input id="longitude" name="longitude" class="form-control" type="number" 
                                                   step="any" value="<?= htmlspecialchars($event['longitude']) ?>" required readonly />
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Update</button>
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
        var map = L.map('map').setView([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker yang bisa di-drag
        var marker = L.marker([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>], {
            draggable: true
        }).addTo(map);

        // Update koordinat saat marker di-drag
        marker.on('dragend', function(e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Update koordinat saat peta diklik
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            marker.setLatLng([lat, lng]);
        });
    </script>
</body>
</html>