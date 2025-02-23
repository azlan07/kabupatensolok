<?php
$title = "SIG WISATA KABUPATEN SOLOK";
include "header.php";
?>
<style>
  .swiper-wrapper {
    height: 450px;
  }

  /* Mengatur gambar di dalam slide */
  .swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1.5s ease-in-out;
    /* Animasi zoom */
  }

  /* Efek Zoom In */
  .swiper-slide-active img {
    transform: scale(1.1);
    /* Zoom in */
  }

  .swiper-slide-next img,
  .swiper-slide-prev img {
    transform: scale(1);
    /* Ukuran normal */
  }

  /* Gambar Tengah Carousel */
  .center-logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
  }

  .center-logo img {
    max-width: 600px;
    /* Atur ukuran logo */
    height: auto;
    opacity: 0.9;
    transition: opacity 0.3s ease-in-out;
  }

  .center-logo img:hover {
    opacity: 1;
    /* Tambahkan efek hover */
  }

  .custom-img-style {
    height: 200px;
    object-fit: cover;
  }

  /* Tambahkan CSS ini di bagian atas atau file CSS terpisah */
  .panel-dashboard {
    margin-bottom: 30px;
  }

  .image-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
    border-radius: 8px 8px 0 0;
  }

  .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .card {
    transition: transform 0.2s;
    margin-bottom: 20px;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    height: 2.4rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    white-space: normal;
  }

  .card-text.description {
    height: 3em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .view-all-btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #f8f9fa;
    color: #007bff;
    text-decoration: none;
    text-align: center;
    border-radius: 4px;
    transition: background-color 0.2s;
    margin-top: 15px;
  }

  .view-all-btn:hover {
    background-color: #e9ecef;
    color: #0056b3;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info panel-dashboard">
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <!-- Slide 1 -->
          <div class="swiper-slide">
            <img src="img/SolokPic/1.jpg" alt="Slide 1">
          </div>
          <!-- Slide 2 -->
          <div class="swiper-slide">
            <img src="img/SolokPic/2.jpg" alt="Slide 2">
          </div>
          <!-- Slide 3 -->
          <div class="swiper-slide">
            <img src="img/SolokPic/3.jpg" alt="Slide 3">
          </div>
        </div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Gambar Tengah -->
        <div class="center-logo">
          <img src="img/SolokPic/logop-768x259.png" alt="Logo Kabupaten Solok">
        </div>
      </div>
    </div>

    <div class="panel panel-info panel-dashboard">
      <div class="panel-heading centered">
        <h2 class="panel-title"><strong> - Welcome Message - </strong></h2>
      </div>
      <!-- <div>
        <h1 style="text-align:center">Selamat Datang</h1>
        <img src="" alt="" srcset="">
      </div> -->
      <div class="panel-body">
        <div class="centered">
          <h4>Sistem informasi ini merupakan aplikasi pemetaan geografis tempat wisata di wilayah Kabupaten Solok.
            Aplikasi ini memuat informasi dan lokasi dari tempat wisata di Kabupaten Solok.</h4>
          <h4>Silakan memilih menu diatas untuk melanjutkan.</h4>
        </div>
      </div>
    </div>

    <div class="panel panel-info panel-dashboard">
      <div class="panel-heading centered">
        <h2 class="panel-title"><strong> - Preview Wisata - </strong></h2>
      </div>

      <div class="panel-body">
        <div class="row">
          <?php
          include "koneksi.php";

          // Query untuk mengambil 5 data wisata
          $query = "SELECT * FROM wisata LIMIT 3";
          $result = mysqli_query($koneksi, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                  <?php if (!empty($row['foto_wisata'])) { ?>
                    <img src="uploads/wisata/<?php echo $row['foto_wisata']; ?>" class="card-img-top" alt="<?php echo $row['nama_wisata']; ?>" style="height: 200px; object-fit: cover;">
                  <?php } else { ?>
                    <div class="bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                      <i class="fa fa-image fa-3x text-muted"></i>
                    </div>
                  <?php } ?>
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nama_wisata']; ?></h5>
                    <p class="card-text">
                      <small class="text-muted"><i class="fa fa-map-marker"></i>
                        <?php
                        // Menampilkan hanya 4 kata dari alamat
                        $alamat = explode(' ', $row['alamat']);
                        $alamat_terpotong = implode(' ', array_slice($alamat, 0, 4));
                        echo $alamat_terpotong . (count($alamat) > 4 ? '...' : '');
                        ?>
                      </small><br>
                      <strong class="text-primary">Rp. <?php echo number_format($row['harga_tiket'], 0, ',', '.'); ?></strong>
                    </p>
                    <a href="detail.php?id_wisata=<?php echo $row['id_wisata']; ?>" class="btn btn-primary btn-sm w-100">
                      <i class="fa fa-map-marker"></i> Detail dan Lokasi
                    </a>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<div class='col-12 text-center'><p>Data wisata tidak ditemukan.</p></div>";
          }
          ?>
        </div>
        <a href="data_wisata.php" class="view-all-btn">
          Lihat Semua Wisata <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <div class="panel panel-info panel-dashboard">
      <div class="panel-heading centered">
        <h2 class="panel-title"><strong> - Preview Fasilitas Wisata - </strong></h2>
      </div>

      <div class="panel-body">
        <div class="row">
          <?php
          include "koneksi.php";

          // Query untuk mengambil 5 data wisata
          $query = "SELECT * FROM fasilitas LIMIT 3";
          $result = mysqli_query($koneksi, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <div class="col-md-4 col-sm-6 col-12 mb-4">
                <div class="card h-100">
                  <?php if (!empty($row['foto_fasilitas1'])) { ?>
                    <img src="uploads/fasilitas/<?php echo $row['foto_fasilitas1']; ?>" class="card-img-top custom-img-style" alt="<?php echo $row['nama_fasilitas']; ?>">
                  <?php } else { ?>
                    <div class="bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                      <i class="fa fa-image fa-3x text-muted"></i>
                    </div>
                  <?php } ?>
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nama_fasilitas']; ?></h5>
                    <p class="card-text">
                      <small class="text-muted"><i class="fa fa-map-marker"></i>
                        <?php
                        // Menampilkan hanya 4 kata dari alamat
                        $alamat = explode(' ', $row['alamat']);
                        $alamat_terpotong = implode(' ', array_slice($alamat, 0, 4));
                        echo $alamat_terpotong . (count($alamat) > 4 ? '...' : '');
                        ?>
                      </small><br>
                    </p>
                    <a href="detailfasilitas.php?id_fasilitas=<?php echo $row['id_fasilitas']; ?>" class="btn btn-primary btn-sm w-100">
                      <i class="fa fa-map-marker"></i> Detail dan Lokasi
                    </a>
                  </div>
                </div>
              </div>

          <?php
            }
          } else {
            echo "<div class='col-12 text-center'><p>Data fasilitas wisata tidak ditemukan.</p></div>";
          }
          ?>
        </div>
        <a href="data_fasilitas.php" class="view-all-btn">
          Lihat Semua Fasilitas <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- Panel Event -->
    <div class="panel panel-info panel-dashboard">
      <div class="panel-heading centered">
        <h2 class="panel-title"><strong> - Event Terbaru - </strong></h2>
      </div>

      <div class="panel-body">
        <div class="row mb-4">
          <?php
          $query_event = "SELECT * FROM event ORDER BY tanggal_mulai DESC LIMIT 4";
          $result_event = mysqli_query($koneksi, $query_event);

          while ($event = mysqli_fetch_assoc($result_event)) {
          ?>
            <div class="col-md-3 mb-4">
              <div class="card h-100">
                <div class="image-container">
                  <?php if (!empty($event['foto_event'])) { ?>
                    <img src="admin/uploads/events/<?= $event['foto_event'] ?>" alt="<?= htmlspecialchars($event['nama_event']) ?>">
                  <?php } else { ?>
                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                      <i class="fa fa-image fa-3x text-muted"></i>
                    </div>
                  <?php } ?>
                </div>
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($event['nama_event']) ?></h5>
                  <p class="card-text">
                    <small class="text-muted">
                      <i class="fa fa-calendar"></i>
                      <?= date('d M Y', strtotime($event['tanggal_mulai'])) ?> -
                      <?= date('d M Y', strtotime($event['tanggal_selesai'])) ?>
                    </small>
                  </p>
                  <p class="card-text description"><?= strip_tags($event['deskripsi']) ?></p>
                  <a href="detail_event.php?id=<?= $event['id_event'] ?>" class="btn btn-sm btn-primary w-100">
                    <i class="fa fa-arrow-right"></i> Selengkapnya
                  </a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <a href="data_event.php" class="view-all-btn">
          Lihat Semua Event <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>

    <!-- Panel Berita -->
    <div class="panel panel-info panel-dashboard">
      <div class="panel-heading centered">
        <h2 class="panel-title"><strong> - Berita Terbaru - </strong></h2>
      </div>

      <div class="panel-body">
        <div class="row">
          <?php
          $query_berita = "SELECT berita.*, admin.nama as nama_admin 
                            FROM berita 
                            JOIN admin ON berita.id_admin = admin.id 
                            ORDER BY tanggal_posting DESC LIMIT 4";
          $result_berita = mysqli_query($koneksi, $query_berita);

          while ($berita = mysqli_fetch_assoc($result_berita)) {
          ?>
            <div class="col-md-3 mb-4">
              <div class="card h-100">
                <div class="image-container">
                  <?php if (!empty($berita['foto_berita'])) { ?>
                    <img src="admin/uploads/berita/<?= $berita['foto_berita'] ?>" alt="<?= htmlspecialchars($berita['judul_berita']) ?>">
                  <?php } else { ?>
                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                      <i class="fa fa-image fa-3x text-muted"></i>
                    </div>
                  <?php } ?>
                </div>
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($berita['judul_berita']) ?></h5>
                  <p class="card-text">
                    <small class="text-muted">
                      <i class="fa fa-user"></i> <?= htmlspecialchars($berita['nama_admin']) ?> |
                      <i class="fa fa-clock-o"></i> <?= date('d M Y', strtotime($berita['tanggal_posting'])) ?>
                    </small>
                  </p>
                  <p class="card-text description"><?= strip_tags($berita['isi_berita']) ?></p>
                  <a href="detail_berita.php?id=<?= $berita['id_berita'] ?>" class="btn btn-sm btn-primary w-100">
                    <i class="fa fa-arrow-right"></i> Selengkapnya
                  </a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <a href="data_berita.php" class="view-all-btn">
          Lihat Semua Berita <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>

  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".mySwiper", {
      loop: true, // Membuat slide berputar
      autoplay: {
        delay: 2500, // Interval waktu otomatis (1,5 detik)
        disableOnInteraction: false, // Tetap otomatis meski pengguna berinteraksi
      },
      effect: "slide", // Efek pergerakan antar slide
      pagination: {
        el: ".swiper-pagination",
        clickable: true, // Pagination dapat diklik
      },
    });
  });
</script>

<?php include_once "footer.php"; ?>