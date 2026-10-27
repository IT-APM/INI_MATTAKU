<head>
    <title>Perubahan Data Karyawan</title>
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<table class="table">
    <tr>
        <td>
            <h4>Semangat Pagi</h4>
            <h4>Terdapat Perubahan Data Karyawan oleh {{ $data['insert_by'] }}</h4>
            <table class="table" style="width:100%" border="1" cellspacing="0" cellpadding="2">
                <thead>
                    <tr>
                        <th style="width:20%">Karyawan</th>
                        <th style="width:50%">Data</th>
                        <th style="width:15%">Perubahan</th>
                        <th style="width:15%">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $_history = json_decode($data['perubahan'], true);
                        $catatan = json_decode($data['catatan'], true);
                    @endphp
                    <tr>
                        <td>{{ $data['nama'] }} / {{ $data['nik'] }}</td>
                        <td>
                            <table class="table table-striped" style="width:100%" border="1" cellspacing="0" cellpadding="2">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Kolom</th>
                                        <th style="width:40%">Sebelum</th>
                                        <th style="width:40%">Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($_history as $_data => $val)
                                    <tr>
                                        <td>{{ $data['lbl_history'][$_data] }}</td>
                                        <td>{{ $val['before'] }}</td>
                                        <td>{{ $val['after'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>{{ $data['created_at'] }}</td>
                        <td>{{ $catatan['sumber'] }}</td>
                    </tr>
                </tbody>
            </table>
            {{-- <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p> --}}
            @include('emails.footer0')
        </td>
    </tr>
</table>