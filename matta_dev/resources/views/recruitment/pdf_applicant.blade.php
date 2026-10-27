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
				<table width="100%" border="0.5px" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM IDENTITAS PELAMAR KERJA</div></td>
                        <td width="25%"><div class="center">No: {{ $trx->id }}</div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>IDENTITAS DIRI</b></td>
					</tr>
				</table>
				<table class="border-top border-bottom border-left border-right" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="24%">Nama</td>
                        <td width="2%">:</td>
                        <td width="74%">{{ $trx->nama }}</td>
                    </tr>
                    <tr>
                        <td>Tempat/Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ $trx->tempat_lahir }}/{{ Carbon\Carbon::parse($trx->tanggal_lahir)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td>Nomor KTP</td>
                        <td>:</td>
                        <td>{{ $trx->ktp }}</td>
                    </tr>
                    <tr>
                        <td>Alamat (Sesuai KTP)</td>
                        <td>:</td>
                        <td>{{ $trx->alamat_ktp }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Domisili</td>
                        <td>:</td>
                        <td>{{ $trx->alamat_domisili }}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{ $trx->agama }}</td>
                    </tr>
                    <tr>
                        <td>Nomor NPWP</td>
                        <td>:</td>
                        <td>{{ $trx->npwp }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon</td>
                        <td>:</td>
                        <td>{{ $trx->telepon }}</td>
                    </tr>
                    @if(!empty($trx->telepon_darurat))
                    @php
                        $telepon_darurat = array_map('trim', explode(',', $trx->telepon_darurat));
                        $str_telepon_darurat = $telepon_darurat[1].', '.$telepon_darurat[2];
                        if(count($telepon_darurat) > 3){
                            $str_telepon_darurat = $telepon_darurat[2].', '.$telepon_darurat[3];
                        }
                    @endphp
                    <tr>
                        <td>Nomor Telepon Darurat</td>
                        <td>:</td>
                        <td>{{ $str_telepon_darurat }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>{{ $trx->email }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{ $trx->status_hidup }} @if(!empty($trx->tanggal_status) && in_array($trx->status_hidup, ['Menikah','Cerai Hidup','Cerai Mati'])) sejak {{ Carbon\Carbon::parse($trx->tanggal_status)->format('d-m-Y') }}@endif</td>
                    </tr>
                </table>
				{{-- keluarga --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>SUSUNAN KELUARGA (Jika Sudah Menikah)</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    {{-- pasangan --}}
                    <tr align="center" style="font-weight: bold;">
                        <td width="12%">Pasangan</td>
                        <td width="28%">Nama</td>
                        <td width="10%">Usia (th)</td>
                        <td width="12%">L/P</td>
                        <td width="13%">Pendidikan</td>
                        <td width="25%">Pekerjaan</td>
                    </tr>
                    <tr>
                        <td>{{ $trx->kelamin_pasangan }}</td>
                        <td>{{ $trx->nama_pasangan }}</td>
                        <td>{{ $trx->usia_pasangan }}</td>
                        <td>@if($trx->kelamin_pasangan=='Istri')Perempuan @elseif($trx->kelamin_pasangan=='Suami')Laki-laki @endif</td>
                        <td>{{ $trx->pendidikan_pasangan }}</td>
                        <td>{{ $trx->pekerjaan_pasangan }}</td>
                    </tr>
                    {{-- anak --}}
                    <tr align="center" style="font-weight: bold;">
                        <td>Anak</td>
                        <td>Nama</td>
                        <td>Usia (th)</td>
                        <td>L/P</td>
                        <td>Pendidikan</td>
                        <td>Pekerjaan</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $anak = App\Models\TrxRecruitmentAnak::where($where)->orderBy('usia', 'desc')->get();
                    $ke = 1;
                    @endphp
                    @forelse ($anak as $data)
                        <tr>
                            <td>Anak ke-{{ $ke++ }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->usia }}</td>
                            <td>{{ $data->kelamin }}</td>
                            <td>{{ $data->pendidikan }}</td>
                            <td>{{ $data->pekerjaan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
				{{-- keluarga --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>SUSUNAN KELUARGA (Termasuk Diri Anda)</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    {{-- orang tua --}}
                    <tr align="center" style="font-weight: bold;">
                        <td width="12%">Orang Tua</td>
                        <td width="28%">Nama</td>
                        <td width="10%">Usia (th)</td>
                        <td width="12%">L/P</td>
                        <td width="13%">Pendidikan</td>
                        <td width="25%">Pekerjaan</td>
                    </tr>
                    <tr>
                        <td>Ayah</td>
                        <td>{{ $trx->nama_bapak }}</td>
                        <td>{{ $trx->usia_bapak }}</td>
                        <td>Laki-laki</td>
                        <td>{{ $trx->pendidikan_bapak }}</td>
                        <td>{{ $trx->pekerjaan_bapak }}</td>
                    </tr>
                    <tr>
                        <td>Ibu</td>
                        <td>{{ $trx->nama_ibu }}</td>
                        <td>{{ $trx->usia_ibu }}</td>
                        <td>Perempuan</td>
                        <td>{{ $trx->pendidikan_ibu }}</td>
                        <td>{{ $trx->pekerjaan_ibu }}</td>
                    </tr>
                    {{-- saudara --}}
                    <tr align="center" style="font-weight: bold;">
                        <td>Hubungan</td>
                        <td>Nama</td>
                        <td>Usia (th)</td>
                        <td>L/P</td>
                        <td>Pendidikan</td>
                        <td>Pekerjaan</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $saudara = App\Models\TrxRecruitmentSaudara::select('nama','usia','kelamin','pendidikan','pekerjaan')->where($where)->orderBy('usia', 'desc')->get()->toArray();
                    array_push($saudara, ['nama'=>$trx->nama, 'usia'=>$trx->usia, 'kelamin'=>$trx->kelamin, 'pendidikan'=>$trx->pendidikan, 'pekerjaan'=>$trx->pekerjaan]);
                    $keys = array_column($saudara, 'usia');
                    array_multisort($keys, SORT_DESC, $saudara);
                    @endphp
                    @forelse ($saudara as $data)
                        <tr>
                            <td>@if($data['usia'] > $trx->usia)Kakak @elseif($data['usia'] < $trx->usia)Adik @elseif($data['usia'] == $trx->usia)Diri Sendiri @endif</td>
                            <td>{{ $data['nama'] }}</td>
                            <td>{{ $data['usia'] }}</td>
                            <td>{{ $data['kelamin'] }}</td>
                            <td>{{ $data['pendidikan'] }}</td>
                            <td>{{ $data['pekerjaan'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
				{{-- pendidikan dan organisasi --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>RIWAYAT PENDIDIKAN DAN ORGANISASI</b></td>
					</tr>
				</table>
                {{-- pendidikan formal --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>1. Pendidikan Formal</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="11%">Jenjang</td>
                        <td width="21%">Nama Sekolah</td>
                        <td width="21%">Kota</td>
                        <td width="21%">Jurusan</td>
                        <td width="8%">Tahun<br/>Masuk</td>
                        <td width="8%">Tahun<br/>Lulus</td>
                        <td width="10%">IPK/NEM</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $formal = App\Models\TrxRecruitmentFormal::where($where)->orderBy('tahun_masuk', 'asc')->get();
                    @endphp
                    @forelse ($formal as $data)
                        <tr>
                            <td>{{ $data->jenjang }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->kota }}</td>
                            <td>{{ $data->jurusan }}</td>
                            <td>{{ $data->tahun_masuk }}</td>
                            <td>{{ $data->tahun_lulus }}</td>
                            <td>{{ $data->nilai }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
                {{-- pendidikan informal --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>2. Pendidikan Informal (Kursus, Training, Workshop, dll) dan Keterampilan</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="53%">Bidang</td>
                        <td width="10%">Tahun</td>
                        <td width="27%">Tempat/Lembaga</td>
                        <td width="10%">Sertifikat</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $informal = App\Models\TrxRecruitmentInformal::where($where)->orderBy('tahun', 'asc')->get();
                    @endphp
                    @forelse ($informal as $data)
                        <tr>
                            <td>{{ $data->bidang }}</td>
                            <td>{{ $data->tahun }}</td>
                            <td>{{ $data->lembaga }}</td>
                            <td>{{ $data->sertifikat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
                {{-- organisasi --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="left"><b>3. Organisasi</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="30%">Nama Organisasi</td>
                        <td width="10%">Periode</td>
                        <td width="30%">Jabatan</td>
                        <td width="30%">Keterangan</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $org = App\Models\TrxRecruitmentOrganisasi::where($where)->orderBy('periode', 'asc')->get();
                    @endphp
                    @forelse ($org as $data)
                        <tr>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->periode }}</td>
                            <td>{{ $data->jabatan }}</td>
                            <td>{{ $data->keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
                {{-- pekerjaan --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>RIWAYAT PEKERJAAN</b></td>
					</tr>
				</table>
				<table border="0.5px" width="100%" cellspacing="0" cellpadding="2">
                    <tr align="center" style="font-weight: bold;">
                        <td width="16%" rowspan="2">Perusahaan</td>
                        <td width="16%" rowspan="2">Kota</td>
                        <td width="20%" colspan="2">Masa Kerja (tahun)</td>
                        <td width="16%" rowspan="2">Jabatan</td>
                        <td width="16%" rowspan="2">Upah</td>
                        <td width="16%" rowspan="2">Alasan Keluar</td>
                    </tr>
                    <tr align="center" style="font-weight: bold;">
                        <td>Masuk</td>
                        <td>Keluar</td>
                    </tr>
                    @php
                    $where = ['id_identitas' => $trx->id];
                    $pekerjaan = App\Models\TrxRecruitmentPekerjaan::where($where)->orderBy('tahun_masuk', 'asc')->get();
                    @endphp
                    @forelse ($pekerjaan as $data)
                        <tr>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->kota }}</td>
                            <td>{{ $data->tahun_masuk }}</td>
                            <td>{{ $data->tahun_keluar }}</td>
                            <td>{{ $data->jabatan }}</td>
                            <td>@if(!empty($data->gaji))Rp. {{ number_format($data->gaji, 0) }}@endif</td>
                            <td>{{ $data->alasan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforelse
                </table>
                {{-- lain-lain --}}
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
					<tr>
						<td align="center"><b>LAIN-LAIN</b></td>
					</tr>
				</table>
				<table width="100%" class="border-top border-bottom border-left border-right" cellspacing="0" cellpadding="2">
                    <tr style="font-weight: bold;">
                        <td>1. Apakah hobi atau kegemaran Anda?</td>
                    </tr>
                    <tr>
                        <td>{!! nl2br(e($trx->hobi)) !!}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>2. Faktor-faktor apakah yang menjadi kelebihan (Strong Point) Anda?</td>
                    </tr>
                    <tr>
                        <td>{!! nl2br(e($trx->kelebihan)) !!}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>3. Hal apa yang perlu ditingkatkan (Areas to Be Improved) dalam diri Anda?</td>
                    </tr>
                    <tr>
                        <td>{!! nl2br(e($trx->peningkatan)) !!}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>4. Apakah Anda menderita sakit keras atau sakit yang berkepanjangan?</td>
                    </tr>
                    <tr>
                        <td><span style="font-family:zapfdingbats;">4</span> {{ $trx->is_sakit }}@if($trx->is_sakit=='Pernah Sakit')<br/>Penyakit {{ $trx->sakit_apa }}, Tahun {{ $trx->sakit_tahun }}<br/>Kondisi saat ini apakah sudah sembuh atau masih dalam proses pengobatan?<br/>{{ $trx->sakit_kondisi }}@endif</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>5. Alasan Anda ingin bergabung di PT. Alita Praya Mitra</td>
                    </tr>
                    <tr>
                        <td>{!! nl2br(e($trx->alasan)) !!}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>6. Gaji yang diharapkan (Rupiah)</td>
                    </tr>
                    <tr>
                        <td>Rp. {{ number_format($trx->gaji, 0) }}</td>
                    </tr>
                </table>
                @php
					// 3=Apply
					$where = ['id_identitas' => $trx->id, 'id_status' => 3];
					$flow1 = App\Models\TrxRecruitmentLog::where($where)->first();
                @endphp
				<table width="100%" class="border-top border-bottom border-left border-right" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        <td>Kandidat<br/><img src="{{ public_path('assets/images/matta/verified_new.png') }}" height="30" /><br/>{{ $flow1->pic }}<br/>{{ $flow1->created_at }}</td>
                    </tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>