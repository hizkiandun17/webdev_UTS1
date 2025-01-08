<?php
    include 'koneksi.php';

    $sql = "SELECT * FROM kos";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    $kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>

    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: admin-login.php");
        exit();
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: ./admin-login.php");
        exit();
    }
?>

<?php

$area = isset($_GET['area']) ? $_GET['area'] : '';
$price_order = isset($_GET['price_order']) ? $_GET['price_order'] : '';

// Membangun query berdasarkan filter
$sql = "SELECT * FROM kos";
$conditions = [];

if (!empty($area)) {
    $conditions[] = "alamat_kos LIKE '%$area%'";
}

if ($price_order == 'terendah') {
    $sql .= " ORDER BY harga ASC";
} elseif ($price_order == 'tertinggi') {
    $sql .= " ORDER BY harga DESC";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="header">
                <h1>List Kos yang Lagi Diskon.</h1>
                <button class="add-button" onclick="showModal('add')">
                    <i class="fas fa-plus"></i> Tambah Kos
                </button>
            </div>

            <div class="card-container">
                <?php
                    // Ambil data kos yang sedang promo
                    $sql = "SELECT * FROM kos WHERE promo = 'yes'"; // Pastikan kolom promo sesuai dengan struktur tabel Anda
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("Query gagal: " . mysqli_error($conn));
                    }

                    $kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    if (empty($kos_list)): ?>
                        <div class="empty-state">
                            <p>Belum ada data kos yang sedang promo</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($kos_list as $kos): ?>
                <div class="kos-card">
                    <div class="kos-image">
                        <?php
                            // Ambil foto dari tabel foto_kos
                            $sqlFoto = "SELECT foto FROM foto_kos WHERE kos_id = " . $kos['id'];
                            $resultFoto = mysqli_query($conn, $sqlFoto);
                            $foto = mysqli_fetch_assoc($resultFoto);
                        ?>
                        <?php if (!empty($foto['foto'])): ?>
                            <?php $imagePath = "./uploads/" . $foto['foto']; ?>
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($kos['nama_kos']); ?>">
                        <?php else: ?>
                            <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                        <?php endif; ?>
                            </div>
                            <div class="kos-details">
                                <div class="kos-info">
                                    <h3><?php echo htmlspecialchars($kos['nama_kos']); ?></h3>
                                    <p class="location"><?php echo htmlspecialchars($kos['alamat_kos']); ?></p>
                                    <p class="description">
                                        <?php 
                                        $max_length = 150;
                                        if (strlen($kos['deskripsi_kos']) > $max_length) {
                                            echo htmlspecialchars(substr($kos['deskripsi_kos'], 0, $max_length)) . '...';
                                        } else {
                                            echo htmlspecialchars($kos['deskripsi_kos']);
                                        }
                                        ?>
                                    </p>
                                    <p class="price">
                                        <?php if (!empty($kos['promo'])): ?>
                                            <span class="original-price">Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000</span>
                                            <span class="promo-price">Rp. <?php echo number_format($kos['harga_setelah_promo'], 3, ',', '.'); ?>,000</span>
                                        <?php else: ?>
                                            Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000
                                        <?php endif; ?>
                                        / Bulan
                                    </p>
                                </div>
                                <div class="kos-actions">
                                    <button class="btn btn-edit" onclick="editKos(<?php echo $kos['id']; ?>)">Edit</button>
                                    <button class="btn btn-delete" onclick="confirmDelete(<?php echo $kos['id']; ?>)">Hapus</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>