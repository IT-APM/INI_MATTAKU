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
	<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PERMOHONAN IZIN SAKIT</div></td>
                        <td width="25%"><div class="center">No: {{ $trxSakit->id }}</div></td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>DIAJUKAN OLEH</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen</td>
						<td width="70%">: {{ $karyawan['nama'] }}<br />: {{ $karyawan['nik'] }}<br />: {{ $karyawan['jabatan'] }}<br />: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }} </td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>IZIN SAKIT</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tahun Izin Sakit</td>
						<td width="70%">: {{ $trxSakit->periode_sakit }}</td>
					</tr>
					<tr>
						<td>Mengajukan izin sakit pada</td>
						<td>: {{ $arrTanggal['tanggal_mulai'] }} sampai dengan {{ $arrTanggal['tanggal_akhir'] }}</td>
					</tr>
					<tr>
						<td>Tanggal izin sakit</td>
						<td>@foreach ($arrTanggal['arrTanggalSakit'] as $value): {{ $value }}<br />@endforeach
						</td>
					</tr>
					<tr>
						<td>Masuk bekerja kembali</td>
						<td>: {{ $arrTanggal['tanggal_masuk'] }}</td>
					</tr>
					<tr>
						<td>Alasan pengajuan izin sakit</td>
						<td>: {!! $trxSakit->alasan !!}</td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>: {!! $trxSakit->alamat !!}</td>
					</tr>
					<tr>
						<td>Nomor telepon</td>
						<td>: {{ $trxSakit->telepon }}</td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>PERSONEL PENGGANTI</strong></td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<th width="30%">Nama / NIP</th>
						<th width="45%">Jabatan / Direktorat / Divisi / Departemen</th>
						<th width="25%">Telepon / Email</th>
					</tr>
					@foreach ($arrPicProperty as $value)
					<tr>
						<td>{{ $value['nama'] }}<br />{{ $value['nik'] }}</td>
						<td>{{ $value['jabatan'] }}<br />{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
						<td>{{ $value['telepon1'] }}<br />{{ $value['email'] }}</td>
					</tr>
					@endforeach
				</table>
				<hr />
				{!! $htmlSakitLog !!}
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>