<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah benar

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID dari parameter URL

    // Query untuk menghapus data
    $sql = "DELETE FROM kos WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, redirect ke halaman sebelumnya dengan pesan sukses
        header("Location: ../admin-dashboard.php?message=Kos berhasil dihapus");
        exit();
    } else {
        // Jika gagal, redirect dengan pesan error
        header("Location: ../admin-dashboard.php?message=Gagal menghapus kos: " . mysqli_error($conn));
        exit();
    }
} else {
    // Jika ID tidak ada, redirect dengan pesan error
    header("Location: ../admin-dashboard.php?message=ID kos tidak ditemukan");
    exit();
}

mysqli_close($conn);
?>
