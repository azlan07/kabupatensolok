<?php
// Title
$title = "Detail Berita";

// Header dan Koneksi
include_once "header.php";
include_once "koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_berita = $_GET['id'];

// Query untuk mengambil detail berita
$query = "SELECT berita.*, admin.nama as nama_admin 
          FROM berita 
          JOIN admin ON berita.id_admin = admin.id 
          WHERE berita.id_berita = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_berita);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$berita = mysqli_fetch_assoc($result);

if (!$berita) {
    header("Location: index.php");
    exit();
}

// Query untuk berita terkait
$query_terkait = "SELECT * FROM berita WHERE id_berita != ? ORDER BY tanggal_posting DESC LIMIT 3";
$stmt_terkait = mysqli_prepare($koneksi, $query_terkait);
mysqli_stmt_bind_param($stmt_terkait, "i", $id_berita);
mysqli_stmt_execute($stmt_terkait);
$result_terkait = mysqli_stmt_get_result($stmt_terkait);
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="panel panel-info panel-dashboard">
                <div class="panel-heading centered">
                    <h2 class="panel-title"><strong> - <?php echo $title ?> - </strong></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title h3 mb-3"><?= htmlspecialchars($berita['judul_berita']) ?></h1>

                    <div class="text-muted mb-4" style="margin-bottom: 6px;">
                        <span class="me-3">
                            <i class="fa fa-user"></i> <?= htmlspecialchars($berita['nama_admin']) ?>
                        </span>
                        <span class="me-3">
                            <i class="fa fa-calendar"></i> <?= date('d F Y H:i', strtotime($berita['tanggal_posting'])) ?>
                        </span>
                    </div>

                    <div class="image-container mb-4">
                        <?php if (!empty($berita['foto_berita'])) { ?>
                            <img src="admin/uploads/berita/<?= $berita['foto_berita'] ?>"
                                alt="<?= htmlspecialchars($berita['judul_berita']) ?>"
                                class="img-fluid rounded">
                        <?php } else { ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="berita-content">
                        <?= $berita['isi_berita'] ?>
                    </div>

                    <!-- Share buttons -->
                    <div class="mt-4 pt-4 border-top">
                        <h5>Bagikan Berita</h5>
                        <div class="d-flex gap-2 mt-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa fa-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($berita['judul_berita']) ?>"
                                class="btn btn-info btn-sm" target="_blank">
                                <i class="fa fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text=<?= urlencode($berita['judul_berita'] . ' - ' . $_SERVER['REQUEST_URI']) ?>"
                                class="btn btn-success btn-sm" target="_blank">
                                <i class="fa fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title h5 mb-4">Berita Terkait</h4>
                    <?php while ($berita_terkait = mysqli_fetch_assoc($result_terkait)): ?>
                        <div class="card mb-3" style="margin-bottom: 8px;">
                            <div class="image-container" style="height: 150px;">
                                <?php if (!empty($berita_terkait['foto_berita'])) { ?>
                                    <img src="admin/uploads/berita/<?= $berita_terkait['foto_berita'] ?>"
                                        alt="<?= htmlspecialchars($berita_terkait['judul_berita']) ?>"
                                        class="img-fluid w-100 h-100 object-fit-cover">
                                <?php } else { ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fa fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title h6">
                                    <?php
                                    $judul = explode(' ', $berita_terkait['judul_berita']);
                                    $judul_terpotong = implode(' ', array_slice($judul, 0, 4));
                                    echo htmlspecialchars($judul_terpotong) . (count($judul) > 4 ? '...' : '');
                                    ?>
                                </h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fa fa-calendar"></i>
                                        <?= date('d F Y', strtotime($berita_terkait['tanggal_posting'])) ?>
                                    </small>
                                </p>
                                <a href="detail_berita.php?id=<?= $berita_terkait['id_berita'] ?>"
                                    class="btn btn-primary btn-sm w-100">
                                    <i class="fa fa-newspaper-o"></i> Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-container {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .berita-content {
        font-size: 16px;
        line-height: 1.8;
    }

    .berita-content img {
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
    }

    @media (max-width: 768px) {
        .image-container {
            height: 200px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentImages = document.querySelectorAll('.berita-content img');
        contentImages.forEach(img => {
            img.classList.add('img-fluid', 'rounded');
        });
    });
</script>

<?php include_once "footer.php"; ?>