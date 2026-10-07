  <?php
  // PROSES SIMPAN
  if (isset($_POST['simpandata'])) {
      $namashift   = $_POST['namashift'];
      $mulai       = $_POST['mulai'];
      $selesai     = $_POST['selesai'];
      $simpan      = mysqli_query($con, "INSERT INTO tbl_shift(nama_shift, jam_masuk, jam_pulang)
                    VALUES ('$namashift', '$mulai', '$selesai')");
  if ($simpan) {
      echo "<script> alert('Data berhasil disimpan');
      window.location='?page=shift';</script>";
      exit;
  } else {
      echo "<script> alert('Data gagal disimpan');</script>";
  }
      }
  //  PROSES EDIT
  if (isset($_POST['editdata'])) {
      $id          = $_POST['id'];
      $namashift   = $_POST['namashift'];
      $mulai       = $_POST['mulai'];
      $selesai     = $_POST['selesai'];
      $simpan = mysqli_query($con, "UPDATE tbl_shift SET nama_shift='$namashift', jam_masuk='$mulai', jam_pulang='$selesai' WHERE id='$id'");

  if ($simpan) {
      echo "<script> alert('Data berhasil disimpan');
      window.location='?page=shift';</script>";
      exit;
  } else {
      echo "<script> alert('Data gagal disimpan');</script>";
  }
      }
  ?>
<div class="col-sm-12">
  <div div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-indent"></i> Table Shift</h5>
      <button type="button" class="btn btn-success float-right rounded-0" data-toggle="modal" data-target="#staticBackdrop">Tambah Data</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">NO</th>
              <th>Nama Shift</th>
              <th>Jam Masuk</th>
              <th>Jam Pulang</th>
              <th width="180" class="text-center">ACTION</th>
          </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $tampil = mysqli_query($con, "SELECT * FROM tbl_shift ORDER BY id DESC");
            while ($data = mysqli_fetch_array($tampil)) { ?>
                <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $data['nama_shift'] ?></td>
                <td><?php echo $data['jam_masuk'] ?></td>
                <td><?php echo $data['jam_pulang'] ?></td>
                <td class="text-center">
                <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal" 
                data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                <a href="?page=hapus_shift&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0" 
                onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i> Hapus</a>
                </td>
               </tr>
              </div>
           <div class="modal fade" id="modalEdit<?= $data['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 400px;">
               <div class="modal-content">
                  <div class="modal-header">
                   <h5 class="modal-title" id="staticBackdropLabel">Input Data Shift</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <form method="POST" action="?page=shift">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                  <div class="card">
                   <div class="modal-body">
                   <div class="form-group col-md-12">
                    <label>Nama Shift</label>
                     <input type="text" class="form-control" name="namashift" value="<?= ($data['nama_shift']); ?>">
                       </div>
                       <div class="form-group col-md-12">
                   <label>Jam Masuk</label>
                   <input type="time" class="form-control" name="mulai" value="<?= $data['jam_masuk']; ?>">
                  </div>
               <div class="form-group col-md-12">
                <label>Jam Pulang</label>
                <input type="time" class="form-control" name="selesai" value="<?= $data['jam_pulang']; ?>">
                 </div>
                <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" name="editdata" class="btn btn-primary">Simpan</button>
             </div>
         </div>
      </form>
    </div>
  </div>
  <?php } ?>
     </tbody>
         </table>
           </div>
             <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog" style="max-width: 400px;">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Input Data Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                 <form method="POST" action="?page=shift">
              <div class="card">
                <div class="modal-body">
                  <div class="form-group col-md-12">
                    <label style="font-weight: normal;">Nama</label>
                    <input type="text" class="form-control" name="namashift" placeholder="masukan Shift" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label style="font-weight: normal;">Jam Masuk</label>
                    <input type="time" class="form-control" name="mulai" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label style="font-weight: normal;">Jama Pulang </label>
                    <input type="time" class="form-control" name="selesai" required>
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
</section>