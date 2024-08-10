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

// Ambil semua data buku
$query = "SELECT * FROM buku";
$result = mysqli_query($koneksi, $query);

function potongDeskripsi($deskripsi, $panjang)
{
  if (strlen($deskripsi) > $panjang) {
    return substr($deskripsi, 0, $panjang) . '...';
  } else {
    return $deskripsi;
  }

}
function potongPenulis($penulis, $panjang)
{
  if (strlen($penulis) > $panjang) {
    return substr($penulis, 0, $panjang) . '...';
  } else {
    return $penulis;
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
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <!-- DataTables Responsive CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
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
      <p><?php echo $_SESSION['nama']; ?></p>
    </div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
    <a href="atur_buku.php"><i class="fas fa-book"></i> Buku</a>
    <a href="atur_pengguna.php"><i class="fas fa-users-cog"></i> Pengguna</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
  <div class="main-content">
    <div class="container-fluid">
      <h2><i class="bi bi-journal-bookmark"></i> Manajemen Buku</h2>

      <!-- Tombol Tambah Buku -->
      <a href="tambah_buku.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Buku</a>
      <h4 class="pt-4"><i class="bi bi-journal-text"></i> Daftar Buku</h4>
      <!-- Tabel Daftar Buku -->
      <div class="table-responsive">
        <table id="bukuTable" class="table table-striped">
          <thead>
            <tr>
              <th>ISBN</th>
              <th>Judul</th>
              <th>Penulis</th>
              <th>Penerbit</th>
              <th>Tahun Terbit</th>
              <th>Deskripsi</th>
              <th>Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo $row['ISBN']; ?></td>
                <td><?php echo $row['Judul']; ?></td>
                <td><?php echo potongPenulis($row['Penulis'], 50); ?></td>
                <td><?php echo $row['Penerbit']; ?></td>
                <td><?php echo $row['Tahun_Terbit']; ?></td>
                <td><?php echo potongDeskripsi($row['Deskripsi'], 50); ?></td>
                <td><?php echo $row['Kategori']; ?></td>
                <td>
                  <a href="ubah_buku.php?isbn=<?php echo $row['ISBN']; ?>" class="btn btn-warning btn-sm"><i
                      class="fas fa-edit"></i> Edit</a>
                  <a href="hapus_buku.php?isbn=<?php echo $row['ISBN']; ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');"><i
                      class="fas fa-trash-alt"></i> Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- DataTables Responsive JS -->
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#bukuTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
      });
    });
  </script>
</body>

</html>