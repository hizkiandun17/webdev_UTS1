<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pemilik = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['gender']);
    $nama_kos = mysqli_real_escape_string($conn, $_POST['nama_kos']);
    $alamat_kos = mysqli_real_escape_string($conn, $_POST['alamat_kos']);
    $deskripsi_kos = mysqli_real_escape_string($conn, $_POST['deskripsi_kos']);
    $luas_bangunan = mysqli_real_escape_string($conn, $_POST['luas_bangunan']);
    $include_listrik = mysqli_real_escape_string($conn, $_POST['electricity']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $promo = mysqli_real_escape_string($conn, $_POST['promo']);
    $harga_setelah_promo = mysqli_real_escape_string($conn, $_POST['harga_setelah_promo']);

    $sql = "INSERT INTO kos (nama_pemilik, whatsapp, email, jenis_kelamin, nama_kos, alamat_kos, deskripsi_kos, luas_bangunan, include_listrik, harga, promo, harga_setelah_promo) 
            VALUES ('$nama_pemilik', '$whatsapp', '$email', '$jenis_kelamin', '$nama_kos', '$alamat_kos', '$deskripsi_kos', '$luas_bangunan', '$include_listrik', '$harga', '$promo', '$harga_setelah_promo')";

    if (mysqli_query($conn, $sql)) {
        $kos_id = mysqli_insert_id($conn);
        $target_dir = "uploads/";

        foreach ($_FILES['foto']['tmp_name'] as $key => $tmp_name) {
            $target_file = $target_dir . basename($_FILES["foto"]["name"][$key]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Cek apakah file adalah gambar
            $check = getimagesize($_FILES["foto"]["tmp_name"][$key]);
            if ($check === false) {
                echo "File bukan gambar.";
                $uploadOk = 0;
            }

            // Cek ukuran file
            if ($_FILES["foto"]["size"][$key] > 5000000) { // 5Mb
                echo "Maaf, ukuran file terlalu besar.";
                $uploadOk = 0;
            }

            // Cek format file
            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
                $uploadOk = 0;
            }

            // Jika semua cek lolos, upload file
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"][$key], $target_file)) {
                    // Simpan nama file ke database
                    $sql_foto = "INSERT INTO foto_kos (kos_id, foto) VALUES ('$kos_id', '" . basename($_FILES["foto"]["name"][$key]) . "')";
                    mysqli_query($conn, $sql_foto);
                } else {
                    echo "Maaf, terjadi kesalahan saat mengupload file.";
                }
            }
        }

        echo "Data berhasil disimpan!";
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

