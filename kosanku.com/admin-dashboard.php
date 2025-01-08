<?php
include 'API/koneksi.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="./style/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./style/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./style/assets/favicon/favicon-16x16.png">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container-wrapper-admin-dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo"><img src="./style/assets/logo.png" alt="" srcset="" height="25"><p>admin</p></div>
            <div class="menu">
                <a href="#" class="active" onclick="loadContent('dashboard')"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" onclick="loadContent('kos-list')"><i class="fas fa-building"></i> Daftar Kos</a>
                <a href="#" onclick="loadContent('kos-promo')"><i class="fas fa-user"></i> Daftar Kos Diskon</a>
                <form method="POST" action="">
                    <button class="button logout-button" type="submit" name="logout">Keluar</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Selamat Datang, <?php echo $_SESSION['user_name']; ?>!</h1>
                <button class="add-button" onclick="showModal('add')">
                    <i class="fas fa-plus"></i> Tambah Kos
                </button>
            </div>

            <div class="search-container">
                <input type="text" id="search_query" placeholder="Masukkan lokasi/Area/Alamat" required>
                <button class="button nav-search-btn" id="search_btn" onclick="searchKos()">Cari</button>
            </div>

            <div id="search_results" class="search-results"></div>

            <h2 class="section-header">List Kos</h2>

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
                                        <?php if (!empty($kos['harga_promo'])): ?>
                                            <span class="original-price">Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000</span>
                                            <span class="promo-price">Rp. <?php echo number_format($kos['harga_setelah_promo'], 3, ',', '.'); ?>,000</span>
                                        <?php else: ?>
                                            Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000
                                        <?php endif; ?>
                                        / Bulan
                                    </p>
                                </div>
                                <div class="kos-actions">
                                    <button class="btn btn-edit" onclick="showModalEdit('add')">Edit</button>
                                    <button class="btn btn-delete" onclick="confirmDelete(<?php echo $kos['id']; ?>)">Hapus</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Edit Modal Form -->
        <div id="editKosModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Ubah Data Properti/Kos</h2>
                <form id="editKosForm" method="POST" action="./API/update-kos.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="kosId"> <!-- Hidden field untuk ID kos -->

                    <h3>Data Pemilik</h3>
                    <input type="text" name="nama_pemilik" id="editNamaPemilik" placeholder="Nama Lengkap/Panggilan" required>
                    <input type="text" name="whatsapp" id="editWhatsApp" placeholder="Nomor WhatsApp (kode negara tanpa '+', contoh: 628...)" required>
                    <input type="email" name="email" id="editEmail" placeholder="Email" required>
                    <div class="radio-group">
                        <label>Jenis Kelamin:</label>
                        <label><input type="radio" name="gender" value="male" id="editGenderMale"> Laki - Laki</label>
                        <label><input type="radio" name="gender" value="female" id="editGenderFemale"> Perempuan</label>
                    </div>

                    <h3>Data Properti/Kos</h3>
                    <input type="text" name="nama_kos" id="editNamaKos" placeholder="Nama Properti/Kos" required>
                    <input type="text" name="alamat_kos" id="editAlamatKos" placeholder="Alamat Properti/Kos" required>
                    <textarea name="deskripsi_kos" id="editDeskripsiKos" placeholder="Deskripsi Properti/Kos" required></textarea>
                    <input type="text" name="luas_bangunan" id="editLuasBangunan" placeholder="Luas Bangunan Properti/Kos" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos sudah include Listrik?</label>
                        <label><input type="radio" name="electricity" value="yes" id="editElectricityYes"> Iya, sudah.</label>
                        <label><input type="radio" name="electricity" value="no" id="editElectricityNo"> Tidak</label>
                    </div>

                    <h3>Foto Properti/Kos</h3>
                    <div id="currentPhotos">
                        <!-- Tempat untuk menampilkan foto yang sudah ada -->
                    </div>
                    <input type="file" name="foto[]" accept="image/*" multiple>
                    <p>Unggah foto baru (kosongkan jika tidak ingin mengubah foto)</p>

                    <h3>Harga Properti/Kos</h3>
                    <input type="text" name="harga" id="editHarga" placeholder="Harga Properti/Kos - Per Bulan" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos ada paket promo?</label>
                        <label><input type="radio" name="promo" value="yes" id="editPromoYes"> Iya, ada.</label>
                        <label><input type="radio" name="promo" value="no" id="editPromoNo"> Tidak</label>
                    </div>
                    <input type="text" name="harga_setelah_promo" id="editHargaSetelahPromo" placeholder="Harga Properti/Kos setelah Promo (Kosongan jika tidak ada)">

                    <div class="action-buttons">
                        <button type="submit" class="add-button">Simpan</button>
                        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Form Tambah/Edit Kos -->
        <div id="kosModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Pendaftaran Properti/Kos</h2>
                <form id="kosForm" method="POST" action="./API/save-kos.php" enctype="multipart/form-data">
                    <h3>Data Pemilik</h3>
                    <input type="text" name="nama_pemilik" placeholder="Nama Lengkap/Panggilan" required>
                    <input type="text" name="whatsapp" placeholder="Nomor WhatsApp (kode negara tanpa '+', contoh: 628...)" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <div class="radio-group">
                        <label>Jenis Kelamin:</label>
                        <label><input type="radio" name="gender" value="male"> Laki - Laki</label>
                        <label><input type="radio" name="gender" value="female"> Perempuan</label>
                    </div>

                    <h3>Data Properti/Kos</h3>
                    <input type="text" name="nama_kos" placeholder="Nama Properti/Kos" required>
                    <input type="text" name="alamat_kos" placeholder="Alamat Properti/Kos" required>
                    <textarea name="deskripsi_kos" placeholder="Deskripsi Properti/Kos" required></textarea>
                    <input type="text" name="luas_bangunan" placeholder="Luas Bangunan Properti/Kos" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos sudah include Listrik?</label>
                        <label><input type="radio" name="electricity" value="yes"> Iya, sudah.</label>
                        <label><input type="radio" name="electricity" value="no"> Tidak</label>
                    </div>
                    <input type="file" name="foto[]" accept="image/*" multiple required>

                    <h3>Harga Properti/Kos</h3>
                    <input type="text" name="harga" placeholder="Harga Properti/Kos - Per Bulan" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos ada paket promo?</label>
                        <label><input type="radio" name="promo" value="yes"> Iya, ada.</label>
                        <label><input type="radio" name="promo" value="no"> Tidak</label>
                    </div>
                    <input type="text" name="harga_setelah_promo" placeholder="Harga Properti/Kos setelah Promo (Kosongan jika tidak ada)">

                    <div class="action-buttons">
                        <button type="submit" class="add-button">Simpan</button>
                        <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./script/script.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
