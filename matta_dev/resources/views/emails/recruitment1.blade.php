<!DOCTYPE html>
<html>
<head>
<title>Undangan Psikotes</title>
<link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
</head>
<body>

<p>Yth. {{ $data['trx']->nama }},<br/>Dengan hormat,</p>
<p>Sehubungan dengan proses Seleksi Penerimaan Karyawan yang sedang berlangsung di PT Alita Praya Mitra, dengan ini kami mengundang Bapak/Ibu/Saudara/i {{ $data['trx']->nama }} untuk mengikuti tahapan psikotes sebagai bagian dari proses Seleksi Penerimaan Karyawan di PT Alita Praya Mitra.</p>
<p>Jadwal pelaksanaan psikotes adalah sebagai berikut:</p>
<table border="0" cellpadding="1" cellspacing="1">	
		<tr>
			<td width="150">Hari/Tanggal</td>
			<td width="5">:</td>
			<td>{{ App\Http\Controllers\TrxRecruitmentController::getTanggalIndonesia(\Carbon\Carbon::parse($data['trx']->check_in)->toDateString()) }}</td>
		</tr>
		<tr>
			<td>Waktu</td>
			<td>:</td>
			<td>{{ \Carbon\Carbon::parse($data['trx']->check_in)->format('H:i') }} WIB s/d {{ \Carbon\Carbon::parse($data['trx']->check_out)->format('H:i') }} WIB</td>
		</tr>
		<tr>
			<td>Agenda</td>
			<td>:</td>
			<td>Psikotes (Online)</td>
		</tr>
		<tr>
			<td>Tempat / Link</td>
			<td>:</td>
			<td><a href="{{ route('recruitment.psychotest', [Crypt::encrypt(array($data['trx']->id, 'psychotest'))]) }}" data-toggle="tooltip" title='Click here'>Klik Link</a> atau Scan QR Code untuk memulai</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><img src="{{ asset('storage/qr/'.base64_encode($data['trx']->id).'.png') }}" alt="QR Code" title="QR Code" width="100" height="100"/></td>
			{{-- <td>{{ QrCode::size(100)->generate(route('recruitment.psychotest', [Crypt::encrypt(array($data['trx']->id, 'psychotest'))])) }}</td> --}}
		</tr>
</table>
<p>Untuk kelancaran Psikotes, mohon Bapak/Ibu/Saudara/i {{ $data['trx']->nama }} mempersiapkan beberapa perlengkapan sebagai berikut:<br />
1. Laptop / Komputer<br />
2. Kertas HVS kosong warna putih 2 lembar<br />
3. Pensil HB<br />
4. Jaringan internet yang stabil</p>

<p>Mohon Bapak/Ibu/Saudara/i {{ $data['trx']->nama }} hadir tepat waktu sesuai jadwal yang telah diinformasikan. Apabila ada hal-hal yang ingin ditanyakan lebih lanjut, silahkan menghubungi:</p>

<p>Sdr. Sofyan Farid Ma'arif<br />
Telepon: 087884350690 (hanya Whatsapp)<br />
Email: sofyan.farid@alita.id</p>

<p>Demikian undangan ini kami sampaikan.<br />
Terimakasih atas perhatian dan kerjasamanya.</p>
{{-- <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
</body>
</html> --}}
@include('emails.footer')
