<?php

if (isset($_POST['simpandata'])) {
  $nama_divisi    = $_POST['nama_divisi'];
  $keterangan     = $_POST['keterangan'];
  $simpan         = mysqli_query($con, "INSERT INTO tbl_divisi(nama_divisi, keterangan) VALUES ('$nama_divisi', '$keterangan')");
  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
    window.location='?page=divisi';</script>";
    exit;
  } else {
    echo "<script> alert('Data gagal disimpan');</script>";
  }
}
if (isset($_POST['editshift'])) {
  $id               = $_POST['id'];
  $nama_divisi      = $_POST['nama_divisi'];
  $keterangan       = $_POST['keterangan'];
  $simpan = mysqli_query($con, "UPDATE tbl_divisi SET nama_divisi='$nama_divisi', keterangan='$keterangan' WHERE id='$id'");

  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
    window.location='?page=divisi';</script>";
    exit;
  } else {
    echo "<script> alert('Data gagal disimpan');</script>";
  }
}
?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-indent"></i> Table Divisi</h5>
      <button type="button" class="btn btn-primary float-right rounded-0 py-1 px-3" data-toggle="modal"
        data-target="#staticBackdrop"><i class="fas fa-plus"></i> Tambah Data</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%">No</th>
              <th width="35%">Divisi</th>
              <th>Keterangan</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $tampil = mysqli_query($con, "SELECT * FROM tbl_divisi ORDER BY id DESC");
            while ($data = mysqli_fetch_array($tampil)) { ?>
              <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo ($data['nama_divisi']) ?></td>
                <td><?php echo ($data['keterangan']) ?></td>
                <td>
                  <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal" data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                  <a href="?page=hapus_divisi&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0" onclick="return confirm('Yakin ingin menghapus data ini?');">
                    <i class="fa fa-trash"></i> Hapus</a>
                </td>
              </tr>
              <!-- MODAL EDIT -->
              <div class="modal fade" id="modalEdit<?= $data['id']; ?>">
                <div class="modal-dialog" style="max-width: 400px;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Divisi</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form method="POST" action="?page=divisi">
                      <input type="hidden" name="id" value="<?= $data['id']; ?>">
                      <div class="card">
                        <div class="modal-body">
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Divis</label>
                            <input type="text" class="form-control" name="nama_divisi" value="<?= ($data['nama_divisi']); ?>">
                          </div>

                          <div class="form-group row">
                            <label style="font-weight: normal;">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan"><?= $data['keterangan'] ?></textarea>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" name="editshift" class="btn btn-primary">Simpan</button>
                          </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- AKHIR MODAL EDIT -->
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="modal fade" id="staticBackdrop">
        <div class="modal-dialog" style="max-width: 400px;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">TAMBAH DIVISI</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="?page=divisi">
              <div class="card">
                <div class="modal-body">
                  <div class="form-group col-md-12">
                    <label style="font-weight: normal;">Divisi</label>
                    <input type="text" class="form-control" name="nama_divisi" placeholder="masukan divisi" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label style="font-weight: normal;">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" name="simpandata" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>