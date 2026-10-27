@extends('layouts.simple.master')

@section('title', 'Peserta')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Peserta</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
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
                                    <th>Peserta</th>
                                    <th>Jenis Pelatihan</th>
                                    <th>Judul Pelatihan/Sertifikasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trx as $data)
                                @php
                                    $nik_property = json_decode($data->nik_property, true);
                                    // check apakah sudah melakukan evaluasi
                                    $check = App\Models\TrxPelatihanPenilaian::where(['id_peserta'=>$data->id])->whereNotIn('tipe', ['readonly'])->whereNotNull('jawaban')->exists();
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $nik_property['nama'] }} / {{ $nik_property['nik'] }}</td>
                                    <td>@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach</td>
                                    <td>{{ $data->_trx->judul }}</td>
                                    <td>
                                        <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sertifikat'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'back_url' => url()->full()]) }}" data-toggle="tooltip" title="Detail"><i class="fa fa-file-text-o"></i></a>
                                        &nbsp;<a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('evaluasi'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title="Evaluasi" target="_blank"><i class="icon-eye"></i></a>
                                        @if($check)<br/><a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('evaluasi_pdf'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)]) }}" data-toggle="tooltip" title="Evaluasi PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>@endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ $back_url }}" class="btn btn-warning" type="button">Back</a>
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