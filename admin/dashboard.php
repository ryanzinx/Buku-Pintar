<?php
session_start();

// Periksa pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// File koneksi.php
require_once "../koneksi.php";

// Fungsi untuk mendapatkan semua admin
function getAdmins($koneksi)
{
  $query = "SELECT * FROM admin";
  return mysqli_query($koneksi, $query);
}

// Mendapatkan semua admin
$admins = getAdmins(getKoneksi());
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php while ($admin = mysqli_fetch_assoc($admins)): ?>
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
        <!-- Tampilkan nama pengguna -->
        <p><?php echo $_SESSION['nama']; ?></p>


      </div>
      <a href="#"><i class="fas fa-home"></i> Home</a>
      <a href="atur_buku.php"><i class="fas fa-book"></i> Buku</a>
      <a href="atur_pengguna.php"><i class="fas fa-users-cog"></i> Pengguna</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
      <div class="container-fluid mt-5">
        <div class="row justify-content-center">
          <div class="col-lg-4 text-center">
            <img src="../assets/foto/<?php echo $admin['Photo']; ?>" alt="Admin Photo" class="img-fluid img-profile">
            <h4 class="mt-3">Selamat Datang, <?php echo $_SESSION['nama']; ?>!</h4>
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <?php endwhile; ?>
</body>

</html>