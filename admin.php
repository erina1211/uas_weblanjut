<?php
// Mulai session
session_start();

// Validasi login dan pastikan user adalah superadmin
if (!isset($_SESSION['login']) || $_SESSION['username'] !== 'superadmin') {
    header("Location: index.php");
    exit;
}

// Import file function.php yang berisi koneksi DB dan fungsi-fungsi penting
require 'function.php';

// Jika melakukan pencarian
if (isset($_POST["cari"])) {
    $kantin = cari_menu($_POST["search"]);

    if (empty($kantin)) {
        $error = true;
    }
} else {
    $kantin = query("SELECT * FROM menu ORDER BY no DESC");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>KantinKita - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS & Font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Loading -->
    <div class="preloader-wrapper">
        <div class="preloader"></div>
    </div>

    <!-- Navbar -->
    <header>
        <nav class="navbar fixed-top navbar-expand-lg shadow navbar-light bg-light py-3 mb-5">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/ChatGPT_Image_27_Jun_2025__10.15.31-removebg-preview.png" width="150">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto fs-6">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="kantin.php">Kantin</a></li>
                        <li class="nav-item"><a class="nav-link" href="Contact.php">Contact</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin.php">Admin</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin keluar?');">Logout</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping fa-lg"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Header -->
    <div class="header mt-5">
        <div class="container">
            <h1 class="text-center">Daftar Makanan</h1>
            <p class="text-center">Berikut adalah daftar makanan yang tersedia di KantinKita.</p>
        </div>
    </div>

    <!-- Search & Tambah -->
    <div class="searchbar my-3">
        <div class="container border p-3">
            <a href="tambah_menu.php" class="btn btn-primary mb-3">Tambah Menu</a>
            <form class="d-flex" method="post">
                <input class="form-control me-2" type="search" placeholder="Cari Kantin..." name="search" autocomplete="off">
                <button class="btn btn-outline-success" type="submit" name="cari">Cari</button>
            </form>
        </div>
    </div>

    <!-- Tabel Menu -->
    <table class="table container mb-5">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Makanan</th>
                <th>Rate</th>
                <th>Harga</th>
                <th>Type</th>
                <th>Nama Warung</th>
                <th>Image</th>
                <th>Alt Makanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Tampilkan error jika pencarian tidak ditemukan -->
            <?php if (isset($error)) : ?>
            <tr>
                <td colspan="9" class="text-center">
                    <div class="text-center mt-3">
                        <img src="images/vector/23629977_6844076-removebg-preview.png" alt="Produk tidak ditemukan" class="mb-3">
                        <p>Maaf, kantin yang Anda cari tidak tersedia.</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>

            <!-- Menampilkan data dari database -->
            <?php $i = 1; foreach ($kantin as $data) : ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $data["nama_makanan"]; ?></td>
                <td><i class="fa-solid fa-star" style="color: #FFD43B;"></i> <?= $data["rate"]; ?></td>
                <td>Rp. <?= number_format($data["price"], 0, ',', '.'); ?></td>
                <td><?= $data["type"]; ?></td>
                <td><?= $data["nama_warung"]; ?></td>
                <td><img src="images/product/<?= $data["img"]; ?>" alt="<?= $data["alt_makanan"]; ?>" width="100"></td>
                <td><?= $data["alt_makanan"]; ?></td>
                <td>
                    <a href="ubah_menu.php?id=<?= $data["no"]; ?>" class="btn btn-success mb-1">Edit</a>
                    <a href="hapus_menu.php?id=<?= $data["no"]; ?>&from=admin" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?');">Hapus</a>
                </td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <footer class="py-5 bg-light">
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom 1 -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <img src="images/ChatGPT_Image_27_Jun_2025__10.15.31-removebg-preview.png" width="250">
                    <div class="social-links mt-5">
                        <ul class="d-flex list-unstyled gap-2">
                            <li><a href="#" class="btn btn-outline-light"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="#" class="btn btn-outline-light"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!-- Kolom 2 -->
                <div class="col-md-2 col-sm-6 mx-3">
                    <h5>Konten</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="kantin.php">Kantin</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <!-- Kolom 3 -->
                <div class="col-md-2 col-sm-6 me-3">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fa-solid fa-location-dot"></i> Jl. Raya No. 123, Jakarta Timur</li>
                        <li><i class="fa-solid fa-phone"></i> +62 123 4567 890</li>
                        <li><i class="fa-solid fa-envelope"></i> erinnovii@gmail.com</li>
                    </ul>
                </div>
                <!-- Kolom 4 -->
                <div class="col-md-3 col-sm-6 ms-3">
                    <h5>Kantin Kita</h5>
                    <p>
                        Kantin Kita adalah tempat makan dengan harga terjangkau dan kualitas terbaik untuk mahasiswa.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Copyright -->
    <div class="bg-dark text-white text-center py-2">
        <p class="m-0">© 2025 KantinKita. All rights reserved.</p>
    </div>

    <!-- Script -->
    <script src="https://kit.fontawesome.com/29df54f092.js" crossorigin="anonymous"></script>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
