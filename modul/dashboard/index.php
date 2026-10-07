<?php
function hitungData($con, $query)
{
  $hasil = mysqli_query($con, $query);
  return mysqli_fetch_assoc($hasil)['total'];
}
$jumlah_karyawan           = hitungData($con, "SELECT COUNT(*) AS total FROM tbl_karyawan");
$jumlah_divisi             = hitungData($con, "SELECT COUNT(*) AS total FROM tbl_divisi");
$jumlah_cuti               = hitungData($con, "SELECT COUNT(*) AS total FROM tbl_cuti 
WHERE MONTH(tanggal_mulai) = MONTH(CURDATE()) 
AND YEAR(tanggal_mulai)    = YEAR(CURDATE())");
$jumlah_off                = hitungData($con, "SELECT COUNT(*) AS total FROM tbl_hari_off 
WHERE MONTH(tanggal_off)   = MONTH(CURDATE()) 
AND YEAR(tanggal_off)      = YEAR(CURDATE())");
?>
<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-primary">
    <div class="inner">
      <h3><?php echo $jumlah_karyawan; ?></h3>
      <p>Jumlah Karyawan</p>
    </div>
    <div class="icon"><i class="fas fa-users"></i></div>
    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-green">
    <div class="inner">
      <h3><?php echo $jumlah_divisi; ?></h3>
      <p>Jumlah Divisi</p>
    </div>
    <div class="icon"><i class="fas fa-sitemap"></i></div>
    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3><?php echo $jumlah_cuti; ?></h3>
      <p>Jumlah Cuti Bulan Ini</p>
    </div>
    <div class="icon"><i class="fas fa-plane-departure"></i></div>
    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-red">
    <div class="inner">
      <h3><?php echo $jumlah_off; ?></h3>
      <p>Jumlah Off</p>
    </div>
    <div class="icon"><i class="fas fa-clock"></i></div>
    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<!-- 
<div class="col-lg-4 col-xs-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-user-shield"></i> Daftar Admin</h5>
    </div>
    <div class="card-body">
      <ul class="list-group">
        <?php
        $admin = mysqli_query($con, "SELECT tbl_karyawan.nama 
        FROM tbl_user
        INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.id
        WHERE tbl_user.role = 'Admin'
        ORDER BY tbl_karyawan.nama ASC");
        while ($a = mysqli_fetch_assoc($admin)) {
        echo "<li class='list-group-item'>{$a['nama']}</li>";
        }
        ?>
      </ul>
    </div>
  </div>
</div>
<div class="col-lg-4 col-xs-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-user-tie"></i> Daftar Supervisor</h5>
    </div>
    <div class="card-body">
      <ul class="list-group">
        <?php
        $supervisor = mysqli_query($con, "SELECT tbl_karyawan.nama 
        FROM tbl_user
        INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.id
        WHERE tbl_user.role = 'Supervisor'
        ORDER BY tbl_karyawan.nama ASC");
        while ($s = mysqli_fetch_assoc($supervisor)) {
          echo "<li class='list-group-item'>{$s['nama']}</li>";
        }
        ?>
      </ul>
    </div>
  </div>
</div> -->