<?php
$username_admin       = $_SESSION['username'] ?? 'Supervisor';
$id_divisi_supervisor = $_SESSION['id_divisi'] ?? '';
if (isset($_POST['tambahcuti'])) {
  $id_karyawan     = $_POST['id_karyawan'];
  $jenis_cuti      = $_POST['jenis_cuti'];
  $tanggal_mulai   = $_POST['tanggal_mulai'];
  $tanggal_selesai = $_POST['tanggal_selesai'];
  $alasan          = $_POST['alasan'];
  $status          = $_POST['status'];
  $diproses_oleh   = ($status != 'Menunggu') ? $username_admin : NULL;
  $tanggal_proses  = ($status != 'Menunggu') ? date('Y-m-d H:i:s') : NULL;

  $simpan = mysqli_query($con, "INSERT INTO tbl_cuti(id_karyawan, jenis_cuti, tanggal_mulai, tanggal_selesai, alasan, status, diproses_oleh, tanggal_proses)
VALUES ('$id_karyawan', '$jenis_cuti', '$tanggal_mulai', '$tanggal_selesai', '$alasan', '$status', " . ($diproses_oleh ? "'$diproses_oleh'" : "NULL") . ", " . ($tanggal_proses ? "'$tanggal_proses'" : "NULL") . ")");

  if ($simpan) {
    echo "<script>alert('Data cuti berhasil ditambahkan');
window.location='?page=kelola-cuti';</script>";
    exit;
  } else {
    echo "<script>alert('Data cuti gagal ditambahkan');</script>";
  }
}

// PROSES SETUJUI / TOLAK
if (isset($_POST['prosesstatus'])) {
  $id           = $_POST['id'];
  $status       = $_POST['status'];
  $tgl_sekarang = date('Y-m-d H:i:s');

  $update = mysqli_query($con, "UPDATE tbl_cuti SET status='$status', 
diproses_oleh='$username_admin', tanggal_proses='$tgl_sekarang' WHERE id='$id'");
  if ($update) {
    echo "<script>alert('Status cuti berhasil diperbarui');
window.location='?page=kelola-cuti';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal memperbarui status');</script>";
  }
}
?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-calendar-check"></i> Data Pengajuan Cuti Karyawan</h5>
      <button type="button" class="btn btn-primary float-right rounded-0 py-1 px-3" data-toggle="modal"
        data-target="#staticBackdrop"><i class="fas fa-plus"></i> Tambah Cuti</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">NO</th>
              <th>NIK</th>
              <th>NAMA</th>
              <th>DIVISI</th>
              <th>JENIS CUTI</th>
              <th>TGL MULAI</th>
              <th>TGL SELESAI</th>
              <th>ALASAN</th>
              <th>STATUS</th>
              <th>DIPROSES OLEH</th>
              <th width="200" class="text-center">ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $role = $_SESSION['role'];
            $id_divisi_supervisor = $_SESSION['id_divisi'];

            if ($role == 'Admin') {
              $filter = "WHERE tbl_cuti.status = 'Menunggu'";
            } else {
              $filter = "WHERE tbl_cuti.status = 'Menunggu' AND tbl_karyawan.id_divisi = '$id_divisi_supervisor'";
            }
            $tampil = mysqli_query($con, "SELECT 
            tbl_cuti.*, 
            tbl_karyawan.nik, 
            tbl_karyawan.nama,
            tbl_divisi.nama_divisi
            FROM tbl_cuti
            INNER JOIN tbl_karyawan 
            ON tbl_cuti.id_karyawan = tbl_karyawan.id
            INNER JOIN tbl_divisi 
            ON tbl_karyawan.id_divisi = tbl_divisi.id
            $filter
            ORDER BY tbl_cuti.id DESC");
            while ($data = mysqli_fetch_array($tampil)) {
              if ($data['status'] == 'Disetujui') {
                $warna = 'badge-success';
              } elseif ($data['status'] == 'Ditolak') {
                $warna = 'badge-danger';
              } else {
                $warna = 'badge-warning';
              }
            ?>
              <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $data['nik'] ?></td>
                <td><?php echo $data['nama'] ?></td>
                <td><?php echo $data['nama_divisi'] ?></td>
                <td><?php echo $data['jenis_cuti'] ?></td>
                <td><?php echo $data['tanggal_mulai'] ?></td>
                <td><?php echo $data['tanggal_selesai'] ?></td>
                <td><?php echo $data['alasan'] ?></td>
                <td><span class="badge <?= $warna ?>"><?= $data['status'] ?></span></td>
                <td>
                  <?= $data['diproses_oleh'] ? $data['diproses_oleh'] . ' (' . $data['tanggal_proses'] . ')' : 'belum ada proses..' ?>
                </td>
                <td class="text-center">
                  <?php if ($data['status'] == 'Menunggu') { ?>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $data['id']; ?>">
                      <input type="hidden" name="status" value="Disetujui">
                      <button type="submit" name="prosesstatus" class="btn btn-success btn-sm rounded-0"
                        onclick="return confirm('Setujui cuti ini?');"><i class="fa fa-check"></i> Setujui</button>
                    </form>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $data['id']; ?>">
                      <input type="hidden" name="status" value="Ditolak">
                      <button type="submit" name="prosesstatus" class="btn btn-danger btn-sm rounded-0"
                        onclick="return confirm('Tolak cuti ini?');"><i class="fa fa-times"></i> Tolak</button>
                    </form>
                  <?php } else { ?>
                    <span class="text-muted">Sudah diproses</span>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH CUTI -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
  tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 470px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Cuti</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Karyawan</label>
            <div class="col-sm-8">
              <select name="id_karyawan" class="form-control" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php
                if ($role == 'Admin') {
                  $qKaryawan = mysqli_query(
                    $con,
                    "SELECT * FROM tbl_karyawan ORDER BY nama ASC"
                  );
                } else {
                  $qKaryawan = mysqli_query(
                    $con,
                    "SELECT * FROM tbl_karyawan 
                    WHERE id_divisi='$id_divisi_supervisor'
                    ORDER BY nama ASC"
                  );
                }
                while ($k = mysqli_fetch_assoc($qKaryawan)) { ?>
                  <option value="<?= $k['id']; ?>"><?= $k['nik']; ?> - <?= $k['nama']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group row mt-4">
            <label class="col-sm-4 col-form-label">Jenis Cuti</label>
            <div class="col-sm-8">
              <select name="jenis_cuti" class="form-control" required>
                <option value="">-- Pilih Jenis Cuti --</option>
                <option value="Cuti Tahunan">Cuti Tahunan</option>
                <option value="Cuti Sakit">Cuti Sakit</option>
                <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                <option value="Cuti Penting">Cuti Penting</option>
              </select>
            </div>
          </div>
          <div class="form-group row mt-4">
            <label class="col-sm-4 col-form-label">Tanggal Mulai</label>
            <div class="col-sm-8">
              <input type="date" class="form-control rounded-0" name="tanggal_mulai" required>
            </div>
          </div>
          <div class="form-group row mt-4">
            <label class="col-sm-4 col-form-label">Tanggal Selesai</label>
            <div class="col-sm-8">
              <input type="date" class="form-control rounded-0" name="tanggal_selesai" required>
            </div>
          </div>
          <div class="form-group row mt-4">
            <label class="col-sm-4 col-form-label">Alasan</label>
            <div class="col-sm-8">
              <textarea class="form-control rounded-0" name="alasan" rows="3" placeholder="Alasan cuti"></textarea>
            </div>
          </div>
          <div class="form-group row mt-2">
            <label class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8">
              <select name="status" class="form-control" required>
                <option value="">Pilih Status..!</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info rounded-0" name="tambahcuti">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>