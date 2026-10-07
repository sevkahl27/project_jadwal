<?php

$username = $_SESSION['username'];
$cekUser  = mysqli_query($con, "SELECT * FROM tbl_user WHERE username='$username'");
$user     = mysqli_fetch_assoc($cekUser);
$id_karyawan = $user['id_karyawan'];
?>
<div class="col-sm-12">
<div class="card">
  <div class="card-header">
    <h5 class="card-title"><i class="fas fa-plane-departure"></i> Riwayat Pengajuan Cuti Anda</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="example1" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="5%">NO</th>
            <th>Nama </th>
            <th>divisi</th>
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
              <td><?= $no++ ?></td>
              <td><?= $_SESSION['username'] ?></td>
              <td><?= $data['divisi'] ?></td>
              <td><?= $data['jenis_cuti'] ?></td>
              <td><?= $data['tanggal_mulai'] ?></td>
              <td><?= $data['tanggal_selesai'] ?></td>
              <td><?= $data['alasan'] ?></td>
              <td><span class="badge <?= $warna ?>"><?= $data['status'] ?></span></td>
              <td class="text-center">
                <button type="button" class="btn btn-info btn-sm rounded-0" data-toggle="modal" data-target="#modalDetail<?= $data['id']; ?>">
                  <i class="fa fa-eye"></i> Detail
                </button>
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
                    <div class="modal-footer mb-3">
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
