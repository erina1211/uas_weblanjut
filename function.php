<?php
$conn = mysqli_connect("localhost", "tommy", "1802005", "uas_weblanjut");
$kantin = query("SELECT * FROM kantin");
$terlaris = query("SELECT * FROM menu where rate >= 4.5 ORDER BY rate DESC LIMIT 6");
$terbaru = query("SELECT * FROM menu ORDER BY no DESC LIMIT 6");
$testimoni = query("SELECT * FROM testimoni ORDER BY no desc LIMIT 3");
function query($query)
{
   global $conn;
   $result = mysqli_query($conn, $query);
   $rows = [];
   while ($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
   }
   return $rows;
}


function tambah_menu($data)
{
   // cek apakah tombol submit sudah ditekan atau belum
   global $conn;
   $nama_makanan = htmlspecialchars($data["nama_makanan"]);
   $rate = htmlspecialchars($data["rate"]);
   $type = htmlspecialchars($data["type"]);
   $harga = htmlspecialchars($data["price"]);
   $nama_warung = isset($data["nama_warung"]) ? htmlspecialchars($data["nama_warung"]) : '';
   $img = isset($data["img"]) ? htmlspecialchars($data["img"]) : '';
   $alt = isset($data["alt_makanan"]) ? htmlspecialchars($data["alt_makanan"]) : '';

   // Cek apakah nama_makanan sudah ada di database
   $cek = mysqli_query($conn, "SELECT nama_makanan FROM menu WHERE nama_makanan = '$nama_makanan'");
   if (mysqli_num_rows($cek) > 0) {
      echo "<script>alert('Nama makanan sudah terdaftar di database!');</script>";
      return false;
   }
   // upload gambar
   $img = upload($type);
   if (!$img) {
      return false;
   }

   // Query data
   $query = "INSERT INTO menu (nama_makanan, rate, price, type, nama_warung, img, alt_makanan) VALUES
            ('$nama_makanan','$rate','$harga','$type','$nama_warung','$img', '$alt');
             ";
   mysqli_query($conn, $query);

   return mysqli_affected_rows($conn);
}



function upload($type)
{

   $namafile = $_FILES['img']['name'];
   $ukuranfile = $_FILES['img']['size'];
   $error = $_FILES['img']['error'];
   $tmpname = $_FILES['img']['tmp_name'];

   // cek apakah tidak ada gambar yang diupload
   if ($error === 4) {
      echo "<script>
                 alert('Pilih gambar terlebih dahulu');
           </script>";

      return false;
   }

   // cek apakah yang diupload adalah gambar
   $ekstensigambarvalid = ['jpg', 'jpeg', 'png'];
   $ekstensigambar = explode('.', $namafile);
   $ekstensigambar = strtolower(end($ekstensigambar));
   if (!in_array($ekstensigambar, $ekstensigambarvalid)) {
      echo "<script>
                 alert('Yang anda upload bukan gambar!');
           </script>";
   }


   // cek jika ukurannya terlalu besar

   if ($ukuranfile > 2000000) {

      echo "<script>
                 alert('Ukuran gambar terlalu besar');
           </script>";
   }

   // generate nama gambar baru
   $namafilebaru = uniqid() . '.' . $ekstensigambar;

   // buat folder tujuan berdasarkan type
   $folder_tujuan = 'images/' . 'product/' . strtolower($type) . '/';

   // buat folder jika belum ada
   if (!file_exists($folder_tujuan)) {
      mkdir($folder_tujuan, 0777, true);
   }

   // upload gambar ke folder tujuan
   move_uploaded_file($tmpname, $folder_tujuan . $namafilebaru);

   return strtolower($type) . '/' . $namafilebaru;
}



// Fungsi Hapus
function hapus_keranjang($id)
{
   global $conn;
   mysqli_query($conn, "DELETE FROM keranjang WHERE no = $id");

   return mysqli_affected_rows($conn);
}
function hapus_menu($id)
{
   global $conn;
   mysqli_query($conn, "DELETE FROM menu WHERE no = $id");

   return mysqli_affected_rows($conn);
}


