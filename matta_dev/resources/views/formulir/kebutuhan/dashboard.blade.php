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
    <li class="breadcrumb-item active">Recruitment</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form id="form" name="form" class="mt-2" action="{{ route('kebutuhan.dashboard', 0) }}" method="get" enctype="multipart/form-data">
                            <div class="row mt-2">
                                <div class="col-sm-4">
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_tahun">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" id="all_tahun" name="all_tahun"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_tahun">
                                        @foreach ($select_tahun as $_data)
                                            <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_tahun)) checked @endif>
                                            {{ $_data }}<br/>
                                        @endforeach
                                    </div></div><br/>
                                    @php $type_status_kerja = request()->get('type_status_kerja'); @endphp
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_status_kerja">Status Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_status_kerja(this)" id="all_status_kerja" name="all_status_kerja"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_status_kerja">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_status_kerja" id="type_status_kerja" value="bar" {{ is_null($type_status_kerja) || $type_status_kerja === 'bar' ? 'checked' : '' }}>
                                            <label class="form-check-label">Bar</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_status_kerja" id="type_status_kerja" value="line" {{ $type_status_kerja === 'line' ? 'checked' : '' }}>
                                            <label class="form-check-label">Line</label>
                                        </div>
                                        <label class="d-block" for="status_kerja">
                                            @foreach ($select_status_kerja as $_data)
                                                <input class="checkbox_animated" id="status_kerja[]" name="status_kerja[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_status_kerja)) checked @endif>
                                                {{ $_data }}<br/>
                                            @endforeach
                                        </label>
                                    </div></div><br/>
                                    @php $type_area = request()->get('type_area'); @endphp
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_area">Area Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_area(this)" id="all_area" name="all_area"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_area">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_area" id="type_area" value="bar" {{ is_null($type_area) || $type_area === 'bar' ? 'checked' : '' }}>
                                            <label class="form-check-label">Bar</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_area" id="type_area" value="line" {{ $type_area === 'line' ? 'checked' : '' }}>
                                            <label class="form-check-label">Line</label>
                                        </div>
                                        <label class="d-block" for="area">
                                            @foreach ($select_area as $_data)
                                                <input class="checkbox_animated" id="area[]" name="area[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_area)) checked @endif>
                                                {{ $_data }}<br/>
                                            @endforeach
                                        </label>
                                    </div></div><br/>
                                </div>
                                <div class="col-sm-4">
                                    @php $type_direktorat = request()->get('type_direktorat'); @endphp
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_direktorat">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_direktorat(this)" id="all_direktorat" name="all_direktorat"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_direktorat">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_direktorat" id="type_direktorat" value="bar" {{ is_null($type_direktorat) || $type_direktorat === 'bar' ? 'checked' : '' }}>
                                            <label class="form-check-label">Bar</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_direktorat" id="type_direktorat" value="line" {{ $type_direktorat === 'line' ? 'checked' : '' }}>
                                            <label class="form-check-label">Line</label>
                                        </div>
                                        <label class="d-block" for="direktorat">
                                            @foreach ($select_direktorat as $_data)
                                                <input class="checkbox_animated" id="direktorat[]" name="direktorat[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_direktorat)) checked @endif>
                                                {{ $_data }}<br/>
                                            @endforeach
                                        </label>
                                    </div></div><br/>
                                    @php $type_divisi = request()->get('type_divisi'); @endphp
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_divisi">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" id="all_divisi" name="all_divisi"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_divisi">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_divisi" id="type_divisi" value="bar" {{ is_null($type_divisi) || $type_divisi === 'bar' ? 'checked' : '' }}>
                                            <label class="form-check-label">Bar</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_divisi" id="type_divisi" value="line" {{ $type_divisi === 'line' ? 'checked' : '' }}>
                                            <label class="form-check-label">Line</label>
                                        </div>
                                        <label class="d-block" for="divisi">
                                            @foreach ($select_divisi as $_data)
                                                <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_divisi)) checked @endif>
                                                {{ $_data }}<br/>
                                            @endforeach
                                        </label>
                                    </div></div><br/>
                                </div>
                                <div class="col-sm-4">
                                    @php $type_departemen = request()->get('type_departemen'); @endphp
                                    <label class="h6" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#box_departemen">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_departemen(this)" id="all_departemen" name="all_departemen"/> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <div class="collapse" id="box_departemen">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_departemen" id="type_departemen" value="bar" {{ is_null($type_departemen) || $type_departemen === 'bar' ? 'checked' : '' }}>
                                            <label class="form-check-label">Bar</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type_departemen" id="type_departemen" value="line" {{ $type_departemen === 'line' ? 'checked' : '' }}>
                                            <label class="form-check-label">Line</label>
                                        </div>
                                        <label class="d-block" for="departemen">
                                            @foreach ($select_departemen as $_data)
                                                <input class="checkbox_animated" id="departemen[]" name="departemen[]" type="checkbox" value="{{ $_data }}" @if (in_array($_data, $arr_select_departemen)) checked @endif>
                                                {{ $_data }}<br/>
                                            @endforeach
                                        </label>
                                    </div></div><br/>
                                </div>
                            </div>
                            @php
                            @endphp
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | 
                                    <a href="{{ route('kebutuhan.dashboard', [
                                    'id'=>0, 'filter'=>'true', 'tahun'=>[now()->year],
                                    'type_direktorat'=>'bar', 'direktorat'=>$select_direktorat,
                                    'type_divisi'=>'bar', 'divisi'=>$select_divisi,
                                    'type_departemen'=>'bar', 'departemen'=>$select_departemen,
                                    'type_status_kerja'=>'bar', 'status_kerja'=>$select_status_kerja,
                                    'type_area'=>'bar', 'area'=>$select_area,
                                    ]) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-primary">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['all']['l_trx'] ?? $lable_data[0] }}</h6>
                                    <h5>{{ $data['all']['n_trx'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-success">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['terpenuhi']['l_trx'] ?? $lable_data[2] }}</h6>
                                    <h5>{{ $data['terpenuhi']['n_trx'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-danger">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['cancel']['l_trx'] ?? $lable_data[4] }}</h6>
                                    <h5>{{ $data['cancel']['n_trx'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-warning">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['outstanding']['l_trx'] ?? $lable_data[6] }}</h6>
                                    <h5>{{ $data['outstanding']['n_trx'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-primary">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['all']['l_orang'] ?? $lable_data[1] }}</h6>
                                    <h5>{{ $data['all']['n_orang'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-success">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['terpenuhi']['l_orang'] ?? $lable_data[3] }}</h6>
                                    <h5>{{ $data['terpenuhi']['n_orang'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-danger">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['cancel']['l_orang'] ?? $lable_data[5] }}</h6>
                                    <h5>{{ $data['cancel']['n_orang'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-3">
                            <div class="card bg-warning">
                                <div class="text-center mt-2 mx-auto d-flex flex-column justify-content-center" style="height:80px;">
                                    <h6>{{ $data['outstanding']['l_orang'] ?? $lable_data[7] }}</h6>
                                    <h5>{{ $data['outstanding']['n_orang'] ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--  --}}
                    <div class="row">
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Direktorat</h5>
                            <div id="chart_direktorat"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Divisi</h5>
                            <div id="chart_divisi"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Status Kerja</h5>
                            <div id="chart_status_kerja"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Area Kerja</h5>
                            <div id="chart_area"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Direktorat (Year on Year)</h5>
                            <div id="chart_direktorat_tahun"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Per Direktorat Per Bulan</h5>
                            <div id="chart_direktorat_bulan_tahun"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">Kebutuhan Posisi Per Direktorat </h5>
                            <div id="chart_direktorat_posisi"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">SLA Per Direktorat</h5>
                            <div id="chart_direktorat_sla"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">SLA Per Divisi</h5>
                            <div id="chart_divisi_sla"></div>
                        </div>
                        <div class="col-xl-12 col-sm-12">
                            <h5 class="text-center">SLA Per Departemen</h5>
                            <div id="chart_departemen_sla"></div>
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
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_direktorat(source) {
    checkboxes = document.getElementsByName('direktorat[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_departemen(source) {
    checkboxes = document.getElementsByName('departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_status_kerja(source) {
    checkboxes = document.getElementsByName('status_kerja[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_area(source) {
    checkboxes = document.getElementsByName('area[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
$(document).ready(function() {
    let total = $("input[name='tahun[]']").length;
    let checked = $("input[name='tahun[]']:checked").length;
    $("#all_tahun").prop("checked", total > 0 && total === checked);
    total = $("input[name='status_kerja[]']").length;
    checked = $("input[name='status_kerja[]']:checked").length;
    $("#all_status_kerja").prop("checked", total > 0 && total === checked);
    total = $("input[name='area[]']").length;
    checked = $("input[name='area[]']:checked").length;
    $("#all_area").prop("checked", total > 0 && total === checked);
    total = $("input[name='direktorat[]']").length;
    checked = $("input[name='direktorat[]']:checked").length;
    $("#all_direktorat").prop("checked", total > 0 && total === checked);
    total = $("input[name='divisi[]']").length;
    checked = $("input[name='divisi[]']:checked").length;
    $("#all_divisi").prop("checked", total > 0 && total === checked);
    total = $("input[name='departemen[]']").length;
    checked = $("input[name='departemen[]']:checked").length;
    $("#all_departemen").prop("checked", total > 0 && total === checked);
    // 
    $('#form').on('submit', function (e) {
        let form = $(this);
        let errors = [];
        let select = form.find($('input[name="tahun[]"]:checked')).length;
        if (select < 1) {
            errors.push("Tahun is mandatory");
        }
        select = form.find($('input[name="status_kerja[]"]:checked')).length;
        if (select < 1) {
            errors.push("Status Kerja is mandatory");
        }
        select = form.find($('input[name="area[]"]:checked')).length;
        if (select < 1) {
            errors.push("Area Kerja is mandatory");
        }
        select = form.find($('input[name="direktorat[]"]:checked')).length;
        if (select < 1) {
            errors.push("Direktorat is mandatory");
        }
        select = form.find($('input[name="divisi[]"]:checked')).length;
        if (select < 1) {
            errors.push("Divisi is mandatory");
        }
        select = form.find($('input[name="departemen[]"]:checked')).length;
        if (select < 1) {
            errors.push("Departemen is mandatory");
        }
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
    // 
    function render_chart_v2(dataObj, containerId, chartType) {
        const categories = Object.keys(dataObj);
        const direktoratSet = new Set();
        Object.values(dataObj).forEach(yearData => {
            Object.keys(yearData).forEach(dir => direktoratSet.add(dir));
        });
        const allDirektorat = Array.from(direktoratSet);
        const series = allDirektorat.map(dir => {
            return {
                name: dir,
                data: categories.map(year => dataObj[year][dir] ?? 0)
            };
        });
        const allValues = [];
        series.forEach(s => s.data.forEach(v => allValues.push(v)));
        const maxValue = Math.max(...allValues) + 5;
        const options = {
            chart: {
                type: chartType,
                height: 300,
                zoom: { enabled: false },
            },
            series: series,
            // colors: [
            //     '#198754',
            //     '#dc3545',
            //     '#ffc107',
            // ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
                labels: {
                    rotate: -30,
                    // offsetY: -5,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: false,
                    // offsetY: 50,
                },
            },
            yaxis: {
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
            dataLabels: {
                style: { colors: ['#000'] }
            },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    render_chart_v2(@json($data['arr_direktorat_year']), 'chart_direktorat_tahun', @json($data['chart_type']['type_direktorat']));
    render_chart_v2(@json($data['arr_direktorat_month_year']), 'chart_direktorat_bulan_tahun', @json($data['chart_type']['type_direktorat']));
    render_chart_v2(@json($data['arr_direktorat_posisi']), 'chart_direktorat_posisi', @json($data['chart_type']['type_direktorat']));
    // 
    function render_chart_v1(dataObj, containerId, chartType) {
        const categorySet = new Set();
        Object.values(dataObj).forEach(statusObj => {
            Object.keys(statusObj).forEach(c => categorySet.add(c));
        });
        const categories = Array.from(categorySet);
        const series = Object.keys(dataObj).map(status => {
            return {
                name: status,
                data: categories.map(c => dataObj[status][c] ?? 0)
            }
        });
        const allValues = [];
        series.forEach(s => s.data.forEach(v => allValues.push(v)));
        const maxValue = Math.max(...allValues) + 5;
        const options = {
            chart: {
                type: chartType,
                height: 300,
                zoom: { enabled: false },
            },
            series: series,
            grid: {
                padding: {
                    left: 20,
                    right: 20
                }
            },
            colors: [
                '#00E396',
                '#FF4560',
                '#FEB019',
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
                labels: {
                    rotate: -30,
                    // offsetY: -5,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: false,
                    // offsetY: 50,
                },
            },
            yaxis: {
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
            dataLabels: {
                style: { colors: ['#000'] }
            },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    render_chart_v1(@json($data['per_direktorat']), 'chart_direktorat', @json($data['chart_type']['type_direktorat']));
    render_chart_v1(@json($data['per_divisi']), 'chart_divisi', @json($data['chart_type']['type_divisi']));
    render_chart_v1(@json($data['per_status_kerja']), 'chart_status_kerja', @json($data['chart_type']['type_status_kerja']));
    render_chart_v1(@json($data['per_area']), 'chart_area', @json($data['chart_type']['type_area']));
    // 
    function render_chart_v3(dataObj, containerId, chartType) {
        const categorySet = new Set();
        Object.values(dataObj).forEach(statusObj => {
            Object.keys(statusObj).forEach(c => categorySet.add(c));
        });
        const categories = Array.from(categorySet);
        const series = Object.keys(dataObj).map(status => {
            return {
                name: status,
                data: categories.map(c => dataObj[status][c] ?? 0)
            }
        });
        const allValues = [];
        series.forEach(s => s.data.forEach(v => allValues.push(v)));
        const maxValue = Math.max(...allValues) + 5;
        const options = {
            chart: {
                type: chartType,
                height: 300,
                zoom: { enabled: false },
            },
            series: series,
            colors: [
                '#00E396',
                '#FF4560',
                '#FEB019',
                '#008FFB',
            ],
            grid: {
                padding: {
                    left: 20,
                    right: 20
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '95%',
                    borderRadius: 3,
                    borderRadiusApplication: 'end',
                    // dataLabels: {
                    //     position: 'top',
                    // }
                }
            },
            xaxis: {
                categories: categories,
                // tickPlacement: chartType === 'line' ? 'between' : 'on',
                tickPlacement: 'between',
                labels: {
                    rotate: -30,
                    // offsetY: -5,
                    rotateAlways: true,
                    hideOverlappingLabels: false,
                    trim: false,
                    // offsetY: 50,
                },
            },
            yaxis: {
                max: maxValue,
                title: { text: 'Jumlah' }
            },
            legend: { position: 'top' },
            fill: { opacity: 0.8 },
            dataLabels: {
                style: { colors: ['#000'] }
            },
        };
        if (chartType === 'line') {
            options.stroke = {
                curve: 'smooth',
                width: 3
            };
            // options.markers = { size: 5 };
        }
        const chart = new ApexCharts(document.querySelector(`#${containerId}`), options);
        chart.render();
    }
    render_chart_v3(@json($data['per_direktorat_sla']), 'chart_direktorat_sla', @json($data['chart_type']['type_direktorat']));
    render_chart_v3(@json($data['per_divisi_sla']), 'chart_divisi_sla', @json($data['chart_type']['type_divisi']));
    render_chart_v3(@json($data['per_departemen_sla']), 'chart_departemen_sla', @json($data['chart_type']['type_departemen']));
});
</script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
@endsection
