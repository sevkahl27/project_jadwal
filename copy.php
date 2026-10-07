<?php

$myId      = $_SESSION['id_karyawan'];
$myDivisi  = $_SESSION['id_divisi'];
$maxOffPerHari = 2;

if (isset($_POST['ajukan_off'])) {
  $tanggal_off = $_POST['tanggal_off'];
  $keterangan  = $_POST['keterangan'];
  $mulaiMinggu = date('Y-m-d', strtotime('monday this week', strtotime($tanggal_off)));
  $akhirMinggu = date('Y-m-d', strtotime('sunday this week', strtotime($tanggal_off)));
  $cekMinggu   = mysqli_query($con, "SELECT * FROM tbl_hari_off WHERE id_karyawan = '$myId' 
  AND status IN ('pending','disetujui') AND tanggal_off BETWEEN '$mulaiMinggu' AND '$akhirMinggu'");

  if (mysqli_num_rows($cekMinggu) > 0) {
    echo "<script>alert('Batas pengajuan off adalah 1x seminggu. Anda sudah memiliki jadwal.'); 
    window.location='?page=ajukan_off';</script>";
    exit;
  }
  $cekKuota = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_hari_off WHERE id_divisi = '$myDivisi' 
    AND tanggal_off = '$tanggal_off' AND status = 'disetujui'");
  $jumlah = mysqli_fetch_assoc($cekKuota)['jumlah'];

  if ($jumlah >= $maxOffPerHari) {
    echo "<script>alert('Tidak bisa mengajukan off tanggal ini.'); 
    window.location='?page=ajukan_off';</script>";
    exit;
  }
  $dataKry = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_shift FROM tbl_karyawan WHERE id='$myId'"));
  $shift   = $dataKry['id_shift'];
  $simpan  = mysqli_query($con, "INSERT INTO tbl_hari_off 
    (id_karyawan, tanggal_off, keterangan, id_shift, id_divisi, status)
    VALUES ('$myId', '$tanggal_off', '$keterangan', '$shift', '$myDivisi', 'pending')");
  if ($simpan) {
    echo "<script>alert('Pengajuan off berhasil dikirim.'); 
    window.location='?page=ajukan_off';</script>";
  } else {
    echo "<script>alert('Gagal mengirim pengajuan.');</script>";
  }
  exit;
}
?>
<div class="card col-md-12">
  <div class="card-header">
    <h5 class="card-title"><i class="fas fa-calendar-plus"></i> Ajukan Hari Off</h5>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="form-group">
        <label>Tanggal Off</label>
        <input type="date" name="tanggal_off" class="form-control" required min="<?= date('Y-m-d'); ?>">
      </div>
      <div class="form-group mt-3">
        <label>Alasan</label>
        <input type="text" name="keterangan" class="form-control" placeholder="mis: Keperluan keluarga">
      </div>
      <button type="submit" name="ajukan_off" class="btn btn-primary mt-3">Ajukan</button>
    </form>
    <hr>
    <h6>Riwayat Pengajuan Saya</h6>
    <table class="table table-bordered table-sm mt-2">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Keterangan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $riwayat = mysqli_query($con, "SELECT * FROM tbl_hari_off WHERE id_karyawan='$myId' ORDER BY tanggal_off DESC");
        while ($r = mysqli_fetch_assoc($riwayat)) {
          $badge = $r['status'] == 'disetujui' ? 'success' : ($r['status'] == 'ditolak' ? 'danger' : 'warning');
        ?>
          <tr>
            <td><?= date('d-m-Y', strtotime($r['tanggal_off'])); ?></td>
            <td><?= $r['keterangan']; ?></td>
            <td><span class="badge badge-<?= $badge; ?>"><?= ucfirst($r['status']); ?></span></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>