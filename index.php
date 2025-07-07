<?php
session_start();
require 'function.php';
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Cek apakah session berasal dari login form atau cookie masih ada
if (!isset($_SESSION["from_login"]) && (!isset($_COOKIE['id']) || !isset($_COOKIE['key']))) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
// pagination
$jmlDataPerHalaman = 6;
$jumlahData = count(query("SELECT * FROM menu"));
$jumlahHalaman = ceil($jumlahData / $jmlDataPerHalaman);
if (isset($_GET["halaman"])) {
  $halamanAktif = $_GET["halaman"];
} else {
  $halamanAktif = 1;
}

if (isset($_POST["cart"])) {
  // ambil data dari form
  $nama_warung = $_POST['nama_warung'];
  $nama_makanan = $_POST['nama_makanan'];
  $harga = $_POST['price'];
  $jumlah = $_POST['jumlah'];
  $img = $_POST['img'];

  // hitung total harga
  $total = $harga * $jumlah;

  // query insert ke tabel keranjang
  $query = "INSERT INTO keranjang ( nama_warung, nama_makanan, harga, jumlah, total, img)
              VALUES ('$nama_warung', '$nama_makanan', '$harga', '$jumlah', '$total', '$img')";

  if (mysqli_query($conn, $query)) {
    echo "<script>alert('Berhasil ditambahkan ke keranjang!'); window.location.href='cart.php';</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KantinKita</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/vendor.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="css/ulasan.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

</head>
<style>
</style>

<body>

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <defs>
      <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
        <path fill="currentColor" d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
        <path fill="currentColor" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
        <path fill="currentColor" d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
        <path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
        <path fill="currentColor" d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
        <path fill="currentColor" d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
        <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
        <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
        <path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
        <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
    </defs>
  </svg>

  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>
  <header>
    <nav class="navbar fixed-top navbar-expand-lg shadow navbar-light bg-light py-3">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="images/ChatGPT_Image_27_Jun_2025__10.15.31-removebg-preview.png" alt="" width="150" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="link collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto fs-6">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="kantin.php">Kantin</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <?php if ($_SESSION['username'] == 'superadmin') : ?>
              <li class="nav-item">
                <a class="nav-link" href="admin.php">Admin</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin keluar?');">Logout</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php">
                <i class="fa-solid fa-cart-shopping fa-lg"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <section class="py-3" style="background-image: url('images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover; margin-top: 120px;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="banner-blocks">

            <div class="banner-ad large bg-info block-1">

              <div class="swiper main-swiper">
                <div class="swiper-wrapper">

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories my-3">Masakan Sunda</div>
                        <h3 class="display-4">Warung bu neneng</h3>
                        <p>Nikmati cita rasa khas Sunda yang autentik di Warung Bu Neneng.
                          Menghidangkan berbagai menu rumahan seperti nasi liwet, sayur asem, ayam goreng, tempe mendoan, dan sambal terasi segar. Dengan suasana yang sederhana dan ramah,
                          warung ini cocok menjadi tempat makan siang favorit mahasiswa yang rindu masakan rumah..</p>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product/Kantin/download (6).jpeg" class="img-fluid">
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">Masakan Jawa</div>
                        <h3 class="banner-title">Warung bu susi</h3>
                        <p>Warung Susi menyajikan aneka masakan khas Jawa yang lezat dan menggugah selera.
                          Mulai dari gudeg, rawon, tahu bacem, hingga oseng mercon,
                          semua dimasak dengan resep tradisional dan penuh kehangatan.
                          Rasa manis dan gurih yang menjadi ciri khas Jawa akan memanjakan lidahmu di setiap suapan.</p>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product/Kantin/download (5).jpeg" class="img-fluid">
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">Masakan Jepang</div>
                        <h3 class="banner-title">Warung bu markima</h3>
                        <p>Bagi pecinta kuliner Jepang, Warung Bu Markima adalah pilihan yang tepat.
                          Di sini tersedia berbagai menu seperti chicken katsu, ramen, sushi roll sederhana,
                          dan bento box yang cocok dengan selera lokal.
                          Warung ini menyajikan pengalaman makan ala Jepang dengan harga terjangkau dan porsi pas untuk mahasiswa..</p>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product/Kantin/Japanese cafe.jpeg" class="img-fluid">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="swiper-pagination"></div>

              </div>
            </div>

            <div class="banner-ad bg-success-subtle block-2" style="background:url('images/download__5_-removebg-preview.png') no-repeat;background-position: right bottom">
              <div class="row banner-content p-5">

                <div class="content-wrapper col-md-7">
                  <div class="categories sale mb-3 pb-3">20% off</div>
                  <h3 class="banner-title">Makanan</h3>
                </div>

              </div>
            </div>

            <div class="banner-ad bg-danger block-3" style="background:url('images/download__7_-removebg-preview.png') no-repeat;background-position: right bottom">
              <div class="row banner-content p-5">

                <div class="content-wrapper col-md-7">
                  <div class="categories sale mb-3 pb-3">15% off</div>
                  <h3 class="item-title">Cemilan</h3>
                </div>

              </div>
            </div>

          </div>
          <!-- / Banner Blocks -->

        </div>
      </div>
    </div>
  </section>

  <section class="py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="section-header d-flex flex-wrap justify-content-between mb-5">
            <h2 class="section-title">Category</h2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <div class="category-carousel swiper">
            <div class="swiper-wrapper">
              <a href="makanan.php" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-bowl-food fa-2xl" style="color: #FFD43B;"></i>
                <h3 class="category-title">Makanan</h3>
              </a>
              <a href="cemilan.php" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-cookie-bite fa-2xl" style="color: #4e363e;"></i>
                <h3 class="category-title">Cemilan</h3>
              </a>
              <a href="minuman.php" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-wine-glass fa-2xl" style="color: #cf3a58;"></i>
                <h3 class="category-title">Minuman</h3>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>


  <section class="py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="section-header d-flex flex-wrap flex-wrap justify-content-between mb-5">

            <h2 class="section-title">Makanan & Minuman Terbaru</h2>

            <div class="d-flex align-items-center">
              <div class="swiper-buttons">
                <button class="swiper-prev brand-carousel-prev btn btn-yellow">❮</button>
                <button class="swiper-next brand-carousel-next btn btn-yellow">❯</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <div class="brand-carousel swiper">
            <div class="swiper-wrapper">

              <?php foreach ($terbaru as $trb): ?>
                <div class="swiper-slide">
                  <div class="card mb-3 p-3 rounded-4 shadow border-0">
                    <div class="row g-0">
                      <div class="col-md-4">
                        <img src="images/product/<?= $trb["img"] ?>" class="img-fluid rounded" alt="Card title">
                      </div>
                      <div class="col-md-8">
                        <div class="card-body py-0">
                          <p class="text-muted mb-0"><?= $trb["nama_warung"] ?></p>
                          <h5 class="card-title"><?= $trb["nama_makanan"] ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>


  <section class="py-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <!-- HEADER -->
          <div class="bootstrap-tabs product-tabs">
            <div class="tabs-header d-flex justify-content-between border-bottom my-5">
              <h3>Makanan/Minuman Terlaris</h3>
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all">All</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-makanan-tab" data-bs-toggle="tab" data-bs-target="#nav-makanan">Makanan</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-minuman-tab" data-bs-toggle="tab" data-bs-target="#nav-minuman">Minuman</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-cemilan-tab" data-bs-toggle="tab" data-bs-target="#nav-cemilan">Cemilan</a>
                </div>
              </nav>
            </div>

            <!-- TAB content -->
            <div class="tab-content" id="nav-tabContent">

              <!-- TAB ALL -->
              <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                  <?php
                  $all = mysqli_query($conn, "SELECT * FROM menu where rate >= 4.5 ORDER BY rate DESC LIMIT 10");
                  foreach ($all as $tls) :
                  ?>
                    <div class="col mb-4">
                      <?php include 'card.php'; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <!-- Tab makanan -->
              <div class="tab-pane fade " id="nav-makanan" role="tabpanel" aria-labelledby="nav-makanan-tab">
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                  <?php
                  $makanan = mysqli_query($conn, "SELECT * FROM menu where rate >= 4.5 and type = 'makanan' ORDER BY rate DESC LIMIT 6");
                  foreach ($makanan as $tls) :
                  ?>
                    <div class="col mb-4">
                      <?php include 'card.php'; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <!-- Tab minuman -->
              <div class="tab-pane fade" id="nav-minuman" role="tabpanel" aria-labelledby="nav-minuman-tab">
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                  <?php
                  $minuman = mysqli_query($conn, "SELECT * FROM menu where rate >= 4.5 and type = 'minuman' ORDER BY rate DESC LIMIT 6");
                  foreach ($minuman as $tls) :
                  ?>
                    <div class="col mb-4">
                      <?php include 'card.php'; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <!-- Tab cemilan -->
              <div class="tab-pane fade" id="nav-cemilan" role="tabpanel" aria-labelledby="nav-cemilan-tab">
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                  <?php
                  $cemilan = mysqli_query($conn, "SELECT * FROM menu where rate >= 4.5 and type = 'cemilan' ORDER BY rate DESC LIMIT 6");
                  foreach ($cemilan as $tls) :
                  ?>
                    <div class="col mb-4">
                      <?php include 'card.php'; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>




  <!-- Testimoni -->
  <section id="latest-blog" class="py-5">
    <div class="container-fluid">
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between my-5">
          <h2 class="section-title">Ulasan Warung</h2>
          <div class="btn-wrap align-right">
            <a href="ulasan.php" class="d-flex align-items-center nav-link">Lihat semua ulasan <svg width="24" height="24">
                <use xlink:href="#arrow-right"></use>
              </svg></a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="container srn">
          <?php foreach ($testimoni as $tst): ?>
            <div class="card crd">
              <div class="card__header">
                <img src="images/product/<?= $tst["img"] ?>" alt="card__image" class="card__image" width="600" height="300">
              </div>
              <div class="card__body">
                <span class="tag tag-blue">Ulasan</span>
                <h4>
                  <a href="#" style="text-decoration: none;">
                    Warung <?= $tst["nama_warung"] ?>
                  </a>
                </h4>
                <p>
                  <?= $tst["deskripsi"] ?>
                </p>
              </div>
              <div class="card__footer">
                <div class="user">
                  <img src="images/product/<?= $tst["img_user"] ?>" alt="user__image" class="user__image" width="50" height="50">
                  <div class="user__info">
                    <h5><?= $tst["nama_akun"] ?></h5>
                    <small><?= $tst["tanggal"] ?></small>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- Akhir Testimoni -->




  <section class="py-5">
    <div class="container-fluid">
      <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5">
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M21.5 15a3 3 0 0 0-1.9-2.78l1.87-7a1 1 0 0 0-.18-.87A1 1 0 0 0 20.5 4H6.8l-.33-1.26A1 1 0 0 0 5.5 2h-2v2h1.23l2.48 9.26a1 1 0 0 0 1 .74H18.5a1 1 0 0 1 0 2h-13a1 1 0 0 0 0 2h1.18a3 3 0 1 0 5.64 0h2.36a3 3 0 1 0 5.82 1a2.94 2.94 0 0 0-.4-1.47A3 3 0 0 0 21.5 15Zm-3.91-3H9L7.34 6H19.2ZM9.5 20a1 1 0 1 1 1-1a1 1 0 0 1-1 1Zm8 0a1 1 0 1 1 1-1a1 1 0 0 1-1 1Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Gratis Ongkir</h5>
                  <p class="card-text">Gratis Ongkir sampai tanggal 25 Juli 2025</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M19.63 3.65a1 1 0 0 0-.84-.2a8 8 0 0 1-6.22-1.27a1 1 0 0 0-1.14 0a8 8 0 0 1-6.22 1.27a1 1 0 0 0-.84.2a1 1 0 0 0-.37.78v7.45a9 9 0 0 0 3.77 7.33l3.65 2.6a1 1 0 0 0 1.16 0l3.65-2.6A9 9 0 0 0 20 11.88V4.43a1 1 0 0 0-.37-.78ZM18 11.88a7 7 0 0 1-2.93 5.7L12 19.77l-3.07-2.19A7 7 0 0 1 6 11.88v-6.3a10 10 0 0 0 6-1.39a10 10 0 0 0 6 1.39Zm-4.46-2.29l-2.69 2.7l-.89-.9a1 1 0 0 0-1.42 1.42l1.6 1.6a1 1 0 0 0 1.42 0L15 11a1 1 0 0 0-1.42-1.42Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>100% pembayaran aman</h5>
                  <p class="card-text">Pembayaran sudah terjamin aman, karna sudah diawasi oleh CIA</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1Zm-7 2h2v3a1 1 0 0 1-2 0Zm-4 0h2v3a1 1 0 0 1-2 0ZM7 7h2v3a1 1 0 0 1-2 0Zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1Zm10 10h-4v-2a2 2 0 0 1 4 0Zm5 0h-3v-2a4 4 0 0 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3.17 3.17 0 0 0 1 .6Zm2-11a1 1 0 0 1-2 0V7h2ZM4.3 3H20a1 1 0 0 0 0-2H4.3a1 1 0 0 0 0 2Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Kualitas terjamin</h5>
                  <p class="card-text">Kualitas terjaga, bahan-bahannya diambil langsung dari sumbernya</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M12 8.35a3.07 3.07 0 0 0-3.54.53a3 3 0 0 0 0 4.24L11.29 16a1 1 0 0 0 1.42 0l2.83-2.83a3 3 0 0 0 0-4.24A3.07 3.07 0 0 0 12 8.35Zm2.12 3.36L12 13.83l-2.12-2.12a1 1 0 0 1 0-1.42a1 1 0 0 1 1.41 0a1 1 0 0 0 1.42 0a1 1 0 0 1 1.41 0a1 1 0 0 1 0 1.42ZM12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20Zm0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Ramah dikantong</h5>
                  <p class="card-text">Harga murah, pas dikantong</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M18 7h-.35A3.45 3.45 0 0 0 18 5.5a3.49 3.49 0 0 0-6-2.44A3.49 3.49 0 0 0 6 5.5A3.45 3.45 0 0 0 6.35 7H6a3 3 0 0 0-3 3v2a1 1 0 0 0 1 1h1v6a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-6h1a1 1 0 0 0 1-1v-2a3 3 0 0 0-3-3Zm-7 13H8a1 1 0 0 1-1-1v-6h4Zm0-9H5v-1a1 1 0 0 1 1-1h5Zm0-4H9.5A1.5 1.5 0 1 1 11 5.5Zm2-1.5A1.5 1.5 0 1 1 14.5 7H13ZM17 19a1 1 0 0 1-1 1h-3v-7h4Zm2-8h-6V9h5a1 1 0 0 1 1 1Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Penawaran baru setiap bulannya</h5>
                  <p class="card-text">Akan ada penawaran/diskon setiap bulannya</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <hr>
  <footer class="py-5">
    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="footer-menu">
            <img src="images/ChatGPT_Image_27_Jun_2025__10.15.31-removebg-preview.png" alt="logo" width="250" class="m-0">
            <div class="social-links mt-5">
              <ul class="d-flex list-unstyled gap-2 text-center">
                <li>
                  <a href="#" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M23 9.71a8.5 8.5 0 0 0-.91-4.13a2.92 2.92 0 0 0-1.72-1A78.36 78.36 0 0 0 12 4.27a78.45 78.45 0 0 0-8.34.3a2.87 2.87 0 0 0-1.46.74c-.9.83-1 2.25-1.1 3.45a48.29 48.29 0 0 0 0 6.48a9.55 9.55 0 0 0 .3 2a3.14 3.14 0 0 0 .71 1.36a2.86 2.86 0 0 0 1.49.78a45.18 45.18 0 0 0 6.5.33c3.5.05 6.57 0 10.2-.28a2.88 2.88 0 0 0 1.53-.78a2.49 2.49 0 0 0 .61-1a10.58 10.58 0 0 0 .52-3.4c.04-.56.04-3.94.04-4.54ZM9.74 14.85V8.66l5.92 3.11c-1.66.92-3.85 1.96-5.92 3.08Z" />
                    </svg>
                  </a>
                </li>
                <li>
                  <a href="#" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2a1.2 1.2 0 0 0-1.2-1.2Zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43a4.94 4.94 0 0 0-1.16-1.77a4.7 4.7 0 0 0-1.77-1.15a7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47a4.78 4.78 0 0 0-1.77 1.15a4.7 4.7 0 0 0-1.15 1.77a7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43a4.7 4.7 0 0 0 1.15 1.77a4.78 4.78 0 0 0 1.77 1.15a7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47a4.7 4.7 0 0 0 1.77-1.15a4.85 4.85 0 0 0 1.16-1.77a7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12ZM20.14 16a5.61 5.61 0 0 1-.34 1.86a3.06 3.06 0 0 1-.75 1.15a3.19 3.19 0 0 1-1.15.75a5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3a3.27 3.27 0 0 1-1.1-.75a3 3 0 0 1-.74-1.15a5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34a3.06 3.06 0 0 1 1.19.8a3.06 3.06 0 0 1 .75 1.1a5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4ZM12 6.87A5.13 5.13 0 1 0 17.14 12A5.12 5.12 0 0 0 12 6.87Zm0 8.46A3.33 3.33 0 1 1 15.33 12A3.33 3.33 0 0 1 12 15.33Z" />
                    </svg>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 mx-3">
          <div class="footer-menu">
            <h5 class="widget-title">Kontent</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="index.php" class="nav-link active">Home</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Kantin </a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Contact</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Logout</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 me-3">
          <div class="footer-menu">
            <h5 class="widget-title">Hubungi Kami</h5>
            <ul class="menu-list list-unstyled">
              <li class="location mb-2">
                <i class="fa-solid fa-location-dot fa-lg" style="color: #919191;"></i>
                <span class="ms-2">Jl. Raya No. 123, Jakarta Timur</span>
              </li>
              <li class="contact mb-3">
                <i class="fa-solid fa-phone fa-lg" style="color: #8f8f8f;"></i>
                <span class="ms-2">+62 123 4567 890</span>
              </li>
              <li class="email mb-3">
                <i class="fa-solid fa-envelope fa-lg" style="color: #949494;"></i>
                <span class="ms-2">erinnovii@gmail.com</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 ms-3">
          <div class="footer-menu">
            <h5 class="widget-title">Kantin Kita</h5>
            <ul class="menu-list list-unstyled">
              <li class="description mb-3">
                <p>
                  Kantin Kita adalah kantin yang menyajikan makanan dan minuman yang terjangkau dan berkualitas.
                  Kami berkomitmen untuk memberikan pengalaman kuliner yang memuaskan bagi mahasiswa dengan harga yang terjangkau.
                </p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <div class="luar bg-dark text-white">
    <div class="container text-center d-flex justify-content-center align-items-center py-1">
      <div class="col-md-6 copyright text-center">
        <p>© 2025 KantinKita . All rights reserved.</p>
      </div>
    </div>
  </div>
  <script src="https://kit.fontawesome.com/29df54f092.js" crossorigin="anonymous"></script>
  <script src="js/jquery-1.11.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
</body>

</html>