function ubah($data)
{
   global $conn;

   $id = intval($data["no"]);
   $nama_makanan = htmlspecialchars($data["nama_makanan"]);
   $rate = htmlspecialchars($data["rate"]);
   $harga = htmlspecialchars($data["price"]);
   $type = htmlspecialchars($data["type"]);
   $nama_warung = htmlspecialchars($data["nama_warung"]);
   $gambarlama = $data["gambarlama"];
   $alt = htmlspecialchars($data["alt_makanan"]);

   // cek apakah user pilih gambar baru atau tidak
   if ($_FILES['img']['error'] === 4) {
      $img = $gambarlama;
   } else {
      $img = upload($type);
   }

   $query = "UPDATE menu SET
              nama_makanan = '$nama_makanan',
              rate = '$rate',
              price = '$harga',
              type = '$type',
              nama_warung = '$nama_warung',
              img = '$img',
              alt_makanan = '$alt'
              WHERE no = $id";

   mysqli_query($conn, $query);

   return mysqli_affected_rows($conn);
}


// Fungsi cari
function cari_kantin($keyword)
{
   global $conn;
   $keyword = mysqli_real_escape_string($conn, $keyword);
   $query = "SELECT * FROM kantin 
            WHERE 
            nama_warung LIKE '%$keyword%' OR 
            date LIKE '%$keyword%' OR 
            deskripsi LIKE '%$keyword%'
            ";
   return query($query);
}
function cari_menu($keyword)
{
   global $conn;
   $keyword = mysqli_real_escape_string($conn, $keyword);
   $query = "SELECT * FROM menu 
            WHERE 
            nama_makanan LIKE '%$keyword%' OR 
            rate LIKE '%$keyword%' OR 
            price LIKE '%$keyword%' OR
            nama_warung LIKE '%$keyword%'
            ";
   return query($query);
}
function cari_makanan($keyword)
{
   global $conn;
   $keyword = mysqli_real_escape_string($conn, $keyword);
   $query = "SELECT * FROM menu 
            WHERE type = 'makanan' AND
            (nama_warung LIKE '%$keyword%' OR 
            nama_makanan LIKE '%$keyword%' OR 
            price LIKE '%$keyword%' OR
            rate LIKE '%$keyword%')
            ";
   return query($query);
}
function cari_minuman($keyword)
{
   global $conn;
   $keyword = mysqli_real_escape_string($conn, $keyword);
   $query = "SELECT * FROM menu 
            WHERE type = 'minuman' AND
            (nama_warung LIKE '%$keyword%' OR 
            nama_makanan LIKE '%$keyword%' OR 
            price LIKE '%$keyword%' OR
            rate LIKE '%$keyword%')
            ";
   return query($query);
}
function cari_cemilan($keyword)
{
   global $conn;
   $keyword = mysqli_real_escape_string($conn, $keyword);
   $query = "SELECT * FROM menu 
            WHERE type = 'cemilan' AND
            (nama_warung LIKE '%$keyword%' OR 
            nama_makanan LIKE '%$keyword%' OR 
            price LIKE '%$keyword%' OR
            rate LIKE '%$keyword%')
            ";
   return query($query);
}


function register($data)
{
   global $conn;

   $username = strtolower(stripslashes($data["username"]));
   $email = mysqli_real_escape_string($conn, $data["emailup"]);
   $password = mysqli_real_escape_string($conn, $data["passwordup"]);
   $password2 = mysqli_real_escape_string($conn, $data["passwordup2"]);

   // cek username sudah ada atau belum
   $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
   if (mysqli_fetch_assoc($result)) {
      echo "<script>alert('Username yang dipilih sudah terdaftar!');</script>";
      return false;
   }

   // cek konfirmasi password
   if ($password !== $password2) {
      echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
      return false;
   }

   // enkripsi password
   $passwordHash = password_hash($password, PASSWORD_DEFAULT);

   // tambahkan user baru ke database
   $query = "INSERT INTO users (email,username, password) VALUES ('$email', '$username', '$passwordHash')";
   mysqli_commit($conn);
   mysqli_query($conn, $query);


   return mysqli_affected_rows($conn);
}
