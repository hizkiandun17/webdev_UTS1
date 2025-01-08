<?php
    // Menghubungkan ke server MySQL
    $conn = mysqli_connect('localhost', 'root', ''); // Tambahkan password jika ada

    // Memeriksa koneksi
    if (!$conn) {
        die("Koneksi ke server gagal: " . mysqli_connect_error());
    }

    // Membuat database
    $sql = "CREATE DATABASE db_marikos";

    if (mysqli_query($conn, $sql)) {
        echo "Database Berhasil Dibuat";
    } else {
        echo "Gagal Membuat Database: " . mysqli_error($conn);
    }

    // Menutup koneksi
    mysqli_close($conn);
?>