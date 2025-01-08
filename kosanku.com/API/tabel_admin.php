<?php
    $dbname='db_marikos';
    $host='localhost';
    $password='';
    $username='root';

    $cnn = mysqli_connect($host,$username,$password,$dbname);

    if (!$cnn) {
        die ("Koneksi Gagal :".mysqli_connect_error());
    }

    $sql = "CREATE TABLE dataadmin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE
    )";

    if (mysqli_query($cnn, $sql)) {
        echo "Tabel Berhasil diBuat";
    } else {
        echo "Tabel Gagal di Buat :".mysqli_error($cnn);
    }

    mysqli_close($cnn);
?>