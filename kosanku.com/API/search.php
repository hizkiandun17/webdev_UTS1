<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah benar

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']); // Mengambil dan membersihkan input pencarian

    // Query untuk mencari kos berdasarkan lokasi, area, atau alamat
    $sql = "SELECT * FROM kos WHERE alamat_kos LIKE '%$query%' OR nama_kos LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    $kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $layout = isset($_GET['layout']) ? $_GET['layout'] : 'default'; // Menentukan layout

    // Menampilkan hasil pencarian berdasarkan layout
    if ($layout === 'admin') {
        // Layout untuk admin
        echo '<div class="admin-card-container">';
        if (empty($kos_list)) {
            echo '<div class="empty-state"><p>Tidak ada kos yang ditemukan untuk pencarian "' . htmlspecialchars($query) . '"</p></div>';
        } else {
            foreach ($kos_list as $kos) {
                echo '<div class="admin-kos-card">';
                echo '<h3>' . htmlspecialchars($kos['nama_kos']) . '</h3>';
                echo '<p>' . htmlspecialchars($kos['alamat_kos']) . '</p>';
                echo '<p>Rp. ' . number_format($kos['harga'], 3, ',', '.') . ',000 / Bulan</p>';
                echo '</div>';
            }
        }
        echo '</div>';
    } else {
        // Layout default
        echo '<div class="search-card-container">';
        if (empty($kos_list)) {
            echo '<div class="empty-state"><p>Tidak ada kos yang ditemukan untuk pencarian "' . htmlspecialchars($query) . '"</p></div>';
        } else {
            foreach ($kos_list as $kos) {
                echo '<div class="kos-card" onclick="openKosPage(' . $kos['id'] . ')">' ;
                echo '<div class="kos-image">';
                // Ambil foto dari tabel foto_kos
                $sqlFoto = "SELECT foto FROM foto_kos WHERE kos_id = " . $kos['id'];
                $resultFoto = mysqli_query($conn, $sqlFoto);
                $foto = mysqli_fetch_assoc($resultFoto);
                if (!empty($foto['foto'])) {
                    $imagePath = "./uploads/" . $foto['foto'];
                    echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($kos['nama_kos']) . '">';
                } else {
                    echo '<img src="/api/placeholder/400/300" alt="Tidak ada gambar">';
                }
                echo '</div>';
                echo '<div class="kos-details">';
                echo '<div class="kos-info">';
                echo '<h3>' . htmlspecialchars(substr($kos['nama_kos'], 0, 30)) . '...</h3>';
                echo '<p class="location">' . htmlspecialchars($kos['alamat_kos']) . '</p>';
                echo '<p class="description">' . htmlspecialchars(substr($kos['deskripsi_kos'], 0, 50)) . '...</p>';
                echo '<p class="price">Rp. ' . number_format($kos['harga'], 3, ',', '.') . ',000 / Bulan</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        echo '</div>';
    }
} else {
    echo "Query tidak valid.";
}

mysqli_close($conn);
?>