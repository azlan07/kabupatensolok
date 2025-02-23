<?php
// Title
$title = "Daftar Berita";
include 'koneksi.php';

$query = "SELECT * FROM event ORDER BY tanggal_mulai DESC";
$result = mysqli_query($koneksi, $query);

// Pagination setup
$items_per_page = 6; // Jumlah item per halaman
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $items_per_page;

// Query untuk menghitung total event
$total_query = "SELECT COUNT(*) as total FROM event";
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $items_per_page);

// Query untuk mengambil event dengan pagination
$query = "SELECT * FROM event ORDER BY tanggal_mulai DESC LIMIT ?, ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $start, $items_per_page);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<?php include_once "header.php"; ?>

<div class="">
    <div class="panel panel-info panel-dashboard">
        <div class="panel-heading centered">
            <h2 class="panel-title"><strong> - <?php echo $title ?> - </strong></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-4 mb-4" style="margin-bottom: 8px;">
                <div class="card h-100 shadow-sm">
                    <!-- Gambar -->
                    <div class="image-container">
                        <?php if (!empty($row['foto_event'])) { ?>
                            <img src="./admin/uploads/events/<?php echo $row['foto_event']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nama_event']); ?>">
                        <?php } else { ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php } ?>
                        <div class="status-badge">
                            <span class="badge <?= $row['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </div>
                    </div>
                    <!-- Konten -->
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['nama_event']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fa fa-calendar"></i>
                                <?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> -
                                <?= date('d M Y', strtotime($row['tanggal_selesai'])) ?>
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fa fa-map-marker"></i> <?= htmlspecialchars($row['alamat']) ?>
                            </small>
                        </p>
                        <a href="detail_event.php?id=<?php echo $row['id_event']; ?>" class="btn btn-primary btn-sm w-100" style="padding: 8px; font-size: x-small;">
                            <i class="fa fa-flag-o"></i> Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="row">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
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
    <?php endif; ?>
</div>

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

    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }

    .card {
        transition: transform 0.2s;
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
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            margin-bottom: 1rem;
        }
    }

    /* Pagination styles */
    .pagination {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .page-link {
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            margin-bottom: 1rem;
        }
    }
</style>

<?php include_once "footer.php"; ?>