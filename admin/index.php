<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:../tampil_data.php?pesan=belum_login");
}
include "../koneksi.php";

// Query untuk data yang sudah ada
$query_wisata = "SELECT COUNT(*) AS total_wisata FROM wisata";
$result_wisata = mysqli_query($koneksi, $query_wisata);
$row_wisata = mysqli_fetch_assoc($result_wisata);
$total_wisata = $row_wisata['total_wisata'];

$query_fasilitas = "SELECT COUNT(*) AS total_fasilitas FROM fasilitas";
$result_fasilitas = mysqli_query($koneksi, $query_fasilitas);
$row_fasilitas = mysqli_fetch_assoc($result_fasilitas);
$total_fasilitas = $row_fasilitas['total_fasilitas'];

// Query untuk event aktif dan total
$query_event_aktif = "SELECT COUNT(*) AS total_event_aktif FROM event WHERE status = 'aktif'";
$result_event_aktif = mysqli_query($koneksi, $query_event_aktif);
$row_event_aktif = mysqli_fetch_assoc($result_event_aktif);
$total_event_aktif = $row_event_aktif['total_event_aktif'];

$query_event_total = "SELECT COUNT(*) AS total_event FROM event";
$result_event_total = mysqli_query($koneksi, $query_event_total);
$row_event_total = mysqli_fetch_assoc($result_event_total);
$total_event = $row_event_total['total_event'];

// Query untuk berita
$query_berita = "SELECT COUNT(*) AS total_berita FROM berita";
$result_berita = mysqli_query($koneksi, $query_berita);
$row_berita = mysqli_fetch_assoc($result_berita);
$total_berita = $row_berita['total_berita'];

// Query untuk event terbaru
$query_latest_events = "SELECT nama_event, tanggal_mulai, tanggal_selesai, status 
                       FROM event 
                       ORDER BY tanggal_mulai DESC 
                       LIMIT 5";
$result_latest_events = mysqli_query($koneksi, $query_latest_events);

// Query untuk berita terbaru
$query_latest_news = "SELECT judul_berita, tanggal_posting 
                     FROM berita 
                     ORDER BY tanggal_posting DESC 
                     LIMIT 5";
$result_latest_news = mysqli_query($koneksi, $query_latest_news);
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body id="page-top">
    <div id="wrapper">
        <?php include "menu_sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "menu_topbar.php"; ?>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Cards Row -->
                    <div class="row">
                        <!-- Wisata Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Wisata</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_wisata; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fasilitas Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Fasilitas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_fasilitas; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Event Aktif</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_event_aktif; ?></div>
                                            <div class="text-xs text-gray-600">Total: <?php echo $total_event; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Berita Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Berita</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_berita; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Statistik Data</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="dataChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-xl-4 col-lg-5">
                            <!-- Event List -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">Event Terbaru</h6>
                                </div>
                                <div class="card-body">
                                    <?php while($event = mysqli_fetch_assoc($result_latest_events)): ?>
                                    <div class="mb-3">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                            <?php echo $event['nama_event']; ?>
                                        </div>
                                        <div class="text-xs mb-0 text-gray-800">
                                            <?php echo date('d M Y', strtotime($event['tanggal_mulai'])); ?> - 
                                            <?php echo date('d M Y', strtotime($event['tanggal_selesai'])); ?>
                                        </div>
                                        <span class="badge badge-<?php echo $event['status'] == 'aktif' ? 'success' : 'secondary'; ?>">
                                            <?php echo $event['status']; ?>
                                        </span>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>

                            <!-- News List -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-warning">Berita Terbaru</h6>
                                </div>
                                <div class="card-body">
                                    <?php while($news = mysqli_fetch_assoc($result_latest_news)): ?>
                                    <div class="mb-3">
                                        <div class="text-xs font-weight-bold mb-1">
                                            <?php echo $news['judul_berita']; ?>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            <?php echo date('d M Y H:i', strtotime($news['tanggal_posting'])); ?>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
    const ctx = document.getElementById('dataChart').getContext('2d');
    const dataChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Wisata', 'Fasilitas', 'Event Aktif', 'Total Event', 'Berita'],
            datasets: [{
                label: 'Jumlah Data',
                data: [
                    <?php echo $total_wisata; ?>, 
                    <?php echo $total_fasilitas; ?>,
                    <?php echo $total_event_aktif; ?>,
                    <?php echo $total_event; ?>,
                    <?php echo $total_berita; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(36, 135, 202, 0.7)',
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(246, 194, 62, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Math.floor(value);
                        }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>