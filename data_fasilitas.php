<?php
// Title
$title = "Daftar Data Fasilitas";

// Header dan Koneksi
include_once "header.php";
include_once "koneksi.php";

// Menyiapkan data untuk JSON
$Q = mysqli_query($koneksi, "SELECT * FROM fasilitas ORDER BY id_fasilitas DESC"); // Mengurutkan secara descending berdasarkan kolom id
if ($Q) {
    $posts = array();
    if (mysqli_num_rows($Q)) {
        while ($post = mysqli_fetch_assoc($Q)) {
            $posts[] = $post;
        }
    }
    $data_json = json_encode(array('results' => $posts));
    file_put_contents('ambildatafasilitas.php', "<?php echo '$data_json'; ?>"); // Simpan ke file agar bisa diakses
}

// Setup Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$obj = json_decode($data_json);
$total_items = count($obj->results);
$total_pages = ceil($total_items / $items_per_page);

// Hitung data yang ditampilkan untuk halaman saat ini
$start_index = ($current_page - 1) * $items_per_page;
$current_items = array_slice($obj->results, $start_index, $items_per_page);
?>

<div class="container">
    <div class="panel panel-info panel-dashboard">
        <div class="panel-heading centered">
            <h2 class="panel-title"><strong> - <?php echo $title ?> - </strong></h2>
        </div>
    </div>

    <!-- Baris Pertama -->
    <!-- <div class="row mb-4">
        <?php
        // Menampilkan 5 data pertama
        $first_half = array_slice($current_items, 0, 5);
        foreach ($first_half as $item) {
        ?>
            <div class="col-md-15th mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($item->foto_fasilitas1)) { ?>
                        <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas1; ?>" class="card-img-top" alt="<?php echo $item->nama_fasilitas; ?>" style="height: 200px; object-fit: cover;">
                    <?php } else { ?>
                        <div class="bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-image fa-3x text-muted"></i>
                        </div>
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item->nama_fasilitas; ?></h5>
                        <p class="card-text">
                            <small class="text-muted"><i class="fa fa-map-marker"></i> <?php echo $item->alamat; ?></small><br>
                        </p>
                        <a href="detail.php?id_fasilitas=<?php echo $item->id_fasilitas; ?>" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-map-marker"></i> Detail dan Lokasi
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div> -->

    <div class="row mb-4" style="margin-bottom: 8px;">
        <?php
        // Menampilkan 5 data pertama
        $first_half = array_slice($current_items, 0, 5);
        foreach ($first_half as $item) {
        ?>
            <div class="col-md-15th mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Gambar -->
                    <div class="image-container">
                        <?php if (!empty($item->foto_fasilitas1)) { ?>
                            <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas1; ?>" class="card-img-top" alt="<?php echo $item->nama_fasilitas; ?>">
                        <?php } else { ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Konten -->
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item->nama_fasilitas; ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fa fa-map-marker"></i>
                                <?php
                                // Membatasi maksimal 4 kata
                                $alamat = explode(' ', $item->alamat); // Memisahkan teks menjadi array berdasarkan spasi
                                $alamat_terpotong = implode(' ', array_slice($alamat, 0, 4)); // Mengambil maksimal 4 kata pertama
                                echo $alamat_terpotong . (count($alamat) > 4 ? '...' : ''); // Menambahkan "..." jika lebih dari 4 kata
                                ?>
                            </small><br>
                        </p>
                        <a href="detailfasilitas.php?id_fasilitas=<?php echo $item->id_fasilitas; ?>" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-map-marker"></i> Detail dan Lokasi
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>


    <!-- Baris Kedua -->
    <div class="row mb-4">
        <?php
        // Menampilkan 5 data berikutnya
        $second_half = array_slice($current_items, 5, 5);
        foreach ($second_half as $item) {
        ?>
            <div class="col-md-15th mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($item->foto_fasilitas1)) { ?>
                        <img src="uploads/fasilitas/<?php echo $item->foto_fasilitas1; ?>" class="card-img-top" alt="<?php echo $item->nama_fasilitas; ?>" style="height: 200px; object-fit: cover;">
                    <?php } else { ?>
                        <div class="bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-image fa-3x text-muted"></i>
                        </div>
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item->nama_fasilitas; ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fa fa-map-marker"></i>
                                <?php
                                // Membatasi maksimal 4 kata
                                $alamat = explode(' ', $item->alamat); // Memisahkan teks menjadi array berdasarkan spasi
                                $alamat_terpotong = implode(' ', array_slice($alamat, 0, 4)); // Mengambil maksimal 4 kata pertama
                                echo $alamat_terpotong . (count($alamat) > 4 ? '...' : ''); // Menambahkan "..." jika lebih dari 4 kata
                                ?>
                            </small><br>
                            <strong class="text-primary">Rp. <?php echo number_format($item->harga_tiket, 0, ',', '.'); ?></strong>
                        </p>
                        <a href="detail.php?id_fasilitas=<?php echo $item->id_fasilitas; ?>" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-map-marker"></i> Detail dan Lokasi
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
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

<!-- Tambahkan CSS Khusus untuk Layout -->
<style>
    /* Mengatur ukuran gambar di dalam card agar seragam */
    .image-container {
        width: 100%;
        height: 200px;
        /* Tinggi gambar tetap */
        overflow: hidden;
        position: relative;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Memastikan gambar tetap proporsional */
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