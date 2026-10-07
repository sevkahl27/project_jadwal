<?php
$role        = $_SESSION['role'] ?? 'Admin';
$myDivisi    = $_SESSION['id_divisi'] ?? '';
$bulan       = $_GET['bulan'] ?? date('m');
$tahun       = $_GET['tahun'] ?? date('Y');
$jumlahHari  = date('t', strtotime("$tahun-$bulan-01"));
$namaBulan   = date('F Y', strtotime("$tahun-$bulan-01"));
$divisi      = ($role === 'Admin' || empty($myDivisi)) ? '' : "WHERE k.id_divisi = '$myDivisi'";
$offMap      = [];
$offData     = mysqli_query($con, "SELECT id_karyawan, DAY(tanggal_off) AS tgl FROM tbl_hari_off
WHERE status = 'disetujui' AND MONTH(tanggal_off) = '$bulan' AND YEAR(tanggal_off) = '$tahun'");
while ($o    = mysqli_fetch_assoc($offData)) {
  $offMap[$o['id_karyawan']][$o['tgl']] = true;
}
$karyawanArr = mysqli_fetch_all(mysqli_query($con, "
SELECT k.id, k.nama, s.nama_shift, d.nama_divisi
FROM tbl_karyawan k
JOIN tbl_shift s ON k.id_shift = s.id
JOIN tbl_divisi d ON k.id_divisi = d.id
$divisi ORDER BY d.nama_divisi ASC, k.nama ASC "), MYSQLI_ASSOC);
function inisialShift($nama)
{
  $nama = strtolower($nama);
  if (strpos($nama, 'pagi')  !== false) return 'P';
  if (strpos($nama, 'siang') !== false) return 'S';
  if (strpos($nama, 'malam') !== false) return 'M';
  return substr(strtoupper($nama), 0, 1);
}
function aturJadwalOff($idKaryawan, $huruf, $bulan, $tahun, $jumlahHari, &$terpakaiShift, $jumlahOff = 5)
{
  $seed = (int)($idKaryawan . $bulan . $tahun);
  mt_srand($seed);
  $mingguMap = [];
  for ($d = 1; $d <= $jumlahHari; $d++) {
    $noMinggu = (int) date('W', strtotime("$tahun-$bulan-" . str_pad($d, 2, '0', STR_PAD_LEFT)));
    $mingguMap[$noMinggu][] = $d;
  }
  $daftarMinggu = array_keys($mingguMap);
  shuffle($daftarMinggu);
  $hasil = [];
  foreach ($daftarMinggu as $mg) {
    if (count($hasil) >= $jumlahOff) break;
    $tanggalMinggu = $mingguMap[$mg];
    shuffle($tanggalMinggu);

    foreach ($tanggalMinggu as $tgl) {
      if (isset($terpakaiShift[$huruf][$tgl])) continue;

      $bersebelahan = false;
      foreach ($hasil as $sudahAda) {
        if (abs($tgl - $sudahAda) <= 1) {
          $bersebelahan = true;
          break;
        }
      }
      if ($bersebelahan) continue;

      $hasil[] = $tgl;
      $terpakaiShift[$huruf][$tgl] = true;
      break;
    }
  }
  if (count($hasil) < $jumlahOff) {
    $semuaTanggal = range(1, $jumlahHari);
    shuffle($semuaTanggal);
    foreach ($semuaTanggal as $tgl) {
      if (count($hasil) >= $jumlahOff) break;
      if (in_array($tgl, $hasil)) continue;
      if (isset($terpakaiShift[$huruf][$tgl])) continue;

      $hasil[] = $tgl;
      $terpakaiShift[$huruf][$tgl] = true;
    }
  }
  if (count($hasil) < $jumlahOff) {
    $semuaTanggal = range(1, $jumlahHari);
    shuffle($semuaTanggal);
    foreach ($semuaTanggal as $tgl) {
      if (count($hasil) >= $jumlahOff) break;
      if (!in_array($tgl, $hasil)) $hasil[] = $tgl;
    }
  }

  mt_srand();
  sort($hasil);
  return $hasil;
}
$terpakaiShift = [];
?>
<div class="card col-md-12">
  <div class="card-header">
    <h5 class="card-title">JADWAL OFF - <?= strtoupper($namaBulan); ?></h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="example1" class="table table-bordered table-hover text-center small">
        <thead>
          <tr>
            <th>NAMA</th>
            <?php for ($d = 1; $d <= $jumlahHari; $d++) {
              $isMinggu = date('N', strtotime("$tahun-$bulan-$d")) == 7;
            ?>
              <th style="<?= $isMinggu ? 'background-color:#dc3545; color:white;' : ''; ?>">
                <?= $d; ?>
              </th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $divisiSebelumnya = null;
          foreach ($karyawanArr as $index => $kry) {
            $idKaryawan       = $kry['id'];
            $huruf            = inisialShift($kry['nama_shift']);
            $tanggalOff       = aturJadwalOff($idKaryawan, $huruf, $bulan, $tahun, $jumlahHari, $terpakaiShift, 5);
            if ($divisiSebelumnya !== $kry['nama_divisi']) {
          ?>
              <tr>
                <td colspan="<?= $jumlahHari + 1; ?>"
                  style="background-color:#e9ecef; color:dark; text-align:center; font-weight:bold; padding:8px;">
                  <?= htmlspecialchars($kry['nama_divisi']); ?>
                </td>
              </tr>
            <?php
              $divisiSebelumnya = $kry['nama_divisi'];
            }
            ?>
            <tr>
              <td class="text-left" style="white-space:nowrap;"><?= htmlspecialchars($kry['nama']); ?></td>
              <?php for ($d = 1; $d <= $jumlahHari; $d++) {
                $isOff = isset($offMap[$idKaryawan][$d]) || in_array($d, $tanggalOff);
              ?>
                <?php if ($isOff) { ?>
                  <td class="btn btn-primary py-0 px-1 mt-2 rounded-5" style="color:white;"><?= $huruf; ?></td>
                <?php } else { ?>
                  <td><?= $huruf; ?></td>
                <?php } ?>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>