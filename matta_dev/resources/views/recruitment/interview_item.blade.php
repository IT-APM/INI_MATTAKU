@extends('layouts.simple.master')

@section('title', 'Recruitment')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
<h3>Interview</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Interview</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h5>{{ session('msg') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload file is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('recruitment.interview_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                        <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label for="id" class="form-label">#ID</label>
                                    <input type="text" class="form-control" name="id" id="id" placeholder="ID" value="{{ $_trx->id_kebutuhan }}/{{ $_trx->id }}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="nama" class="form-label">Nama/Email Kandidat</label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama/Email" value="{{ $_trx->nama }}/{{ $_trx->email }}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="interviewer">Pewawancara</label>
                                    {{-- <select class="form-select col-sm-12" id="interviewer" name="interviewer" required="" disabled>
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if($data->nik == $trx->interviewer) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select> --}}
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="" disabled>
                                        {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if(in_array($data->nik, explode(';', $trx->interviewer))) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="metode" class="form-label">Metode</label>
                                    <input type="text" class="form-control" name="metode" id="metode" placeholder="metode" value="{{ $trx->metode }}" disabled>
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button> | <a href="{{ route('recruitment.applicant_list', 0) }}" class="btn btn-warning" type="button">Back</a>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label for="jenis" class="form-label">Tipe Wawancara</label>
                                    <input type="text" class="form-control text-uppercase" name="jenis" id="jenis" placeholder="Tipe Wawancara" value="{{ $trx->jenis }}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="" value="{{ $trx->check_in }}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" value="{{ $trx->check_out }}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="file_interview">File Interview</label>&nbsp;@if(!empty($trx->file_interview))<a href="{{ asset('assets/recruitment/upload/'.$trx->file_interview) }}" target="_blank" data-toggle="tooltip" title="File Interpretasi"><i class="fa fa-file-text-o"></i>@endif
                                    <input name="file_interview" id="file_interview" class="form-control" type="file">
                                    <label class="form-label">*Maksimal 20 MB (any format)</label>
                                </div>
                            </div>
                        </div>
                    </form>
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
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
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
// 
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
@endsection