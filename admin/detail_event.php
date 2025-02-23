<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
}
include "../koneksi.php";

// Get event ID from URL
$id_event = isset($_GET['id_event']) ? $_GET['id_event'] : null;

if (!$id_event) {
    header("Location: event.php?status=error&message=ID Event tidak ditemukan");
    exit();
}

// Query untuk mengambil detail event
$query = "SELECT * FROM event WHERE id_event = ?";

try {
    $stmt = mysqli_prepare($koneksi, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_event);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $event = mysqli_fetch_assoc($result);
        } else {
            header("Location: event.php?status=error&message=Event tidak ditemukan");
            exit();
        }
    } else {
        throw new Exception("Error dalam persiapan query: " . mysqli_error($koneksi));
    }
} catch (Exception $e) {
    header("Location: event.php?status=error&message=Terjadi kesalahan sistem");
    exit();
}

// Set default timezone
date_default_timezone_set('UTC');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<head>
    <style>
        .event-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .event-content {
            font-size: 1.1em;
            line-height: 1.8;
        }
        .metadata {
            font-size: 0.9em;
            color: #6c757d;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        #map {
            height: 400px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "menu_sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "menu_topbar.php"; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Event</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Informasi Event</h6>
                            <div class="action-buttons">
                                <!-- <a href="edit_event.php?id=<?= $event['id_event'] ?>" 
                                   class="btn btn-sm btn-warning">
                                   <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus_event.php?id=<?= $event['id_event'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                   <i class="fas fa-trash"></i> Hapus
                                </a> -->
                                <a href="event.php" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="mb-3"><?= htmlspecialchars($event['nama_event']) ?></h2>
                                    
                                    <div class="metadata mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>
                                                    <i class="fas fa-calendar"></i> Tanggal Mulai: 
                                                    <?= date('d-m-Y', strtotime($event['tanggal_mulai'])) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>
                                                    <i class="fas fa-calendar-check"></i> Tanggal Selesai: 
                                                    <?= date('d-m-Y', strtotime($event['tanggal_selesai'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="event-content">
                                        <h5><i class="fas fa-map-marker-alt"></i> Alamat:</h5>
                                        <p><?= nl2br(htmlspecialchars($event['alamat'])) ?></p>

                                        <h5><i class="fas fa-info-circle"></i> Deskripsi:</h5>
                                        <p><?= nl2br(htmlspecialchars($event['deskripsi'])) ?></p>

                                        <div id="map"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <?php if ($event['foto_event']): ?>
                                        <div class="card mb-4">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Foto Event</h6>
                                            </div>
                                            <div class="card-body">
                                                <img src="uploads/events/<?= $event['foto_event'] ?>" 
                                                     alt="Foto Event" class="event-image">
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Detail Informasi</h6>
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <i class="fas fa-clock"></i> Durasi Event:<br>
                                                <?php
                                                $start = new DateTime($event['tanggal_mulai']);
                                                $end = new DateTime($event['tanggal_selesai']);
                                                $interval = $start->diff($end);
                                                echo $interval->days + 1 . " hari";
                                                ?>
                                            </p>
                                            <p>
                                                <i class="fas fa-map-pin"></i> Koordinat:<br>
                                                Lat: <?= $event['latitude'] ?><br>
                                                Long: <?= $event['longitude'] ?>
                                            </p>
                                            <p>
                                                <i class="fas fa-check-circle"></i> Status:<br>
                                                <span class="badge <?= $event['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= ucfirst($event['status']) ?>
                                                </span>
                                            </p>
                                            <p>
                                                <i class="fas fa-clock"></i> Current Time:<br>
                                                <?= '2025-02-03 10:02:35' ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker
        L.marker([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>])
            .addTo(map)
            .bindPopup("<?= htmlspecialchars($event['nama_event']) ?>");
    </script>
</body>
</html>