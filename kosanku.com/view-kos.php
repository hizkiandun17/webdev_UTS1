<?php
// Sertakan Koneksi Database
include 'API/koneksi.php'; // Pastikan file ini ada dan berisi koneksi ke database

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil Data Kos Berdasarkan ID
$sqlKos = "SELECT * FROM kos WHERE id = $id";
$resultKos = mysqli_query($conn, $sqlKos);

if (!$resultKos) {
    die("Query gagal: " . mysqli_error($conn));
}

$kos = mysqli_fetch_assoc($resultKos);

// Pastikan data kos ditemukan
if (!$kos) {
    die("Kos tidak ditemukan.");
}

// Ambil Data Foto Berdasarkan ID Kos
$sqlFoto = "SELECT foto FROM foto_kos WHERE kos_id = $id LIMIT 3";
$resultFoto = mysqli_query($conn, $sqlFoto);

if (!$resultFoto) {
    die("Query gagal: " . mysqli_error($conn));
}

// Simpan hasil query foto ke dalam array
$foto_list = mysqli_fetch_all($resultFoto, MYSQLI_ASSOC);

// Cek apakah ada foto yang ditemukan
if (empty($foto_list)) {
    $foto_list = []; // Inisialisasi sebagai array kosong jika tidak ada foto
}

// Ambil Data Review Berdasarkan ID Kos
$sqlReview = "SELECT * FROM kos_review WHERE kos_id = $id ORDER BY created_at DESC"; // Pastikan ada kolom created_at di tabel review_kos
$resultReview = mysqli_query($conn, $sqlReview);
$reviews = mysqli_fetch_all($resultReview, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="./style/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./style/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./style/assets/favicon/favicon-16x16.png">
    <title><?php echo htmlspecialchars($kos['nama_kos']); ?></title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <nav>
        <div class="logo"><img src="./style/assets/logo.png" alt="Kosanku" srcset=""></div>

        <div class="search-container">
            <input type="text" id="search_query" placeholder="Masukkan lokasi/Area/Alamat" required>
            <button class="button nav-search-btn" id="search_btn" onclick="searchKoss()">Cari</button>
        </div>

        <div class="nav-links">
            <a href="#">Tentang Kami</a>
            <button class="button nav-daftar-btn" onclick=" window.open('https://wa.me/6289605894010?text=Halo,%20saya%20ingin%20mendaftarkan%20kos%20Saya.','_blank')">Daftarkan Kosmu</button>
        </div>
    </nav>

    <div class="container-wrapper-view-kos">

        <div id="search_results" class="search-results" style="display: none;"></div>

        <div class="property-header">
            <h1><?php echo htmlspecialchars($kos['nama_kos']); ?></h1>
            <div class="property-info">
                <span><i class="fa fa-user" aria-hidden="true"></i> <?php echo htmlspecialchars($kos['nama_pemilik']); ?></span>
                <span><i class="fa fa-phone" aria-hidden="true"></i> +<?php echo htmlspecialchars($kos['whatsapp']); ?></span>
                <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo htmlspecialchars($kos['alamat_kos']); ?></span>

                <button class="button rent-button" onclick="window.open('https://wa.me/<?php echo htmlspecialchars($kos['whatsapp']); ?>?text=Halo,%20saya%20ingin%20mengajukan%20atau%20bertanya%20-%20tanya%20mengenai%20kos.','_blank')">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i> Ajukan Sewa
                </button>
            </div>
        </div>

        <div class="gallery">
            <div class="main-image">
                <?php if (!empty($foto_list)): ?>
                    <?php $mainImagePath = "./uploads/" . $foto_list[0]['foto']; ?>
                    <img src="<?php echo $mainImagePath; ?>" alt="<?php echo htmlspecialchars($kos['nama_kos']); ?>">
                <?php else: ?>
                    <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                <?php endif; ?>
            </div>
            <div class="group-side-image">
                <?php for ($i = 1; $i < count($foto_list) && $i < 3; $i++): ?>
                    <div class="side-image">
                        <?php $sideImagePath = "./uploads/" . $foto_list[$i]['foto']; ?>
                        <img src="<?php echo $sideImagePath; ?>" alt="<?php echo htmlspecialchars($kos['nama_kos']); ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="content">
            <div class="left-content">
                <div class="description">

                    <h2>Property Description</h2>
                    <p><?php echo htmlspecialchars($kos['deskripsi_kos']); ?></p>

                    <div class="features">
                        <div class="feature">
                            <div class="feature-icon">📏</div>
                            <span><?php echo htmlspecialchars($kos['luas_bangunan']); ?>²</span>
                        </div>
                        <div class="feature">
                            <div class="feature-icon">⚡</div>
                            <span>
                                <?php 
                                    if ($kos['include_listrik'] === 'yes') {
                                        echo "Kos ini sudah termasuk listrik";
                                    } else {
                                        echo "Kos ini belum termasuk listrik";
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="reviews">
                    <h2>Top Reviews</h2>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review">
                                <div class="review-header">
                                    <div class="review-avatar"></div>
                                    <div>
                                        <h3><?php echo htmlspecialchars($review['reviewer_name']); ?></h3>
                                        <div class="stars"><?php echo str_repeat('⭐', $review['rating']); ?></div>
                                    </div>
                                </div>
                                <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="noreview">Belum ada review.</p>
                    <?php endif; ?>
                </div>

                <!-- <button id="addReviewBtn" class="addreview-btn"  onclick="togglePopup()">Add Review</button> -->

                <!-- modal add review -->
                <div id="reviewModal" class="modal-review">
                    <div class="modal-content">
                        <h2>Masukkan Review</h2>
                        <form id="reviewForm" action="./API/submit-review.php" method="POST">
                            <input type="hidden" name="kos_id" value="<?php echo $id; ?>">
                            <div class="data">
                                <div>
                                    <input type="text" id="reviewerName" name="reviewerName" required placeholder="Masukkan Nama">
                                </div>
                                <div>
                                    <label for="reviewRating">Rating:</label>
                                    <select id="reviewRating" name="reviewRating" required>
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                </div>
                                <div>
                                    <textarea id="reviewText" name="reviewText" rows="4" required placeholder="Kata - kata hari ini reviewerrr"></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="reviewSubmit-btn">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="pricing-card">
                <div class="price">
                    <div class="original-price">
                        <?php 
                            if ($kos['promo'] === 'yes') {
                                echo "Rp. " . number_format((float)$kos['harga'], 3, ',', '.') . ",000";
                            } else {
                                echo "";
                            }
                        ?>
                    </div>
                    <div class="current-price">
                        <?php 
                            if ($kos['promo'] === 'yes') {
                                echo "Rp. " . number_format((float)$kos['harga_setelah_promo'], 3, ',', '.') . ",000 / Bulan";
                            } else {
                                
                                echo "Rp. " . number_format((float)$kos['harga'], 3, ',', '.') . ",000 / Bulan";
                            }
                        ?>
                    </div>
                </div>
                <p>Interested in renting?</p>
                <button class="rent-button" onclick="window.open('https://wa.me/<?php echo htmlspecialchars($kos['whatsapp']); ?>?text=Halo,%20saya%20ingin%20mengajukan%20atau%20bertanya%20-%20tanya%20mengenai%20kos.','_blank')">Rent Now</button>
            </div>
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
                        <li><a href="#">Apa itu Kosanku?</a></li>
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
                <p>© 2024 Kosanku - Temukan Kos Impianmu</p>
            </div>
        </div>
    </footer>
    
    <script src="./script/script.js"></script>
</body>
</html>

<?php
// Tutup koneksi
mysqli_close($conn);
?>
