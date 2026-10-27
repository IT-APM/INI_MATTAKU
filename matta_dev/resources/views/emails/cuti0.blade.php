@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Permohonan Cuti yang harus anda review</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td>No Formulir</td>
    <td>: {{ $data['id_trx'] }}</td>
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
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Permohonan Cuti</a></strong></h1>
@include('emails.footer')