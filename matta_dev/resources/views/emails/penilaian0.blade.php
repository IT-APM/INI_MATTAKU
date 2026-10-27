@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Penilaian Karyawan yang harus anda lengkapi</strong></p>
<p></p>
<p><span style="text-decoration: underline;"><strong>Keterangan</strong></span></p>
<p><strong>Formulir Penilaian Reguler : Evaluasi untuk Karyawan yang habis masa Kontrak Kerja</strong></p>
<p><strong>Formulir Penilaian Asessment : Evaluasi untuk Karyawan setelah/dalam masa percobaan</strong></p>
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
<p>Klik link berikut untuk melakukan pengisian Formulir Penilaian Karyawan tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Penilaian Karyawan</a></strong></h1>
@include('emails.footer')