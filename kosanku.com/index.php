<?php

include 'API/koneksi.php';

// Data Kos dengan Harga Termurah
$sql = "SELECT * FROM kos ORDER BY harga ASC LIMIT 8";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Simpan hasil query ke dalam array
$kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Kos Promo php
// Data Kos Promo
$sql = "SELECT * FROM kos WHERE promo = 1 ORDER BY harga_setelah_promo ASC LIMIT 3";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Simpan hasil query ke dalam array
$promo_kos_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="./style/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./style/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./style/assets/favicon/favicon-16x16.png">
    <title>Marikos - Temukan Kos Impianmu</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>

    <nav id="navbar">
        <div class="logo"><img src="./style/assets/logo.png" alt="Marikos" srcset=""></div>

        <div class="search-container">
            <input type="text" id="search_query" placeholder="Masukkan lokasi/Area/Alamat" required>
            <button class="button nav-search-btn" id="search_btn" onclick="searchKos()">Cari</button>
        </div>

        <div class="nav-links">
            <a href="#">Tentang Kami</a>
            <button class="button nav-daftar-btn" onclick=" window.open('https://wa.me/6289605894010?text=Halo,%20saya%20ingin%20mendaftarkan%20kos%20Saya.','_blank')">Daftarkan Kosmu</button>
        </div>
    </nav>

    <div class="container-wrapper-main">

        <div id="search_results" class="search-results" style="display: none;"></div>

        <section class="hero" id="hero">
            <img src="./style/assets/hero-img.png" alt="Modern Building" class="hero-image">
            <div class="hero-content">
                <h1>Temukan Kos yang pas dalam hitungan detik!</h1>
                <p>Kita bisa bantu kalian dalam nyari kos yang dekat dan terjangkau!</p>
                <button class="button cta-button" onclick="scrollToKosTermurah()">Cari Sekarang!</button>
            </div>
        </section>

        <h2 class="section-title" id="kos-termurah">Paling murah di Denpasar</h2>
        <div class="kos-grid">
            <?php if (empty($kos_list)): ?>
                <p>Tidak ada kos yang tersedia.</p>
            <?php else: ?>
                <?php foreach($kos_list as $kos): ?>

            <div class="kos-card" onclick="openKosPage(<?php echo $kos['id']; ?>)">
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
                    <div class="kos-title">
                        <?php 
                            $max_length = 20;
                            if (strlen($kos['nama_kos']) > $max_length) {
                                echo htmlspecialchars(substr($kos['nama_kos'], 0, $max_length)) . '...';
                            } else {
                                echo htmlspecialchars($kos['nama_kos']);
                            }
                        ?>
                    </div>
                    <div class="kos-location">
                        <?php 
                            $max_length = 20; 
                            if (strlen($kos['alamat_kos']) > $max_length) {
                                echo htmlspecialchars(substr($kos['alamat_kos'], 0, $max_length)) . '...';
                            } else {
                                echo htmlspecialchars($kos['alamat_kos']);
                            }
                        ?>
                    </div>
                    <div class="kos-price">
                        Rp. 
                        <?php echo number_format((float)$kos['harga'], 3, ',', '.'); ?>
                        ,000 / Bulan
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <button class="button see-more-button">Lihat Lebih Banyak</button>


        <h2 class="section-title">Kos yang lagi promo!</h2>
        <div class="promo-grid">
            <?php if (!empty($promo_kos_list)): ?>
                <!-- Card Besar -->
                <div class="promo-card large" onclick="openKosPage(<?php echo $promo_kos_list[0]['id']; ?>)">
                    <div class="promo-image">
                        <?php
                        // Ambil foto dari tabel foto_kos untuk promo
                        $sqlFoto = "SELECT foto FROM foto_kos WHERE kos_id = " . $promo_kos_list[0]['id'];
                        $resultFoto = mysqli_query($conn, $sqlFoto);
                        $foto = mysqli_fetch_assoc($resultFoto);
                        ?>
                        <?php if (!empty($foto['foto'])): ?>
                            <?php $imagePath = "./uploads/" . basename($foto['foto']); ?>
                            <?php if (file_exists($imagePath)): ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($promo_kos_list[0]['nama_kos']); ?>">
                            <?php else: ?>
                                <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                        <?php endif; ?>
                    </div>
                    <div class="promo-details">
                        <div class="promo-title"><?php echo htmlspecialchars($promo_kos_list[0]['nama_kos']); ?></div>
                        <div class="promo-description"><?php echo htmlspecialchars($promo_kos_list[0]['deskripsi_kos']); ?></div>
                        <div class="promo-price">
                            <?php if (!empty($kos['promo'])): ?>
                                <span class="original">Rp. <?php echo number_format((float)$promo_kos_list[0]['harga'], 3, ',', '.'); ?>,000</span>
                                <span class="promo-price">Rp. <?php echo number_format((float)$promo_kos_list[0]['harga_setelah_promo'], 3, ',', '.'); ?>,000</span>
                            <?php else: ?>
                                Rp. <?php echo number_format($kos['harga'], 3, ',', '.'); ?>,000
                            <?php endif; ?>
                                / Bulan
                        </div>
                    </div>
                </div>

                <!-- Card Kecil -->
                <?php for ($i = 1; $i < count($promo_kos_list) && $i < 3; $i++): ?>
                    <div class="promo-card small" onclick="openKosPage(<?php echo $promo_kos_list[$i]['id']; ?>)">
                        <div class="promo-image">
                            <?php
                            // Ambil foto dari tabel foto_kos untuk promo
                            $sqlFoto = "SELECT foto FROM foto_kos WHERE kos_id = " . $promo_kos_list[$i]['id'];
                            $resultFoto = mysqli_query($conn, $sqlFoto);
                            $foto = mysqli_fetch_assoc($resultFoto);
                            ?>
                            <?php if (!empty($foto['foto'])): ?>
                                <?php $imagePath = "./uploads/" . basename($foto['foto']); ?>
                                <?php if (file_exists($imagePath)): ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($promo_kos_list[$i]['nama_kos']); ?>">
                                <?php else: ?>
                                    <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                                <?php endif; ?>
                            <?php else: ?>
                                <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                            <?php endif; ?>
                        </div>
                        <div class="promo-details">
                            <div class="promo-title"><?php echo htmlspecialchars($promo_kos_list[$i]['nama_kos']); ?></div>
                            <div class="promo-location"><?php echo htmlspecialchars($promo_kos_list[$i]['alamat_kos']); ?></div>
                            <div class="promo-price">Rp. <?php echo number_format((float)$promo_kos_list[0]['harga_setelah_promo'], 3, ',', '.'); ?>,000 / Bulan</div>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <p>Tidak ada kos promo yang tersedia.</p>
            <?php endif; ?>
        </div>



        <button class="button see-more-button">Lihat Lebih Banyak</button>

        <div class="daftar-section">
            <h2>Mau kos - kosan mu banyak yang nyari?</h2>
            <h2>Daftarkan Sekarang!</h2>
            <button class="button daftar-cta" onclick=" window.open('https://wa.me/6289605894010?text=Halo,%20saya%20ingin%20mendaftarkan%20kos%20Saya.','_blank')">Hubungi Kami</button>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-main">
                <div class="footer-brand">
                    <div class="logo"><img src="./style/assets/logo.png" alt="" srcset=""></div>
                    <div class="subtitle">#45, In nostrud exercitation,<br>Fedora, neyh-nedbog<br>coloresit, e345003</div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>EXPLORE</h3>
                    <ul>
                        <li><a href="#">Denpasar Utara</a></li>
                        <li><a href="#">Denpasar Timur</a></li>
                        <li><a href="#">Denpasar Selatan</a></li>
                        <li><a href="#">Denpasar Barat</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>KNOW MORE</h3>
                    <ul>
                        <li><a href="#">Apa itu Marikos?</a></li>
                        <li><a href="#">Cara Daftar Kos</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Syarat dan Ketentuan Umum</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>ABOUT</h3>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="admin-login.php" target="_blank">Admin?</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2024 Marikos - Temukan Kos Impianmu</p>
            </div>
        </div>
    </footer>


    <script src="./script/script.js"></script>
</body>
</html>

<?php
    mysqli_close($conn);
?>