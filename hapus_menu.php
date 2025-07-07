<?php
session_start();
require 'function.php';

if (!isset($_SESSION['login']) || $_SESSION['username'] !== 'superadmin' || !isset($_SESSION['dari_admin']) || $_SESSION['dari_admin'] !== true) {
    echo "<script>
        alert('Akses ditolak!');
        window.location.href = 'index.php';
    </script>";
    exit;
}
if (isset($_GET['from']) && $_GET['from'] === 'admin') {
    $_SESSION['dari_admin'] = true;
}
if (!isset($_GET["id"])) {
    echo "<script>
            alert('ID menu tidak ditemukan!');
            window.location.href = 'admin.php';
          </script>";
    exit;
}
$id = $_GET["id"];

if (hapus_menu($id) > 0) {
    unset($_SESSION['dari_admin']);
    echo "
     <script>
           alert ('Menu berhasil dihapus!')
           document.location.href ='admin.php';
     </script>
   	";
} else {
    echo "
     <script>
           alert ('Menu gagal dihapus!')
           document.location.href ='admin.php';
     </script>
   	";
}
