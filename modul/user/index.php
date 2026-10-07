<?php
// PROSES SIMPAN
if (isset($_POST['simpandata'])) {
  $id_karyawan = $_POST['id_karyawan'];
  $username    = $_POST['username'];
   $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role        = $_POST['role'];
  $simpan = mysqli_query($con, "INSERT INTO tbl_user(id_karyawan, username, password, role)
VALUES ('$id_karyawan', '$username', '$password', '$role')");
  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
window.location='?page=user';</script>";
    exit;
  } else {
    echo "Error: " . mysqli_error($con);
  }
}

//  PROSES EDIT
if (isset($_POST['editdata'])) {
  $id          = $_POST['id'];
  $id_karyawan = $_POST['id_karyawan'];
  $username    = $_POST['username'];
  $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role        = $_POST['role'];
  $simpan = mysqli_query($con, "UPDATE tbl_user 
SET id_karyawan='$id_karyawan', username='$username', password='$password', role='$role' WHERE id='$id'");

  if ($simpan) {
    echo "<script> alert('Data berhasil disimpan');
window.location='?page=user';</script>";
    exit;
  } else {
    echo "Error: " . mysqli_error($con);
  }
}
?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><i class="fas fa-indent"></i> Table User</h5>
      <button type="button" class="btn btn-success float-right rounded-0" data-toggle="modal" data-target="#staticBackdrop">Tambah Data</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">NO</th>
              <th>Nama Karyawan</th>
              <th>Email</th>
              <th>Role</th>
              <th width="180" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $tampil = mysqli_query($con, "SELECT 
            tbl_user.*, 
            tbl_karyawan.nama AS nama_karyawan
            FROM tbl_user
            INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.id
            ORDER BY tbl_user.id DESC");
            while ($data = mysqli_fetch_array($tampil)) { ?>
              <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $data['nama_karyawan'] ?></td>
                <td><?php echo $data['username'] ?></td>
                <td><?php echo $data['role'] ?></td>
                <td class="text-center">
                  <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal"
                    data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                  <a href="?page=hapus_user&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0"
                    onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i> Hapus</a>
                    
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="modalEdit<?= $data['id']; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 400px;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Edit Data User</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form method="POST" action="?page=user">
                      <input type="hidden" name="id" value="<?= $data['id']; ?>">
                      <div class="modal-body">
                        <div class="form-group col-md-12">
                          <label style="font-weight: normal;">Karyawan</label>
                          <select class="form-control" name="id_karyawan" required>
                            <option value="">-- Pilih Karyawan --</option>
                            <?php
                            $karyawan = mysqli_query($con, "SELECT tbl_karyawan.id, tbl_karyawan.nama, tbl_divisi.nama_divisi
                            FROM tbl_karyawan
                            INNER JOIN tbl_divisi ON tbl_karyawan.id_divisi = tbl_divisi.id
                            ORDER BY tbl_karyawan.nama ASC");
                            while ($k = mysqli_fetch_array($karyawan)) {
                              $selected = ($k['id'] == $data['id_karyawan']) ? 'selected' : '';
                              echo "<option value='{$k['id']}' $selected>{$k['nama']} - {$k['nama_divisi']}</option>";
                            }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-12">
                          <label style="font-weight: normal;">Email</label>
                          <input type="text" class="form-control" name="username" value="<?= $data['username']; ?>">
                        </div>
                        <div class="form-group col-md-12">
                          <label style="font-weight: normal;">Password</label>
                          <input type="password" class="form-control" name="password" value="<?= $data['password']; ?>">
                        </div>
                        <div class="form-group col-md-12">
                          <label style="font-weight: normal;">Role</label>
                          <select class="form-control" name="role" required>
                            <option value="">-- Pilih Role --</option>
                             <option value="Admin" <?= ($data['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Supervisor" <?= ($data['role'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                            <option value="Supervisor" <?= ($data['role'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                           <option value="Karyawan"<?= ($data['role'] == 'Karyawan') ? 'selected' : ''; ?>>Karyawan</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" name="editdata" class="btn btn-primary">Simpan</button>
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
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 400px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Tambah Data User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="?page=user">
          <div class="modal-body">
            <div class="form-group col-md-12">
              <label style="font-weight: normal;">Karyawan</label>
              <select class="form-control" name="id_karyawan" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php
                $karyawan = mysqli_query($con, "SELECT tbl_karyawan.id, tbl_karyawan.nama, tbl_divisi.nama_divisi
                FROM tbl_karyawan
                INNER JOIN tbl_divisi ON tbl_karyawan.id_divisi = tbl_divisi.id
                ORDER BY tbl_karyawan.nama ASC");
                while ($k = mysqli_fetch_array($karyawan)) {
                  echo "<option value='{$k['id']}'>{$k['nama']} - {$k['nama_divisi']}</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-12">
              <label style="font-weight: normal;">Email</label>
              <input type="email" class="form-control" name="username" placeholder="example@gamil.com" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
              <label style="font-weight: normal;">Password</label>
              <input type="password" class="form-control" name="password" placeholder="masukan password" autocomplete="new-password">
            </div>
            <div class="form-group col-md-12">
              <label style="font-weight: normal;">Role</label>
              <select class="form-control" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="Admin">Admin</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Karyawan">Karyawan</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" name="simpandata" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>