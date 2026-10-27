@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Terdapat Surat Pemberitahuan Penyesuaian Upah yang harus anda review</strong></p>
<p></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="80%">: {{ $data['nama'] }}</td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>: {{ $data['jabatan'] }}</td>
  </tr>
  <tr>
    <td>Direktorat</td>
    <td>: {{ $data['divisi'] }}</td>
  </tr>
  <tr>
    <td>Divisi</td>
    <td>: {{ $data['dep'] }}</td>
  </tr>
  <tr>
    <td>Departemen</td>
    <td>: {{ $data['sub_dep'] }}</td>
  </tr>
</table>
</p>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Surat Pemberitahuan Penyesuaian Upah</a></strong></h1>
@include('emails.footer')