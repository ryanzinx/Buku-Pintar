<?php
session_start();
require_once "koneksi.php";
$koneksi = getKoneksi();

// Mendapatkan ISBN dari URL
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Mengambil data buku berdasarkan ISBN
    $query = "SELECT * FROM buku WHERE ISBN = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "Buku tidak ditemukan.";
        exit;
    }
} else {
    echo "ISBN tidak disediakan.";
    exit;
}

// Mendapatkan hanya nama file tanpa path
$fileName = basename($book['File_Buku']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?php echo htmlspecialchars($book['Judul']); ?></title>
    <!-- Favicon -->
    <link rel="icon" href="assets/foto/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
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
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php"><i class="bi bi-house-door"></i>
                                Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#profil"><i class="bi bi-person"></i> Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./list_buku/"><i class="bi bi-journal-text"></i> List
                                Buku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#kontak"><i class="bi bi-telephone"></i> Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Detail Buku -->
        <div class="container py-3">
            <center>
                <h3>Detail Buku</h3>
            </center>
            <center>
                <hr width="50%">
            </center>
            <div class="row pt-3">
                <div class="col-md-4">
                    <img src="<?php echo htmlspecialchars($book['Cover_Buku']); ?>" class="img-fluid" alt="Cover Buku">
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($book['Judul']); ?></h2>
                    <p><strong>Penulis:</strong> <?php echo htmlspecialchars($book['Penulis']); ?></p>
                    <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($book['Penerbit']); ?></p>
                    <p><strong>Tahun Terbit:</strong> <?php echo htmlspecialchars($book['Tahun_Terbit']); ?></p>
                    <p><strong>Kategori:</strong> <?php echo htmlspecialchars($book['Kategori']); ?></p>
                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['ISBN']); ?></p>
                    <p><strong>File Buku:</strong> <a href="assets/buku/<?php echo htmlspecialchars($fileName); ?>"
                            download><?php echo htmlspecialchars($fileName); ?></a></p>


                    <p><?php echo nl2br(htmlspecialchars($book['Deskripsi'])); ?></p>
                    <button class="btn btn-primary" onclick="openPDF()">Baca Online</button>
                    <a href="./list_buku/" class="btn btn-secondary">Kembali ke Daftar Buku</a>
                </div>
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
                <div class="row pt-5">
                    <div class="col text-center">
                        <p>&copy; 2024 SMP Negeri 3 Cibarusah. All Rights Reserved.</p>
                    </div>
                </div>
                <!-- Garis Pemisah -->
                <center>
                    <hr width="50%">
                </center>
                <div class="row">
                    <div class="col text-center">
                        <a href="index.php" onclick="loadPage(this.href); return false;">Home</a> |
                        <a href="profil.php" onclick="loadPage(this.href); return false;">Profil</a> |
                        <a href="list_buku.php" onclick="loadPage(this.href); return false;">List Buku</a> |
                        <a href="kontak.php" onclick="loadPage(this.href); return false;">Kontak</a>
                    </div>
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
    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
    <!-- Inisialisasi Peta -->
    <script>
        var map = L.map('map').setView([-6.414500000000, 107.097100000000], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([-6.414500000000, 107.097100000000]).addTo(map)
            .bindPopup('SMP Negeri 3 Cibarusah')
            .openPopup();
    </script>
    <!-- Fungsi untuk membuka PDF di tab baru -->
    <script>
        function openPDF() {
            var url = 'assets/buku/<?php echo htmlspecialchars($fileName); ?>';
            window.open(url, '_blank');
        }
    </script>
</body>

</html>