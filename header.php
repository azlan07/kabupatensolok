<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIG WISATA KABUPATEN SOLOK</title>
  <link rel="icon" href="kabupatensolok.png">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/datatable-bootstrap.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="tengah">
        <div class="head-depan tengah">
          <div class="row">
            <div class="col-md-1">
              <a href="index.php"><img src="img/kabupatensolok.png" width="70x" height="80x" alt="no images" title="" /></a>

            </div>
            <div class="col-md-11">
              <h1 class="judul-head">Sistem Informasi Geografis Wisata</h1>
              <p><i class="fa fa-map-marker fa-fw"></i> KABUPATEN SOLOK</p>
            </div>
          </div>
          <hr class="hr1 margin-b-10" />
        </div>
      </div>
    </div>
  </div>
  <div class="container margin-b70">
    <nav class="navbar navbar-default navbar-utama" role="navigation">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Status</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li><a href="index.php"><i class="fa fa-home"></i> HALAMAN DEPAN</a></li>

          <!-- Dropdown Data -->
          <li class="dropdown-container">
            <div class="dropdown">
              <button class="dropbtn"><i class="fa fa-list-ul"></i> DATA <i class="fa fa-caret-down"></i></button>
              <div class="dropdown-content">
                <a href="data_wisata.php"><i class="fa fa-list-ul"></i> DATA WISATA</a>
                <a href="data_fasilitas.php"><i class="fa fa-list-ul"></i> DATA FASILITAS</a>
              </div>
            </div>
          </li>

          <!-- Dropdown Peta -->
          <li class="dropdown-container">
            <div class="dropdown">
              <button class="dropbtn"><i class="fa fa-map-marker"></i> PETA <i class="fa fa-caret-down"></i></button>
              <div class="dropdown-content">
                <a href="peta.php"><i class="fa fa-map-marker"></i> PETA WISATA</a>
                <a href="petafasilitas.php"><i class="fa fa-map-marker"></i> PETA FASILITAS WISATA</a>
              </div>
            </div>
          </li>
          <li><a href="data_event.php"><i class="fa fa-flag"></i> EVENT</a></li>
          <li><a href="data_berita.php"><i class="fa fa-file"></i> BERITA</a></li>
          <li><a href="./admin/login.php"><i class="fa fa-home"></i> LOGIN</a></li>
        </ul>
      </div>

      <style>
        .dropdown-container {
          display: flex;
          align-items: center;
          position: relative;
        }

        .dropdown {
          position: relative;
          display: inline-block;
        }

        .dropbtn {
          background: none;
          color: black;
          /* Mengubah warna teks menjadi hitam */
          padding: 15px;
          font-size: 14px;
          border: none;
          cursor: pointer;
          transition: background-color 0.3s;
          display: flex;
          align-items: center;
          gap: 5px;
        }

        .dropdown-content {
          display: none;
          position: absolute;
          background-color: white;
          min-width: 250px;
          box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
          z-index: 9999;
          border-radius: 4px;
          top: 100%;
        }

        .dropdown-content a {
          color: black !important;
          /* Memastikan warna teks hitam */
          padding: 12px 16px;
          text-decoration: none;
          display: block;
          transition: background-color 0.3s;
        }

        /* Hover effects */
        .dropdown-content a:hover {
          background-color: #f1f1f1;
          color: black !important;
          /* Memastikan warna teks tetap hitam saat hover */
        }

        .dropbtn:hover {
          background-color: rgba(0, 0, 0, 0.1);
          /* Mengubah warna hover menjadi abu-abu gelap */
        }

        /* Show dropdown menu on hover */
        .dropdown:hover .dropdown-content {
          display: block;
        }

        /* Optional: Add arrow rotation on hover */
        .dropdown:hover .fa-caret-down {
          transform: rotate(180deg);
          transition: transform 0.3s;
        }

        /* Menyesuaikan dengan navbar */
        .dropdown-container {
          height: 100%;
        }

        .dropbtn {
          height: 100%;
          line-height: 20px;
        }

        /* Memastikan dropdown tetap di atas elemen lain */
        .nav .dropdown-container {
          position: relative;
          z-index: 1000;
        }

        /* Mengubah warna teks untuk semua link di navbar */
        .nav.navbar-nav li a {
          color: black !important;
        }

        .nav.navbar-nav li a:hover {
          background-color: rgba(0, 0, 0, 0.1);
        }
      </style>
    </nav>