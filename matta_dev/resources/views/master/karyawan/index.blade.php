@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/css/buttons.dataTables.min.css') }}"> -->
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
<h3>Data Master Karyawan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('error') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | 
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | 
                    <a href="{{ route('karyawancopy.create') }}" class="btn btn-success" type="button"><i class="fa fa-copy" aria-hidden="true"></i> Copy data</a> | 
                    <a href="{{ route('karyawan.upload_data', 0) }}" class="btn btn-info" type="button"><i class="fa fa-upload" aria-hidden="true"></i> Upload data</a> | 
                    <a href="{{ route('karyawan.download_allphoto', 0) }}" class="btn btn-info" type="button"><i class="fa fa-download" aria-hidden="true"></i> Semua Foto</a> | 
                    <a href="{{ route('karyawan.bulanan', 0) }}" class="btn btn-outline-primary" type="button"><i class="fa fa-users" aria-hidden="true"></i> Perubahan bulanan</a>
                    <div class="collapse" id="collapse_filter">
                        <form class="m-4" action="{{ route('karyawan.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6" for="nama">Karyawan</label>
                                    <br/><input name="nama" id="nama" class="form-control" type="text" value="{{ $nama }}">
                                    <br/><label class="h6" for="layer1">Layer 1</label>
                                    <br/><input name="layer1" id="layer1" class="form-control" type="text" value="{{ $layer1 }}">
                                    <br/><label class="h6" for="layer2">Layer 2</label>
                                    <br/><input name="layer2" id="layer2" class="form-control" type="text" value="{{ $layer2 }}">
                                    <br/><label class="h6" for="layer3">Layer 3</label>
                                    <br/><input name="layer3" id="layer3" class="form-control" type="text" value="{{ $layer3 }}">
                                    <br/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="is_aktif">
                                        @foreach ($is_aktif as $data)
                                        <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="{{ $data['id'] }}" @if(in_array($data['id'], $arr_is_aktif)) checked @endif> {{ $data['is_aktif'] }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                    <label class="h6">Status Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_kode_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_status">
                                        @foreach ($kode_status as $data)
                                        <input class="checkbox_animated" id="kode_status[]" name="kode_status[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_kode_status)) checked @endif> {{ $data->status_karyawan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                    <label class="h6">Grup Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_wg_id(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="wg_id">
                                        @foreach ($wg_id as $data)
                                        <input class="checkbox_animated" id="wg_id[]" name="wg_id[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_wg_id)) checked @endif> {{ $data->wg_name }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_kode_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_perusahaan">
                                        @foreach ($kode_perusahaan as $data)
                                        <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_kode_perusahaan)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                    <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_kode_level(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_level">
                                        @foreach ($kode_level as $data)
                                        <input class="checkbox_animated" id="kode_level[]" name="kode_level[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_kode_level)) checked @endif> {{ $data->level }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Layer 1</th>
                                    <th>Layer 2</th>
                                    <th>Layer 3</th>
                                    <th>Level</th>
                                    <th>Jabatan</th>
                                    <th>Grup Kerja</th>
                                    <th>Status Kerja</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Project</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $data)
                                <!-- @inject('layer_1', 'App\Http\Controllers\KaryawanController')
                                @inject('layer_2', 'App\Http\Controllers\KaryawanController')
                                @inject('layer_3', 'App\Http\Controllers\KaryawanController') -->
                                <!-- $arr = json_decode($layer_1::getLayer1($data->layer1), true) -->
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>@if ($data->is_aktif == 1) <span class="badge badge-success">Aktif</span> @else <span class="badge badge-danger">Non Aktif</span> @endif</td>
                                    <!-- <td><img class="img-30 me-2" src="{{ asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg') }}" alt="#"></td> -->
                                    <!-- <td><img class="img-50 me-2" src="{{ asset('assets/images/logo/logo_2.jpg') }}" alt="#"></td> -->
                                    <td>
                                        @if (file_exists(public_path().'/assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg'))
                                            <a href="{{ asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg') }}" target="_blank"><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg') }}" alt="user"></a>
                                        @else
                                            <a href="{{ asset('assets/images/matta/karyawan/user.png') }}" target="_blank"><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/user.png') }}" alt="user"></a>
                                        @endif
                                    </td>
                                    <!-- <td>{{ $data->nik }}</td> -->
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->_perusahaan->nama }}</td>
                                    <td>{{ $data->_divisi->nama }}</td>
                                    <td>{{ $data->_departemen->nama }}</td>
                                    <td>{{ $data->_subdepartemen->nama }}</td>
                                    <td>@if (!empty($data->_layer1)) {{ $data->_layer1->nama }} @endif</td>
                                    <td>@if (!empty($data->_layer2)) {{ $data->_layer2->nama }} @endif</td>
                                    <td>@if (!empty($data->_layer3)) {{ $data->_layer3->nama }} @endif</td>
                                    <td>{{ $data->_level->level }}</td>
                                    <td>@if (!empty($data->_jabatan->jabatan)) {{ $data->_jabatan->jabatan }} @endif</td>
                                    <td>{{ $data->_wg->wg_name }}</td>
                                    <td>{{ $data->_status->status_karyawan }}</td>
                                    <td>@if (!empty($data->tanggal_bergabung)) {{ \Carbon\Carbon::parse($data->tanggal_bergabung)->format('d-m-Y') }} @endif</td>
                                    <td>@if (!empty($data->tanggal_berakhir)) {{ \Carbon\Carbon::parse($data->tanggal_berakhir)->format('d-m-Y') }} @endif</td>
                                    <!-- <td>{{ $layer_1::getLayer1($data->layer1) }}</td>
                                        <td>{{ $layer_1::getLayer2($data->layer2) }}</td>
                                        <td>{{ $layer_1::getLayer3($data->layer3) }}</td> -->
                                    <td>{{ $data->_findim_project->project_id }}-{{ $data->_findim_project->project_desc }}</td>
                                    <td>
                                        <!-- <a href="#" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>&nbsp; -->
                                        <form action="{{ route('karyawan.destroy', Crypt::encrypt($data->id)) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <a href="{{ route('plafon.edit', $data->id) }}"><i class="icon-pencil-alt"></i></a>&nbsp; -->
                                            <!-- <li class="delete"><a href="#" onclick="return confirm('Yakin mau dihapus?')"><i class="icon-trash"></i></a></li> -->
                                            <!-- <a onClick="return confirm('Are you sure want to delete this data?')"><i class="icon-trash"></i></a> -->
                                            <!-- <a href="#"><i class="icon-trash show_confirm"></i></a> -->
                                            <!-- <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button> -->
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('karyawan.edit', Crypt::encrypt($data->id)) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Layer 1</th>
                                    <th>Layer 2</th>
                                    <th>Layer 3</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{--  --}}
<script type="text/javascript">
function toggle_is_aktif(source) {
    checkboxes = document.getElementsByName('is_aktif[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_status(source) {
    checkboxes = document.getElementsByName('kode_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_wg_id(source) {
    checkboxes = document.getElementsByName('wg_id[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_level(source) {
    checkboxes = document.getElementsByName('kode_level[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
</script>
@endsection