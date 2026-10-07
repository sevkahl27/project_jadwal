<?php
if (isset($_GET['id'])) {
$id = $_GET['id'];
$hapus = mysqli_query($con, "DELETE FROM tbl_shift WHERE id='$id'");
if ($hapus) {
            echo "<script>alert('Data berhasil dihapus');
            window.location='?page=shift';</script>";
    } else {
            echo "<script>alert('Data gagal dihapus');
            window.location='?page=shift';</script>";
    }
        exit;
}
?>