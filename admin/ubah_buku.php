<?php
session_start();

// Periksa pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// File koneksi.php
require_once "../koneksi.php";

// Koneksi ke database
$koneksi = getKoneksi();

// Periksa apakah parameter ISBN telah diterima
if (isset($_GET['isbn'])) {
  $isbn = mysqli_real_escape_string($koneksi, $_GET['isbn']);

  // Ambil informasi buku berdasarkan ISBN
  $query = "SELECT * FROM buku WHERE ISBN = '$isbn'";
  $result = mysqli_query($koneksi, $query);
  $buku = mysqli_fetch_assoc($result);

  if (!$buku) {
    echo '<script>alert("Buku tidak ditemukan!"); window.location.href="atur_buku.php";</script>';
    exit();
  }
} else {
  echo '<script>alert("ISBN tidak diberikan!"); window.location.href="atur_buku.php";</script>';
  exit();
}

// Proses form saat data di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
  $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
  $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
  $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
  $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
  $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

  // Handle cover upload
  $cover_buku = $buku['Cover_Buku'];
  if (isset($_FILES['cover_baru']) && $_FILES['cover_baru']['error'] === UPLOAD_ERR_OK) {
    $cover_tmp = $_FILES['cover_baru']['tmp_name'];
    $cover_name = $_FILES['cover_baru']['name'];
    $cover_ext = pathinfo($cover_name, PATHINFO_EXTENSION);
    $cover_baru_name = uniqid() . '.' . $cover_ext;
    $cover_baru_path = 'assets/foto/' . $cover_baru_name;
    if (move_uploaded_file($cover_tmp, '../' . $cover_baru_path)) {
      if (file_exists('../' . $cover_buku)) {
        unlink('../' . $cover_buku);
      }
      $cover_buku = $cover_baru_path;
    }
  }

  // Update buku dalam database
  $query = "UPDATE buku SET Judul = '$judul', Penulis = '$penulis', Penerbit = '$penerbit', Tahun_Terbit = '$tahun_terbit', Deskripsi = '$deskripsi', Kategori = '$kategori', Cover_Buku = '$cover_buku' WHERE ISBN = '$isbn'";

  if (mysqli_query($koneksi, $query)) {
    echo '<script>alert("Buku berhasil diperbarui!"); window.location.href="atur_buku.php";</script>';
  } else {
    echo '<script>alert("Terjadi kesalahan saat memperbarui buku!");</script>';
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
      <h1><i class="bi bi-person-circle"></i></h1>
      <h3>Welcome</h3>
      <p><?php echo htmlspecialchars($_SESSION['nama']); ?></p>
    </div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
    <a href="atur_buku.php"><i class="fas fa-book"></i> Buku</a>
    <a href="atur_pengguna.php"><i class="fas fa-users-cog"></i> Pengguna</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
  <div class="main-content">
    <div class="container-fluid">
      <h2><i class="bi bi-journal-code"></i> Ubah Buku</h2>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class=" mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul"
              value="<?php echo htmlspecialchars($buku['Judul']); ?>" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="penerbit" class="form-label">Penerbit</label>
            <input type="text" class="form-control" id="penerbit" name="penerbit"
              value="<?php echo htmlspecialchars($buku['Penerbit']); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="penulis" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="penulis" name="penulis"
              value="<?php echo htmlspecialchars($buku['Penulis']); ?>" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
            <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit"
              value="<?php echo htmlspecialchars($buku['Tahun_Terbit']); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
              <option value="Fiksi" <?php echo $buku['Kategori'] == 'Fiksi' ? 'selected' : ''; ?>>Fiksi</option>
              <option value="Non-Fiksi" <?php echo $buku['Kategori'] == 'Non-Fiksi' ? 'selected' : ''; ?>>Non-Fiksi
              </option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
            required><?php echo htmlspecialchars($buku['Deskripsi']); ?></textarea>
        </div>
        <div class="mb-3">
          <label for="cover_baru" class="form-label">Cover Buku (opsional)</label>
          <input class="form-control" type="file" id="cover_baru" name="cover_baru">
          <p>Cover saat ini: <img src="../<?php echo htmlspecialchars($buku['Cover_Buku']); ?>" alt="Cover Buku"
              style="width: 50px; height: 70px;"></p>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="atur_buku.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>