<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Kebutuhan Karyawan yang harus anda review</strong></p>
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
</table>
</p>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Kebutuhan Karyawan</a></strong></h1>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/kebutuhan0.blade.php ENDPATH**/ ?>