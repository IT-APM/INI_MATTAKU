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
@php
$pengaju_property = json_decode($trxMaster->pengaju_property, true);
$atasan_property = json_decode($trxMaster->atasan_property, true);
$koordinator_property = json_decode($trxMaster->koordinator_property, true);
@endphp
<body>
	<!-- start -->
	<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM BIAYA PERJALANAN DINAS</div></td>
                        <td width="25%"><div class="center">No: {{ $trxMaster->id }}</div></td>
					</tr>
				</table>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>
						@if ($trxMaster->luar_dalam == 'ca_dalam') CASH ADVANCE PERJALANAN DINAS DALAM NEGERI
						@elseif ($trxMaster->luar_dalam == 'ca_luar') CASH ADVANCE PERJALANAN DINAS LUAR NEGERI
						@elseif ($trxMaster->luar_dalam == 'reimburse') REIMBURSEMENT PERJALANAN DINAS DALAM NEGERI
						@elseif ($trxMaster->luar_dalam == 'reimburse_luar') REIMBURSEMENT PERJALANAN DINAS LUAR NEGERI
						@endif
						</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>PELAKSANA PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Nama Karyawan</td>
						<td width="2%">:</td>
						<td width="68%">{{ $pengaju_property['nama'] }}</td>
					</tr>
					<tr>
						<td>NIP</td>
						<td>:</td>
						<td>{{ $pengaju_property['nik'] }}</td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>{{ $pengaju_property['jabatan'] }}</td>
					</tr>
					<tr>
						<td>Level</td>
						<td>:</td>
						<td>{{ $pengaju_property['level'] }}</td>
					</tr>
					<tr>
						<td>Direktorat / Divisi / Departemen</td>
						<td>:</td>
						<td>{{ $pengaju_property['divisi'] }} / {{ $pengaju_property['dep'] }} / {{ $pengaju_property['sub_dep'] }}</td>
					</tr>
					<tr>
						<td>Nama Atasan Langsung</td>
						<td>:</td>
						<td>{{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }}</td>
					</tr>
				</table>
				<hr/>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>INFORMASI PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="30%">Tahun Pengajuan</td>
						<td width="2%">:</td>
						<td width="68%">{{ $trxMaster->periode }}</td>
					</tr>
					<tr>
						<td>Golongan</td>
						<td>:</td>
						<td>@if($trxMaster->is_leader==1) Leader @elseif($trxMaster->is_leader==2) Member @elseif($trxMaster->is_leader==3) Management @endif</td>
					</tr>
					<tr>
						<td>Kelompok Perjalanan Dinas</td>
						<td>:</td>
						<td>Project: {{ $kelompok['project'] }} / Corporate: {{ $kelompok['nonproject'] }}</td>
					</tr>
					<tr>
						<td>Uraian Tugas Perjalanan Dinas</td>
						<td>:</td>
						<td>{!! nl2br(str_replace('<', ' ', $trxMaster->uraian)) !!}</td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
                        <td width="50%">Tujuan</td>
						<td width="15%">Tanggal dari</td>
						<td width="15%">Tanggal sampai</td>
						<td width="15%">Jumlah hari</td>
					</tr>
					@foreach ($trxDetailInfo as $data)
					<tr align="center">
						<td>{{ ++$no_info }}</td>
						@if (in_array($trxMaster->luar_dalam, ['ca_dalam', 'reimburse']))
						<td align="left">{{ $data->_tujuan1_dalam->propinsi }} - {{ $data->_tujuan2_dalam->kota }}</td>
						@elseif (in_array($trxMaster->luar_dalam, ['ca_luar', 'reimburse_luar']))
						<td align="left">{{ $data->_tujuan1_luar->negara }}</td>
						@endif
						<td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}</td>
						<td>{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
						<td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1 }}</td>
					</tr>
					@endforeach
				</table>
				<hr/>
				<!--  -->
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><strong>BIAYA PERJALANAN DINAS</strong></td>
					</tr>
				</table>
				<hr/>
				<!--  -->
				@php
				$total_p_idr = 0;
				$total_p_usd = 0;
				@endphp
				@if(count($trxDetailBiayaPersonel)>0)
				<strong>&nbsp;Biaya Personel</strong><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
						<td width="10%">Kode COA</td>
						<td width="40%">Keterangan</td>
						<td width="5%">Qty</td>
						<td width="15%">Nominal</td>
						<td width="10%">Satuan</td>
						<td width="15%">Total</td>
					</tr>
					@foreach ($trxDetailBiayaPersonel as $data)
					@php
					$kode_coa = explode('-', $data->kode_coa);
					if($data->satuan == 'IDR'){
						$total_p_idr += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_p_usd += $data->nominal * $data->jumlah;
					}
					@endphp
					<tr>
						<td>{{ ++$no_biaya_info }}</td>
						<td>{{ $kode_coa[0] }}</td>
						@if (in_array($trxMaster->luar_dalam, ['ca_dalam', 'reimburse']))
						<td align="left">{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_dalam->propinsi }}) @endif</td>
						@elseif (in_array($trxMaster->luar_dalam, ['ca_luar', 'reimburse_luar']))
						<td align="left">{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_luar->negara }}) @endif</td>
						@endif
						<td>{{ $data->jumlah }}</td>
						<td align="right">{{ number_format($data->nominal, 0) }}</td>
						<td>{{ $data->satuan }}</td>
						<td align="right">{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="5" align="right"><strong>Total Biaya Personel</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong>{{ number_format($total_p_idr, 0) }} / {{ number_format($total_p_usd, 0) }}</strong></td>
					</tr>
				</table>
				@endif
				<!--  -->
				@php
				$total_l_idr = 0;
				$total_l_usd = 0;
				@endphp
				@if(count($trxDetailBiaya)>0)
				<strong>&nbsp;Biaya Lain-lain</strong><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%">#</td>
						<td width="50%">Biaya</td>
						<td width="5%">Qty</td>
						<td width="15%">Nominal</td>
						<td width="10%">Satuan</td>
						<td width="15%">Total</td>
					</tr>
					@foreach ($trxDetailBiaya as $data)
					@php
					if($data->satuan == 'IDR'){
						$total_l_idr += $data->nominal * $data->jumlah;
					}else if($data->satuan == 'USD'){
						$total_l_usd += $data->nominal * $data->jumlah;
					}
					@endphp
					<tr align="center">
						<td>{{ ++$no_biaya }}</td>
						<td align="left">{{ $data->biaya }}</td>
						<td>{{ $data->jumlah }}</td>
						<td align="right">{{ number_format($data->nominal, 0) }}</td>
						<td>{{ $data->satuan }}</td>
						<td align="right">{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="4" align="right"><strong>Total Biaya Lain-lain</strong></td>
						<td align="center"><strong>IDR / USD</strong></td>
						<td align="right"><strong>{{ number_format($total_l_idr, 0) }} / {{ number_format($total_l_usd, 0) }}</strong></td>
					</tr>
				</table>
				@endif
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="75%" colspan="5" align="right"><strong>Total @if($trxMaster->luar_dalam == 'reimburse') Reimbursement @else Cash Advance @endif</strong></td>
						<td width="10%" align="center"><strong>IDR / USD</strong></td>
						<td width="15%" align="right"><strong>{{ number_format($total_p_idr+$total_l_idr, 0) }} / {{ number_format($total_p_usd+$total_l_usd, 0) }}</strong></td>
					</tr>
				</table>
				{!! $htmlLog !!}
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>
</html>