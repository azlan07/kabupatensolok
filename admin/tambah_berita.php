<?php
include '../koneksi.php';
session_start();

// Cek login
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

// Ambil id_admin dari session
$username = $_SESSION['username'];
$query_admin = "SELECT id FROM admin WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $query_admin);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result_admin = mysqli_stmt_get_result($stmt);
$admin_data = mysqli_fetch_assoc($result_admin);
$id_admin = $admin_data['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_berita = $_POST['judul_berita'];
    $isi_berita = $_POST['isi_berita'];
    $tanggal_posting = date('Y-m-d H:i:s');
    
    $error = false;
    $error_message = '';

    // Handle upload foto
    if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['foto_berita']['type'], $allowed_types)) {
            $error = true;
            $error_message = "Tipe file tidak diizinkan. Hanya JPEG, JPG, dan PNG yang diperbolehkan.";
        } elseif ($_FILES['foto_berita']['size'] > $max_size) {
            $error = true;
            $error_message = "Ukuran file terlalu besar. Maksimal 5MB.";
        } else {
            $target_dir = "uploads/berita/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['foto_berita']['name'], PATHINFO_EXTENSION);
            $foto_berita = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $foto_berita;

            if (!move_uploaded_file($_FILES['foto_berita']['tmp_name'], $target_file)) {
                $error = true;
                $error_message = "Gagal mengupload file.";
            }
        }
    } else {
        $error = true;
        $error_message = "Foto berita harus diupload!";
    }

    if (!$error) {
        $query = "INSERT INTO berita (judul_berita, isi_berita, tanggal_posting, foto_berita, id_admin) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $judul_berita, $isi_berita, $tanggal_posting, $foto_berita, $id_admin);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: berita.php?status=success&message=Berita berhasil ditambahkan");
            exit();
        } else {
            $error = true;
            $error_message = "Gagal menyimpan berita: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<head>
    <!-- Include CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <style>
        .preview-image {
            max-width: 200px;
            margin-top: 10px;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "menu_sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include "menu_topbar.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Berita Baru</h1>
                    </div>

                    <!-- Card Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Berita</h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error) && $error): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <?php echo $error_message; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <!-- Form Grid -->
                            <div class="row">
                                <div class="col-md-8">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Judul Berita</label>
                                            <input type="text" class="form-control" name="judul_berita" 
                                                   value="<?= isset($_POST['judul_berita']) ? htmlspecialchars($_POST['judul_berita']) : '' ?>" 
                                                   required />
                                        </div>

                                        <div class="form-group">
                                            <label>Isi Berita</label>
                                            <textarea class="form-control" id="isi_berita" name="isi_berita" rows="10" required><?= isset($_POST['isi_berita']) ? htmlspecialchars($_POST['isi_berita']) : '' ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto Berita</label>
                                            <input type="file" name="foto_berita" class="form-control" 
                                                   accept="image/*" required 
                                                   onchange="previewImage(this);" />
                                            <div id="imagePreview"></div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                            <a href="berita.php" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Kembali
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                <!-- Info Panel -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><i class="fas fa-user"></i> Author: <?= $_SESSION['username'] ?></p>
                                            <p><i class="fas fa-calendar"></i> Tanggal: <?= date('d-m-Y') ?></p>
                                            <hr>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Tips:
                                                <ul>
                                                    <li>Gunakan judul yang menarik</li>
                                                    <li>Foto dengan rasio 16:9 akan terlihat lebih baik</li>
                                                    <li>Pastikan konten berita informatif</li>
                                                </ul>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script>
        // Inisialisasi CKEditor
        CKEDITOR.replace('isi_berita', {
            height: 400,
            removeButtons: 'Image' // Hapus tombol image dari toolbar
        });

        // Preview image
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="preview-image img-thumbnail">';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>