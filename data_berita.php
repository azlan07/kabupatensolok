<?php
// Title
$title = "Daftar Berita";

// Header dan Koneksi
include_once "header.php";
include_once "koneksi.php";

// Pagination setup
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $items_per_page;

// Query untuk menghitung total berita
$total_query = "SELECT COUNT(*) as total FROM berita";
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $items_per_page);

// Query untuk mengambil berita dengan pagination
$query = "SELECT berita.*, admin.nama as nama_admin 
          FROM berita 
          JOIN admin ON berita.id_admin = admin.id 
          ORDER BY tanggal_posting DESC 
          LIMIT ?, ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $start, $items_per_page);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="">
    <div class="panel panel-info panel-dashboard">
        <div class="panel-heading centered">
            <h2 class="panel-title"><strong> - <?php echo $title ?> - </strong></h2>
        </div>
    </div>

    <!-- Baris Pertama -->
    <div class="row mb-4" style="margin-bottom: 8px;">
        <?php
        $count = 0;
        while ($berita = mysqli_fetch_assoc($result)) {
            if ($count < 5) {
        ?>
                <div class="col-md-15th mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Gambar -->
                        <div class="image-container">
                            <?php if (!empty($berita['foto_berita'])) { ?>
                                <img src="admin/uploads/berita/<?php echo $berita['foto_berita']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($berita['judul_berita']); ?>">
                            <?php } else { ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Konten -->
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $judul = explode(' ', $berita['judul_berita']);
                                $judul_terpotong = implode(' ', array_slice($judul, 0, 4));
                                echo htmlspecialchars($judul_terpotong) . (count($judul) > 4 ? '...' : '');
                                ?>
                            </h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($berita['nama_admin']); ?> |
                                    <i class="fa fa-calendar"></i> <?php echo date('d F Y', strtotime($berita['tanggal_posting'])); ?>
                                </small>
                            </p>
                            <a href="detail_berita.php?id=<?php echo $berita['id_berita']; ?>" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-newspaper-o"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
            $count++;
        }
        mysqli_data_seek($result, 5);
        ?>
    </div>

    <!-- Baris Kedua -->
    <div class="row mb-4">
        <?php
        $count = 0;
        while ($berita = mysqli_fetch_assoc($result)) {
            if ($count < 5) {
        ?>
                <div class="col-md-15th mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Gambar -->
                        <div class="image-container">
                            <?php if (!empty($berita['foto_berita'])) { ?>
                                <img src="admin/uploads/berita/<?php echo $berita['foto_berita']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($berita['judul_berita']); ?>">
                            <?php } else { ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Konten -->
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $judul = explode(' ', $berita['judul_berita']);
                                $judul_terpotong = implode(' ', array_slice($judul, 0, 4));
                                echo htmlspecialchars($judul_terpotong) . (count($judul) > 4 ? '...' : '');
                                ?>
                            </h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($berita['nama_admin']); ?> |
                                    <i class="fa fa-calendar"></i> <?php echo date('d F Y', strtotime($berita['tanggal_posting'])); ?>
                                </small>
                            </p>
                            <a href="detail_berita.php?id=<?php echo $berita['id_berita']; ?>" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-newspaper-o"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
            $count++;
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- CSS untuk Layout -->
<style>
    .image-container {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .col-md-15th {
        width: 20%;
        float: left;
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (max-width: 992px) {
        .col-md-15th {
            width: 33.33333333%;
        }
    }

    @media (max-width: 768px) {
        .col-md-15th {
            width: 50%;
        }
    }

    @media (max-width: 480px) {
        .col-md-15th {
            width: 100%;
        }
    }
</style>

<?php include_once "footer.php"; ?>