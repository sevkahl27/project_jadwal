<?php
session_start();
include "../koneksi.php";
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($con, $_POST['username']); $password = $_POST['password'];
  $cek = mysqli_query($con, "SELECT * FROM tbl_user WHERE username='$username'");
  if (mysqli_num_rows($cek) > 0) {
    $data = mysqli_fetch_assoc($cek);
   if (password_verify($password, $data['password'])) {
      $_SESSION['id']          = $data['id'];
      $_SESSION['username']    = $data['username'];
      $_SESSION['role']        = $data['role'];
      $_SESSION['id_karyawan'] = $data['id_karyawan'];
      if (!empty($data['id_karyawan'])) {
        $id_k = $data['id_karyawan'];
        $q_karyawan = mysqli_query($con, "SELECT id_divisi FROM tbl_karyawan WHERE id='$id_k'");
        if (mysqli_num_rows($q_karyawan) > 0) {
          $dt_karyawan = mysqli_fetch_assoc($q_karyawan);
          $_SESSION['id_divisi'] = $dt_karyawan['id_divisi']; 
        }
      }
      switch ($data['role']) {
        case 'Admin':
          header("Location: ../beranda-admin.php");
          break;
        case 'Supervisor':
          header("Location: ../beranda-supervisor.php");
          break;
        case 'Karyawan':
          header("Location: ../beranda-karyawan.php");
          break;
        default:
          header("Location: ../beranda-admin.php");
          break;
      }
      exit;
    }
  }
  echo "<script>
alert('Username atau Password salah!');
window.location='login.php';
</script>";
  exit;
}
