@extends('layouts.simple.master')

@section('title', 'Formulir Izin')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Rekapitulasi Izin</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Izin</li>
<li class="breadcrumb-item active">Rekapitulasi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="{{ route('izin.recap', 0) }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal Dari</label>
                                    <br /><input class="form-control" name="date_from" id="date_from" type="date" required="" value="{{ $date_from }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal Sampai</label>
                                    <br /><input class="form-control" name="date_to" id="date_to" type="date" required="" value="{{ $date_to }}">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="is_aktif">
                                        @foreach ($is_aktif as $data)
                                            <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="{{ $data['id'] }}" @if (in_array($data['id'], $arr_is_aktif)) checked @endif>
                                            {{ $data['is_aktif'] }}<br />
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Tipe</label>&nbsp;<input type="checkbox" onClick="toggle_tipe(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="tipe">
                                        @foreach ($tipe as $data)
                                            <input class="checkbox_animated" id="tipe[]" name="tipe[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_tipe)) checked @endif>
                                            {{ $data->tipe }}<br />
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_perusahaan">
                                        @foreach ($perusahaan as $data)
                                            <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="{{ $data->id }}" @if (in_array($data->id, $arr_perusahaan)) checked @endif>
                                            {{ $data->nama }}<br />
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_divisi">
                                        <div id="div_kode_divisi">
                                            @foreach ($divisi as $data)
                                                <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="{{ $data->id }}" @if (in_array($data->id, $arr_divisi)) checked @endif>
                                                {{ $data->nama }}<br />
                                            @endforeach
                                        </div>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_departemen">
                                        <div id="div_kode_departemen">
                                            @foreach ($departemen as $data)
                                                <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen()" value="{{ $data->id }}" @if (in_array($data->id, $arr_departemen)) checked @endif>
                                                {{ $data->nama }}<br />
                                            @endforeach
                                        </div>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_sub_departemen">
                                        <div id="div_kode_sub_departemen">
                                            @foreach ($sub_departemen as $data)
                                                <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_sub_departemen)) checked @endif>
                                                {{ $data->nama }}<br />
                                            @endforeach
                                        </div>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Jumlah</th>
                                    <th>Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            @php $no = 0; @endphp
                            <tbody>
                                @foreach ($trx as $data)
                                @php
                                    $karyawan_property = json_decode($data['karyawan_property'], true);
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td><a target="_blank" href="{{ route('izin.recap.detail', [0, 'karyawan'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->karyawan), 'tipe'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->tipe), 'date_from'=>$date_from, 'date_to'=>$date_to]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a></td>
                                    <td>{{ $data->jumlah }}</td>
                                    <td>{{ $karyawan_property['nama'] }}</td>
                                    <td>{{ $karyawan_property['nik'] }}</td>
                                    <td>{{ $karyawan_property['perusahaan'] }}</td>
                                    <td>{{ $karyawan_property['divisi'] }}</td>
                                    <td>{{ $karyawan_property['dep'] }}</td>
                                    <td>{{ $karyawan_property['sub_dep'] }}</td>
                                    <td>{{ $data->_tipe->tipe }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('izin.index') }}" class="btn btn-warning" type="button">Back</a>
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
function get_kode_perusahaan() {
    getDivisi();
}
// 
function get_kode_divisi() {
    getDepartemen();
}
// 
function get_kode_departemen() {
    getSubDepartemen();
}
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('kode_divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDepartemen();
}
// 
function toggle_kode_departemen(source) {
    checkboxes = document.getElementsByName('kode_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getSubDepartemen();
}
// 
function toggle_kode_sub_departemen(source) {
    checkboxes = document.getElementsByName('kode_sub_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_tipe(source) {
    checkboxes = document.getElementsByName('tipe[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function getDivisi() {
    var _kode_perusahaan = [];
    $('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    $('#div_kode_divisi').empty();
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    // AJAX request 
    $.ajax({
        url: "{{ route('getDivisi') }}" + "/" + _kode_perusahaan,
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
                    var option = "<input class='checkbox_animated' id='kode_divisi[]' name='kode_divisi[]' type='checkbox' onClick='get_kode_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen() {
    var _kode_divisi = [];
    $('input[id="kode_divisi[]"]:checked').each(function() {
        _kode_divisi.push($(this).val());
    });
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "{{ route('getDepartemen') }}" + "/" + _kode_divisi,
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
                    var option = "<input class='checkbox_animated' id='kode_departemen[]' name='kode_departemen[]' type='checkbox' onClick='get_kode_departemen()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen() {
    var _kode_departemen = [];
    $('input[id="kode_departemen[]"]:checked').each(function() {
        _kode_departemen.push($(this).val());
    });
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "{{ route('getSubDepartemen') }}" + "/" + _kode_departemen,
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
                    var option = "<input class='checkbox_animated' id='kode_sub_departemen[]' name='kode_sub_departemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_sub_departemen").append(option);
                }
            }
        }
    });
}
// 
function toggle_is_aktif(source) {
    checkboxes = document.getElementsByName('is_aktif[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable').DataTable({
    // dom: 'lBfrtip',
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'rt>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "lengthMenu": [
        [50, 100, 200, 500, -1],
        [50, 100, 200, 500, "All"]
    ]
});
</script>
@endsection