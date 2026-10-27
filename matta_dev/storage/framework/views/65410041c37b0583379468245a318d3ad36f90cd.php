<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Penilaian Karyawan yang harus anda lengkapi</strong></p>
<p></p>
<p><span style="text-decoration: underline;"><strong>Keterangan</strong></span></p>
<p><strong>Formulir Penilaian Reguler : Evaluasi untuk Karyawan yang habis masa Kontrak Kerja</strong></p>
<p><strong>Formulir Penilaian Asessment : Evaluasi untuk Karyawan setelah/dalam masa percobaan</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td>No Formulir</td>
    <td>: <?php echo e($data['id_trx']); ?></td>
  </tr>
  <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="80%">: <?php echo e($data['nama']); ?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>: <?php echo e($data['jabatan']); ?></td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>: <?php echo e($data['unit_kerja']); ?></td>
  </tr>
  <tr>
    <td>Nama Atasan Langsung</td>
    <td>: <?php echo e($data['atasan']); ?></td>
  </tr>
</table>
</p>
<p>Klik link berikut untuk melakukan pengisian Formulir Penilaian Karyawan tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Penilaian Karyawan</a></strong></h1>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/penilaian0.blade.php ENDPATH**/ ?>