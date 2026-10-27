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
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM PENILAIAN KARYAWAN</div></td>
                        <td width="25%"><div class="center">No: {{ $data['id_data'] }}</div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>EVALUASI KARYAWAN ({{ $data['tipe'] }})</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Karyawan<br />
                            NIP<br />
                            Jabatan<br />
                            Direktorat / Divisi / Departemen<br />
                            Perusahaan<br />
                            Nama Atasan Langsung<br />
                            Tanggal Bergabung<br />
                            Tanggal Berakhir</td>
                        <td width="70%">: {{ $karyawan['nama'] }}<br />
                            : {{ $karyawan['nik'] }}<br />
                            : {{ $karyawan['jabatan'] }}<br />
                            : {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}<br />
                            : {{ $karyawan['perusahaan'] }}<br />
                            : {{ $penilai1['nama'] }}<br />
                            : @if(!empty($karyawan['tanggal_bergabung'])) {{ Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y') }} @endif<br />
                            : @if(!empty($karyawan['tanggal_berakhir'])) {{ Carbon\Carbon::parse($karyawan['tanggal_berakhir'])->format('d-m-Y') }} @endif</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>PARA PENILAI</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    {!! $para_penilai !!}
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>HASIL PENILAIAN</strong></td>
                    </tr>
                </table>
                @if($data['id_tipe'] == 6)
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="80%"><strong>Obyek Penilaian</strong></td>
                        <td width="20%" align="center"><strong>Penilai 1</strong></td>
                    </tr>
                    @foreach ($angka_skor as $_data)
                    <tr>
                        <td>{{ $_data->id_nilai3 }}. {{ $_data->penilaian_1 }}</td>
                        <td align="center">{{ $_data->skor_penilai_1 }}</td>
                    </tr>
                    @endforeach
                    @if($angka_rata>0)
                    <tr>
                        <td align="right"><strong>Total Score</strong></td>
                        <td align="center"><strong>{{ $angka_rata }}</strong></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        @if(count($angka_skor)==14)
                            <td align="center"><strong>Result: @if($angka_rata>35) Extend @else Not Extend @endif</strong></td>
                        @else
                            <td align="center"><strong>Result: @if($angka_rata>40) Extend @else Not Extend @endif</strong></td>
                        @endif
                    </tr>
                    @endif
                </table>
                @else
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="70%"><strong>Obyek Penilaian</strong></td>
                        <td width="15%" align="center"><strong>Karyawan</strong></td>
                        <td width="15%" align="center"><strong>Penilai 1</strong></td>
                    </tr>
                    {!! $angka_skor !!}
                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    {!! $angka_total !!}
                </table>
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                    {!! $angka_rata !!}
                </table>
                @endif
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>KESIMPULAN DAN REKOMENDASI</strong></td>
                    </tr>
                </table>
                <hr />
                {!! $isian_kes_rek !!}
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>Status Perpanjangan: {{ $data['perpanjang'] }}</td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        {!! $timestamp !!}
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>