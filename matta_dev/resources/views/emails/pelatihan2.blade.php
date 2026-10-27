@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Formulir Pengajuan Pelatihan anda sudah disetujui para pihak.</strong></p>
<p>Berikut datanya:</p>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">No Formulir</td>
    <td width="2%">:</td>
    <td width="68%">{{ $data['trx']->id }}</td>
  </tr>
</table>
<p></p>
<strong>Pengaju:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">Nama Karyawan</td>
    <td width="2%">:</td>
    <td width="68%">{{ $data['nama'] }}</td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>:</td>
    <td>{{ $data['jabatan'] }}</td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>:</td>
    <td>{{ $data['unit_kerja'] }}</td>
  </tr>
</table>
<strong>Pelatihan:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="30%">Judul</td>
    <td width="2%">:</td>
    <td width="68%">{{ $data['trx']->judul }}</td>
  </tr>
  <tr>
    <td>Penyelenggara</td>
    <td>:</td>
    <td>{{ $data['trx']->penyelenggara }}</td>
  </tr>
  @php
  $tanggal_range = [];
  if(!empty($data['trx']->tanggal_range)){
      $tanggal_range = json_decode($data['trx']->tanggal_range, true);
      $formatted_ranges = array_map(function($range) {
          [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
          return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
      }, $tanggal_range);
  }
  @endphp
  <tr>
    <td>Tanggal Pelaksanaan</td>
    <td>:</td>
    {{-- <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_mulai)->format('d-m-Y') }} sampai {{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_akhir)->format('d-m-Y') }}</td> --}}
    @if(empty($data['trx']->tanggal_range))
    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_mulai)->format('d-m-Y') }} s/d {{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_akhir)->format('d-m-Y') }}</td>
    @else
    <td>@foreach ($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
    @endif
  </tr>
  <tr>
    <td>Tempat</td>
    <td>:</td>
    <td>{{ $data['trx']->tempat }}</td>
  </tr>
  <tr>
    <td>Peserta</td>
    <td>:</td>
    <td>@php $arrPeserta = explode(';', $data['trx']->peserta_property) @endphp
      @foreach ($arrPeserta as $value)
        @php $_value = json_decode($value, true) @endphp
        {{ $_value['nama'] }} /  {{ $_value['nik'] }}<br/>
      @endforeach
    </td>
  </tr>
</table>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Pengajuan Pelatihan</a></strong></h1>
@include('emails.footer')