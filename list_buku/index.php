<?php
session_start();
require_once "../koneksi.php";
$koneksi = getKoneksi();

// Inisialisasi variabel pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Mengambil data buku berdasarkan pencarian atau semua buku
$query = "SELECT * FROM buku WHERE Judul LIKE ? ORDER BY Judul ASC";
$stmt = $koneksi->prepare($query);
$like_search = '%' . $search . '%';
$stmt->bind_param("s", $like_search);
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);

// Fungsi untuk membuat tautan ke huruf abjad
function createAbjadLink($letter)
{
    return '<a href="#' . $letter . '">' . $letter . '</a> ';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <!-- Favicon -->
    <link rel="icon" href="../assets/foto/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memuat Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
</head>

<body>
    <div class="container-fluid w-100 no-margin">
        <!-- Header Banner -->
        <div class="header-banner">
            <div class="header-banner-content">
                <img src="../assets/foto/LOGOSMP.png" alt="Logo SMP Negeri 3 Cibarusah" class="logo pt-2">
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
                            <a class="nav-link" aria-current="page" href="../index.php"><i class="bi bi-house-door"></i>
                                Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../#profil"><i class="bi bi-person"></i> Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php"><i class="bi bi-journal-text"></i> List Buku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../#kontak"><i class="bi bi-telephone"></i> Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Daftar Buku -->
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Daftar Buku</h2>
                <!-- Form Pencarian -->
                <form class="d-flex" action="index.php" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Cari Buku" aria-label="Search"
                        name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
            </div>

            <!-- Tautan Abjad -->
            <div class="abjad-links mb-3">
                <?php
                foreach (range('A', 'Z') as $letter) {
                    echo createAbjadLink($letter);
                }
                ?>
            </div>

            <!-- Daftar Buku berdasarkan Abjad -->
            <?php
            $currentLetter = '';
            foreach ($books as $book) {
                $firstLetter = strtoupper($book['Judul'][0]);
                if ($firstLetter !== $currentLetter) {
                    if ($currentLetter !== '') {
                        echo '</ul>'; // Tutup daftar sebelumnya
                    }
                    $currentLetter = $firstLetter;
                    echo '<h3 id="' . $currentLetter . '">' . $currentLetter . '</h3>';
                    echo '<ul class="list-group mb-3">';
                }
                echo '<li class="list-group-item">';
                echo '<a href="../detail_buku.php?isbn=' . htmlspecialchars($book['ISBN']) . '">' . htmlspecialchars($book['Judul']) . '</a>';
                echo '</li>';
            }
            if ($currentLetter !== '') {
                echo '</ul>'; // Tutup daftar terakhir
            }
            ?>
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
                        <a href="../index.php">Home</a> |
                        <a href="../#profil">Profil</a> |
                        <a href="list_buku/index.php">List Buku</a> |
                        <a href="../#kontak">Kontak</a>
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
    <!-- Custom JS -->
    <script src="../script.js"></script>
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
</body>

</html>