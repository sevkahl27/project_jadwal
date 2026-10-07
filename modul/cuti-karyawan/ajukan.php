  <?php
  $username    = $_SESSION['username'];
  $cekUser     = mysqli_query($con, "SELECT * FROM tbl_user WHERE username='$username'");
  $user        = mysqli_fetch_assoc($cekUser);
  $id_karyawan = $user['id_karyawan'];

  // PROSES SIMPAN PENGAJUAN CUTI
  if (isset($_POST['ajukancuti'])) {
    $jenis_cuti      = $_POST['jenis_cuti'];
    $tanggal_mulai   = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $alasan          = $_POST['alasan'];
    $simpan          = mysqli_query($con, "INSERT INTO tbl_cuti(id_karyawan, jenis_cuti, tanggal_mulai, tanggal_selesai, alasan, status)
                       VALUES ('$id_karyawan', '$jenis_cuti', '$tanggal_mulai', '$tanggal_selesai', '$alasan', 'Menunggu')");

    if ($simpan) {
      echo "<script> alert('Pengajuan cuti berhasil dikirim'); 
        window.location='?page=ajukan-cuti';</script>";
      exit;
    } else {
      echo "<script>alert('Pengajuan cuti gagal dikirim');</script>";
    }
  }
  // PROSES EDIT CUTI
  if (isset($_POST['editcuti'])) {
    $id                = $_POST['id'];
    $jenis_cuti        = $_POST['jenis_cuti'];
    $tanggal_mulai     = $_POST['tanggal_mulai'];
    $tanggal_selesai   = $_POST['tanggal_selesai'];
    $alasan            = $_POST['alasan'];
    $update            = mysqli_query($con, "UPDATE tbl_cuti SET 
    jenis_cuti         ='$jenis_cuti', 
    tanggal_mulai      ='$tanggal_mulai', 
    tanggal_selesai    ='$tanggal_selesai', 
    alasan             ='$alasan' 
  WHERE id='$id' AND id_karyawan='$id_karyawan' AND status='Menunggu'");
    if ($update) {
      echo "<script>
        alert('Pengajuan cuti berhasil diubah');
        window.location='?page=ajukan-cuti';
    </script>";
      exit;
    } else {
      echo "<script>
        alert('Pengajuan cuti gagal diubah');
        window.location='?page=ajukan-cuti';
    </script>";
      exit;
    }
  }
              // BATAL CUTI
  if (isset($_GET['page']) && $_GET['page'] == 'batal-cuti') {
    $id = $_GET['id'];
    mysqli_query($con, "DELETE FROM tbl_cuti WHERE id='$id'");
    echo "<script>window.location='?page=ajukan-cuti';</script>";
    exit;
  }
  ?>
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title"><i class="fas fa-plane-departure"></i> Riwayat Pengajuan Cuti</h5>
        <button type="button" class="btn btn-primary float-right rounded-0 py-1 px-3" data-toggle="modal"
          data-target="#staticBackdrop"><i class="fas fa-plus"></i> Ajukan Cuti</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">NO</th>
                <th>JENIS CUTI</th>
                <th>TGL MULAI</th>
                <th>TGL SELESAI</th>
                <th>ALASAN</th>
                <th>STATUS</th>
                <th width="220" class="text-center">ACTION</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = mysqli_query($con, "SELECT * FROM tbl_cuti WHERE id_karyawan='$id_karyawan' ORDER BY id DESC");
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
                  <td><?php echo $data['jenis_cuti'] ?></td>
                  <td><?php echo $data['tanggal_mulai'] ?></td>
                  <td><?php echo $data['tanggal_selesai'] ?></td>
                  <td><?php echo $data['alasan'] ?></td>
                  <td><span class="badge <?= $warna ?>"><?= $data['status'] ?></span></td>
                  <td class="text-center">
                    <button type="button" class="btn btn-info btn-sm rounded-0" data-toggle="modal" data-target="#modalDetail<?= $data['id']; ?>">
                      <i class="fa fa-eye"></i> Detail
                    </button>
                    <?php if ($data['status'] == 'Menunggu') { ?>
                      <a href="#" class="btn btn-warning btn-sm rounded-0" data-toggle="modal"
                        data-target="#modalEdit<?= $data['id']; ?>"><i class="far fa-edit"></i> Edit</a>
                      <a href="?page=batal-cuti&id=<?= $data['id']; ?>" class="btn btn-danger btn-sm rounded-0"
                        onclick="return confirm('Batalkan pengajuan cuti ini?');"><i class="fa fa-trash"></i> Batal</a>
                    <?php } ?>
                  </td>
                </tr>
                <!-- MODAL EDIT -->
                <div class="modal fade" id="modalEdit<?= $data['id']; ?>" data-backdrop="static"
                  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog" style="max-width: 450px;">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Pengajuan Cuti</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                        <div class="modal-body">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Jenis Cuti</label>
                            <div class="col-sm-8">
                              <select name="jenis_cuti" class="form-control" required>
                                <option value="Cuti Tahunan" <?= ($data['jenis_cuti'] == 'Cuti Tahunan') ? 'selected' : ''; ?>>Cuti Tahunan</option>
                                <option value="Cuti Sakit" <?= ($data['jenis_cuti'] == 'Cuti Sakit') ? 'selected' : ''; ?>>Cuti Sakit</option>
                                <option value="Cuti Melahirkan" <?= ($data['jenis_cuti'] == 'Cuti Melahirkan') ? 'selected' : ''; ?>>Cuti Melahirkan</option>
                                <option value="Cuti Penting" <?= ($data['jenis_cuti'] == 'Cuti Penting') ? 'selected' : ''; ?>>Cuti Penting</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row mt-4">
                            <label class="col-sm-4 col-form-label">Tanggal Mulai</label>
                            <div class="col-sm-8">
                              <input type="date" class="form-control rounded-0" name="tanggal_mulai" value="<?= $data['tanggal_mulai']; ?>" required>
                            </div>
                          </div>
                          <div class="form-group row mt-4">
                            <label class="col-sm-4 col-form-label">Tanggal Selesai</label>
                            <div class="col-sm-8">
                              <input type="date" class="form-control rounded-0" name="tanggal_selesai" value="<?= $data['tanggal_selesai']; ?>" required>
                            </div>
                          </div>
                          <div class="form-group row mt-4">
                            <label class="col-sm-4 col-form-label">Alasan</label>
                            <div class="col-sm-8">
                              <textarea class="form-control rounded-0" name="alasan" rows="3"><?= $data['alasan']; ?></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary rounded-0" name="editcuti">Simpan </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- MODAL DETAIL CUTI  -->
                <div class="modal fade" id="modalDetail<?= $data['id']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog" style="max-width: 400px;">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detail Pengajuan Cuti</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row mb-4">
                          <div class="col-5">Jenis Cuti</div>
                          <div class="col"> : <?= $data['jenis_cuti'] ?></div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Tanggal Mulai</div>
                          <div class="col"> : <?= $data['tanggal_mulai'] ?></div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Tanggal Selesai</div>
                          <div class="col"> : <?= $data['tanggal_selesai'] ?></div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Alasan</div>
                          <div class="col"> : <?= $data['alasan'] ?></div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Status</div>
                          <div class="col">
                            : <span class="badge <?= $warna ?>"> <?= $data['status'] ?></span>
                          </div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Diproses Oleh</div>
                          <div class="col">
                            : <?= $data['diproses_oleh'] ?: 'Belum diproses' ?>
                          </div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-5">Tanggal Diproses</div>
                          <div class="col">
                            : <?= $data['tanggal_proses'] ?: '.....!' ?>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL TAMBAH / AJUKAN CUTI -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 450px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Form Ajukan Cuti</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <div class="form-group row">
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
          </div>
          <div class="modal-footer mt-3">
            <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-info rounded-0" name="ajukancuti">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>