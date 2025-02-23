<?php
$title = "SIG WISATA KABUPATEN SOLOK";
include "header.php";
?>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info panel-dashboard">
                <div class="panel-heading centered">
                    <h2 class="panel-title"><strong> - Peta Lokasi Wisata - </strong></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Div untuk menampilkan peta -->
    <div id="map" style="width: 100%; height: 480px;"></div>

    <!-- Menyertakan Library Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script type="text/javascript">
        // Inisialisasi peta
        var map = L.map('map').setView([-0.9632193533203104, 100.78149209828896], 10); // Koordinat pusat awal

        // Tambahkan tile layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Data lokasi wisata dari database (menggunakan PHP untuk men-generate JSON)
        var wisataLocations = [
            <?php
            $data = file_get_contents('http://localhost/KABUPATENSOLOK/ambildata.php');
            if (json_decode($data, true)) {
                $obj = json_decode($data);
                foreach ($obj->results as $item) {
                    echo "{ 
                        id: '{$item->id_wisata}', 
                        nama: '{$item->nama_wisata}', 
                        alamat: '{$item->alamat}', 
                        lat: {$item->latitude}, 
                        lng: {$item->longitude} 
                    },";
                }
            }
            ?>
        ];

        // Tambahkan marker ke peta
        wisataLocations.forEach(function(location) {
            var marker = L.marker([location.lat, location.lng]).addTo(map);

            // Popup untuk marker
            marker.bindPopup(
                `<strong>${location.nama}</strong><br>
                 ${location.alamat}<br>
                 <a href='detail.php?id_wisata=${location.id}' target='_blank'>Info Detail</a>`
            );
        });
    </script>
</section>
<?php include_once "footer.php"; ?>
