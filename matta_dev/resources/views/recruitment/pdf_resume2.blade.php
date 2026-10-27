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
				{{-- <p>&nbsp;</p>
				<p>&nbsp;</p> --}}
				{{-- Rekomendasi --}}
				@php $x=0 @endphp
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<td width="5%"><b>No</b></td>
						<td width="27%"><b>Core Competence</b></td>
						<td width="40%"><b>Standard Competence</b></td>
						<td width="8%"><b>Point<br/>10-100</b></td>
						<td width="20%"><b>Keterangan</b></td>
					</tr>
					@php $x=4 @endphp
					<tr>
						<td align="center" rowspan="2">1</td>
						<td align="center" rowspan="2"><b>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</b></td>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=5 @endphp
					<tr>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=6 @endphp
					<tr>
						<td align="center" rowspan="2">2</td>
						<td align="center" rowspan="2"><b>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</b></td>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=7 @endphp
					<tr>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=8 @endphp
					<tr>
						<td align="center" rowspan="2">3</td>
						<td align="center" rowspan="2"><b>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</b></td>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=9 @endphp
					<tr>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=10 @endphp
					<tr>
						<td align="center" rowspan="2">4</td>
						<td align="center" rowspan="2"><b>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</b></td>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=11 @endphp
					<tr>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=12 @endphp
					<tr>
						<td align="center" rowspan="2">5</td>
						<td align="center" rowspan="2"><b>@if($resume[$x]->urutan == 1){{ $resume[$x]->pernyataan1 }}@endif</b></td>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
					@php $x=13 @endphp
					<tr>
						<td>{{ $resume[$x]->pernyataan2 }}</td>
						<td align="center">{{ $resume[$x]->jawaban }}</td>
						<td>{{ $resume[$x]->keterangan }}</td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<span>PT Alita Praya Mitra - {{ App\Models\MtrUtility::find(51)->informasi }}<br/>{{ $resume[$x]->updated_at }}</span>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>