@extends('layouts.simple.master')

@section('title', 'Sertifikat')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Sertifikat</h3>
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
                    @php $nik_property = json_decode($trx->nik_property, true); @endphp
                    <div class="row">
                        <div class="col-md-4">
                            Peserta
                            <h6>{{ $nik_property['nama'] }} / {{ $nik_property['nik'] }}</h6>
                        </div>
                        <div class="col-md-4">
                            Jenis Pelatihan
                            <h6>@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach</h6>
                        </div>
                        <div class="col-md-4">
                            Judul Pelatihan/Sertifikasi
                            <h6>{{ $trx->_trx->judul }}</h6>
                        </div>
                    </div>
                    {{--  --}}
                    <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sertifikat_detail'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'back_url' => url()->full()]) }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    {{-- <th>Peserta</th> --}}
                                    {{-- <th>Jenis Pelatihan</th>
                                    <th>Judul Pelatihan/Sertifikasi</th> --}}
                                    <th>Nama Sertifikat</th>
                                    <th>No Sertifikat</th>
                                    <th>Penerbit</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Lulus</th>
                                    <th>Tanggal Berlaku</th>
                                    {{-- <th>File Kartu Anggota</th>
                                    <th>File Kartu Lisensi</th>
                                    <th>File Modul</th>
                                    <th>File Sertifikat Keahlian</th>
                                    <th>File Sertifikat Pelatihan</th> --}}
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_trx as $data)
                                @php $nik_property = json_decode($data->nik_property, true); @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    {{-- <td>{{ $nik_property['nama'] }} / {{ $nik_property['nik'] }}</td> --}}
                                    {{-- <td>@foreach($arrJenis as $value){{ $value->jenis }} @if(!$loop->last) <br/> @endif @endforeach</td>
                                    <td>{{ $data->judul }}</td> --}}
                                    <td>{{ $data->nama_sertifikat }}</td>
                                    <td>{{ $data->no_sertifikat }}</td>
                                    <td>{{ $data->penerbit }}</td>
                                    @php
                                    $tanggal_ujian = [];
                                    $formatted_ranges = [];
                                    if(!empty($data->tanggal_ujian)){
                                        $tanggal_ujian = json_decode($data->tanggal_ujian, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_ujian);
                                    }
                                    @endphp
                                    <td>@if(!empty($formatted_ranges)) @foreach($formatted_ranges as $_tanggal){{ $_tanggal }} @if(!$loop->last) <br/> @endif @endforeach @endif</td>
                                    <td>@if($data->is_lulus === "1") Lulus @elseif($data->is_lulus === "0") Tidak Lulus @endif</td>
                                    @php
                                    $tanggal_berlaku = [];
                                    $formatted_ranges = [];
                                    if(!empty($data->tanggal_berlaku)){
                                        $tanggal_berlaku = json_decode($data->tanggal_berlaku, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_berlaku);
                                    }
                                    @endphp
                                    <td>@if(!empty($formatted_ranges)) @foreach($formatted_ranges as $_tanggal){{ $_tanggal }} @if(!$loop->last) <br/> @endif @endforeach @endif</td>
                                    {{-- <td>@if(!empty($data->file_anggota)) <a href="{{ asset('assets/pelatihan/'.$data->file_anggota) }}" target="_blank">File Kartu Anggota</a> @endif</td>
                                    <td>@if(!empty($data->file_lisensi)) <a href="{{ asset('assets/pelatihan/'.$data->file_lisensi) }}" target="_blank">File Kartu Lisensi</a> @endif</td>
                                    <td>@if(!empty($data->file_modul))  <a href="{{ asset('assets/pelatihan/'.$data->file_modul) }}" target="_blank">File Modul</a> @endif</td>
                                    <td>@if(!empty($data->file_pelatihan))  <a href="{{ asset('assets/pelatihan/'.$data->file_pelatihan) }}" target="_blank">File Sertifikat Pelatihan</a> @endif</td>
                                    <td>@if(!empty($data->file_keahlian))  <a href="{{ asset('assets/pelatihan/'.$data->file_keahlian) }}" target="_blank">File Sertifikat Keahlian</a> @endif</td> --}}
                                    <td>
                                        <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sertifikat_update'), 'id_peserta' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'id_sertifikat' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'back_url' => url()->full()]) }}" data-toggle="tooltip" title="Detail"><i class="fa fa-file-text-o"></i></a>
                                        &nbsp;<a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('penilaian'), 'id_penilaian' => (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'back_url' => url()->full()]) }}" data-toggle="tooltip" title="Penilaian" target="_blank"><i class="icon-eye"></i></a>
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