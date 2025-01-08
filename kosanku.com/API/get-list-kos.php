<?php
include 'koneksi.php';

$sql = "SELECT * FROM kos";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="card-container">
    <?php if (empty($kos_list)): ?>
        <div class="empty-state">
            <p>Belum ada data kos yang ditambahkan</p>
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
                            Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000 / Bulan
                        </p>
                    </div>
                    <div class="kos-actions">
                        <button class="btn btn-edit" onclick="editKos(<?php echo $kos['id']; ?>)">Edit</button>
                        <button class="btn btn-delete" onclick="deleteKos(<?php echo $kos['id']; ?>)">Hapus</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
mysqli_close($conn);
?>
