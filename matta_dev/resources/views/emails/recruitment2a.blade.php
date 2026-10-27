<!DOCTYPE html>
<html>
<head>
<title>Undangan Interview {{ $data['metode'] }}</title>
<link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
</head>
<body>

<p>Yth. {{ $data['interviewer']->nama }},<br/>Dengan hormat,</p>

<p>Dengan ini kami informasikan jadwal interview untuk kandidat atas nama {{ $data['trx']->nama }} yang melamar pada posisi {{ $data['posisi'] }}.</p>

<p>Jadwal pelaksanaan interview adalah sebagai berikut:</p>
<table border="0" cellpadding="1" cellspacing="1">	
		<tr>
			<td width="150">Hari/Tanggal</td>
			<td width="5">:</td>
			<td>{{ App\Http\Controllers\TrxRecruitmentController::getTanggalIndonesia(\Carbon\Carbon::parse($data['check_in'])->toDateString()) }}</td>
		</tr>
		<tr>
			<td>Waktu</td>
			<td>:</td>
			<td>{{ \Carbon\Carbon::parse($data['check_in'])->format('H:i') }} WIB s/d {{ \Carbon\Carbon::parse($data['check_out'])->format('H:i') }} WIB</td>
		</tr>
		<tr>
			<td>Agenda</td>
			<td>:</td>
			<td>Interview {{ $data['metode'] }}</td>
		</tr>
		@if($data['metode'] == 'Offline')
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
		@elseif($data['metode'] == 'Online')
		<tr>
			<td>Tempat / Link</td>
			<td>:</td>
			<td><a href="{{ $data['href'] }}" data-toggle="tooltip" title='Klik Link'>Klik Link</a></td>
		</tr>
		@endif
</table>
<p>Mohon Bapak/Ibu/Saudara/i hadir tepat waktu sesuai jadwal yang telah diinformasikan.<br />Apabila ada hal-hal yang ingin ditanyakan lebih lanjut, silahkan menghubungi Kami</p>

<p>Demikian informasi undangan ini kami sampaikan.<br />Terimakasih atas perhatian dan kerjasamanya.</p>

{{-- <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
</body>
</html> --}}
@include('emails.footer')
