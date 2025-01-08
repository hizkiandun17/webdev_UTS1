<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama_pemilik = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) : null;
    $nama_kos = mysqli_real_escape_string($conn, $_POST['nama_kos']);
    $alamat_kos = mysqli_real_escape_string($conn, $_POST['alamat_kos']);
    $deskripsi_kos = mysqli_real_escape_string($conn, $_POST['deskripsi_kos']);
    $luas_bangunan = mysqli_real_escape_string($conn, $_POST['luas_bangunan']);
    $electricity = isset($_POST['include_listrik']) ? mysqli_real_escape_string($conn, $_POST['include_listrik']) : null;
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $promo = mysqli_real_escape_string($conn, $_POST['promo']);
    $harga_setelah_promo = mysqli_real_escape_string($conn, $_POST['harga_setelah_promo']);

    // Update query
    $sql = "UPDATE kos SET 
        nama_pemilik = '$nama_pemilik',
        whatsapp = '$whatsapp',
        email = '$email',
        jenis_kelamin = '$gender',
        nama_kos = '$nama_kos',
        alamat_kos = '$alamat_kos',
        deskripsi_kos = '$deskripsi_kos',
        luas_bangunan = '$luas_bangunan',
        include_listrik = '$electricity',
        harga = '$harga',
        promo = '$promo',
        harga_setelah_promo = '$harga_setelah_promo'
        WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Cek apakah ada foto baru yang diunggah
        if (!empty($_FILES['foto']['name'][0])) {
            $targetDir = "../uploads/";
            foreach ($_FILES['foto']['name'] as $key => $name) {
                $targetFilePath = $targetDir . basename($_FILES['foto']['name'][$key]);
                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'][$key], $targetFilePath)) {
                    // Simpan nama foto ke database
                    $sqlFoto = "INSERT INTO foto_kos (kos_id, foto) VALUES ($id, '" . mysqli_real_escape_string($conn, $_FILES['foto']['name'][$key]) . "')";
                    mysqli_query($conn, $sqlFoto);
                }
            }
        }

        // Redirect atau tampilkan pesan sukses
        header("Location: ../admin-dashboard.php?message=Data kos berhasil diperbarui");
        exit();
    } else {
        // Redirect atau tampilkan pesan error
        header("Location: ../admin-dashboard.php?message=Gagal memperbarui data kos: " . mysqli_error($conn));
        exit();
    }
}

mysqli_close($conn);
?>
