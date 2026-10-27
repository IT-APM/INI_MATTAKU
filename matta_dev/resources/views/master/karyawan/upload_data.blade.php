@extends('layouts.simple.master')
@section('title', 'Upload Data Karyawan')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Upload Data Master Karyawan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Karyawan</li>
<li class="breadcrumb-item active">Upload Data</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
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
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('karyawan.upload_data_xlsx', 0) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                            @csrf
                            <input type="hidden" id="jenis" name="jenis" value="data_master_karyawan">
                            <label class="h6">Data Master Karyawan</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input name="template" id="template" class="form-control" type="file" required="">
                                    <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                    <br/><a href="{{ asset('assets/template/template_upload_karyawan_v4.1.xlsx') }}" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    // 
</script>
@endsection