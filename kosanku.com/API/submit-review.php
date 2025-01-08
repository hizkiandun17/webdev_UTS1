<?php
include 'koneksi.php'; // Pastikan file ini ada dan berisi koneksi ke database

$response = array(); // Array untuk menyimpan respons

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kos_id = intval($_POST['kos_id']);
    $reviewerName = mysqli_real_escape_string($conn, $_POST['reviewerName']);
    $reviewRating = intval($_POST['reviewRating']);
    $reviewText = mysqli_real_escape_string($conn, $_POST['reviewText']);

    // Query untuk memasukkan review ke dalam database
    $sqlInsertReview = "INSERT INTO kos_review (kos_id, reviewer_name, rating, review_text) VALUES ('$kos_id', '$reviewerName', '$reviewRating', '$reviewText')";

    if (mysqli_query($conn, $sqlInsertReview)) {
        header("Location: ../view-kos.php?id=$kos_id");
        exit();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . mysqli_error($conn);
    }
}

// Mengembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Tutup koneksi
mysqli_close($conn);
?>
