<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
.border-left {
  border-left: 0.5px thin black;
}
.border-right {
  border-right: 0.5px thin black;
}
.border-top {
    border-top: 0.5px thin black;
}
.border-bottom {
    border-bottom: 0.5px thin black;
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
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENERIMAAN KARYAWAN BARU</div></td>
                        <td width="25%"><div class="center">No: {{ $_trx->id }}</div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>PERSONAL</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Nama Lengkap</td>
                        <td width="2%">:</td>
                        <td width="69%">{{ $_trx->nama }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $_trx->alamat_ktp }}</td>
                    </tr>
                    <tr>
                        <td>Tempat/Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ $_trx->tempat_lahir }}/{{ Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td>Pendidikan</td>
                        <td>:</td>
                        <td>{{ $_trx->pendidikan }}</td>
                    </tr>
					@php
					$formal = App\Models\TrxRecruitmentFormal::where(['id_identitas'=>$_trx->id, 'jenjang'=>$_trx->pendidikan])->first();
					$kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
					$kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
					@endphp
					@if(!empty($formal))
                    <tr>
                        <td>Jurusan</td>
                        <td>:</td>
                        <td>{{ $formal->jurusan }}</td>
                    </tr>
					@endif
                </table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>KELENGKAPAN DOKUMEN</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					{{-- <span style="font-family:zapfdingbats;">4</span> --}}
					{{-- <span style="font-family:zapfdingbats;">6</span> --}}
					<tr>
                        <td width="50%">@if(!empty($_trx->cv)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif CV Lengkap</td>
						<td width="50%" style="border-left: 0.5px thin black;">@if(!empty($_trx->file_ijazah)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Ijazah</td>
                    </tr>
					<tr>
                        <td>@if(!empty($_trx->file_sertifikat_keahlian)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Sertifikat Keahlian</td>
						<td style="border-left: 0.5px thin black;">@if(!empty($_trx->file_skck)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Surat Keterangan Catatan Kepolisian (SKCK)</td>
                    </tr>
					<tr>
                        <td>@if(!empty($_trx->file_ktp)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Kartu Tanda Penduduk (KTP)</td>
						<td style="border-left: 0.5px thin black;">@if(!empty($_trx->file_bpjs_tk)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Kartu BPJS Ketenagakerjaan (BPJSTK)</td>
                    </tr>
					<tr>
                        <td>@if(!empty($_trx->file_kk)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Kartu Keluarga (KK)</td>
						<td style="border-left: 0.5px thin black;">@if(!empty($_trx->file_bpjs_kes)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Kartu BPJS Kesehatan (BPJSKS)</td>
                    </tr>
					<tr>
                        <td>@if(!empty($_trx->file_npwp)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Kartu NPWP</td>
						<td style="border-left: 0.5px thin black;">@if(!empty($_trx->file_buku_tabungan)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Copy Buku Tabungan (No. Rekening Bank Mandiri)</td>
                    </tr>
					<tr>
                        <td>@if(!empty($_trx->file_ket_sehat)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Surat Keterangan Sehat</td>
						<td style="border-left: 0.5px thin black;">@if(!empty($_trx->file_covid)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Sertifikat Vaksin Covid-19</td>
                    </tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>ORGANISASI</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Posisi / Jabatan</td>
                        <td width="2%">:</td>
						{{-- $trx->posisi --}}
                        <td width="69%">{{ App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>:</td>
						{{-- $trxM->_level->level --}}
                        <td>{{ App\Models\Level::find($kebutuhan_atribute['id_level'])->level }}</td>
                    </tr>
                    <tr>
                        <td>Area Kerja</td>
                        <td>:</td>
						{{-- $trx->area_kerja --}}
                        <td>{{ App\Models\Propinsi::find($kebutuhan_atribute['id_area'])->propinsi }}</td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen Kandidat</td>
                        <td>:</td>
						{{-- {{ $trxM->_divisi_kandidat->nama }} / {{ $trxM->_dep_kandidat->nama }} / {{ $trxM->_sub_dep_kandidat->nama }} --}}
                        <td>{{ App\Models\Divisi::find($kebutuhan_atribute['id_divisi_kandidat'])->nama }} / {{ App\Models\Departemen::find($kebutuhan_atribute['id_dep_kandidat'])->nama }} / {{ App\Models\SubDepartemen::find($kebutuhan_atribute['id_sub_dep_kandidat'])->nama }}</td>
                    </tr>
					@php
					$atasan = json_decode($trxM->atasan_property, true);
					@endphp
                    <tr>
                        <td>Atasan Langsung Kandidat</td>
                        <td>:</td>
                        {{-- <td>@if(!empty($atasan)){{ $atasan['nama'] }} / {{ $atasan['nik'] }}@endif</td> --}}
                        <td>{{ $kebutuhan_atribute_atasan_property['nama'] }} / {{ $kebutuhan_atribute_atasan_property['nik'] }}</td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>:</td>
                        {{-- <td>{{ $trx->divisi }}</td> --}}
						<td>{{ $kebutuhan_atribute_atasan_property['divisi'] }} / {{ $kebutuhan_atribute_atasan_property['dep'] }} / {{ $kebutuhan_atribute_atasan_property['sub_dep'] }}</td>
                    </tr>
                </table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>BENEFIT</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					@php $benefit_utama = explode(',', $trx->benefit_utama); @endphp
					<tr>
                        <td width="55%">@if(!empty($trx->thp)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Take Home Pay (THP): Rp. {{ number_format($trx->thp, 0) }}</td>
						{{-- 9=Cuti Setelah 1 tahun --}}
						<td width="45%" style="border-left: 0.5px thin black;">@if(in_array(9, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Cuti Setelah 1 tahun</td>
                    </tr>
					<tr>
						{{-- 1=Upah Lembur --}}
                        <td>@if(in_array(1, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Upah Lembur</td>
						{{-- 10=Asuransi --}}
						<td style="border-left: 0.5px thin black;">@if(in_array(10, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Asuransi</td>
                    </tr>
					<tr>
						{{-- 2=Medical Reimbursement --}}
                        <td>@if(in_array(2, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Medical Reimbursement</td>
						{{-- 11=Tunjangan Corporate --}}
						<td style="border-left: 0.5px thin black;">@if(in_array(10, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Tunjangan Corporate</td>
                    </tr>
					<tr>
						{{-- 3=PPh 21, ditanggung perusahaan --}}
						{{-- 4=PPh 21, ditanggung perusahaan & karyawan --}}
                        <td>@if(in_array(3, $benefit_utama)||in_array(4, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif PPh 21 @if(in_array(3, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> Karyawan @elseif(in_array(4, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Karyawan @endif</td>
						{{-- 12=Tunjangan Project / Operasional --}}
						<td style="border-left: 0.5px thin black;">@if(in_array(12, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Tunjangan Project / Operasional</td>
                    </tr>
					<tr>
						{{-- 5=BPJS Ketenagakerjaan, ditanggung perusahaan --}}
						{{-- 6=BPJS Ketenagakerjaan, ditanggung  perusahaan & karyawan --}}
                        <td>@if(in_array(5, $benefit_utama)||in_array(6, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif BPJS Ketenagakerjaan @if(in_array(5, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> Karyawan @elseif(in_array(6, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Karyawan @endif</td>
						{{-- 13=Alat kerja (laptop / notebook) --}}
						<td style="border-left: 0.5px thin black;">@if(in_array(12, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif Alat kerja (laptop / notebook)</td>
                    </tr>
					<tr>
						{{-- 7=BPJS Kesehatan, ditanggung perusahaan --}}
						{{-- 8=BPJS Kesehatan, ditanggung  perusahaan & karyawan --}}
                        <td>@if(in_array(7, $benefit_utama)||in_array(8, $benefit_utama)) <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> @else <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> @endif BPJS Kesehatan @if(in_array(7, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px"> Karyawan @elseif(in_array(8, $benefit_utama)) ditanggung: <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Perusahaan <img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px"> Karyawan @endif</td>
						{{-- benefit_lainnya --}}
						<td style="border-left: 0.5px thin black;">&nbsp;</td>
                    </tr>
					<tr>
						{{-- benefit_lainnya --}}
						<td colspan="2" style="border-top: 0.5px thin black;">Keterangan:<br/>@if(!empty($trx->benefit_lainnya)){!! nl2br($trx->benefit_lainnya) !!}@endif</td>
                    </tr>
				</table>
				<br/><br/>
				<table width="100%" border="0.5" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>KEPEGAWAIAN</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="29%">Mulai Bergabung</td>
                        <td width="2%">:</td>
                        <td width="69%">{{ Carbon\Carbon::parse($trx->tgl_bergabung)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td>Pencatatan Kepegawaian</td>
                        <td>:</td>
                        {{-- <td>{{ $trx->_catat_pegawai->pencatatan }}</td> --}}
						<td><table width="100%" cellspacing="0" cellpadding="0"><tr><td width="20%">@if($trx->id_catat_pegawai == 1)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif ALITA</td><td width="20%">@if($trx->id_catat_pegawai == 2)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif Outsource</td></tr></table></td>
                    </tr>
                    <tr>
                        <td>Status Kepegawaian</td>
                        <td>:</td>
                        {{-- <td>{{ $trx->_status_pegawai->status }}</td> --}}
						<td><table width="100%" cellspacing="0" cellpadding="0"><tr><td width="20%">@if($trx->id_status_pegawai == 1)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif Kontrak</td><td width="15%">@if($trx->id_status_pegawai == 2)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif Tetap</td><td width="40%">@if($trx->id_status_pegawai == 3)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif Tenaga Lepas Harian (TLH)</td><td width="25%">@if($trx->id_status_pegawai == 4)<img src="{{ public_path('assets/images/matta/check.png') }}" alt="check_uncheck" width="9px">@else<img src="{{ public_path('assets/images/matta/uncheck.png') }}" alt="check_uncheck" width="9px">@endif Konsultan</td></tr></table></td>
                    </tr>
                    <tr>
                        <td>Masa Perjanjian Kerja</td>
                        <td>:</td>
                        <td>{{ $trx->masa_kerja }} bulan</td>
                    </tr>
                </table>
				<br/><br/>
				<table width="100%" class="border-top border-bottom border-left border-right" align="center" cellpadding="2" cellspacing="0">
					@php
					// 17=Negotiation Deal by Candidate
					// 18=Negotiation No Deal by Candidate
					$where = ['id_identitas' => $trx->id_identitas];
					$flow1 = App\Models\TrxRecruitmentLog::where($where)->whereIn('id_status', [17,18])->orderBy('id', 'desc')->first();
					// 12=Negotiation Process
					$where = ['id_identitas' => $trx->id_identitas, 'id_status' => 12];
					$flow2 = App\Models\TrxRecruitmentLog::where($where)->first();
					// 26=Approval By Leader
					$where = ['id_identitas' => $trx->id_identitas, 'id_status' => 26];
					$flow3 = App\Models\TrxRecruitmentLog::where($where)->first();
					@endphp
                    <tr>
                        <td>
							Kandidat<br/>
							@if(!empty($flow1))
							@if($flow1->id_status == 17)
							<img src="{{ public_path('assets/images/matta/approved_new.png') }}" height="30" /><br/>{{ $flow1->pic }}<br/>{{ $flow1->created_at }}
							@elseif($flow1->id_status == 18)
							<img src="{{ public_path('assets/images/matta/rejected_new.png') }}" height="30" /><br/>{{ $flow1->pic }}<br/>{{ $flow1->created_at }}
							@endif
							@endif
						</td>
                        @if(!empty($flow2))<td>{{ App\Models\MtrUtility::find(51)->informasi }}<br/><img src="{{ public_path('assets/images/matta/verified_new.png') }}" height="30" /><br/>{{ $flow2->pic }}<br/>{{ $flow2->created_at }}</td>@endif
						{{-- atasan kandidat --}}
                        @if(!empty($flow3))<td>Mengetahui/Menyetujui<br/><img src="{{ public_path('assets/images/matta/approved_new.png') }}" height="30" /><br/>{{ $flow3->pic }}<br/>{{ $flow3->created_at }}</td>@endif
                        {{-- <td>Diarsip oleh<br/><img src="{{ public_path('assets/images/matta/informed_new.png') }}" height="30" /><br/>{{ $kandidat->pic }}<br/>{{ $kandidat->created_at }}</td> --}}
                    </tr>
                </table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>