<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: ../../login/login.php');
  exit;
}
require '../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$tgl_mulai            = $_GET['tgl_mulai'] ?? '';
$tgl_selesai          = $_GET['tgl_selesai'] ?? '';
$role                 = $_SESSION['role'];
$id_divisi_supervisor = $_SESSION['id_divisi'] ?? '';

if ($role == 'Admin') {
  $filter = "";
} else {
  $filter = "WHERE tbl_karyawan.id_divisi = '$id_divisi_supervisor'";
}

if ($tgl_mulai != '' && $tgl_selesai != '') {
  $filter .= ($filter == '' ? "WHERE " : " AND ")
    . "tbl_cuti.tanggal_mulai BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

$tampil = mysqli_query($con, "SELECT
  tbl_cuti.*, tbl_karyawan.nik, tbl_karyawan.nama, tbl_divisi.nama_divisi
  FROM tbl_cuti
  INNER JOIN tbl_karyawan ON tbl_cuti.id_karyawan = tbl_karyawan.id
  INNER JOIN tbl_divisi ON tbl_karyawan.id_divisi = tbl_divisi.id
  $filter
  ORDER BY tbl_cuti.tanggal_mulai ASC");

ob_start();
?>
<style>
  body { font-family: sans-serif; font-size: 12px; }
  h3 { text-align: center; margin-bottom: 5px; }
  p.periode { text-align: center; margin-top: 0; margin-bottom: 15px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #333; padding: 6px; text-align: left; }
  th { background-color: #eee; }
</style>

<h3>Laporan Data Cuti Karyawan</h3>
<?php if ($tgl_mulai != '' && $tgl_selesai != '') { ?>
  <p class="periode">Periode: <?= $tgl_mulai ?> s/d <?= $tgl_selesai ?></p>
<?php } ?>

<table>
  <thead>
    <tr>
      <th>NO</th>
      <th>NIK</th>
      <th>NAMA</th>
      <th>DIVISI</th>
      <th>JENIS CUTI</th>
      <th>TGL MULAI</th>
      <th>TGL SELESAI</th>
      <th>ALASAN</th>
      <th>STATUS</th>
      <th>DIPROSES OLEH</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while ($d = mysqli_fetch_array($tampil)) {
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $d['nik'] ?></td>
        <td><?= $d['nama'] ?></td>
        <td><?= $d['nama_divisi'] ?></td>
        <td><?= $d['jenis_cuti'] ?></td>
        <td><?= $d['tanggal_mulai'] ?></td>
        <td><?= $d['tanggal_selesai'] ?></td>
        <td><?= $d['alasan'] ?></td>
        <td><?= $d['status'] ?></td>
        <td><?= $d['diproses_oleh'] ? $d['diproses_oleh'] : '-' ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php
$html = ob_get_clean();

// Generate PDF dengan Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Laporan-Cuti.pdf', ['Attachment' => 0]);