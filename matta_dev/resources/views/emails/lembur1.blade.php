@include('emails.header')
<p>Dear {{ $data['nama'] }},</p>
<p>Semangat Pagi,</p>
<p><strong>Pengajuan lembur anda telah disetujui. </strong></p>
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
    <td>Direktorat / Divisi / Departemen</td>
    <td>: {{ $data['unit_kerja'] }}</td>
  </tr>
  <tr>
    <td>Pekerjaan</td>
    <td>: {{ $data['overtime']['ot_title'] }}</td>
  </tr>
  <tr>
    <td>Deskripsi</td>
    <td>: {{ $data['overtime']['ot_desc'] }}</td>
  </tr>
  <tr>
    <td>Waktu mulai</td>
    <td>: {{ $data['overtime']['ot_start_at'] }}</td>
  </tr>
  <tr>
    <td>Waktu akhir</td>
    <td>: {{ $data['overtime']['ot_end_at'] }}</td>
  </tr>
</table>
</p>
<p>Silahkan melakukan clock-in dan clock-out pada jam tersebut.<br/>
<p>Karyawan yang mengajukan lembur diwajibkan untuk melakukan clock-in dan clock-out sesuai dengan jam pengajuan lembur.</p></p>
<h1>Perhatian: Karyawan yang tidak melakukan clock-in dan clock-out pada saat lembur maka tidak dimasukkan kedalam perhitungan pengajuan lembur.</h1>
@include('emails.footer')