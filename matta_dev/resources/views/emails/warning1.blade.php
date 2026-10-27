<!DOCTYPE html>
<html lang="en">

<head>
    <title>Surat Peringatan</title>
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
@php $nik_property = json_decode($data['trx']->nik_property, true); @endphp

<body>
    <div style="text-align:center;">
        {{-- <img src="https://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini.png" alt="Sudahkah anda absen hari ini" width="720" height="479"> --}}
        <img src="https://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini_v21.png" alt="img" width="720" height="509">
    </div>
    <div style="text-align:center;background-color:red;color:white;">
        <p style="padding:5px;">Surat Peringatan {{ $data['teguran_ke'] }}</p>
    </div>
    <div>
        <div>
            <div>
                <p>Dear Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}</p>
                {{-- <p>Terima kasih telah menggunakan Aplikasi Mobile / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa Absensi Kehadiran wajib dilakukan sekurang-kurangnya 80%.</p> --}}
                <p>Terima kasih telah menggunakan Mesin Fingerprint / Aplikasi Mobile MATTA / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa Absensi Kehadiran wajib dilakukan sekurang-kurangnya 80% di setiap bulannya.</p>
                <p>Bersama ini Kami sampaikan bahwa absensi kehadiran Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }} pada tanggal {{ \Carbon\Carbon::parse($data['trx']->date_from)->format('d-m-Y') }} sd {{ \Carbon\Carbon::parse($data['trx']->date_to)->format('d-m-Y') }} kurang dari 80% dari yang seharusnya terpenuhi (hadir = {{ $data['trx']->arr_clock }} dari jumlah hari kerja = {{ $data['trx']->arr_base }}).</p>
                <p>Persentase kehadiran anda adalah {{ round($data['trx']->persentase) }} %.</p>
                <div style="text-align:center;background-color:red;color:white;">
                    @if($data['trx']->arr_clock == 0)
                    <p style="padding:5px;">Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }} tidak melakukan Absensi Kehadiran sama sekali</p>
                    @else
                    <p style="padding:5px;">Mohon untuk dapat lebih ditingkatkan lagi Absensi Kehadirannya</p>
                @endif
                </div>
                @if($data['teguran_ke'] == 'Pertama')
                {{-- <p>Sehubungan dengan Surat Teguran Pertama sampai dengan Surat Teguran Kelima telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan {{ $data['teguran_ke'] }} (akan dikirimkan dalam email terpisah).</p> --}}
                <p>Sehubungan dengan Pengingat Absensi Pertama sampai dengan Pengingat Absensi Kelima telah kami kirimkan melalui email secara berturut-turut seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan {{ $data['teguran_ke'] }} (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Kedua. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                @elseif($data['teguran_ke'] == 'Kedua')
                <p>Sehubungan dengan Surat Peringatan Pertama telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan {{ $data['teguran_ke'] }} (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Ketiga. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                @elseif($data['teguran_ke'] == 'Ketiga')
                <p>Sehubungan dengan Surat Peringatan Kedua telah kami kirimkan melalui email seperti tertera pada history body email ini, dengan berat hati kami terbitkan Surat Peringatan {{ $data['teguran_ke'] }} (akan dikirimkan dalam email terpisah).</p>
                <p>Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Ketiga. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                @endif
                {{-- <p>Terima kasih atas perhatiannya.</p> --}}
                <p>Demikian kami sampaikan.<br/>Terima kasih atas perhatiannya.</p>
                {{-- <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p> --}}
                @include('emails.footer0')
                <p>{{ $data['datetime'] }}</p>
            </div>
        </div>
    </div>
</body>

</html>

{!! $data['history_email'] !!}