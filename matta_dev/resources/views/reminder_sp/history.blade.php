@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style>
    button.dt-button {
        float: none;
        text-align: center;
        color: black;
        background: white;
    }
/* css untuk table izin */
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
@endsection

@section('breadcrumb-title')
<h3>Pengingat Absensi & Surat Peringatan Histori</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pengingat Absensi & Surat Peringatan</li>
<li class="breadcrumb-item active">Histori</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse mt-2" id="collapse_filter">
                        <form action="{{ route('reminder_sp.show', 0) }}" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div>
                                        <label class="h6 mt-2" for="nik">Karyawan</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="0" @if(in_array('0', $_arr_nik)) selected @endif>Semua Karyawan</option>
                                            @foreach ($karyawan as $data)
                                            <option value="{{ $data->nik }}" @if(in_array($data->nik, $_arr_nik)) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6 mt-2">Working Group</label>&nbsp;<input type="checkbox" onClick="toggle_wg(this)" />&nbsp;Check All
                                    <label class="d-block" for="wg">
                                        @foreach ($wg as $data)
                                        <input class="checkbox_animated" id="wg[]" name="wg[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $_arr_wg)) checked @endif> {{ $data->wg_name }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6 mt-2">Level</label>&nbsp;<input type="checkbox" onClick="toggle_level(this)" />&nbsp;Check All
                                    <label class="d-block" for="level">
                                        @foreach ($level as $data)
                                        <input class="checkbox_animated" id="level[]" name="level[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $_arr_level)) checked @endif> {{ $data->level }}<br/>
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mt-2 table-responsive">
                        <table class="display nowrap" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ke</th>
                                    <th>Tanggal Awal</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Working Group</th>
                                    <th>NIP</th>
                                    <th>Nama Karyawan</th>
                                    <th>Level</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Email Karyawan</th>
                                    <th>Nama Layer 1</th>
                                    <th>Email Layer 1</th>
                                    <th>Jumlah Hari Kerja</th>
                                    <th>Jumlah Kehadiran</th>
                                    <th>Persentase Kehadiran</th>
                                    <th>Keterlambatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($trx as $data)
                                @php
                                $nik_property = json_decode($data->nik_property, true);
                                $where = ['nik' => $nik_property['layer1']];
                                $layer1 = App\Models\Karyawan::where($where)->first();
                                // check history
                                $teguran_ke = 'Pertama';
                                $type = 'Pengingat Absensi';
                                // check nik yang sama pada 1 bulan sebelumnya
                                $where = ['nik' => $data->nik, 'date_from' => Carbon\Carbon::parse($data->date_from)->modify('-1 month')->format('Y-m-d')];
                                $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                if(!empty($trxSend)){
                                    if($trxSend->type == 'reminder'){
                                        if($trxSend->number == 1){
                                            $teguran_ke = 'Kedua';
                                        }elseif($trxSend->number == 2){
                                            $teguran_ke = 'Ketiga';
                                        }elseif($trxSend->number == 3){
                                            $teguran_ke = 'Keempat';
                                        }elseif($trxSend->number == 4){
                                            $teguran_ke = 'Kelima';
                                        }elseif($trxSend->number == 5){
                                            $teguran_ke = 'Pertama';
                                            $type = 'Surat Peringatan';
                                        }
                                    }elseif($trxSend->type == 'warning'){
                                        $type = 'Surat Peringatan';
                                        if($trxSend->number == 1){
                                            $teguran_ke = 'Kedua';
                                        }elseif($trxSend->number == 2){
                                            $teguran_ke = 'Ketiga';
                                        }
                                    }
                                }
                                // terlambat
                                if(!empty($data->terlambat)){
                                    $type = 'Pengingat Keterlambatan';
                                    // check history
                                    $teguran_ke = 'Pertama';
                                    // check nik yang sama pada 1 bulan sebelumnya
                                    $where = ['nik' => $data->nik, 'date_from' => Carbon\Carbon::parse($data->date_from)->modify('-1 month')->format('Y-m-d')];
                                    $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNotNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                    if(!empty($trxSend)){
                                        if($trxSend->type == 'late'){
                                            $type = 'Pengingat Keterlambatan';
                                            $arr = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh', 'Kesebelas', 'Keduabelas'];
                                            $teguran_ke = $arr[$trxSend->number];
                                        }
                                    }
                                }
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $type }} {{ $teguran_ke }}</td>
                                    <td>{{ Carbon\Carbon::parse($data->date_from) ->format('d-m-Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($data->date_to) ->format('d-m-Y') }}</td>
                                    <td>{{ $nik_property['wg_name'] }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $nik_property['nama'] }}</td>
                                    <td>{{ $nik_property['level'] }}</td>
                                    <td>{{ $nik_property['perusahaan'] }}</td>
                                    <td>{{ $nik_property['divisi'] }}</td>
                                    <td>{{ $nik_property['departemen'] }}</td>
                                    <td>{{ $nik_property['sub_departemen'] }}</td>
                                    <td>{{ $nik_property['email'] }}</td>
                                    <td>{{ $layer1->nama }}</td>
                                    <td>{{ $layer1->email }}</td>
                                    <td>{{ $data->arr_base }}</td>
                                    <td>{{ $data->arr_clock }}</td>
                                    <td>{{ round($data->persentase) }} %</td>
                                    <td>
                                        @php
                                        $str_jam_kerja = 'Toleransi Jam kerja';
                                        if($nik_property['wg_id'] > 1){
                                            $str_jam_kerja = 'Jam kerja';
                                        }
                                        if(!empty($data->terlambat)){
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
                                            $terlambat = json_decode($data->terlambat, true);
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
                                                if($nik_property['wg_id'] > 1){
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
                                            echo $html;
                                        }
                                        @endphp
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script type="text/javascript">
    $('#_datatable').DataTable({
        // dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'rt>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "lengthMenu": [
            [50, 100, 200, 500, -1],
            [50, 100, 200, 500, "All"]
        ]
    });
    // 
    function toggle_wg(source) {
        checkboxes = document.getElementsByName('wg[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
    // 
    function toggle_level(source) {
        checkboxes = document.getElementsByName('level[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
@endsection
