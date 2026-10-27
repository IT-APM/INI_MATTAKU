<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengingat Keterlambatan</title>
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .tbl-laporan {
            border-collapse: collapse;
            margin: 5px;
            width: 100%;
        }
        .tbl-laporan th,
        .tbl-laporan td {
            border: 1px solid #dee2e6;
            padding: 6px 10px;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-laporan thead th {
            background-color: #f0f7f3;
            color: #0B3D2E;
            font-weight: 600;
        }
        .tbl-laporan tbody tr:hover {
            background-color: #f0faf4;
        }
    </style>
</head>
@php $nik_property = json_decode($data['trx']->nik_property, true); @endphp

<body>
    <div style="text-align:center;">
        <img src="https://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini_v21.png" alt="img" width="720" height="509">
    </div>
    <div style="text-align:center;background-color:red;color:white;">
        <p style="padding:5px;">Pengingat Keterlambatan {{ $data['teguran_ke'] }}</p>
    </div>
    <div>
        <div>
            <div>
                @php
                $str_jam_kerja = 'Toleransi Jam kerja';
                if($nik_property['wg_id'] > 1){
                    $str_jam_kerja = 'Jam kerja';
                }
                if(!empty($data['trx']->terlambat)){
                    $html = '<table class="tbl-laporan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Tanggal</th>
                            <th>'.$str_jam_kerja.'</th>
                            <th>Clock-in</th>
                            <th>Keterlambatan</th>
                            <th>Izin</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $terlambat = json_decode($data['trx']->terlambat, true);
                    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $_no = 0;
                    foreach ($terlambat as $_terlambat){
                        $str_izin = '';
                        if(!empty($_terlambat['izin'])){
                            $izin = $_terlambat['izin'][0];
                            $str_izin .= App\Models\IjinTipe::find($izin['tipe'])->tipe;
                            if($izin['tipe'] == 2){
                                $str_izin .= ' Jam '.$izin['jam_awal'].' s/d '.$izin['jam_akhir'];
                            }
                        }
                        $clockin = $_terlambat['clockin'];
                        $att_property = json_decode(App\Models\Attendance::find($_terlambat['id_attendance'])->att_property, true);
                        // if($nik_property['wg_id'] > 1){
                        if($att_property['wg_id'] > 1){
                            $clockin = \Carbon\Carbon::parse($_terlambat['clockin']) ->format('H:i:s');
                        }
                        $html .= '<tr>
                            <th>'.++$_no.'</th>
                            <td>'.$hari[\Carbon\Carbon::parse($_terlambat['tanggal'])->dayOfWeek].'</td>
                            <td>'.\Carbon\Carbon::parse($_terlambat['tanggal']) ->format('d-m-Y').'</td>
                            <td>'.$clockin.'</td>
                            <td>'.\Carbon\Carbon::parse($_terlambat['att_timestamp']) ->format('H:i:s').'</td>
                            <td>'.$_terlambat['durasi'].'</td>
                            <td>'.$str_izin.'</td>
                        </tr>';
                    }
                    $html .= '</tbody></table>';
                }
                @endphp
                <p>Dear Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}</p>
                <p>Terima kasih telah menggunakan Mesin Fingerprint / Aplikasi Mobile MATTA / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa keterlambatan kehadiran dan absensi dapat ditoleransi maksimal 3 (tiga) kali dalam satu bulan. Keterlambatan dikarenakan pengajuan ijin khusus melalui MATTA, tidak termasuk dalam perhitungan ini.</p>
                <p>Bersama ini Kami sampaikan bahwa terdapat keterlambatan kehadiran dan absensi dari Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }} sebanyak {{ count($terlambat) }} kali untuk periode {{ \Carbon\Carbon::parse($data['trx']->date_from)->format('d-m-Y') }} sd {{ \Carbon\Carbon::parse($data['trx']->date_to)->format('d-m-Y') }} yaitu pada tanggal:</p>
                <p>{!! $html !!}</p>
                <p>Mohon untuk meningkatkan kehadiran tepat waktu di tempat kerja.</p>
                <p>Informasi ini adalah sebagai Pengingat Keterlambatan {{ $data['teguran_ke'] }} untuk Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}. Kami harap dapat melakukan hadir tepat waktu di tempat kerja setiap hari ke depannya.</p>
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