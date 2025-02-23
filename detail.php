<?php include "header.php"; ?>
<?php
$id = $_GET['id_wisata'];
include_once "ambildata_id.php";
$obj = json_decode($data);
$id_wisata = "";
$nama_wisata = "";
$alamat = "";
$deskripsi = "";
$harga_tiket = "";
$lat = "";
$long = "";
foreach ($obj->results as $item) {
  $id_wisata .= $item->id_wisata;
  $nama_wisata .= $item->nama_wisata;
  $alamat .= $item->alamat;
  $deskripsi .= $item->deskripsi;
  $harga_tiket .= $item->harga_tiket;
  $lat .= $item->latitude;
  $long .= $item->longitude;
}

$title = "Detail dan Lokasi : " . $nama_wisata;
?>

<style>
  .image-container {
    display: flex;
    justify-content: center;
    /* Untuk memusatkan secara horizontal */
    align-items: center;
    /* Untuk memusatkan secara vertikal */
    height: 500px;
    /* Atur tinggi kontainer jika perlu */
  }

  .custom-img-style {
    height: 500px;
    object-fit: cover;
  }
</style>

<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<!-- Add Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

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
      iconAnchor: [16, 32],
      popupAnchor: [0, -32]
    });

    // Add marker
    var marker = L.marker([<?php echo $lat ?>, <?php echo $long ?>], {
      icon: customIcon
    }).addTo(map);

    // Create popup content
    var popupContent = '<div id="content">' +
      '<div id="siteNotice">' +
      '</div>' +
      '<h3><?php echo $nama_wisata ?></h3>' +
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

<!-- start banner Area -->
<section class="about-banner relative">
  <div class="overlay overlay-bg"></div>
  <div class="container">
    <div class="row d-flex align-items-center justify-content-center">
      <div class="about-content col-lg-12">
        <h1 class="text-white">
          Detail Informasi Wisata
        </h1>
      </div>
    </div>
  </div>
</section>
<!-- End banner Area -->

<!-- Start about-info Area -->
<section class="about-info-area section-gap">
  <div class="container" style="padding-top: 20px;">
    <div class="row">
      <div class="col-md-12" data-aos="fade-up" data-aos-delay="200">
        <div class="panel panel-info panel-dashboard">
          <div class="panel-heading centered">
            <h2 class="panel-title"><strong>Foto Wisata </strong></h4>
          </div>
          <div class="panel-body">
            <div class="image-container">
              <img src="uploads/wisata/<?php echo $item->foto_wisata; ?>" class="card-img-top custom-img-style" alt="<?php echo $item->nama_wisata; ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-7" data-aos="fade-up" data-aos-delay="200">
        <div class="panel panel-info panel-dashboard">
          <div class="panel-heading centered">
            <h2 class="panel-title"><strong>Informasi Wisata </strong></h4>
          </div>
          <div class="panel-body">
            <table class="table">
              <tr>
                <th>Detail</th>
              </tr>
              <tr>
                <td>Nama Wisata</td>
                <td>
                  <h5><?php echo $nama_wisata ?></h5>
                </td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>
                  <h5><?php echo $alamat ?></h5>
                </td>
              </tr>
              <tr>
                <td>Deskripsi</td>
                <td>
                  <h5><?php echo $deskripsi ?></h5>
                </td>
              </tr>
              <tr>
                <td>Harga Tiket</td>
                <td>
                  <h5>Rp. <?php echo $harga_tiket ?></h5>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-5" data-aos="zoom-in">
        <div class="panel panel-info panel-dashboard">
          <div class="panel-heading centered">
            <h2 class="panel-title"><strong>Lokasi</strong></h4>
          </div>
          <div class="panel-body">
            <div id="map-canvas" style="width:100%;height:380px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End about-info Area -->

<?php include_once "footer.php"; ?>