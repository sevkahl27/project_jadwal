<?php
// PROSES SIMPAN
if (isset($_POST['simpandata'])) {
  $id_karyawan = $_POST['id_karyawan'];
  $id_divisi   = $_POST['divisi'];
  $id_shift    = $_POST['shift'];
  $tanggal_off = $_POST['tanggal_off'];
  $keterangan  = $_POST['keterangan'];
  $simpan      = mysqli_query($con, "INSERT INTO tbl_hari_off(id_karyawan, id_divisi, id_shift, tanggal_off, keterangan)
                 VALUES('$id_karyawan', '$id_divisi', '$id_shift', '$tanggal_off', '$keterangan')");

  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
    window.location='?page=jadwal_off';</script>";
    exit;
  } else {
    die(mysqli_error($con));
  }
}
if (isset($_POST['editdata'])) {
  $id          = $_POST['id'];
  $id_divisi   = $_POST['divisi'];
  $id_shift    = $_POST['shift'];
  $tanggal_off = $_POST['tanggal_off'];
  $keterangan  = $_POST['keterangan'];
  $simpan      = mysqli_query($con, "UPDATE tbl_hari_off SET 
  id_divisi    ='$id_divisi',
  id_shift     ='$id_shift',
  tanggal_off  ='$tanggal_off',
  keterangan   ='$keterangan'
  WHERE id     ='$id'");

  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
        window.location='?page=jadwal_off';</script>";
    exit;
  } else {
    echo "<script> alert('Data gagal disimpan');</script>";
  }
}
?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-indent"></i> Table Jadwal Off</h5>
      <button type="button" class="btn btn-success float-right rounded-0" data-toggle="modal" data-target="#staticBackdrop">Tambah Data</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">NO</th>
              <th>Nama Karyawan</th>
              <th>Divisi</th>
              <th>Shift</th>
              <th>Tangal OFF</th>
              <th>Keterangan</th>
              <th width="180" class="text-center">ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $tampil = mysqli_query($con, "SELECT tbl_hari_off.*, tbl_karyawan.nama, tbl_divisi.nama_divisi, tbl_shift.nama_shift
            FROM tbl_hari_off
            INNER JOIN tbl_karyawan ON tbl_hari_off.id_karyawan = tbl_karyawan.id
            INNER JOIN tbl_divisi ON tbl_hari_off.id_divisi = tbl_divisi.id
            INNER JOIN tbl_shift ON tbl_hari_off.id_shift = tbl_shift.id
            ORDER BY tbl_hari_off.id DESC");
            while ($data = mysqli_fetch_array($tampil)) { ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['nama_divisi'] ?></td>
                <td><?= $data['nama_shift'] ?></td>
                <td><?= $data['tanggal_off'] ?></td>
               <td><?= substr($data['keterangan'], 0, 30) . '...'; ?></td>
                <td class="text-center">
                  <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal"
                    data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                  <a href="?page=hapus_off&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0"
                    onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i> Hapus</a>
                </td>
              </tr>

              <div class="modal fade" id="modalEdit<?= $data['id']; ?>" data-backdrop="static"
                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 450px ;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Edit Data Jadwal Off</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form method="POST">
                      <input type="hidden" name="id" value="<?= $data['id']; ?>">
                      <div class="modal-body">
                        <div class="form-group row mt-4">
                          <label class="col-sm-3 col-form-label">Karyawan</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control rounded-0" value="<?= $data['nama'] ?>" readonly>
                          </div>
                        </div>

                        <div class="form-group row mt-4">
                          <label class="col-sm-3 col-form-label">Divisi</label>
                          <div class="col-sm-9">
                            <select name="divisi" class="form-control" required>
                              <option value="">-- Pilih Divisi --</option>
                              <?php
                              $editdivisi = mysqli_query($con, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
                              while ($de = mysqli_fetch_assoc($editdivisi)) { ?>
                                <option value="<?= $de['id']; ?>" <?= ($de['id'] == $data['id_divisi']) ? 'selected' : ''; ?>>
                                  <?= $de['nama_divisi']; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row mt-4">
                          <label class="col-sm-3 col-form-label">Shift</label>
                          <div class="col-sm-9">
                            <select name="shift" class="form-control" required>
                              <option value="">-- Pilih Shift --</option>
                              <?php
                              $editshift = mysqli_query($con, "SELECT * FROM tbl_shift ORDER BY nama_shift ASC");
                              while ($se = mysqli_fetch_assoc($editshift)) { ?>
                                <option value="<?= $se['id']; ?>" <?= ($se['id'] == $data['id_shift']) ? 'selected' : ''; ?>>
                                  <?= $se['nama_shift']; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row mt-4">
                          <label class="col-sm-3 col-form-label">Tanggal Off</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control rounded-0" name="tanggal_off" value="<?= $data['tanggal_off'] ?>">
                          </div>
                        </div>

                        <div class="form-group row mt-4">
                          <label class="col-sm-3 col-form-label">Keterangan</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control rounded-0" name="keterangan" value="<?= $data['keterangan'] ?>">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer mt-3 mb-3">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="editdata">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 450px ;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Jadwal Off</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <div class="form-group row mt-4">
              <label class="col-sm-3 col-form-label">Nama Karyawan</label>
              <div class="col-sm-9">
                <select name="id_karyawan" class="form-control" required>
                  <option value="">Pilih Nama Karyawan</option>
                  <?php
                  $karyawan = mysqli_query($con, "SELECT * FROM tbl_karyawan ORDER BY nama ASC");
                  while ($k = mysqli_fetch_assoc($karyawan)) { ?>
                    <option value="<?= $k['id']; ?>"><?= $k['nama']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-sm-3 col-form-label">Divisi</label>
              <div class="col-sm-9">
                <select name="divisi" class="form-control" required>
                  <option value="">Pilih Divisi</option>
                  <?php
                  $qDivisi = mysqli_query($con, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
                  while ($d = mysqli_fetch_assoc($qDivisi)) { ?>
                    <option value="<?= $d['id']; ?>"><?= $d['nama_divisi']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-sm-3 col-form-label">Shift</label>
              <div class="col-sm-9">
                <select name="shift" class="form-control" required>
                  <option value="">Pilih Shift</option>
                  <?php
                  $qShift = mysqli_query($con, "SELECT * FROM tbl_shift ORDER BY nama_shift ASC");
                  while ($s = mysqli_fetch_assoc($qShift)) { ?>
                    <option value="<?= $s['id']; ?>"><?= $s['nama_shift']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-sm-3 col-form-label">Tanggal Off</label>
              <div class="col-sm-9">
                <input type="date" class="form-control rounded-0" name="tanggal_off">
              </div>
            </div>

            <div class="form-group row mt-4">
              <label class="col-sm-3 col-form-label">Keterangan</label>
              <div class="col-sm-9">
                <input type="text" class="form-control rounded-0" name="keterangan" placeholder="Keterangan">
              </div>
            </div>
          </div>
          <div class="modal-footer mt-3 mb-3">
            <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-info rounded-0" name="simpandata">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>  