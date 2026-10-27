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

<!-- start -->

<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM REKOMENDASI KARYAWAN</div></td>
                        <td width="25%"><div class="center">No: {{ $trxM->id }}</div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KARYAWAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Tanggal Bergabung<br/>Status<br/>Level</td>
                        <td width="70%">: {{ $karyawan['nama'] }}<br/>: {{ $karyawan['nik'] }}<br/>:
                            {{ $karyawan['jabatan'] }}<br/>: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} /
                            {{ $karyawan['sub_dep'] }}<br/>: @if(!empty($karyawan['tanggal_bergabung'])) {{ Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y') }} @endif<br/>:  {{ $karyawan['status_karyawan'] }}<br/>: {{ $karyawan['level'] }}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>REKOMENDASI DARI ATASAN LANGSUNG</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2">Saya yang bertanda tangan di bawah ini :</td>
                    </tr>
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen</td>
                        <td width="70%">: {{ $atasan['nama'] }}<br/>: {{ $atasan['nik'] }}<br/>:
                            {{ $atasan['jabatan'] }}<br/>: {{ $atasan['divisi'] }} / {{ $atasan['dep'] }} /
                            {{ $atasan['sub_dep'] }}</td>
                    </tr>
                    <tr>
                        <td>Mengajukan</td>
                        <td>: @foreach ($trxD as $value)
                                {{ ++$no }}. {{ $value->jenis_pengajuan }} 
                                @if($value->jenis_pengajuan == 'Insentif')
                                    {{ $value->sebelum }} selama {{ $value->setelah }} bulan <br/>&nbsp;
                                @elseif($value->jenis_pengajuan == 'Nilai Prosentase Kenaikan Gaji')
                                    {{ $value->setelah }} %<br/>&nbsp;
                                @else
                                    dari {{ $value->sebelum }} menjadi {{ $value->setelah }} <br/>&nbsp;
                                @endif
                            @endforeach
                        </td>
                        {{-- <td>: @foreach ($trxD as $value)
                                {{ ++$no }}. {{ $value->jenis_pengajuan }} dari {{ $value->sebelum }} menjadi {{ $value->setelah }}
                                <br/>&nbsp;
                            @endforeach
                        </td> --}}
                    </tr>
                    <tr>
                        <td colspan="2">Untuk karyawan tersebut di atas terhitung mulai tanggal {{ $trxM->terhitung_tanggal }}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>DASAR PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>{!! $trxM->dasar_pengajuan !!}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>CATATAN PENGAJU</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>{!! $trxM->catatan_pengajuan !!}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-1</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: {{ $reviewer1['nama'] }}<br/>: {{ $reviewer1['nik'] }}<br/>:
                            {{ $reviewer1['jabatan'] }}<br/>: {{ $reviewer1['divisi'] }} / {{ $reviewer1['dep'] }} /
                            {{ $reviewer1['sub_dep'] }}<br/>: {!! $trxM->catatan_reviewer1 !!}</td>
                    </tr>
                </table>
                <hr />
                @if(!empty($reviewer2))
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-2</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: {{ $reviewer2['nama'] }}<br/>: {{ $reviewer2['nik'] }}<br/>:
                            {{ $reviewer2['jabatan'] }}<br/>: {{ $reviewer2['divisi'] }} / {{ $reviewer2['dep'] }} /
                            {{ $reviewer2['sub_dep'] }}<br/>: {!! $trxM->catatan_reviewer2 !!}</td>
                    </tr>
                </table>
                <hr />
                @endif
                @if(!empty($reviewer3))
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>CATATAN APPROVAL-3</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br/>NIP<br/>Jabatan<br/>Direktorat / Divisi / Departemen<br/>Catatan</td>
                        <td width="70%">: {{ $reviewer3['nama'] }}<br/>: {{ $reviewer3['nik'] }}<br/>:
                            {{ $reviewer3['jabatan'] }}<br/>: {{ $reviewer3['divisi'] }} / {{ $reviewer3['dep'] }} /
                            {{ $reviewer3['sub_dep'] }}<br/>: {!! $trxM->catatan_reviewer3 !!}</td>
                    </tr>
                </table>
                <hr />
                @endif
                @if(!empty($rekomendasiLog))
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>CATATAN {{ strtoupper(App\Models\MtrUtility::find(51)->informasi) }}</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>{!! $rekomendasiLog->keterangan !!}</td>
                    </tr>
                </table>
                <hr />
                @endif
                {!! $htmlLog !!}
            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html>