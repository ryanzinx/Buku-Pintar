<?php
session_start();

// Periksa  pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// File koneksi.php
require_once "../koneksi.php";

// Koneksi ke database
$koneksi = getKoneksi();

// Proses penambahan buku baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Memeriksa apakah file buku telah diunggah
  if (!empty($_FILES['file_buku']['name']) && !empty($_FILES['cover_buku']['name'])) {
    // Memeriksa jenis file buku
    $fileType = $_FILES['file_buku']['type'];
    if ($fileType !== 'application/pdf') {
      echo '<script>alert("Hanya file PDF yang diperbolehkan!");</script>';
      exit();
    }

    // Memeriksa jenis file cover buku
    $coverType = $_FILES['cover_buku']['type'];
    $allowedCoverTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($coverType, $allowedCoverTypes)) {
      echo '<script>alert("Hanya file gambar (JPEG, PNG, GIF) yang diperbolehkan untuk cover buku!");</script>';
      exit();
    }

    // Direktori penyimpanan file
    $targetDirBuku = "../assets/buku/";
    $targetDirCover = "../assets/foto/";
    // Nama file setelah diunggah
    $targetFileBuku = $targetDirBuku . basename($_FILES["file_buku"]["name"]);
    $targetFileCover = $targetDirCover . basename($_FILES["cover_buku"]["name"]);

    // Pindahkan file yang diunggah ke direktori tujuan
    if (move_uploaded_file($_FILES["file_buku"]["tmp_name"], $targetFileBuku) && move_uploaded_file($_FILES["cover_buku"]["tmp_name"], $targetFileCover)) {
      // Potong bagian "../" dari path
      $dbPathBuku = str_replace("../", "", $targetFileBuku);
      $dbPathCover = str_replace("../", "", $targetFileCover);

      // Ambil data dari form
      $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
      $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
      $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
      $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
      $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
      $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
      $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

      // Query untuk menambahkan buku baru ke database
      $query = "INSERT INTO buku (ISBN, Judul, Penulis, Penerbit, Tahun_Terbit, Deskripsi, Kategori, File_Buku, Cover_Buku) 
                      VALUES ('$isbn', '$judul', '$penulis', '$penerbit', '$tahun_terbit', '$deskripsi', '$kategori', '$dbPathBuku', '$dbPathCover')";
      mysqli_query($koneksi, $query);

      // Redirect ke halaman manajemen buku
      header("Location: atur_buku.php");
      exit();
    } else {
      // Jika gagal mengunggah file
      echo '<script>alert("Terjadi kesalahan saat mengunggah file!");</script>';
      exit();
    }
  } else {
    // Jika pengguna tidak memilih file buku atau cover buku
    echo '<script>alert("Silakan pilih file buku dan cover buku!");</script>';
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Buku</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light d-lg-none" style="background-color: #87CEEB;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><i class="bi bi-person-circle"></i> Welcome,
        <?php echo $_SESSION['nama']; ?>!</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="atur_buku.php"><i class="fas fa-book"></i> Buku</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="atur_pengguna.php"><i class="fas fa-users-cog"></i> Pengguna</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="sidebar d-none d-lg-block">
    <div class="text-center mb-4">
      <h1> <i class="bi bi-person-circle"></i></h1>
      <h3>Welcome</h3>
      <p><?php echo $_SESSION['nama']; ?></p>
    </div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
    <a href="atur_buku.php"><i class="fas fa-book"></i> Buku</a>
    <a href="atur_pengguna.php"><i class="fas fa-users-cog"></i> Pengguna</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
  <div class="main-content">
    <div class="container-fluid">
      <h2 class="mb-4"><i class="bi bi-journal-plus"></i> Tambah Buku</h2>

      <!-- Form Tambah Buku -->
      <form action="tambah_buku.php" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="isbn" class="form-label">ISBN:</label>
            <input type="text" class="form-control" id="isbn" name="isbn" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="judul" class="form-label">Judul Buku:</label>
            <input type="text" class="form-control" id="judul" name="judul" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="penulis" class="form-label">Penulis:</label>
            <input type="text" class="form-control" id="penulis" name="penulis" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="penerbit" class="form-label">Penerbit:</label>
            <input type="text" class="form-control" id="penerbit" name="penerbit" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="tahun_terbit" class="form-label">Tahun Terbit:</label>
            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori:</label>
            <select class="form-control" id="kategori" name="kategori" required>
              <option value="Fiksi">Fiksi</option>
              <option value="Non-Fiksi">Non-Fiksi</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi:</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="file_buku" class="form-label">Upload Buku (PDF):</label>
          <input type="file" class="form-control" id="file_buku" name="file_buku" accept=".pdf" required>
        </div>
        <div class="mb-3">
          <label for="cover_buku" class="form-label">Upload Cover Buku (JPEG, PNG, GIF):</label>
          <input type="file" class="form-control" id="cover_buku" name="cover_buku" accept=".jpg, .jpeg, .png, .gif"
            required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Buku</button>
      </form>
    </div>
  </div>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>