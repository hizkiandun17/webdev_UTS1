// Show modal tambah kos admin dashboard
    function showModal() {
        document.getElementById('kosModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('kosModal').style.display = 'none';
    }

    // Tambahkan event listener pada tombol "Tambah"
    document.querySelector('.add-button').addEventListener('click', showModal);
// 

// Buka card kos tab baru
    function openKosPage(id) {
        window.open('view-kos.php?id=' + id, '_blank');
    }
// 

// Filters AJAX
function filterKos(area = '', price_order = '') {
    // Mengosongkan konten utama
    document.querySelector('.main-content').innerHTML = '';

    var xhr = new XMLHttpRequest();
    var url = "API/filter-kos.php?area=" + encodeURIComponent(area) + "&price_order=" + encodeURIComponent(price_order);
    
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Mengisi konten utama dengan data yang diterima
            document.querySelector('.main-content').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
// 


// Hapus Kos
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kos ini?')) {
        console.log('Menghapus kos dengan ID:', id); // Log ID yang akan dihapus
        window.location.href = 'API/hapus-kos.php?id=' + id;
    }
}
// 

// Cari kos
function searchKos() {
    const query = document.getElementById('search_query').value;

    if (query) {
        fetch('API/search.php?query=' + encodeURIComponent(query))
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
// 

// Carikos page view-kos.php
function searchKoss() {
    const query = document.getElementById('search_query').value;

    if (query) {
        fetch('API/search.php?query=' + encodeURIComponent(query) + '&layout=view')
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

// 

// Edit Modal
function showModalEdit() {
    document.getElementById('editKosModal').style.display = 'block';
}

function closeModalEdit() {
    document.getElementById('editKosModal').style.display = 'none';
}

// Tambahkan event listener pada tombol "Tambah"
document.querySelector('.add-button').addEventListener('click', showModalEdit);


function openEditModal(kos) {
    // Mengisi data ke dalam modal
    document.getElementById('kosId').value = kos.id;
    document.getElementById('editNamaPemilik').value = kos.nama_pemilik;
    document.getElementById('editWhatsApp').value = kos.whatsapp;
    document.getElementById('editEmail').value = kos.email;
    document.getElementById('editNamaKos').value = kos.nama_kos;
    document.getElementById('editAlamatKos').value = kos.alamat_kos;
    document.getElementById('editDeskripsiKos').value = kos.deskripsi_kos;
    document.getElementById('editLuasBangunan').value = kos.luas_bangunan;
    document.getElementById('editHarga').value = kos.harga;
    document.getElementById('editHargaSetelahPromo').value = kos.harga_setelah_promo;

    // Set radio button untuk jenis kelamin
    if (kos.gender === 'male') {
        document.getElementById('editGenderMale').checked = true;
    } else {
        document.getElementById('editGenderFemale').checked = true;
    }

    // Set radio button untuk listrik
    if (kos.electricity === 'yes') {
        document.getElementById('editElectricityYes').checked = true;
    } else {
        document.getElementById('editElectricityNo').checked = true;
    }

    // Set radio button untuk promo
    if (kos.promo === 'yes') {
        document.getElementById('editPromoYes').checked = true;
    } else {
        document.getElementById('editPromoNo').checked = true;
    }

    // Tampilkan foto yang sudah ada
    const currentPhotosDiv = document.getElementById('currentPhotos');
    currentPhotosDiv.innerHTML = ''; // Kosongkan sebelumnya
    if (kos.foto) {
        kos.foto.forEach(function(foto) {
            const img = document.createElement('img');
            img.src = './uploads/' + foto; // Sesuaikan dengan path foto
            img.alt = 'Foto Kos';
            img.style.width = '100px'; // Atur ukuran foto
            img.style.marginRight = '10px';
            currentPhotosDiv.appendChild(img);
        });
    }

    // Tampilkan modal
    document.getElementById('editKosModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editKosModal').style.display = 'none';
}

// 

// Index Auto Scroll
function scrollToKosTermurah() {
    const element = document.getElementById('kos-termurah');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}
// 

// Reviews Function
document.getElementById('reviewForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah pengiriman formulir default

    const formData = new FormData(this);

    fetch('API/submit_review.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Tampilkan pesan dari server
        // Tambahkan logika untuk menampilkan review baru di halaman jika diperlukan
        // Misalnya, Anda bisa menambahkan elemen baru ke dalam daftar review
        location.reload(); // Reload halaman untuk menampilkan review terbaru
    })
    .catch(error => console.error('Error:', error));
});
// 

// Load content ad
function loadContent(page) {
    let url = '';

    // Mengatur URL berdasarkan halaman yang dipilih
    if (page === 'dashboard') {
        url = 'admin-dashboard.php'; // Halaman dashboard
    } else if (page === 'kos-list') {
        url = 'API/ad-kos-list.php'; // Halaman daftar kos
    } else if (page === 'kos-promo') {
        url = 'API/ad-kos-promo.php'; // Halaman daftar kos promo
    }

    // Mengambil konten dari URL yang ditentukan
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.querySelector('.main-content').innerHTML = data; // Ganti isi main-content

            // Mengatur kelas active pada tautan sidebar
            const menuItems = document.querySelectorAll('.menu a');
            menuItems.forEach(item => {
                item.classList.remove('active'); // Hapus kelas active dari semua item
            });

            // Menambahkan kelas active pada item yang diklik
            if (page === 'dashboard') {
                menuItems[0].classList.add('active'); // Dashboard
            } else if (page === 'kos-list') {
                menuItems[1].classList.add('active'); // Daftar Kos
            } else if (page === 'kos-promo') {
                menuItems[2].classList.add('active'); // Daftar Kos Promo
            }
        })
        .catch(error => console.error('Error:', error));
}
// 



    
