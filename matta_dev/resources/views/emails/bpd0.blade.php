@include('emails.header')
<p>Semangat Pagi {{ $data['nama_to'] }} / {{ $data['nik_to'] }},</p>
<p><strong>Terdapat Pengajuan {{ $data['title'] }} @if(str_contains($data['view'], 'reject')) <b style='color:red;'>(DITOLAK)</b> @endif yang harus anda review.</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td>No Formulir</td>
    <td>: {{ Crypt::decrypt($data['id']) }}</td>
  </tr>
  <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="80%">: {{ $data['nama'] }}</td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>: {{ $data['jabatan'] }}</td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>: {{ $data['unit_kerja'] }}</td>
  </tr>
  <tr>
    <td>Nama Atasan Langsung</td>
    <td>: {{ $data['atasan'] }}</td>
  </tr>
</table>
</p>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">{{ $data['title'] }}</a></strong></h1>
@include('emails.footer')