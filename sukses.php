<?php
// sukses.php

session_start();
if (!isset($_SESSION["login"])) {
  header("Location: login.php");
  exit;
}
if (!isset($_SESSION['dari_checkout'])) {
    header("Location: checkout.php");
    exit;
}
unset($_SESSION['dari_checkout']);
// Hapus isi keranjang setelah checkout
// Jika menggunakan session
// unset($_SESSION['cart']);

// Jika menggunakan database
// include 'function.php';
// mysqli_query($conn, "DELETE FROM keranjang");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi Berhasil - KantinKita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5 text-center">
        <div class="card shadow p-4">
            <h1 class="text-success mb-4">🎉 Pembayaran Berhasil!</h1>
            <p class="lead">Terima kasih telah berbelanja di <strong>KantinKita</strong>.</p>
            <p>Pesanan Anda sedang diproses dan akan segera disiapkan.</p>
            <hr>
            <p class="text-muted">Jika ada kendala, silakan hubungi kontak kami di halaman <a href="contact.php">Contact</a>.</p>
            <a href="index.php" class="btn btn-primary mt-3">Kembali ke Beranda</a>
        </div>
    </div>

</body>

</html>
