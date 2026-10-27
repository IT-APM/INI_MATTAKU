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
				
				
				<?php $x=0 ?>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%"><b>No</b></td>
						<td width="27%"><b>Core Competence</b></td>
						<td width="40%"><b>Standard Competence</b></td>
						<td width="8%"><b>Point<br/>10-100</b></td>
						<td width="20%"><b>Keterangan</b></td>
					</tr>
					<?php $x=4 ?>
					<tr>
						<td align="center" rowspan="2">1</td>
						<td align="center" rowspan="2"><b><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></b></td>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=5 ?>
					<tr>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=6 ?>
					<tr>
						<td align="center" rowspan="2">2</td>
						<td align="center" rowspan="2"><b><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></b></td>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=7 ?>
					<tr>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=8 ?>
					<tr>
						<td align="center" rowspan="2">3</td>
						<td align="center" rowspan="2"><b><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></b></td>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=9 ?>
					<tr>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=10 ?>
					<tr>
						<td align="center" rowspan="2">4</td>
						<td align="center" rowspan="2"><b><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></b></td>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=11 ?>
					<tr>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=12 ?>
					<tr>
						<td align="center" rowspan="2">5</td>
						<td align="center" rowspan="2"><b><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></b></td>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
					<?php $x=13 ?>
					<tr>
						<td><?php echo e($resume[$x]->pernyataan2); ?></td>
						<td align="center"><?php echo e($resume[$x]->jawaban); ?></td>
						<td><?php echo e($resume[$x]->keterangan); ?></td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<span>PT Alita Praya Mitra - <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?><br/><?php echo e($resume[$x]->updated_at); ?></span>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/pdf_resume2.blade.php ENDPATH**/ ?>