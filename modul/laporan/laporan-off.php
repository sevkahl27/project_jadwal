 <h6>Riwayat Pengajuan Saya</h6>
    <table class="table table-bordered table-sm mt-2">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Keterangan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $riwayat = mysqli_query($con, "SELECT * FROM tbl_hari_off WHERE id_karyawan='$myId' ORDER BY tanggal_off DESC");
        while ($r = mysqli_fetch_assoc($riwayat)) {
          $badge = $r['status'] == 'disetujui' ? 'success' : ($r['status'] == 'ditolak' ? 'danger' : 'warning');
        ?>
          <tr>
            <td><?= date('d-m-Y', strtotime($r['tanggal_off'])); ?></td>
            <td><?= $r['keterangan']; ?></td>
            <td><span class="badge badge-<?= $badge; ?>"><?= ucfirst($r['status']); ?></span></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>