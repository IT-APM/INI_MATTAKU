<!DOCTYPE html>
<html>
<head>
<title>Undangan Interview <?php echo e($data['metode']); ?></title>
<link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
</head>
<body>

<?php
$kebutuhan_atribute = json_decode($data['trx']->kebutuhan_atribute, true);
$kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
?>

<p>Yth. <?php echo e($data['interviewer']->nama); ?>,<br/>Dengan hormat,</p>


<p>Dengan ini kami informasikan jadwal interview untuk kandidat atas nama <?php echo e($data['trx']->nama); ?> yang melamar pada posisi <?php echo e(App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan); ?>.</p>

<p>Jadwal pelaksanaan interview adalah sebagai berikut:</p>
<table border="0" cellpadding="1" cellspacing="1">	
		<tr>
			<td width="150">Hari/Tanggal</td>
			<td width="5">:</td>
			<td><?php echo e(App\Http\Controllers\TrxRecruitmentController::getTanggalIndonesia(\Carbon\Carbon::parse($data['check_in'])->toDateString())); ?></td>
		</tr>
		<tr>
			<td>Waktu</td>
			<td>:</td>
			<td><?php echo e(\Carbon\Carbon::parse($data['check_in'])->format('H:i')); ?> WIB s/d <?php echo e(\Carbon\Carbon::parse($data['check_out'])->format('H:i')); ?> WIB</td>
		</tr>
		<tr>
			<td>Agenda</td>
			<td>:</td>
			<td>Interview <?php echo e($data['metode']); ?></td>
		</tr>
		<?php if($data['metode'] == 'Offline'): ?>
		<tr>
			<td>Tempat</td>
			<td>:</td>
			<td><a href="https://maps.app.goo.gl/4aqNmc12zhn7yts89" data-toggle="tooltip" title='Alita Building'>Alita Building</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td>Jl. Muara No.108, RT.6/RW.2, Tanjung Barat, Jagakarsa, Kota Jakarta Selatan 12530</td>
		</tr>
		<?php elseif($data['metode'] == 'Online'): ?>
		<tr>
			<td>Tempat / Link</td>
			<td>:</td>
			<td><a href="<?php echo e($data['href']); ?>" data-toggle="tooltip" title='Klik Link'>Klik Link</a></td>
		</tr>
		<?php endif; ?>
</table>
<p>Mohon Bapak/Ibu/Saudara/i hadir tepat waktu sesuai jadwal yang telah diinformasikan.<br />Apabila ada hal-hal yang ingin ditanyakan lebih lanjut, silahkan menghubungi Kami</p>

<p>Demikian informasi undangan ini kami sampaikan.<br />Terimakasih atas perhatian dan kerjasamanya.</p>


<?php echo $__env->make('emails.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/recruitment2a.blade.php ENDPATH**/ ?>