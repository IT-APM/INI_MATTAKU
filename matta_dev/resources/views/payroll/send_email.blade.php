@extends('layouts.simple.master')
@section('title', 'Formulir Auto Penilaian Karyawan')

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
<h3>Payroll Email</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Email</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <ul class="nav nav-tabs nav-success" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-current-tab" data-bs-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="false">Current</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-history-tab" data-bs-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="true">History</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <form action="{{ route('payroll.upload_pemberitahuan_upah', 0) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <input name="template" id="template" class="form-control" type="file" required="">
                                    <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-success" data-bs-toggle="tooltip" title="Upload Daftar Penyesuaian Upah"><i class="fa fa-upload"></i> Upload</button> | <a href="{{ route('payroll.download_xlsx', 0) }}" class="btn btn-warning" data-bs-toggle="tooltip" title="Download Daftar Penyesuaian Upah"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                </div>
                            </div>
                            </form>
                            <form action="{{ route('payroll.send_email_exe') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            @csrf
                            <input type="hidden" id="tipe" name="tipe" value="current">
                            @if(count($trx_current)>0)
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute" data-bs-toggle="tooltip" title="Kirimkan Email"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete" data-bs-toggle="tooltip" title="Delete Email"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            @endif
                            <div class="table-responsive">
                                <table class="display" id="_datatable_current">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_current(this)" /></th>
                                            <th>No</th>
                                            <th>#ID</th>
                                            <th>Tahun</th>
                                            <th>Bulan</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Subject</th>
                                            {{-- <th>Email</th> --}}
                                            <th>Link</th>
                                            <th>PDF</th>
                                            <th>Created_at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no=0;
                                            $nik_reviewer = explode(',', App\Models\MtrUtility::find(45)->informasi)[0];
                                        @endphp
                                        @foreach ($trx_current as $data)
                                        <tr>
                                            @php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $email = json_decode($data->email, true);
                                                $exists = App\Models\TrxPayrollPenyesuaianUpahLog::where(['no_surat'=>$data->no_surat, 'nik'=>$data->nik, 'id_status'=>2, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun])->exists();
                                            @endphp
                                            <td>
                                                {{-- tidak digunakan --}}
                                                {{-- @if($data->id_addsub != '0')
                                                    <input id="id_current[]" name="id_current[]" type="checkbox" value="{{ $data->id }}">
                                                @elseif($data->id_addsub == '0' && !empty($data->param1))
                                                    @if($exists)
                                                        <input id="id_current[]" name="id_current[]" type="checkbox" value="{{ $data->id }}">
                                                    @endif
                                                @endif --}}
                                                <input id="id_current[]" name="id_current[]" type="checkbox" value="{{ $data->id }}">
                                            </td>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->tahun }}</td>
                                            <td>{{ $bulan[$data->bulan - 1] }}</td>
                                            <td>{{ $nik_property['nama'] }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $email['subject'] }}</td>
                                            {{-- <td>{!! $email['body'] !!}</td> --}}
                                            <td>
                                                @if(!empty($data->filename) && $data->id_addsub == 0)
                                                <a href="{{ route('payroll.review', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'pic' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($nik_reviewer), 'id_status' => 1 ]) }}</td>" target="_blank"><i class="fa fa-link"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($data->filename) && $data->id_addsub == 0)
                                                <a href="{{ asset('assets/penyesuaian_upah').'/'.$data->filename }}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ $data->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </form>
                        </div>
                        {{--  --}}
                        <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
                            {{-- @if(count($trx_history)>0)
                            <form action="{{ route('payroll.send_email_exe') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            @csrf
                            <input type="hidden" id="tipe" name="tipe" value="history">
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            @endif --}}
                            <div class="table-responsive">
                                <table class="display" id="_datatable_history">
                                    <thead>
                                        <tr>
                                            {{-- <th><input type="checkbox" onClick="toggle_status_history(this)" /></th> --}}
                                            <th>No</th>
                                            <th>Tahun</th>
                                            <th>Bulan</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Subject</th>
                                            {{-- <th>Email</th> --}}
                                            <th>PDF</th>
                                            <th>Created_at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no=0;
                                        @endphp
                                        @foreach ($trx_history as $data)
                                        <tr>
                                            @php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $email = json_decode($data->email, true);
                                            @endphp
                                            {{-- <td><input id="id_history[]" name="id_history[]" type="checkbox" value="{{ $data->id }}"></td> --}}
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->tahun }}</td>
                                            <td>{{ $bulan[$data->bulan - 1] }}</td>
                                            <td>{{ $nik_property['nama'] }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $email['subject'] }}</td>
                                            {{-- <td>{!! $email['body'] !!}</td> --}}
                                            <td>
                                                {{-- @if(!empty($data->filename) && $data->id_addsub == 0) --}}
                                                <a href="{{ asset('assets/penyesuaian_upah').'/'.$data->filename }}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                {{-- @endif --}}
                                            </td>
                                            <td>{{ $data->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- </form> --}}
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
// 
function toggle_status_current(source) {
    checkboxes = document.getElementsByName('id_current[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_history(source) {
    checkboxes = document.getElementsByName('id_history[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable_current').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
});
// 
$('#_datatable_history').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]],
});
// 
</script>
@endsection