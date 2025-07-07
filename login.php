<?php
session_start();
require 'function.php';

// Cek cookie login
if (!isset($_SESSION['login']) && isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    $result = mysqli_query($conn, "SELECT username FROM users WHERE no = '$id'");
    $row = mysqli_fetch_assoc($result);

    if ($row && $key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION["from_login"] = true;
    }
}

// Jika sudah login, langsung arahkan ke index
if (isset($_SESSION["login"])) {
    if (
        !isset($_COOKIE['id']) &&
        !isset($_COOKIE['key']) &&
        !isset($_SESSION['from_login'])
    ) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    header("Location: index.php");
    exit;
}

// Jika cookie HILANG tapi session masih ada → tendang ke login
if (!isset($_COOKIE['id'], $_COOKIE['key']) && isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// SIGNUP
if (isset($_POST["signup"])) {
    if (register($_POST) > 0) {
        echo "<script>alert('User baru berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Registrasi gagal.');</script>";
    }
}

// LOGIN
if (isset($_POST["signin"])) {
    $email = $_POST["emailin"] ?? '';
    $password = $_POST["passwordin"] ?? '';

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email ='$email'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = ($row["username"] === "superadmin") ? "admin" : "user";

            // Remember me
            if (isset($_POST["remember"])) {
                setcookie('id', $row['no'], time() + 60*60);
                setcookie('key', hash('sha256', $row['username']), time() + 60);
            }

            header("Location: index.php");
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login untuk KantinKita</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/v4-shims.min.css">
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form method="post">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" placeholder="Username" name="username" id="username" autofocus required />
                <input type="email" placeholder="Email" name="emailup" id="emailup" required />
                <input type="password" placeholder="Password" name="passwordup" id="passwordup" required />
                <input type="password" placeholder="Confirm Password" name="passwordup2" id="passwordup2" required />
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form method="post">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <?php if (isset($error)) : ?>

                    <p style="color: red; font-style: italic;">Username atau password salah!</p>

                <?php endif; ?>
                <input type="email" placeholder="Email" name="emailin" autofocus required />
                <input type="password" placeholder="Password" name="passwordin" required />
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">remember me?</label>
                </div>
                <button type="submit" name="signin">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Selamat Datang!</h1>
                    <p>Untuk tetap terhubung dengan kami, silakan login dengan info pribadi Anda</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Haloo</h1>
                    <p>Masukkan detail pribadi Anda dan mulailah perjalanan bersama kami</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>

</html>