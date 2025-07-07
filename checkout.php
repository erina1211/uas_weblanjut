<?php
require 'function.php';
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['dari_cart'])) {
    header("Location: cart.php");
    exit;
}

// Ambil data keranjang
$cartItems = query("SELECT * FROM keranjang");
$totalHarga = 0;
foreach ($cartItems as $item) {
    $totalHarga += $item['harga'] * $item['jumlah'];
}

// Tangani proses checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulasi penyimpanan ke database atau logika pembayaran
    // Setelah selesai, kosongkan keranjang
    mysqli_query($conn, "DELETE FROM keranjang");
    $_SESSION['dari_checkout'] = true;
    unset($_SESSION['dari_cart']);
    header("Location: sukses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - KantinKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Checkout</h2>
        <div class="card p-4 mb-4">
            <h4 class="mb-3">Detail Pembelian</h4>
            <ul class="list-group mb-3">
                <?php foreach ($cartItems as $item) : ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <img src="images/product/<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['nama_makanan']) ?>" class="img-thumbnail me-3" style="width: 80px;">
                            <h6 class="my-0"><?= htmlspecialchars($item['nama_makanan']); ?> (x<?= $item['jumlah'] ?>)</h6>
                            <small class="text-muted">Dari: <?= htmlspecialchars($item['nama_warung']); ?></small>
                        </div>
                        <span class="text-muted">Rp <?= number_format($item['harga'] * $item['jumlah'], 3, ',', '.') ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong>Rp <?= number_format($totalHarga, 3, ',', '.') ?></strong>
                </li>
            </ul>

            <form method="POST">
                <h5 class="mb-3">Informasi Pembeli</h5>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="pembayaran" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="pembayaran" name="pembayaran" required>
                        <option value="">Pilih...</option>
                        <option value="COD">Bayar di Tempat (COD)</option>
                        <option value="Transfer">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">Lanjutkan Pembayaran</button>
            </form>
        </div>
        <a href="cart.php" class="btn btn-secondary">Kembali ke Keranjang</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>