@extends('layouts.simple.master')

@section('title', 'Dokumen')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Dokumen</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Kebutuhan Karyawan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kebutuhan Karyawan</th>
                                    <th>Nama Calon Karyawan</th>
                                    <th>Tanggal Interview</th>
                                    <th>File Interview</th>
                                    <th>Tanggal Psikotes</th>
                                    <th>File Psikotes</th>
                                    <th>Tanggal Negosiasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->_posisi->pekerjaan }}</td>
                                    <td>{{ $data->nama_karyawan }}</td>
                                    <td>@if(!empty($data->tanggal_interview)) {{ \Carbon\Carbon::parse($data->tanggal_interview)->format('d-m-Y') }} @endif</td>
                                    <td>@if(!empty($data->file_interview)) <a href="{{ asset('assets/kebutuhan/'.$data->file_interview) }}" target="_blank">File Interview</a> @endif</td>
                                    <td>@if(!empty($data->tanggal_psikotes)) {{ \Carbon\Carbon::parse($data->tanggal_psikotes)->format('d-m-Y') }} @endif</td>
                                    <td>@if(!empty($data->file_psikotes)) <a href="{{ asset('assets/kebutuhan/'.$data->file_psikotes) }}" target="_blank">File Psikotes</a> @endif</td>
                                    <td>@if(!empty($data->tanggal_negosiasi)) {{ \Carbon\Carbon::parse($data->tanggal_negosiasi)->format('d-m-Y') }} @endif</td>
                                    <td>
                                        <a href="{{ route('kebutuhan_detail.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)) }}" data-toggle="tooltip" title="Document"><i class="fa fa-file-text-o"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Kebutuhan Karyawan</th>
                                    <th>Nama Calon Karyawan</th>
                                    <th>Tanggal Interview</th>
                                    <th>File Interview</th>
                                    <th>Tanggal Psikotes</th>
                                    <th>File Psikotes</th>
                                    <th>Tanggal Negosiasi</th>
                                    <th>Detail</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <a href="{{ route('kebutuhan.index') }}" class="btn btn-warning" type="button">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection