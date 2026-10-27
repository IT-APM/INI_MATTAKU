<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
	<title>Matta Reborn</title>
</head>

<body>
	<!-- start -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">RESUME HASIL TES</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trx->id); ?></div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="24%">Tanggal Psikotest</td>
						<td width="2%">:</td>
						<td width="74%"><?php echo e(Carbon\Carbon::parse($trx->check_in)->format('d-m-Y')); ?></td>
					</tr>
					<tr>
						<td>Tanggal Interview</td>
						<td>:</td>
						<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
								<?php $__currentLoopData = $interview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td width="15%"><?php echo e(Carbon\Carbon::parse($data->check_in)->format('d-m-Y')); ?></td>
									<td width="10%"><?php echo e($data->metode); ?></td>
									<td width="75%">
										
										<?php $__currentLoopData = explode(';', $data->interviewer); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<?php 
										$interviewer = '';
										$nama = App\Models\Karyawan::where(['nik' => $_data])->first()->nama;
										$interviewer .= $nama.'/'.$_data;
										if(!$loop->last){
											$interviewer .= '<br/>';
										}
										?>
										<?php echo $interviewer; ?>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</table>
							
						</td>
					</tr>
					<tr>
						<td>Perusahaan</td>
						<td>:</td>
						<td>PT. ALITA PRAYA MITRA</td>
					</tr>
				</table>
				<br/><br/>
				
				<?php $x=0 ?>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<th width="5%" rowspan="2"><b>NO</b></th>
						<th width="45%" rowspan="2"><b>NAMA</b></th>
						<th colspan="2"><b>REKOMENDASI</b></th>
					</tr>
					<tr align="center">
						<td><b>DISARANKAN</b></td>
						<td><b>TIDAK DISARANKAN</b></td>
					</tr>
					<tr align="center">
						<td width="5%">1</td>
						<td width="45%"><?php echo e($trx->nama); ?></td>
						
						<td width="25%"><?php if($resume[$x]->jawaban): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="15px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="15px"> <?php endif; ?></td>
						<td width="25%"><?php if(!$resume[$x]->jawaban): ?><img src="<?php echo e(public_path('assets/images/matta/check.png')); ?>" alt="check_uncheck" width="15px"> <?php else: ?> <img src="<?php echo e(public_path('assets/images/matta/uncheck.png')); ?>" alt="check_uncheck" width="15px"> <?php endif; ?></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>URAIAN PERILAKU</b></td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<th width="50%"><b>Strong Point</b></th>
						<th width="50%"><b>To Be Develop</b></th>
					</tr>
					<tr>
						<?php $x=1 ?>
						
						<td><?php echo nl2br(e($resume[$x]->jawaban)); ?></td>
						<?php $x=2 ?>
						
						<td><?php echo nl2br(e($resume[$x]->jawaban)); ?></td>
					</tr>
				</table>
				
				<?php $x=3 ?>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td align="justify"><b>Kesimpulan :</b><br/><?php echo nl2br(e($resume[$x]->jawaban)); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/pdf_resume.blade.php ENDPATH**/ ?>