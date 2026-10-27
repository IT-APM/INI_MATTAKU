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
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">RESUME HASIL TES</div></td>
                        <td width="25%"><div class="center">No: {{ $trx->id }}</div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="24%">Tanggal Psikotest</td>
						<td width="2%">:</td>
						<td width="74%">{{ Carbon\Carbon::parse($trx->check_in)->format('d-m-Y') }}</td>
					</tr>
					<tr>
						<td>Tanggal Interview</td>
						<td>:</td>
						<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
								@foreach($interview as $data)
								<tr>
									<td width="15%">{{ Carbon\Carbon::parse($data->check_in)->format('d-m-Y') }}</td>
									<td width="10%">{{ $data->metode }}</td>
									<td width="75%">
										{{-- @php $interviewer = ''; @endphp --}}
										@foreach(explode(';', $data->interviewer) as $_data)
										@php 
										$interviewer = '';
										$nama = App\Models\Karyawan::where(['nik' => $_data])->first()->nama;
										$interviewer .= $nama.'/'.$_data;
										if(!$loop->last){
											$interviewer .= '<br/>';
										}
										@endphp
										{!! $interviewer !!}
										@endforeach
									</td>
								</tr>
								@endforeach
							</table>
							{{-- @foreach($interview as $data)
							@php $interviewer = ''; @endphp
							@foreach(explode(';', $data->interviewer) as $_data)
							@php 
							$nama = App\Models\Karyawan::where(['nik' => $_data])->first()->nama;
							$interviewer .= $nama.'/'.$_data.' ';
							@endphp
							@endforeach
							{{ Carbon\Carbon::parse($data->check_in)->format('d-m-Y') }}<br/>Metode: {{ $data->metode }}<br/>Oleh: {{ $interviewer }} @if(!$loop->last) <br/> @endif
							@endforeach --}}
						</td>
					</tr>
					<tr>
						<td>Perusahaan</td>
						<td>:</td>
						<td>PT. ALITA PRAYA MITRA</td>
					</tr>
				</table>
				<br/><br/>
				{{-- Rekomendasi --}}
				@php $x=0 @endphp
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
						<td width="45%">{{ $trx->nama }}</td>
						{{-- <span style="font-family:zapfdingbats;font-size:20px;">4</span> --}}
						<td width="25%">@if($resume[$x]->jawaban)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="15px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="15px"> @endif</td>
						<td width="25%">@if(!$resume[$x]->jawaban)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="15px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="15px"> @endif</td>
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
						@php $x=1 @endphp
						{{-- Strong Point --}}
						<td>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
						@php $x=2 @endphp
						{{-- To Be Develop --}}
						<td>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
					</tr>
				</table>
				{{-- Kesimpulan --}}
				@php $x=3 @endphp
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td align="justify"><b>Kesimpulan :</b><br/>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>