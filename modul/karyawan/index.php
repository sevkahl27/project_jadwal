<?php
// $judul   = "Dashboard";
// $menu    = "";
$query      = mysqli_query($con, "SELECT nik FROM tbl_karyawan ORDER BY id DESC LIMIT 1");
$nik        = mysqli_fetch_assoc($query);
if ($nik) {
  $urut     = (int) substr($nik['nik'], -3);
  $urut++;
} else {
  $urut     = 1;
}
$nik = "EMP-" . sprintf("%03d", $urut);
 // PROSES SIMPAN
if (isset($_POST['simpandata'])) {
  $nama   = $_POST['nama'];
  $no_hp  = $_POST['no_hp'];
  $divisi = $_POST['divisi'];
  $shift  = $_POST['shift'];
  $simpan = mysqli_query($con, "INSERT INTO tbl_karyawan(nik, nama, no_hp, id_divisi, id_shift)
  VALUES ('$nik', '$nama', '$no_hp', '$divisi', '$shift')");
  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan'); 
    window.location='?page=karyawan';</script>"; exit;
  } else {
    echo "<script>alert('Data gagal disimpan'); </script>";
  }
  }
 // PROSES EDIT
if (isset($_POST['editdata'])) {
  $id     = $_POST['id'];
  $nama   = $_POST['nama'];
  $no_hp  = $_POST['no_hp'];
  $divisi = $_POST['divisi'];
  $shift  = $_POST['shift'];
  $update = mysqli_query($con, "UPDATE tbl_karyawan SET nama='$nama', no_hp='$no_hp', id_divisi='$divisi', id_shift='$shift' WHERE id='$id'");
  if ($update) {
    echo "<script>
    alert('Data berhasil diubah'); 
    window.location='?page=karyawan'; </script>";
    exit;
  } else {
    echo "<script>alert('Data gagal diubah');</script>";
  }
  }
?>
<div class="col-sm-12">
  <div div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-indent"></i> Table Karyawan</h5>
      <button type="button" class="btn btn-primary float-right rounded-0 py-1 px-3" data-toggle="modal" 
        data-target="#staticBackdrop"><i class="fas fa-plus"></i> Tambah Data</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">NO</th>
              <th>NIK</th>
              <th>NAMA</th>
              <th>No.Telpon</th>
              <th>DIVISI</th>
              <th>SHIFT</th>
              <th width="180" class="text-center">ACTION</th>
          </tr>
          </thead>
          <tbody>
          <?php
            $no = 1;
            $tampil = mysqli_query($con, "SELECT 
                tbl_karyawan.*, 
                tbl_divisi.nama_divisi, 
                tbl_shift.nama_shift 
                FROM tbl_karyawan
                LEFT JOIN tbl_divisi ON tbl_karyawan.id_divisi = tbl_divisi.id
                LEFT JOIN tbl_shift ON tbl_karyawan.id_shift = tbl_shift.id
                ORDER BY tbl_karyawan.id DESC");

            while ($data = mysqli_fetch_array($tampil)) { ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $data['nik'] ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['no_hp'] ?></td>
                <td><?= $data['nama_divisi'] ?></td>
                <td><?= $data['nama_shift'] ?></td>
                <td class="text-center">
                <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal" 
                data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                <a href="?page=hapus_karyawan&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0" 
                onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i> Hapus</a>
                </td>
                </tr>
                </div>
                   <div class="modal fade" id="modalEdit<?= $data['id']; ?>" data-backdrop="static" 
                   data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
               <div class="modal-dialog" style="max-width: 450px;">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Input Data Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="POST">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    <div class="modal-body">
                      <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label">Nik</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="nik" value="<?= $data['nik']; ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label"> Karyawan</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="nama" value="<?= $data['nama'] ?>">
                        </div>
                      </div>
                       <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label">No.Telepon</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="no_hp" value="<?= $data['no_hp'] ?>">
                        </div>
                      </div>
                      <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label">Divisi</label>
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
                        <label for="text" class="col-sm-3 col-form-label">Shift</label>
                        <div class="col-sm-9">
                          <select name="shift" class="form-control" required>
                            <option value="">--Pilih Shift--</option>
                            <?php
                              $editshift = mysqli_query($con, "SELECT * FROM tbl_shift ORDER BY nama_shift ASC");
                               while ($se = mysqli_fetch_assoc($editshift)) { ?>
                                <option value="<?= $se['id']; ?>" <?= ($se['id'] == $data['id_shift']) ? 
                                'selected' : ''; ?>><?= $se['nama_shift']; ?></option>
                              <?php } ?>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" name="editdata">Simpan</button>
                       </div>
                      </div>
                   </div>
                </form>
              </div>
            </div>
                <?php } ?>
               </tbody>
               </table>
               </div>

             <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" 
              tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog" style="max-width: 450px;">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Input Data Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="POST">
                    <div class="modal-body">
                      <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label">Nik</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="nik" value="<?= $nik; ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label"> Karyawan</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="nama" placeholder="Nama Karyawan">
                        </div>
                      </div>
                       <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label"> No.Telepon</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control rounded-0" name="no_hp" placeholder="No.Telepon">
                        </div>
                      </div>
                      <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label">Divisi</label>
                        <div class="col-sm-9">
                          <select name="divisi" class="form-control" required>  
                            <option value="">-- Pilih Divisi --</option>
                            <?php
                            $qDivisi = mysqli_query($con, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
                            while ($d = mysqli_fetch_assoc($qDivisi)) { ?>
                            <option value="<?= $d['id']; ?>"> <?= $d['nama_divisi']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                      </div>
                      <div class="form-group row mt-4">
                        <label for="text" class="col-sm-3 col-form-label">Shift</label>
                        <div class="col-sm-9">
                          <select name="shift" class="form-control" required>
                            <option value="">--Pilih Shift--</option>
                            <?php
                            $qShift = mysqli_query($con, "SELECT * FROM tbl_shift ORDER BY nama_shift ASC");
                            while ($s = mysqli_fetch_assoc($qShift)) { ?>
                            <option value="<?= $s['id']; ?>"> <?= $s['nama_shift']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer mt-3 mb-3 ">
                      <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-info rounded-0" name="simpandata">Simpan</button>
                       </div>
                      </div>
                   </div>
                </form>
              </div>
            </div>
        </div>
     </div>
  </div>
</section>