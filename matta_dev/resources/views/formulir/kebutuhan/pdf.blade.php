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
{{-- start --}}
<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENGAJUAN KEBUTUHAN PEGAWAI</div></td>
                        <td width="25%"><div class="center">No: {{ $trxM->id }}</div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                        <td width="70%">: {{ $pengaju['nama'] }}<br/>: {{ $pengaju['nik'] }}<br/>: {{ $pengaju['jabatan'] }}<br/>: {{ $pengaju['divisi'] }} / {{ $pengaju['dep'] }} / {{ $pengaju['sub_dep'] }}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KEBUTUHAN REKRUITMEN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Jabatan/posisi</td>
                        <td width="70%">: {{ $trxM->_pekerjaan->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>: {{ $trxM->_level->level }}</td>
                    </tr>
                    <tr>
                        <td>Area Kerja</td>
                        <td>: Propinsi: {{ $trxM->_area->propinsi }}@if(!empty($trxM->id_kota)), Kota: {{ $trxM->_kota->kota }} @endif</td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>: {{ $trxM->_divisi_kandidat->nama }} / {{ $trxM->_dep_kandidat->nama }} / {{ $trxM->_sub_dep_kandidat->nama }}</td>
                    </tr>
                    @php
                        $atasan_property = $trxM->atasan_property;
                        $atasan_property = json_decode($atasan_property, true);
                    @endphp
                    <tr>
                        <td>Atasan Langsung Kandidat</td>
                        <td>: @if(!empty($atasan_property)) {{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }} @endif</td>
                    </tr>
                    <tr>
                        <td>Jabatan Atasan Langsung</td>
                        <td>: @if(!empty($atasan_property)) {{ $atasan_property['jabatan'] }} @endif</td>
                    </tr>
                    <tr>
                        <td>Direktorat / Divisi / Departemen</td>
                        <td>: {{ $pengaju['divisi'] }} / {{ $pengaju['dep'] }} / {{ $pengaju['sub_dep'] }}</td>
                    </tr>
                    <tr>
                        <td>Status Kerja</td>
                        <td>: {{ $trxM->_status_kerja->status_kerja }}</td>
                    </tr>
                    @if(!empty($trxM->waktu_kontrak))
                    <tr>
                        <td>Waktu Kontrak</td>
                        <td>: {{ $trxM->waktu_kontrak }} (Bulan)</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Jumlah</td>
                        <td>: {{ $trxM->jumlah }} (Orang) {{ $trxM->_kelamin->kelamin }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Permintaan</td>
                        <td>: {{ $trxM->_jenis_permintaan->jenis }}</td>
                    </tr>
                    @if(!empty($trxM->nik_karyawan))
                    <tr>
                        <td>Karyawan Penggantian</td>
                        <td>@foreach ($arr_nik_karyawan as $value){{ $loop->iteration }}. {{ $value['nama'] }} / {{ $value['nik'] }} @if(next($arr_nik_karyawan)) <br/> @endif @endforeach</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2">Apakah sudah masuk dalam rencana @if($trxM->id_jenis_permintaan == 1) penggantian @else penambahan @endif pegawai? @if($trxM->id_rencana == 0) Belum @else Sudah @endif</td>
                    </tr>
                    <tr>
                        <td>Alasan Permintaan</td>
                        <td>: {!! nl2br($trxM->penjelasan) !!}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>FINDIM / COST ALLOCATION</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Project</td>
                        <td width="70%">: {{ $trxM->_findim_project->project_id }} - {{ $trxM->_findim_project->project_desc }}</td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td>: {{ $trxM->_findim_divisi->divisi_id }} - {{ $trxM->_findim_divisi->divisi_desc }}</td>
                    </tr>
                    <tr>
                        <td>Departemen</td>
                        <td>: {{ $trxM->_findim_departemen->departemen_id }} - {{ $trxM->_findim_departemen->departemen_desc }}</td>
                    </tr>
                    <tr>
                        <td>Sub Departemen</td>
                        <td>: {{ $trxM->_findim_subdepartemen->subdepartemen_id }} - {{ $trxM->_findim_subdepartemen->subdepartemen_desc }}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>TUGAS, WEWENANG DAN TANGGUNG JAWAB</strong></td>
                    </tr>
                </table>
                {{-- <hr /> --}}
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Tugas</td>
                        <td width="70%">{!! nl2br($trxM->tugas) !!}</td>
                    </tr>
                    <tr>
                        <td>Wewenang</td>
                        <td>{!! nl2br($trxM->wewenang) !!}</td>
                    </tr>
                    <tr>
                        <td>Tanggung jawab</td>
                        <td>{!! nl2br($trxM->tanggung_jawab) !!}</td>
                    </tr>
                </table>
                {{-- <hr /> --}}
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KUALIFIKASI / SPESIFIKASI JABATAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Pendidikan Minimum</td>
                        <td width="70%">: {{ $trxM->_jenjang_pendidikan->jenjang }}</td>
                    </tr>
                    @if(!empty($trxM->id_jurusan_pendidikan))
                    <tr>
                        <td>Jurusan</td>
                        <td>: 
                        @if($trxM->id_jurusan_pendidikan <> '0')
                            @foreach (explode(',', $trxM->id_jurusan_pendidikan) as $index => $value){{ $index+1 }}. {{ App\Models\PendidikanJurusan::find($value)->jurusan }} @if (!$loop->last) <br/>&nbsp; @endif @endforeach
                        @endif
                        </td>
                        {{-- <td>: {{ $trxM->_jurusan_pendidikan->jurusan }} ({{ $trxM->_jurusan_pendidikan->_kelompok->kelompok }})</td> --}}
                    </tr>
                    @endif
                    @if(!empty($trxM->nilai_ipk))
                    <tr>
                        <td>Nilai IPK</td>
                        <td>: {{ $trxM->nilai_ipk }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Usia Minimum</td>
                        <td>: {{ $trxM->umur_minimum }} (Tahun)</td>
                    </tr>
                    <tr>
                        <td>Usia Maksimum</td>
                        <td>: {{ $trxM->umur_maksimum }} (Tahun)</td>
                    </tr>
                    <tr>
                        <td>Pengalaman Kerja (Tahun)</td>
                        <td>: {{ $trxM->_pengalaman->pengalaman }}</td>
                    </tr>
                    <hr/>
                    <tr>
                        <td>Training yang seharusnya sudah diikuti</td>
                        <td>: 
                        @if(!empty($trxM->id_pelatihan))
                            @foreach ($pelatihan as $index => $value){{ $index+1 }}. {{ $value->sub_pelatihan }} @if (!$loop->last) <br/>&nbsp; @endif @endforeach
                        @endif
                        </td>
                        {{-- <td><ol>
                        @if(!empty($trxM->id_pelatihan))
                            @foreach ($pelatihan as $value)
                            <li>{{ $value->sub_pelatihan }}</li>
                            @endforeach
                        @endif
                        </ol></td> --}}
                    </tr>
                    <hr/>
                    <tr>
                        <td>Kursus/sertifikasi yang seharusnya sudah diperoleh</td>
                        <td>: 
                        @if(!empty($trxM->id_sertifikasi))
                            @foreach ($sertifikasi as $index => $value){{ $index+1 }}. {{ $value->sub_sertifikasi }} @if (!$loop->last) <br/>&nbsp; @endif @endforeach
                        @endif
                        </td>
                        {{-- <td><ol>
                        @if(!empty($trxM->id_sertifikasi))
                            @foreach ($sertifikasi as $value)
                            <li>{{ $value->sub_sertifikasi }}</li>
                            @endforeach
                        @endif
                        </ol></td> --}}
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KEMAMPUAN PRIBADI</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Ketrampilan yang harus sudah dipunyai</td>
                        <td width="70%">: 
                        @if(!empty($trxM->id_keterampilan))
                            @foreach ($keterampilan as $index => $value){{ $index+1 }}. {{ $value->sub_keterampilan }} @if (!$loop->last) <br/>&nbsp; @endif @endforeach
                        @endif
                        </td>
                        {{-- <td width="70%"><ol>
                        @if(!empty($trxM->id_keterampilan))
                            @foreach ($keterampilan as $value)
                            <li>{{ $value->sub_keterampilan }}</li>
                            @endforeach
                        @endif
                        </ol></td> --}}
                    </tr>
                    <hr/>
                    <tr>
                        <td>Kepribadian dasar yang harus dimiliki</td>
                        <td>: 
                        @if(!empty($trxM->id_kepribadian))
                            @foreach ($kepribadian as $index => $value){{ $index+1 }}. {{ $value->sub_kepribadian }} @if (!$loop->last) <br/>&nbsp; @endif @endforeach
                        @endif
                        </td>
                        {{-- <td><ol>
                        @if(!empty($trxM->id_kepribadian))
                            @foreach ($kepribadian as $value)
                            <li>{{ $value->sub_kepribadian }}</li>
                            @endforeach
                        @endif
                        </ol></td> --}}
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><strong>Diperkirakan Waktu Mulai Bekerja : Tanggal: {{ Carbon\Carbon::createFromFormat('Y-m-d', $trxM->tanggal)->format('d-m-Y') }}</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>Keterangan Lain:<br/>{!! nl2br($trxM->keterangan_lain) !!}</td>
                    </tr>
                </table>
                <hr />
                {!! $htmlLog !!}
            </td>
        </tr>
    </table>
</body>
{{-- finish --}}
</html>