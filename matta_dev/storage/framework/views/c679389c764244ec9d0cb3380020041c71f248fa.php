<?php echo $__env->make('emails.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<p>Semangat Pagi,</p>
<p><strong>Status Formulir Reimbursement anda adalah <?php echo e($data['status']); ?>.</strong></p>
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




<?php if(in_array($data['id_status'], [2,3,6,7])): ?>
<?php if(in_array($data['id_status'], [2,6]) && $data['email_to'] == App\Models\Karyawan::where('nik', App\Models\MtrUtility::find(12)->informasi)->value('email')): ?>
<p>Klik link berikut untuk proses lebih lanjut:</p>
<h1><strong>Proses <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Reimbursement</a></strong></h1>
<?php else: ?>
<p>Klik link berikut untuk download pdf Formulir Reimbursement:</p>
<h1><strong>Klik <a href="<?php echo e($data['link']); ?>" target="_blank">Formulir Reimbursement</a></strong></h1>
<?php endif; ?>
<?php endif; ?>
<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/reimburse_done.blade.php ENDPATH**/ ?>