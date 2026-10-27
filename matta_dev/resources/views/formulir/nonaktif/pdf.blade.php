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
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM KARYAWAN NON AKTIF</div></td>
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
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Atasan Langsung<br />Tanggal Bergabung<br />Tanggal Berakhir Kontrak<br />Tanggal Non-Aktif<br />Alasan Non-Aktif<br />Keterangan</td>
                        <td width="70%">: {{ $karyawan['nama'] }}<br />: {{ $karyawan['nik'] }}<br />: {{ $karyawan['jabatan'] }}<br />: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}<br />: {{ $atasan['nama'] }}<br />: @if(!empty($karyawan_properties->tanggal_bergabung)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_bergabung)->format('d-m-Y') }} @endif<br />: @if(!empty($karyawan_properties->tanggal_berakhir)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_berakhir)->format('d-m-Y') }} @endif<br />: @if(!empty($trxM->tanggal)) {{ \Carbon\Carbon::parse($trxM->tanggal)->format('d-m-Y') }} @endif<br />: {{ $trxM->_alasan->alasan }}<br />: {!! $trxM->keterangan !!}</td>
                    </tr>
                </table>
                <hr />
                <!-- di -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>DIREKTORAT / DIVISI / DEPARTEMEN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_di as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 6 = Reject by PIC DIVISI
                    // 8 = Approve by PIC DIVISI
                    $whereIn = [6, 8];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- ga -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>GENERAL AFFAIRS</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_ga as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 10 = Reject by PIC GA
                    // 12 = Approve by PIC GA
                    $whereIn = [10, 12];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- it -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>IT</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_it as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 14 = Reject by PIC IT
                    // 16 = Approve by PIC IT
                    $whereIn = [14, 16];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- hc -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PEOPLE & CULTURE</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_hc as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 22 = Reject by PIC HC
                    // 24 = Approve by PIC HC
                    $whereIn = [22, 24];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- ko -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KOPERASI KARYAWAN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_ko as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 18 = Reject by PIC KOPERASI
                    // 20 = Approve by PIC KOPERASI
                    $whereIn = [18, 20];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- am -->
                @if(count($trxD_am)>0)
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>APPLICATION MANAGEMENT</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_am as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true);
                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                    // 28 = Reject by PIC Application Management
                    // 30 = Approve by PIC Application Management
                    $whereIn = [28, 30];
                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan<br />Catatan PIC</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}<br />
                        : @if(!empty($log)){{ $log->keterangan }}@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                @endif
                <!--  -->
                {!! $htmlLog !!}
                <!--  -->
            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html>