<?php
// Title
$title = "Daftar Berita";

// Header dan Koneksi
include_once "header.php";
include_once "koneksi.php";

// Pagination setup
$limit = 9; // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk menghitung total berita
$total_query = "SELECT COUNT(*) as total FROM berita";
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

// Query untuk mengambil berita dengan pagination
$query = "SELECT berita.*, admin.nama as nama_admin 
          FROM berita 
          JOIN admin ON berita.id_admin = admin.id 
          ORDER BY tanggal_posting DESC 
          LIMIT ?, ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .berita-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .berita-card:hover {
            transform: translateY(-5px);
        }
        .berita-image {
            height: 200px;
            object-fit: cover;
        }
        .page-header {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <!-- Include your header/navbar here -->

    <div class="page-header">
        <div class="container">
            <h1>Berita Terbaru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Berita</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            <?php while ($berita = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4">
                <div class="card berita-card">
                    <img src="admin/uploads/berita/<?= $berita['foto_berita'] ?>" 
                         class="card-img-top berita-image" 
                         alt="<?= htmlspecialchars($berita['judul_berita']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($berita['judul_berita']) ?></h5>
                        <p class="card-text text-muted small">
                            <i class="fa fa-user"></i> <?= htmlspecialchars($berita['nama_admin']) ?> |
                            <i class="fa fa-calendar"></i> <?= date('d F Y', strtotime($berita['tanggal_posting'])) ?>
                        </p>
                        <p class="card-text">
                            <?= substr(strip_tags($berita['isi_berita']), 0, 150) ?>...
                        </p>
                        <a href="detail_berita.php?id=<?= $berita['id_berita'] ?>" 
                           class="btn btn-primary">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= ($page - 1) ?>">Previous</a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= ($page + 1) ?>">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <!-- Include your footer here -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>