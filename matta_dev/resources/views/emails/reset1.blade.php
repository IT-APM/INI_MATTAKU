@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Berikut adalah data anda:</strong></p>
<p></p>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="80%">: {{ $data['nama'] }}</td>
  </tr>
  <tr>
    <td>NIP</td>
    <td>: {{ $data['nik'] }}</td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>: {{ $data['jabatan'] }}</td>
  </tr>
  <tr>
    <td>Direktorat / Divisi / Departemen</td>
    <td>: {{ $data['unit_kerja'] }}</td>
  </tr>
</table>
</p>
<h1><strong>Password baru anda adalah: {{ $data['new_password'] }}</strong></h1>
@include('emails.footer')