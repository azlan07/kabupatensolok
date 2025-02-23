<?php
include '../koneksi.php';
session_start();

// Cek login
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

// Cek ID berita
if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit();
}

$id_berita = $_GET['id'];

// Ambil data berita
$query = "SELECT * FROM berita WHERE id_berita = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_berita);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$berita = mysqli_fetch_assoc($result);

// Jika berita tidak ditemukan
if (!$berita) {
    header("Location: berita.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_berita = $_POST['judul_berita'];
    $isi_berita = $_POST['isi_berita'];
    $foto_lama = $berita['foto_berita'];

    $error = false;
    $error_message = '';

    // Handle upload foto baru jika ada
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
            $file_extension = pathinfo($_FILES['foto_berita']['name'], PATHINFO_EXTENSION);
            $foto_berita = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $foto_berita;

            if (move_uploaded_file($_FILES['foto_berita']['tmp_name'], $target_file)) {
                // Hapus foto lama jika ada
                if ($foto_lama && file_exists($target_dir . $foto_lama)) {
                    unlink($target_dir . $foto_lama);
                }
            } else {
                $error = true;
                $error_message = "Gagal mengupload file.";
            }
        }
    } else {
        $foto_berita = $foto_lama;
    }

    if (!$error) {
        $query = "UPDATE berita SET 
                  judul_berita = ?,
                  isi_berita = ?,
                  foto_berita = ?
                  WHERE id_berita = ?";

        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $judul_berita, $isi_berita, $foto_berita, $id_berita);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: berita.php?status=success&message=Berita berhasil diupdate");
            exit();
        } else {
            $error = true;
            $error_message = "Gagal mengupdate berita: " . mysqli_error($koneksi);
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Berita</h1>
                    </div>

                    <!-- Card Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Berita</h6>
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
                                                   value="<?= htmlspecialchars($berita['judul_berita']) ?>" 
                                                   required />
                                        </div>

                                        <div class="form-group">
                                            <label>Isi Berita</label>
                                            <textarea class="form-control" id="isi_berita" name="isi_berita" 
                                                      rows="10" required><?= htmlspecialchars($berita['isi_berita']) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto Berita</label>
                                            <?php if ($berita['foto_berita']): ?>
                                                <div class="mb-2">
                                                    <img src="uploads/berita/<?= $berita['foto_berita'] ?>" 
                                                         alt="Current Image" class="preview-image img-thumbnail">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="foto_berita" class="form-control" 
                                                   accept="image/*" onchange="previewImage(this);" />
                                            <div id="imagePreview"></div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> 
                                                Biarkan kosong jika tidak ingin mengubah foto
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update
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
                                            <h6 class="m-0 font-weight-bold text-primary">Informasi Berita</h6>
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <i class="fas fa-user"></i> Author: <?= $berita['nama_admin'] ?? $_SESSION['username'] ?>
                                            </p>
                                            <p>
                                                <i class="fas fa-clock"></i> Dibuat: 
                                                <?= date('d-m-Y H:i', strtotime($berita['tanggal_posting'])) ?>
                                            </p>
                                            <p>
                                                <i class="fas fa-edit"></i> Terakhir diupdate: 
                                                <?= date('Y-m-d H:i:s') ?>
                                            </p>
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
            removeButtons: 'Image', // Hapus tombol image dari toolbar
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                { name: 'forms', groups: [ 'forms' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others', groups: [ 'others' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] }
            ]
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