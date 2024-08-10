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
  <title>Manage Users</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
      <h3><i class="fas fa-user-cog"></i> Manajemen Admin</h3>
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Operation was successful!</div>
      <?php endif; ?>
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Operation failed. Please try again.</div>
      <?php endif; ?>
      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i class="fas fa-plus"></i> Tambah Admin
      </button>

      <!-- Modal Tambah Admin -->
      <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addAdminModalLabel">Tambah Admin Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="tambah_user.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="adminName" class="form-label">Nama Admin</label>
                  <input type="text" class="form-control" id="adminName" name="adminName" required>
                </div>
                <div class="mb-3">
                  <label for="adminEmail" class="form-label">Email Admin</label>
                  <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                </div>
                <div class="mb-3">
                  <label for="adminPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                </div>
                <div class="mb-3">
                  <label for="adminRole" class="form-label">Role</label>
                  <input type="text" class="form-control" id="adminRole" name="adminRole" required>
                </div>
                <div class="mb-3">
                  <label for="adminPhoto" class="form-label">Foto Admin</label>
                  <input type="file" class="form-control" id="adminPhoto" name="adminPhoto" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Admin</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <h4 class="mt-2"><i class="fas fa-users-cog"></i> Daftar Admin</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col">Photo</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($admin = mysqli_fetch_assoc($admins)): ?>
              <tr>
                <th scope="row"><?php echo $admin['UserID']; ?></th>
                <td><?php echo $admin['Nama']; ?></td>
                <td><?php echo $admin['Email']; ?></td>
                <td><?php echo $admin['Role']; ?></td>
                <td><img src="../assets/foto/<?php echo $admin['Photo']; ?>" alt="Admin Photo" class="img-thumbnail"
                    style="width: 50px;"></td>
                <td>
                  <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editAdminModal<?php echo $admin['UserID']; ?>">
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  <form action="hapus_user.php" method="POST" style="display:inline;">
                    <input type="hidden" name="adminID" value="<?php echo $admin['UserID']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                  </form>
                </td>
              </tr>

              <!-- Modal Edit Admin -->
              <div class="modal fade" id="editAdminModal<?php echo $admin['UserID']; ?>" tabindex="-1"
                aria-labelledby="editAdminModalLabel<?php echo $admin['UserID']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editAdminModalLabel<?php echo $admin['UserID']; ?>">Edit Admin</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="edit_user.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="adminID" value="<?php echo $admin['UserID']; ?>">
                        <div class="mb-3">
                          <label for="adminName" class="form-label">Nama Admin</label>
                          <input type="text" class="form-control" id="adminName" name="adminName"
                            value="<?php echo $admin['Nama']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label for="adminEmail" class="form-label">Email Admin</label>
                          <input type="email" class="form-control" id="adminEmail" name="adminEmail"
                            value="<?php echo $admin['Email']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label for="adminPassword" class="form-label">Password</label>
                          <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                        </div>
                        <div class="mb-3">
                          <label for="adminRole" class="form-label">Role</label>
                          <input type="text" class="form-control" id="adminRole" name="adminRole"
                            value="<?php echo $admin['Role']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label for="adminPhoto" class="form-label">Foto Admin</label>
                          <input type="file" class="form-control" id="adminPhoto" name="adminPhoto">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

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
</body>

</html>