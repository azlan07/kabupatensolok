<?php include "header.php"; ?>
<?php
$id = $_GET['id_fasilitas'];
include_once "ambildata_id_fasilitas.php";
$obj = json_decode($data);
$id_fasilitas = "";
$nama_fasilitas = "";
$alamat = "";
$lat = "";
$long = "";
$harga = "";
$detail = "";
$banyak_pengunjung = "";
foreach ($obj->results as $item) {
    $id_fasilitas .= $item->id_fasilitas;
    $nama_fasilitas .= $item->nama_fasilitas;
    $alamat .= $item->alamat;
    $lat .= $item->latitude;
    $long .= $item->longitude;
    $harga .= $item->harga;
    $detail .= $item->detail;
    $banyak_pengunjung .= $item->banyak_pengunjung;
}

$title = "Detail dan Lokasi : " . $nama_fasilitas;
?>

<!-- Tambahkan CSS Swiper -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<!-- Ganti script Google Maps dengan Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Hapus script Google Maps yang lama dan ganti dengan script Leaflet -->
<script>
    function initialize() {
        // Create map instance
        var map = L.map('map-canvas').setView([<?php echo $lat ?>, <?php echo $long ?>], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create custom marker icon
        var customIcon = L.icon({
            iconUrl: 'img/markermap.png',
            iconSize: [82, 82],
            iconAnchor: [41, 41],
            popupAnchor: [0, -41]
        });

        // Add marker with custom icon
        var marker = L.marker([<?php echo $lat ?>, <?php echo $long ?>], {
            icon: customIcon
        }).addTo(map);

        // Create popup content
        var popupContent = '<div id="content">' +
            '<div id="siteNotice">' +
            '</div>' +
            '<h3><?php echo $nama_fasilitas ?></h3>' +
            '<div id="bodyContent">' +
            '<p><?php echo $alamat ?></p>' +
            '</div>' +
            '</div>';

        // Bind popup to marker
        marker.bindPopup(popupContent);
    }

    // Initialize map when DOM is loaded
    document.addEventListener('DOMContentLoaded', initialize);
</script>

<!-- CSS Tambahan -->
<style>
    .swiper {
        width: 100%;
        height: 400px;
        margin-bottom: 20px;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-box {
        background: #f8f9fc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .info-box h4 {
        color: #4e73df;
        margin-bottom: 10px;
    }

    #map-canvas {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .leaflet-popup-content h3 {
        margin: 0 0 10px 0;
        color: #4e73df;
        font-size: 1.2em;
    }

    .leaflet-popup-content p {
        margin: 0;
        color: #666;
    }

    .info-timestamp {
        font-size: 0.8em;
        color: #858796;
        margin-top: 10px;
        text-align: right;
    }
</style>

<!-- Script Google Maps dan lainnya tetap sama seperti sebelumnya -->

<!-- start banner Area -->
<section class="about-banner relative">
    <div class="overlay overlay-bg"></div>
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">Detail Informasi Geografis Wisata</h1>
            </div>
        </div>
    </div>
</section>

<!-- Start about-info Area -->
<section class="about-info-area section-gap">
    <div class="container" style="padding-top: 20px;">
        <!-- Tambahkan Swiper untuk Foto -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="panel panel-info panel-dashboard">
                    <div class="panel-heading centered">
                        <h2 class="panel-title"><strong>Galeri Foto</strong></h2>
                    </div>
                    <div class="panel-body">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <?php if (!empty($item->foto_fasilitas1)): ?>
                                    <div class="swiper-slide">
                                        <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas1; ?>" alt="Foto 1">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item->foto_fasilitas2)): ?>
                                    <div class="swiper-slide">
                                        <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas2; ?>" alt="Foto 2">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item->foto_fasilitas3)): ?>
                                    <div class="swiper-slide">
                                        <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas3; ?>" alt="Foto 3">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-box">
                    <h4>Harga</h4>
                    <p class="h5">Rp. <?php echo number_format($harga, 0, ',', '.'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <h4>Banyak Pengunjung per-Bulan</h4>
                    <p class="h5"><?php echo number_format($banyak_pengunjung); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <h4>Detail Informasi</h4>
                    <p><?php echo nl2br($detail); ?></p>
                </div>
            </div>
        </div>

        <!-- Konten yang sudah ada -->
        <div class="row">
            <div class="col-md-7" data-aos="fade-up" data-aos-delay="200">
                <!-- Panel informasi wisata tetap sama -->
                <div class="panel panel-info panel-dashboard">
                    <div class="panel-heading centered">
                        <h2 class="panel-title"><strong>Informasi Wisata</strong></h2>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>Detail</th>
                            </tr>
                            <tr>
                                <td>Nama Wisata</td>
                                <td>
                                    <h5><?php echo $nama_fasilitas ?></h5>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>
                                    <h5><?php echo $alamat ?></h5>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-5" data-aos="zoom-in">
                <!-- Panel map tetap sama -->
                <div class="panel panel-info panel-dashboard">
                    <div class="panel-heading centered">
                        <h2 class="panel-title"><strong>Lokasi</strong></h2>
                    </div>
                    <div class="panel-body">
                        <div id="map-canvas" style="width:100%;height:380px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tambahkan Script Swiper -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
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
</script>

<?php include_once "footer.php"; ?>