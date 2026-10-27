@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Status Formulir Reimbursement anda adalah {{ $data['status'] }}.</strong></p>
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
</table>
</p>
{{-- 2=Disetujui dan Proses Pembayaran --}}
{{-- 3=Ditolak --}}
{{-- 6=Penangguhan Pengajuan --}}
{{-- 7=Pembayaran Berhasil --}}
@if(in_array($data['id_status'], [2,3,6,7]))
@if(in_array($data['id_status'], [2,6]) && $data['email_to'] == App\Models\Karyawan::where('nik', App\Models\MtrUtility::find(12)->informasi)->value('email'))
<p>Klik link berikut untuk proses lebih lanjut:</p>
<h1><strong>Proses <a href="{{ $data['link'] }}" target="_blank">Formulir Reimbursement</a></strong></h1>
@else
<p>Klik link berikut untuk download pdf Formulir Reimbursement:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Reimbursement</a></strong></h1>
@endif
@endif
@include('emails.footer')