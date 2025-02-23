<?php
include_once "header.php";
include_once "koneksi.php";

$id_event = $_GET['id'];

// Query untuk mengambil detail event
$query = "SELECT * FROM event WHERE id_event = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_event);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    header("Location: index.php");
    exit();
}
?>

<div class="container" style="margin-bottom: 8px;">
    <div class="row mb-4">
        <div class="col-12">
            <div class="panel panel-info panel-dashboard">
                <div class="panel-heading centered">
                    <h2 class="panel-title"><strong> - Detail Event - </strong></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Image Container -->
                    <div class="image-container mb-4">
                        <?php if (!empty($event['foto_event'])) { ?>
                            <img src="admin/uploads/events/<?= $event['foto_event'] ?>" 
                                 alt="<?= htmlspecialchars($event['nama_event']) ?>"
                                 class="img-fluid rounded">
                        <?php } else { ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php } ?>
                    </div>

                    <h1 class="card-title h3 mb-4"><?= htmlspecialchars($event['nama_event']) ?></h1>

                    <div class="event-details mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fa fa-calendar"></i>
                                    <strong>Tanggal Mulai:</strong><br>
                                    <?= date('d F Y', strtotime($event['tanggal_mulai'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fa fa-calendar-check-o"></i>
                                    <strong>Tanggal Selesai:</strong><br>
                                    <?= date('d F Y', strtotime($event['tanggal_selesai'])) ?>
                                </p>
                            </div>
                        </div>
                        <p class="mb-2">
                            <i class="fa fa-map-marker"></i>
                            <strong>Lokasi:</strong><br>
                            <?= htmlspecialchars($event['alamat']) ?>
                        </p>
                        <p class="mb-3">
                            <i class="fa fa-info-circle"></i>
                            <strong>Status:</strong>
                            <span class="badge <?= $event['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($event['status']) ?>
                            </span>
                        </p>
                    </div>

                    <div class="event-description">
                        <h4 class="mb-3">Deskripsi Event</h4>
                        <div class="content-text">
                            <?= nl2br(htmlspecialchars($event['deskripsi'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title h5 mb-4">Lokasi Event</h4>
                    <div id="map" class="map-container mb-4"></div>
                    
                    <!-- Share buttons -->
                    <h5 class="mb-3">Bagikan Event</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                           class="btn btn-primary btn-sm flex-grow-1" target="_blank">
                            <i class="fa fa-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($event['nama_event']) ?>" 
                           class="btn btn-info btn-sm flex-grow-1" target="_blank">
                            <i class="fa fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode($event['nama_event'] . ' - ' . $_SERVER['REQUEST_URI']) ?>" 
                           class="btn btn-success btn-sm flex-grow-1" target="_blank">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-container {
    width: 100%;
    max-height: 400px;
    overflow: hidden;
    position: relative;
    border-radius: 8px;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.map-container {
    height: 300px;
    border-radius: 8px;
    overflow: hidden;
}

.event-details i {
    width: 25px;
    color: #666;
}

.content-text {
    line-height: 1.8;
    color: #333;
}

.badge {
    padding: 0.5em 1em;
    border-radius: 4px;
}

.btn-sm {
    padding: 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .image-container {
        max-height: 300px;
    }
    
    .map-container {
        height: 250px;
    }
}
</style>

<!-- Initialize Leaflet Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([<?= $event['latitude'] ?>, <?= $event['longitude'] ?>])
     .addTo(map)
     .bindPopup("<?= htmlspecialchars($event['nama_event']) ?>");
});
</script>

<?php include_once "footer.php"; ?>