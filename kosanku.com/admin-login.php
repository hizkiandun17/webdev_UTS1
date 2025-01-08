<?php
    // Mulai sesi
    session_start();

    // Menghubungkan ke database
    $conn = mysqli_connect('localhost', 'root', '', 'db_marikos'); // Pastikan database sudah ada

    // Memeriksa koneksi
    if (!$conn) {
        die("Koneksi ke server gagal: " . mysqli_connect_error());
    }

    // Memproses form login
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Mencari pengguna di database
        $sql = "SELECT * FROM dataadmin WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Memverifikasi password
            if (password_verify($password, $row['password'])) {
                // Set session dan redirect ke halaman admin
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nama'];
                header("Location: ./admin-dashboard.php"); // Ganti dengan halaman admin Anda
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan!";
        }
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="./style/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./style/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./style/assets/favicon/favicon-16x16.png">
    <title>Selamat Datang Adminkuh</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container-wrapper-login">
        <div class="login-container">
            <div class="login-form">
                <div class="logo">
                    <img src="./style/assets/logo.png" alt="MariKos" height="25">
                </div>
                <h1>Selamat Datang Admin</h1>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="form">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Alamat email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit">Masuk</button>
                </form>

                <div class="terms">
                Bantu orang temukan Kos yang pas dalam hitungan detik!<br>
                    <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </div>
            </div>

            <img src="./style/assets/hero-login.jpg" class="illustration" alt="" srcset="">
        </div>
    </div>
</body>
</html>