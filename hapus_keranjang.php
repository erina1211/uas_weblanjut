<?php
require 'function.php';
session_start();
if (!isset($_SESSION["login"])) {
      header("Location: login.php");
      exit;
}
if (!isset($_SESSION['dari_cart']) || !isset($_SESSION['boleh_hapus'])) {
    header("Location: cart.php");
    exit;
}

// Setelah masuk hapus_keranjang, langsung hapus izin agar tidak bisa akses ulang

$id = $_GET["id"];

if (hapus_keranjang($id) > 0) {
      echo "
      <script>
      alert ('Keranjang berhasil dihapus!')
      document.location.href ='cart.php';
      </script>
      ";
} else {
      echo "
      <script>
      alert ('Keranjang gagal dihapus!')
      document.location.href ='cart.php';
      </script>
      ";
}
unset($_SESSION['boleh_hapus']);
