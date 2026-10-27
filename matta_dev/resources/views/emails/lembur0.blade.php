@include('emails.header')
<style>
td {
  vertical-align: top;
}
</style>
<p>Dear {{ $data['atasan'] }},</p>
<p>Semangat Pagi,</p>
<p><strong>Terdapat Pengajuan Lembur yang harus anda review</strong></p>
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
    <td>Tipe</td>
    <td>: {{ $data['overtime']['ot_type'] }}</td>
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
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Pengajuan lembur</a></strong></h1>
<p>Syarat dan Ketentuan OVERTIME (OT) :<br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="2%">1</td>
    <td width="2%">:</td>
    <td width="96%">OT yang Anda buat dan ajukan TIDAK BERIRISAN dengan OT Anda yang lain.</td>
  </tr>
  <tr>
    <td>2</td>
    <td>:</td>
    <td>OT TIDAK BISA DILAKUKAN pada jam kerja normal / regular Anda.</td>
  </tr>
  <tr>
    <td>3</td>
    <td>:</td>
    <td>Jam mulai OT di HARI KERJA adalah 1 (satu) jam setelah berakhirnya JAM KERJA REGULER.
	  <br/>Contoh: Jam Kerja Reguler pukul 09.00 – 18.00 maka OT dimulai pukul pukul 19.00
	  </td>
  </tr>
  <tr>
    <td>4</td>
    <td>:</td>
    <td>Pengajuan OT bisa dilakukan dalam hari atau bulan yang berbeda.
	  <br/>Contoh : Pengajuan OT untuk besok atau pekan depan dapat dibuat saat ini
	  </td>
  </tr>
  <tr>
    <td>5</td>
    <td>:</td>
    <td>Klaim OT yang dihitung adalah jika memenuhi SEMUA SYARAT berikut:
    <br/>A. OT yang telah mendapatkan PERSETUJUAN Atasan terkait.<br/>
    B. Durasi OT yang dihitung adalah WAKTU CLOCK IN dan CLOCK OUT pada periode pengajuan OT. Selain durasi diluar periode OT, TIDAK DIHITUNG SEBAGAI REALISASI OT.<br/>
    C. Kegiatan CLOCK IN dan CLOCK OUT WAJIB dilakukan seluruhnya oleh Pengaju OT. Tanpa CLOCK IN dan CLOCK OUT, perhitungan OT tidak muncul di sistem dan dianggap tidak mempunyai realisasi OT.
    </td>
  </tr>
</table>
</p>
@include('emails.footer')