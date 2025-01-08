<form>
                    <h3>Data Pemilik</h3>
                    <input type="text" placeholder="Nama Lengkap/Panggilan" required>
                    <input type="text" placeholder="Nomor WhatsApp (kode negara tanpa '+', contoh: 628...)">
                    <input type="email" placeholder="Email" required>
                    <div class="radio-group">
                        <label>Jenis Kelamin:</label>
                        <label><input type="radio" name="gender" value="male"> Laki - Laki</label>
                        <label><input type="radio" name="gender" value="female"> Perempuan</label>
                    </div>

                    <h3>Data Properti/Kos</h3>
                    <input type="text" placeholder="Nama Properti/Kos" required>
                    <input type="text" placeholder="Alamat Properti/Kos" required>
                    <textarea placeholder="Deskripsi Properti/Kos" required></textarea>
                    <input type="text" placeholder="Luas Bangunan Properti/Kos" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos sudah include Listrik?</label>
                        <label><input type="radio" name="electricity" value="yes"> Iya, sudah.</label>
                        <label><input type="radio" name="electricity" value="no"> Tidak</label>
                    </div>
                    <input type="file" placeholder="Masukkan Foto" required>

                    <h3>Harga Properti/Kos</h3>
                    <input type="text" placeholder="Harga Properti/Kos - Per Bulan" required>
                    <div class="radio-group">
                        <label>Apakah Properti/Kos ada paket promo?</label>
                        <label><input type="radio" name="promo" value="yes"> Iya, ada.</label>
                        <label><input type="radio" name="promo" value="no"> Tidak</label>
                    </div>
                    <input type="text" placeholder="Harga Properti/Kos setelah Promo (Kosongan jika tidak ada)">

                    <div class="action-buttons">
                        <button type="submit" class="add-button">Simpan</button>
                        <button type="button" class="btn btn-delete" onclick="closeModal()">Batal</button>
                    </div>
                </form>


                <?php if (empty($kos_list)): ?>
                    <div class="empty-state">
                        <p>Belum ada data kos yang ditambahkan</p>
                    </div>
                <?php else: ?>
                    <?php foreach($kos_list as $kos): ?>
                        


                        <div class="card-container">

                
<div class="kos-card">
    <div class="kos-image">
        <img src="/api/placeholder/400/300" alt="Kos Air">
    </div>
    <div class="kos-details">
        <div class="kos-info">
            <h3>Kos Air</h3>
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent varius maximus mauris sit amet mattis.</p>
            <p class="price">Rp. 900.000 / Bulan</p>
        </div>
        <div class="kos-actions">
            <button class="btn btn-edit">Edit</button>
            <button class="btn btn-delete">Hapus</button>
        </div>
    </div>
</div>
</div>



<?php if (!empty($kos['foto'])): ?>
                                    <?php $imagePath = "./uploads/" . $kos['foto']; ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($kos['nama_kos']); ?>">
                                    <p>Path: <?php echo $imagePath; ?></p> 
                                <?php else: ?>
                                    <img src="/api/placeholder/400/300" alt="Tidak ada gambar">
                                <?php endif; ?>



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

                $check = getimagesize($_FILES["foto"]["tmp_name"][$key]);
                if ($check === false) {
                    echo "File bukan gambar.";
                    $uploadOk = 0;
                }

                if ($_FILES["foto"]["size"][$key] > 5000000) { // 5Mb
                    echo "Maaf, ukuran file terlalu besar.";
                    $uploadOk = 0;
                }

                if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"][$key], $target_file)) {
                        $sql_foto = "INSERT INTO foto_kos (kos_id, foto) VALUES ('$kos_id', '$target_file')";
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






Dump 2 Januari
<!-- Search Function -->
function searchKoss() {
    const query = document.getElementById('search_query').value;

    if (query) {
        fetch('search.php?query=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('search_results').innerHTML = data;
                document.getElementById('search_results').style.display = 'block'; // Tampilkan hasil pencarian
                document.getElementById('hero').style.display = 'none'; // Sembunyikan hero section
            })
            .catch(error => console.error('Error:', error));
    } else {
        alert('Silakan masukkan lokasi/area/alamat untuk mencari kos.');
    }
}

<!-- search.php -->
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



<div class="sidebar">
            <div class="logo"><img src="./style/assets/logo.png" alt="" srcset="" height="25"><p>admin</p></div>
            <div class="menu">
                <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="admin-dashboard-kos-list.php"><i class="fas fa-building"></i> Daftar Kos</a>
                <a href="admin-dashboard-kos-promo.php"><i class="fas fa-user"></i> Daftar Kos Diskon</a>
                <form method="POST" action="">
                    <button class="button logout-button" type="submit" name="logout">Keluar</button>
                </form>
            </div>
        </div>