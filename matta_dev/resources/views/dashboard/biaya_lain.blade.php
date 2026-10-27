@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    /* button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    } */
</style>
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Biaya Lain</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Biaya Lain</h5>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('dashboard.biaya_lain', 0) }}" method="get" enctype="multipart/form-data">
                                <div class="row mt-2">
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
                                    {{-- <div class="col-sm-3">
                                        <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                        <label class="d-block" for="tahun">
                                            @foreach ($tahun as $data)
                                                <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $data['tahun'] }}" @if (in_array($data['tahun'], $arr_tahun)) checked @endif>
                                                {{ $data['tahun'] }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Bulan</label>&nbsp;<input type="checkbox" onClick="toggle_bulan(this)" /> Check All
                                        <label class="d-block" for="bulan">
                                            @foreach ($bulan as $data)
                                                <input class="checkbox_animated" id="bulan[]" name="bulan[]" type="checkbox" value="{{ $data['angka'] }}" @if (in_array($data['angka'], $arr_bulan)) checked @endif>
                                                {{ $data['bulan'] }}<br />
                                            @endforeach
                                        </label>
                                    </div> --}}
                                    <div class="col-sm-3">
                                        <label class="h6">Status Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_status_karyawan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="status_karyawan">
                                            @foreach ($status_karyawan as $data)
                                                <input class="checkbox_animated" id="status_karyawan[]" name="status_karyawan[]" type="checkbox" value="{{ $data->status_karyawan }}" @if (in_array($data->status_karyawan, $arr_status_karyawan)) checked @endif>
                                                {{ $data->status_karyawan }}<br />
                                            @endforeach
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="perusahaan">
                                            @foreach ($perusahaan as $data)
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" onClick="get_perusahaan()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_perusahaan)) checked @endif>
                                                {{ $data['nama'] }}<br />
                                            @endforeach
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="divisi">
                                            <div id="div_divisi">
                                                @foreach ($divisi as $data)
                                                    <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" onClick="get_divisi()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_divisi)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_departemen(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="departemen">
                                            <div id="div_departemen">
                                                @foreach ($departemen as $data)
                                                    <input class="checkbox_animated" id="departemen[]" name="departemen[]" type="checkbox" onClick="get_departemen()" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_departemen)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_subdepartemen(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="subdepartemen">
                                            <div id="div_subdepartemen">
                                                @foreach ($subdepartemen as $data)
                                                    <input class="checkbox_animated" id="subdepartemen[]" name="subdepartemen[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['nama'], $arr_subdepartemen)) checked @endif>
                                                    {{ $data['nama'] }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('dashboard.biaya_lain', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--  --}}
                        @php
                            $kesehatan = 0;
                            $overtime = 0;
                            $bpjstk = 0;
                            $bpjsks = 0;
                            // $total_biaya_lain = 0;
                            $group_by = [];
                        @endphp
                        @foreach ($payroll as $data)
                        @php
                            $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                            // status = apm
                            if($data->status == 'apm'){
                                // bpjstk
                                $arr_id = [1,2,3,4,5,6];
                                $bpjs = App\Models\TrxPayrollBpjstkLog::where($where)->whereIn('id_bpjstk', $arr_id)->orderBy('id_bpjstk', 'asc')->get();
                                $bpjstk = round($bpjs->sum('angka'), 0);
                                // bpjsks
                                $arr_id = [1,2,3];
                                $bpjs = App\Models\TrxPayrollBpjskesLog::where($where)->whereIn('id_bpjskes', $arr_id)->orderBy('id_bpjskes', 'asc')->get();
                                $bpjsks = round($bpjs->sum('angka'), 0);
                                // 
                                $kesehatan = round($data->kesehatan, 0);
                                $overtime = round($data->overtime, 0);
                                // $total_biaya_lain = $bpjstk + $bpjsks + $kesehatan + $overtime;
                            }
                            // status = osc
                            elseif($data->status == 'osc'){
                                // bpjstk
                                $arr_id = [7];
                                $bpjs = App\Models\TrxPayrollBpjstkLog::where($where)->whereIn('id_bpjstk', $arr_id)->orderBy('id_bpjstk', 'asc')->get();
                                $bpjstk = round($bpjs->sum('angka'), 0);
                                // bpjsks
                                $arr_id = [4];
                                $bpjs = App\Models\TrxPayrollBpjskesLog::where($where)->whereIn('id_bpjskes', $arr_id)->orderBy('id_bpjskes', 'asc')->get();
                                $bpjsks = round($bpjs->sum('angka'), 0);
                                // 
                                $kesehatan = round($data->kesehatan, 0);
                                $overtime = round($data->overtime, 0);
                                // $total_biaya_lain = $bpjstk + $bpjsks + $kesehatan + $overtime;
                            }
                            // 
                            array_push($group_by, ['bulan_tahun'=>$data->bulan.'-'.$data->tahun, 'perusahaan'=>$data->perusahaan, 'divisi'=>$data->divisi, 'departemen'=>$data->departemen, 'subdepartemen'=>$data->subdepartemen, 'status_karyawan'=>$data->status_karyawan, 'bpjstk'=>$bpjstk, 'bpjsks'=>$bpjsks, 'kesehatan'=>$kesehatan, 'overtime'=>$overtime]);
                        @endphp
                        @endforeach
                        @php
                            $data = collect($group_by)->groupBy('bulan_tahun');
                            $waktu = [];
                            $arr_bpjstk = [];
                            $arr_bpjsks = [];
                            $arr_kesehatan = [];
                            $arr_overtime = [];
                            $arr_total_biaya_lain = [];
                            $total_biaya_lain = 0;
                            foreach($data as $key => $value){
                                array_push($waktu, $key);
                                $_bpjstk = 0;
                                $_bpjsks = 0;
                                $_kesehatan = 0;
                                $_overtime = 0;
                                $_total_biaya_lain = 0;
                                foreach($value as $_value){
                                    $_bpjstk += $_value['bpjstk'];
                                    $_bpjsks += $_value['bpjsks'];
                                    $_kesehatan += $_value['kesehatan'];
                                    $_overtime += $_value['overtime'];
                                    $_total_biaya_lain = $_bpjstk + $_bpjsks + $_kesehatan + $_overtime;
                                }
                                array_push($arr_bpjstk, $_bpjstk);
                                array_push($arr_bpjsks, $_bpjsks);
                                array_push($arr_kesehatan, $_kesehatan);
                                array_push($arr_overtime, $_overtime);
                                array_push($arr_total_biaya_lain, $_total_biaya_lain);
                                $total_biaya_lain += $_total_biaya_lain;
                            }
                        @endphp
                        {{--  --}}
                        <div class="row mt-2">
                            <div class="col-xl-4 col-sm-4">&nbsp;</div>
                            <div class="col-xl-4 col-sm-4">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                        <div class="media faq-widgets">
                                            <div class="media-body text-center">
                                                <h5>Total Biaya Lain</h5>
                                                <h5>@if(!empty($payroll)) {{ number_format($total_biaya_lain, 0) }} @else 0 @endif</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-4">&nbsp;</div>
                        </div>
                        {{--  --}}
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="display nowrap" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Bulan-Tahun</th>
                                                <th>BPJSTK</th>
                                                <th>BPJSKS</th>
                                                <th>Kesehatan</th>
                                                <th>Lembur</th>
                                                <th>Total Biaya Lain</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($arr_total_biaya_lain)>0)
                                            @for($x = 0; $x < count($arr_total_biaya_lain); $x++)
                                            <tr>
                                                <td>{{ $x+1 }}</td>
                                                <td>{{ $waktu[$x] }}</td>
                                                <td class="text-end">{{ number_format($arr_bpjstk[$x], 0) }}</td>
                                                <td class="text-end">{{ number_format($arr_bpjsks[$x], 0) }}</td>
                                                <td class="text-end">{{ number_format($arr_kesehatan[$x], 0) }}</td>
                                                <td class="text-end">{{ number_format($arr_overtime[$x], 0) }}</td>
                                                <td class="text-end">{{ number_format($arr_total_biaya_lain[$x], 0) }}</td>
                                            </tr>
                                            @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--  --}}
                            <hr class="mt-2 mb-2" />
                            {{--  --}}
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Total Biaya Lain</h5>
                                    <div id="chart_total_biaya_lain"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">
                                <div class="card">
                                    <h5 class="text-center mt-2">Biaya Lain</h5>
                                    <div id="chart_biaya_lain"></div>
                                </div>
                            </div>
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
<script type="text/javascript">
// 
$('#_datatable1').DataTable({
	dom: 'lfrtip',
    // dom: 'rtip',
});
// 
$('#_datatable2').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable3').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable4').DataTable({
	dom: 'lfrtip',
});
// 
$('#_datatable5').DataTable({
	dom: 'lfrtip',
});
// 
function get_perusahaan() {
    getDivisi();
}
// 
function get_divisi() {
    getDepartemen();
}
// 
function get_departemen() {
    getSubDepartemen();
}
// 
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_bulan(source) {
    checkboxes = document.getElementsByName('bulan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDepartemen();
}
// 
function toggle_departemen(source) {
    checkboxes = document.getElementsByName('departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getSubDepartemen();
}
// 
function toggle_subdepartemen(source) {
    checkboxes = document.getElementsByName('subdepartemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_karyawan(source) {
    checkboxes = document.getElementsByName('status_karyawan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function getDivisi() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    $('#div_divisi').empty();
    $('#div_departemen').empty();
    $('#div_subdepartemen').empty();
    // AJAX request 
    $.ajax({
        url: "{{ route('payroll.get_divisi',0) }}" + "?perusahaan=" + _perusahaan,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='divisi[]' name='divisi[]' type='checkbox' onClick='get_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    var _divisi = [];
    $('input[id="divisi[]"]:checked').each(function() {
        _divisi.push($(this).val());
    });
    $('#div_departemen').empty();
    $('#div_subdepartemen').empty();
    $.ajax({
        url: "{{ route('payroll.get_departemen',0) }}" + "?perusahaan=" + _perusahaan + "&divisi=" + _divisi,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='departemen[]' name='departemen[]' type='checkbox' onClick='get_departemen()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen() {
    var _perusahaan = [];
    $('input[id="perusahaan[]"]:checked').each(function() {
        _perusahaan.push($(this).val());
    });
    var _divisi = [];
    $('input[id="divisi[]"]:checked').each(function() {
        _divisi.push($(this).val());
    });
    var _departemen = [];
    $('input[id="departemen[]"]:checked').each(function() {
        _departemen.push($(this).val());
    });
    $('#div_subdepartemen').empty();
    $.ajax({
        url: "{{ route('payroll.get_subdepartemen',0) }}" + "?perusahaan=" + _perusahaan + "&divisi=" + _divisi + "&departemen=" + _departemen,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='subdepartemen[]' name='subdepartemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    $("#div_subdepartemen").append(option);
                }
            }
        }
    });
}
// 
$(document).ready(function() {
    var options = {
        series: [{
            name: 'Total Biaya Lain',
            data: {{ \Illuminate\Support\Js::from($arr_total_biaya_lain) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($waktu) }},
        },
        yaxis: {
            title: {
                text: 'Total Biaya Lain',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_total_biaya_lain"), options).render();
    // 
    var options = {
        series: [{
            name: 'BPJSTK',
            data: {{ \Illuminate\Support\Js::from($arr_bpjstk) }}
        },{
            name: 'BPJSKS',
            data: {{ \Illuminate\Support\Js::from($arr_bpjsks) }}
        },{
            name: 'Kesehatan',
            data: {{ \Illuminate\Support\Js::from($arr_kesehatan) }}
        },{
            name: 'Lembur',
            data: {{ \Illuminate\Support\Js::from($arr_overtime) }}
        }],
        chart: {
            type: 'line',
            height: 350,
        },
        stroke: {
          curve: 'smooth'
        },
        colors: ['#f57b20', '#4e71ff', '#6de1d2', '#ffd63a'],
        xaxis: {
            categories: {{ \Illuminate\Support\Js::from($waktu) }},
        },
        yaxis: {
            title: {
                text: 'Biaya Lain',
            },
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            },
        },
    };
    new ApexCharts(document.querySelector("#chart_biaya_lain"), options).render();
});
</script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
