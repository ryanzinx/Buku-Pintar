<?php
session_start();
require_once "koneksi.php";
$koneksi = getKoneksi();

// Fetch Fiksi books
$queryFiksi = "SELECT * FROM buku WHERE Kategori = 'Fiksi'";
$resultFiksi = mysqli_query($koneksi, $queryFiksi);
$fiksiBooks = mysqli_fetch_all($resultFiksi, MYSQLI_ASSOC);

// Fetch Non-Fiksi books
$queryNonFiksi = "SELECT * FROM buku WHERE Kategori = 'Non-Fiksi'";
$resultNonFiksi = mysqli_query($koneksi, $queryNonFiksi);
$nonFiksiBooks = mysqli_fetch_all($resultNonFiksi, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Pintar</title>
    <!-- Favicon -->
    <link rel="icon" href="assets/foto/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

</head>


<body>
    <div class="container-fluid w-100 no-margin">
        <!-- Header Banner -->
        <div class="header-banner">
            <div class="header-banner-content">
                <img src="assets/foto/LOGOSMP.png" alt="Logo SMP Negeri 3 Cibarusah" class="logo pt-2">
                <h1 class="pt-3">SMP NEGERI 3 CIBARUSAH</h1>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #87CEEB;">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php"><i
                                    class="bi bi-house-door"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#profil"><i class="bi bi-person"></i> Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="list_buku/"><i class="bi bi-journal-text"></i> List Buku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#kontak"><i class="bi bi-telephone"></i> Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-content">
                <h1>Selamat Datang di Buku Pintar</h1>
                <p>"A reader lives a thousand lives before he dies. The man who never reads lives only one."
                    <br>– George R.R. Martin
                </p>
                <a href="#content" class="btn btn-primary">Mulai Membaca</a>
            </div>
        </div>

        <div id="content" class="py-5 container">
            <!-- Konten halaman beranda -->
            <center>
                <h2 id="greeting"></h2>
            </center>
            <!-- Informasi Real-Time -->
            <div class="real-time-info">
                <h3>
                    <p id="real-time"></p>
                </h3>
            </div>
            <center>
                <hr width="35%">
            </center>
            <div class="row py-5">
                <div class="col-12">
                    <h3><i class="bi bi-book"></i> Buku Fiksi</h3>
                    <div class="row">
                        <?php foreach ($fiksiBooks as $index => $book): ?>
                            <div class="col-md-2">
                                <a href="detail_buku.php?isbn=<?php echo urlencode($book['ISBN']); ?>"
                                    class="text-decoration-none text-dark">
                                    <div class="card mb-4">
                                        <img src="assets/foto/placeholder.jpg"
                                            data-src="<?php echo htmlspecialchars($book['Cover_Buku']); ?>"
                                            class="card-img-top lazyload" alt="Cover Buku" width="300" height="400">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <?php echo htmlspecialchars(substr($book['Judul'], 0, 30)); ?>...</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-12">
                    <h3><i class="bi bi-book"></i> Buku Non-Fiksi</h3>
                    <div class="row">
                        <?php foreach ($nonFiksiBooks as $index => $book): ?>
                            <div class="col-sm-2 mb-4">
                                <a href="detail_buku.php?isbn=<?php echo urlencode($book['ISBN']); ?>"
                                    class="text-decoration-none text-dark">
                                    <div class="card">
                                        <img src="assets/foto/placeholder.jpg"
                                            data-src="<?php echo htmlspecialchars($book['Cover_Buku']); ?>"
                                            class="card-img-top lazyload" alt="Cover Buku" width="300" height="400">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <?php echo htmlspecialchars(substr($book['Judul'], 0, 30)); ?>...</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Garis Pemisah -->
        <center>
            <hr width="35%">
        </center>

        <!-- Profile Section -->
        <div id="profil" class="profile py-3 container">
            <h3 class="py-3">Profil Sekolah</h3>
            <!-- Ruang untuk Logo Sekolah -->
            <center>
                <img src="assets/foto/LOGOSMP.png" alt="Logo Sekolah" style="width: 150px; height: auto;">
            </center>
            <br>
            <h4>Visi</h4>
            <p>Terwujudnya Generasi yang Cerdas, Mandiri, Tekun dan Religius Serta Berwawasan Likungan, dengan indikator
                sebagai berikut:</p>
            <ul>
                <li>Meningkatnya hasil belajar siswa</li>
                <li>Tumbuh dan berkembangnya etos kerja yang profesional</li>
                <li>Lahirnya peserta didik yang memiliki kreativitas, kemandirian dan kecakapan hidup.</li>
                <li>Terciptanya budaya disiplin sekolah</li>
                <li>Terciptanya nuansa agamis yang mewarnai kehidupan sekolah</li>
                <li>Memiliki keharmonisan hubungan baik antar sesama warga sekolah maupun dengan luar sekolah</li>
                <li>Terpeliharanya kebersihan dan kesehatan warga sekolah dengan lingkungannya</li>
            </ul>
            <h4>Misi</h4>
            <ul>
                <li>Meningkatkan kualitas pembelajaran.</li>
                <li>Menumbuhkembangkan kompetisi guru dalam melaksanakan tupoksi</li>
                <li>Menumbuhkembangkan kreativitas, kemandirian dan kecakapan hidup.</li>
                <li>Menumbuhkembangkan budaya disiplin warga sekolah.</li>
                <li>Membudayakan kehidupan agamis pada semua warga sekolah.</li>
                <li>Menumbuhkan sikap tenggang rasa, kebersamaan, dan persaudaraan antar warga sekolah dengan
                    masyarakat.</li>
                <li>Menumbuhkembangkan budaya bersih, sehat dan cinta lingkungan.</li>
            </ul>

        </div>

        <!-- Garis Pemisah -->
        <center>
            <hr width="35%">
        </center>

        <!-- Contact Section -->
        <div id="kontak" class="kontak py-3 container">
            <h3>Kontak Kami</h3>
            <div class="social-icons d-flex justify-content-center" style="gap: 50px;">
                <a href="https://www.facebook.com" target="_blank"><i class="bi bi-facebook"
                        style="font-size: 2rem;"></i></a>
                <a href="https://www.instagram.com" target="_blank"><i class="bi bi-instagram"
                        style="font-size: 2rem;"></i></a>
                <a href="https://wa.me/1234567890" target="_blank"><i class="bi bi-whatsapp"
                        style="font-size: 2rem;"></i></a>
                <a href="mailto:email@example.com" target="_blank"><i class="bi bi-envelope"
                        style="font-size: 2rem;"></i></a>
            </div>
        </div>

        <!-- Garis Pemisah -->
        <center>
            <hr width="35%">
        </center>

        <footer class="py-3">
            <div class="container">
                <div class="row">
                    <!-- Informasi Kontak -->
                    <div class="col-md-6">
                        <h5>Hubungi Kami</h5>
                        <p>
                            <i class="bi bi-geo-alt-fill"></i> Jl. Pendidikan No.3, Cibarusah, Bekasi<br>
                            <i class="bi bi-telephone-fill"></i> +62 123 456 789<br>
                            <i class="bi bi-envelope-fill"></i> info@sekolahcibarusah.sch.id
                        </p>
                    </div>
                    <!-- Elemen Peta -->
                    <div class="col-md-6 d-flex flex-column align-items-center">
                        <h5>Lokasi Kami</h5>
                        <div id="map" style="height: 200px; width: 300px;"></div>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12 text-center">
                        <p>&copy; 2024 SMP Negeri 3 Cibarusah. All rights reserved.</p>
                    </div>
                </div>
                <!-- Garis Pemisah -->
                <center>
                    <hr width="50%">
                </center>
                <div class="row">
                    <div class="col text-center">
                        <a href="index.php">Home</a> |
                        <a href="#profil">Profil</a> |
                        <a href="./list_buku/index.php">List Buku</a> |
                        <a href="#kontak">Kontak</a>
                    </div>
                </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- LazySizes JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
    <script>
        var map = L.map('map').setView([-6.414500000000, 107.097100000000], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        L.marker([-6.414500000000, 107.097100000000]).addTo(map)
            .bindPopup('SMP Negeri 3 Cibarusah')
            .openPopup();
    </script>
</body>

</html>