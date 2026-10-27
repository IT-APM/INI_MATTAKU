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
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM IZIN</div></td>
                        <td width="25%"><div class="center">No: {{ $trx->id }}</div></td>
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
                        <td width="70%">: {{ $karyawan['nama'] }}<br />: {{ $karyawan['nik'] }}<br />:
                            {{ $karyawan['jabatan'] }}<br />: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} /
                            {{ $karyawan['sub_dep'] }} </td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>IZIN</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2">Mohon untuk izin,</td>
                    </tr>
                    <tr>
                        <td width="30%">Tipe Izin</td>
                        <td width="70%">: {{ $trx->_tipe->tipe }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal) }}</td>
                    </tr>
                    @if($trx->tipe == 2)
                    <tr>
                        <td>Jam Mulai</td>
                        <td>: {{ $trx->jam_awal }}</td>
                    </tr>
                    <tr>
                        <td>Jam Selesai</td>
                        <td>: {{ $trx->jam_akhir }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Alasan / Keperluan</td>
                        <td>: {!! $trx->alasan !!}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"><strong>CATATAN</strong></td>
                    </tr>
                </table>
                <hr />
                @php
                $atasan_property = json_decode($trx->atasan_property, true);
                $layer2_property = json_decode($trx->layer2_property, true);
                $where = ['id_trx' => $trx->id, 'nik_pic' => $trx->atasan];
                $logAtasan = App\Models\TrxIjinLog::where($where)->whereIn('id_status', [2,3])->orderBy('id', 'desc')->first();
                $where = ['id_trx' => $trx->id, 'nik_pic' => $trx->layer2];
                $logLayer2 = App\Models\TrxIjinLog::where($where)->whereIn('id_status', [6,7])->orderBy('id', 'desc')->first();
                @endphp
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    @if(!empty($atasan_property) && !empty($logAtasan))
                    <tr>
                        <td width="30%">Catatan {{ $atasan_property['nama'] }}</td>
                        <td width="70%">: {{ $logAtasan->keterangan }}</td>
                    </tr>
                    @endif
                    @if(!empty($layer2_property) && !empty($logLayer2))
                    <tr>
                        <td>Catatan {{ $layer2_property['nama'] }}</td>
                        <td>: {{ $logLayer2->keterangan }}</td>
                    </tr>
                    @endif
                </table>
                <hr />
				{!! $htmlLog !!}
            </td>
        </tr>
    </table>
    <!-- finish -->
</body>

</html>