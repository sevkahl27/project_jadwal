<?php
if (isset($_GET['id'])) {

    $id    = $_GET['id'];
    $hapus = mysqli_query($con, "DELETE FROM tbl_cuti WHERE id='$id'");

    if ($hapus) {
        echo "<script>
            alert('Pengajuan cuti berhasil dibatalkan');
            window.location='?page=ajukan-cuti';
        </script>";
    } else {
        echo "<script>
            alert('Pengajuan cuti gagal dibatalkan');
            window.location='?page=ajukan-cuti';
        </script>";
    }

    exit;
}
?>