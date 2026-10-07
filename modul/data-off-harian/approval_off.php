<?php
// approval_off.php

$role      = strtolower($_SESSION['role'] ?? '');
$myDivisi  = $_SESSION['id_divisi'] ?? '';
$adminName = $_SESSION['username'] ?? 'Admin';

// PROSES APPROVE / REJECT
if (isset($_POST['proses'])) {
  $id     = $_POST['id'];
  $aksi   = $_POST['aksi']; // 'disetujui' atau 'ditolak'

  // Kalau approve, cek ulang kuota (jaga-jaga ada yang approve bersamaan)
  if ($aksi == 'disetujui') {
    $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_hari_off WHERE id='$id'"));
    $cekKuota = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_hari_off 
      WHERE id_divisi = '{$data['id_divisi']}' 
      AND tanggal_off = '{$data['tanggal_off']}' 
      AND status = 'disetujui'");
    $jumlah = mysqli_fetch_assoc($cekKuota)['jumlah'];
    if ($jumlah >= 2) { // samakan dengan $maxOffPerHari
      echo "<script>alert('Tidak bisa disetujui. Kuota off tanggal ini untuk divisi tersebut sudah penuh.'); 
      window.location='?page=approval_off';</script>";
      exit;
    }
  }
  mysqli_query($con, "UPDATE tbl_hari_off SET 
    status = '$aksi', 
    diproses_oleh = '$adminName', 
    tanggal_proses = NOW() 
    WHERE id = '$id'");
  echo "<script>window.location='?page=approval_off';</script>";
  exit;
}
$whereDivisi = ($role == 'admin') ? "" : "AND id_divisi = '$myDivisi'";
?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-plane-departure"></i> Riwayat Pengajuan Cuti Anda</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Nik</th>
              <th>Nama</th>
              <th>Divisi</th>
              <th>Tanggal</th>
              <th>Keterangan</th>
              <th>Action</th>
            </tr>
        <?php
        $pending = mysqli_query($con, "SELECT tbl_hari_off.*, tbl_karyawan.nik, 
        tbl_karyawan.nama, tbl_divisi.nama_divisi FROM tbl_hari_off
          LEFT JOIN tbl_karyawan ON tbl_hari_off.id_karyawan = tbl_karyawan.id
          LEFT JOIN tbl_divisi ON tbl_hari_off.id_divisi = tbl_divisi.id
          WHERE status = 'pending' $whereDivisi
          ORDER BY tanggal_off ASC");
        while ($p = mysqli_fetch_assoc($pending)) { ?>
        <tr>
          <td><?= $p['nik']; ?></td>
          <td><?= $p['nama']; ?></td>
          <td><?= $p['nama_divisi']; ?></td>
          <td><?= date('d-m-Y', strtotime($p['tanggal_off'])); ?></td>
          <td><?= $p['keterangan']; ?></td>
          <td>
            <form method="POST" class="d-inline">
              <input type="hidden" name="id" value="<?= $p['id']; ?>">
              <input type="hidden" name="aksi" value="disetujui">
              <button type="submit" name="proses" class="btn btn-success btn-sm">Setujui</button>
            </form>
            <form method="POST" class="d-inline">
              <input type="hidden" name="id" value="<?= $p['id']; ?>">
              <input type="hidden" name="aksi" value="ditolak">
              <button type="submit" name="proses" class="btn btn-danger btn-sm">Tolak</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>