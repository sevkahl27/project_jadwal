<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Karyawan') {
  header('Location: login/login.php');
  exit;
}
include 'koneksi.php';
$id_k = $_SESSION['id_karyawan'];
$query = mysqli_query($con, "
SELECT k.nama, d.nama_divisi, s.nama_shift FROM tbl_karyawan k
LEFT JOIN tbl_divisi d ON k.id_divisi = d.id
LEFT JOIN tbl_shift s ON k.id_shift = s.id
WHERE k.id = '$id_k'
");
$data = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title> Sistem Jadwal | OFF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css" />
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper d-flex flex-column min-vh-100">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#">
            <i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="beranda-karyawan.php" class="nav-link">Home</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link text-primary" data-toggle="dropdown" href="#">
            <span class="dark"><i class="fas fa-user-circle"></i> Hy :  <?= $data['nama'] ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              <div class="media">
                <img
                  src="dist/img/user1-128x128.jpg"
                  alt="User Avatar"
                  class="img-size-50 mr-3 img-circle" />
                <div class="media-body">
                  <p class="mb-1">
                    <span style="display:inline-block; width:60px;">Nama</span> :
                    <?= $data['nama'] ?>
                  </p>
                  <p class="mb-1">
                    <span style="display:inline-block; width:60px;">Divisi</span> :
                    <?= $data['nama_divisi'] ?>
                  </p>
                  <p class="mb-0">
                    <span style="display:inline-block; width:60px;">Shift</span> :
                    <?= $data['nama_shift'] ?>
                  </p>
                  <p class="mb-0">
                    <span style="display:inline-block; width:60px;">Jabatan</span> :
                    <?= $_SESSION['role'] ?>
                  </p>
                </div>
              </div>
            </a>
            <div class="text-center">
              <a href="login/login.php" class="btn btn-outline-danger mt-3 mb-3 rounded-0 px-3 py-1">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </div>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="#" class="brand-link">
        <img
          src="dist/img/AdminLTELogo.png"
          alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: 0.8" />
        <span class="brand-text font-weight-light">SISTEM JADWAL</span>
      </a>
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img
              src="dist/img/user.png"
              class="img-circle elevation-2"
              alt="User Image" />
          </div>

          <div class="info">
            <a href="#" class="d-block"><?php echo $data['nama'] ?></a>
          </div>
        </div>
        <nav class="mt-2">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false">
            <li class="nav-item has-treeview menu-open">
              <a href="beranda-karyawan.php?page=beranda" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-calendar-times"></i>
                <p>
                  jadwal OFF
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="beranda-karyawan.php?page=data-off-harian" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Data OFF</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="beranda-karyawan.php?page=ajukan-off" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajukan OFF</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="beranda-karyawan.php?page=ajukan-cuti" class="nav-link">
                <i class="nav-icon fas fa-plane-departure"></i>
                <p>Ajukan Cuti</p>
              </a>
            </li>
            <li class="nav-item has-treeview">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  Laporan
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="beranda-karyawan.php?page=cuti-karyawan" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Cuti</p>
                  </a>
                </li>
              </ul>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="laporan-off-karyawan" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan OFF</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-sign-in-alt"></i>
                <p>
                  Keluar
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="login/login.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Login</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="content-wrapper flex-fill">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
            <?php
            $page = $_GET['page'] ?? 'beranda';

            switch ($page) {
              case 'data-off-harian':
                $judul = 'Jadwal OFF';
                break;
              case 'ajukan-off':
                $judul = 'Pengajuan OFF';
                break;
              case 'ajukan-cuti':
                $judul = 'Pengajuan Cuti';
                break;
              case 'cuti-karyawan':
                $judul = 'Laporan Cuti';
                break;
              case 'laporan-off':
                $judul = 'Laporan OFF';
                break;
              case 'jadwal_off':
                $judul = 'Jadwal OFF';
                break;
              case 'beranda':
              default:
                $judul = 'Dashboard';
                break;
            }
            ?>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="beranda-karyawan.php">Home</a>
                </li>
                <li class="breadcrumb-item active text-primary">
                  <?= $judul; ?>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row" />
          <?php
          if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
              case 'ajukan-cuti':
                include "modul/cuti-karyawan/ajukan.php";
                break;
              case 'data-off-harian':
                include "modul/data-off-harian/kalender_off.php";
                break;
              case 'batal-cuti':
                include "modul/cuti-karyawan/batal-cuti.php";
                break;
              case 'ajukan-off':
                include "modul/data-off-harian/ajukan_off.php";
                break;
              case 'cuti-karyawan':
                include "modul/cuti-karyawan/laporan.php";
                break;
              case 'jadwal_off':
                include "modul/jadwal_off/index.php";
                break;
              case 'beranda':
                include "modul/dashboard/index.php";
                break;
              default:
                include "modul/gagal/index.php";
                break;
            }
          } else {
            include "modul/dashboard/index.php";
          }
          ?>
      </section>
    </div>
    <footer class="main-footer">
      <strong> Dibuat  &copy; 
        <a href="#">@Sefka Hulu</a>.</strong>2026/2027
      <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.0.1-pre</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <script>
    $.widget.bridge("uibutton", $.ui.button);
  </script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/chart.js/Chart.min.js"></script>
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="dist/js/adminlte.js"></script>
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script>
    $(function() {
      $("#example1").DataTable();
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
      });
    });
  </script>
</body>

</html>