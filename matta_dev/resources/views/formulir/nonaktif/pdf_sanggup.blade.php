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
@php
    $property = json_decode($trxM->pengaju_property, true);
    $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">SURAT PERNYATAAN KESANGGUPAN</div></td>
                        <td width="25%"><div class="center">No: {{ $trxM->id }}</div></td>
                    </tr>
                </table>
                <br/><br/><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="justify">Yang menyatakan di bawah ini :</td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="3%">&nbsp;</td>
                        <td width="3%">&nbsp;</td>
                        <td width="10%">Nama</td>
                        <td width="84%">: {{ $property['nama'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Jabatan</td>
                        <td>: {{ $property['jabatan'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>NIP</td>
                        <td>: {{ $property['nik'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Direktorat</td>
                        <td>: {{ $property['divisi'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Divisi</td>
                        <td>: {{ $property['dep'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Departemen</td>
                        <td>: {{ $property['sub_dep'] }}</td>
                    </tr>
                </table>
                <br/><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="3%">&nbsp;</td>
                        <td width="3%">1.</td>
                        <td width="94%">Adalah subjek hukum yang terikat sebagai karyawan <b>PT ALITA PRAYA MITRA</b>.</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>2.</td>
                        <td>Masa Kerja di <b>PT ALITA PRAYA MITRA</b> berakhir per tanggal {{ \Carbon\Carbon::parse($trxM->tanggal)->format('d') }} {{ $bulan[\Carbon\Carbon::parse($trxM->tanggal)->format('m')-1] }} {{ \Carbon\Carbon::parse($trxM->tanggal)->format('Y') }} dikarenakan {{ ucwords($trxM->_alasan->alasan) }}.</td>
                    </tr>
                </table>
                <br/><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><u><strong>MENYATAKAN</strong></u></td>
                    </tr>
                </table>
                <br/><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2" align="justify">
                    <tr>
                        <td align="justify">Dalam hal berakhirnya hubungan kerja sebagai Karyawan <b>PT ALITA PRAYA MITRA</b> tersebut di atas maka, SANGGUP UNTUK :</td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2" align="justify">
                    <tr>
                        <td width="3%">&nbsp;</td>
                        <td width="3%">A.</td>
                        <td width="94%">Menjaga Seluruh Kerahasiaan Pekerjaan yang telah dilakukannya di <b>PT ALITA PRAYA MITRA</b>, termasuk di dalamnya hal-hal yang terkait dengan Produk, Harga, Teknologi, Sistem Kerja serta Organisasi, dan tidak memberikan informasi terkait hal-hal tersebut di atas kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>B.</td>
                        <td>Menyerahkan kembali seluruh Alat Kerja milik <b>PT ALITA PRAYA MITRA</b> yang telah diberikan sebagai penunjang pekerjaan, dalam keadaan dan fungsi yang baik, dibuktikan dengan Membuat Berita Acara Serah Terima Alat Kerja;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>C.</td>
                        <td>Menjaga Seluruh Kerahasiaan Data dan Informasi <b>PT ALITA PRAYA MITRA</b> atas berakhirnya masa Kerja, dan tidak memberikan informasi terkait hal-hal tersebut kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>D.</td>
                        <td>Dituntut sesuai hukum yang berlaku, apabila melanggar seluruh ketentuan dalam Surat Pernyataan Kesanggupan ini.</td>
                    </tr>
                </table>
                <br/><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2" align="justify">
                    <tr>
                        <td>Pernyataan ini dibuat secara sadar, tanpa ada tekanan dari pihak manapun dan akan dipatuhi dan dilaksanakan secara penuh tanggung jawab, dan sesuai dengan peraturan Perundangan yang berlaku.</td>
                    </tr>
                </table>
                <br/><br/>
                {{-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><b>PT ALITA PRAYA MITRA</b><br/>Jakarta, {{ \Carbon\Carbon::parse($trxLog->created_at)->format('d') }} {{ $bulan[\Carbon\Carbon::parse($trxLog->created_at)->format('m')-1] }} {{ \Carbon\Carbon::parse($trxLog->created_at)->format('Y') }}</td>
                    </tr>
                </table>
                <br/><br/> --}}
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center">Menyetujui<br/><br/><img src="{{ public_path('assets/images/matta/approved_new.png')}}" height="30" /><br/>{{ $property['nama'] }}<br/>{{ $property['jabatan'] }}<br/>{{ $trxLog->created_at }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html>