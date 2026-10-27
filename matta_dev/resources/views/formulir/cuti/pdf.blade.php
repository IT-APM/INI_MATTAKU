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
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PERMOHONAN CUTI</div></td>
                        <td width="25%"><div class="center">No: {{ $trxCuti->id }}</div></td>
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
						<td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
						<td width="70%">: {{ $karyawan['nama'] }}<br/>: {{ $karyawan['nik'] }}<br/>: {{ $karyawan['jabatan'] }}<br/>: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }} </td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>TIPE CUTI</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tipe Cuti</td>
						<td width="70%">: {{ $trxCuti->_tipe->tipe_cuti }}</td>
					</tr>
					<tr>
						<td>Tahun Periode Cuti</td>
						<td>: {{ $trxCuti->periode_cuti }}</td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>MASA CUTI</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Mengajukan cuti pada</td>
						<td width="70%">: {{ $arrTanggal['tanggal_mulai'] }} sampai dengan {{ $arrTanggal['tanggal_akhir'] }}</td>
					</tr>
					<tr>
						<td>Jumlah hari cuti</td>
						<td>: {{ $trxCuti->jumlah_hari }}</td>
					</tr>
					{{-- 12=Ibadah Umroh (7 hari) --}}
					@if($trxCuti->tipe_cuti == 12)
					{{-- 5 hari sebelum --}}
					<tr>
						<td>Tanggal sebelum</td>
						<td>
							@for ($x = 0; $x <5; $x++)
								: {{ $arrTanggal['arrTanggalCuti'][$x] }}@if($x<4)<br/>@endif
							@endfor
							{{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
							{{ $value }}<br/>
							@endforeach --}}
						</td>
					</tr>
					{{-- 7 hari umroh --}}
					<tr>
						<td>Tanggal umroh</td>
						<td>
							@for ($x = 5; $x <12; $x++)
								: {{ $arrTanggal['arrTanggalCuti'][$x] }}@if($x<11)<br/>@endif
							@endfor
							{{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
							{{ $value }}<br/>
							@endforeach --}}
						</td>
					</tr>
					{{-- 3 hari sesudah --}}
					<tr>
						<td>Tanggal sesudah</td>
						<td>
							@for ($x = 12; $x <15; $x++)
								: {{ $arrTanggal['arrTanggalCuti'][$x] }}@if($x<14)<br/>@endif
							@endfor
							{{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
							{{ $value }}<br/>
							@endforeach --}}
						</td>
					</tr>
					@else
					<tr>
						<td>Tanggal cuti</td>
						<td>@foreach ($arrTanggal['arrTanggalCuti'] as $value): {{ $value }}@if(!$loop->last)<br/>@endif @endforeach
						</td>
					</tr>
					@endif
					{{-- <tr>
						<td>Tanggal cuti</td>
						<td>@foreach ($arrTanggal['arrTanggalCuti'] as $value): {{ $value }}<br/>@endforeach
						</td>
					</tr> --}}
					<tr>
						<td>Masuk bekerja kembali</td>
						<td>: {{ $arrTanggal['tanggal_masuk'] }}</td>
					</tr>
					<tr>
						<td>Alasan pengajuan cuti</td>
						<td>: {!! $trxCuti->alasan !!}</td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>: {!! $trxCuti->alamat !!}</td>
					</tr>
					<tr>
						<td>Nomor telepon</td>
						<td>: {{ $trxCuti->telepon }}</td>
					</tr>
					<tr>
						<td>Keterangan</td>
						<td>: {{ $trxCuti->keterangan }}</td>
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
						<td>{{ $value['nama'] }}<br/>{{ $value['nik'] }}</td>
						<td>{{ $value['jabatan'] }}<br/>{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
						<td>{{ $value['telepon1'] }}<br/>{{ $value['email'] }}</td>
					</tr>
					@endforeach
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>RESUME</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tipe Cuti</td>
						<td width="70%">: {{ $trxCuti->_tipe->tipe_cuti }}</td>
					</tr>
					<tr>
						<td>Tahun Periode Cuti</td>
						<td>: {{ $trxCuti->periode_cuti }}</td>
					</tr>
					<tr>
						<td>Jumlah hari cuti</td>
						<td>: {{ $trxCuti->jumlah_hari }}</td>
					</tr>
				</table>
				<hr />
				{{-- catatan atasan --}}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>CATATAN ATASAN</strong></td>
					</tr>
				</table>
				<hr />
				@php
				$where = ['id_trx' => $trxCuti->id];
				$catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
				@endphp
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>@if(!empty($catatan)){!! $catatan->keterangan !!}@endif</td>
					</tr>
				</table>
				<hr />
				{{-- reject by hco --}}
				@php
				$where = ['id_trx' => $trxCuti->id];
				$catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [8])->orderBy('id', 'desc')->first();
				@endphp
				@if(!empty($catatan))
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>CATATAN {{ strtoupper(App\Models\MtrUtility::find(51)->informasi) }}</strong></td>
					</tr>
				</table>
				<hr />
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>{{ $catatan->keterangan }}</td>
					</tr>
				</table>
				<hr />
				@endif
				{!! $htmlCutiLog !!}
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>