<?php
    $dbname='db_marikos';
    $host='localhost';
    $password='';
    $username='root';

    $cnn = mysqli_connect($host,$username,$password,$dbname);

    if (!$cnn) {
        die ("Koneksi Gagal :".mysqli_connect_error());
    }

    $sql = "CREATE TABLE kos_review (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kos_id INT NOT NULL,
        reviewer_name VARCHAR(100) NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (kos_id) REFERENCES kos(id) ON DELETE CASCADE
    )";

    if (mysqli_query($cnn, $sql)) {
        echo "Tabel Berhasil diBuat";
    } else {
        echo "Tabel Gagal di Buat :".mysqli_error($cnn);
    }

    mysqli_close($cnn);
?>
