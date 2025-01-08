<?php
    $dbname='db_marikos';
    $host='localhost';
    $password='';
    $username='root';

    $cnn = mysqli_connect($host,$username,$password,$dbname);

    if (!$cnn) {
        die ("Koneksi Gagal :".mysqli_connect_error());
    }

    $sql = "CREATE TABLE kos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_pemilik VARCHAR(100) NOT NULL,
        whatsapp VARCHAR(20) NOT NULL,
        email VARCHAR(100) NOT NULL,
        jenis_kelamin ENUM('male', 'female') NOT NULL,
        nama_kos VARCHAR(100) NOT NULL,
        alamat_kos VARCHAR(255) NOT NULL,
        deskripsi_kos TEXT NOT NULL,
        luas_bangunan VARCHAR(50) NOT NULL,
        include_listrik ENUM('yes', 'no') NOT NULL,
        harga DECIMAL(10, 2) NOT NULL,
        promo ENUM('yes', 'no') NOT NULL,
        harga_setelah_promo DECIMAL(10, 2),
        foto VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (mysqli_query($cnn, $sql)) {
        echo "Tabel Berhasil diBuat";
    } else {
        echo "Tabel Gagal di Buat :".mysqli_error($cnn);
    }

    mysqli_close($cnn);
?>