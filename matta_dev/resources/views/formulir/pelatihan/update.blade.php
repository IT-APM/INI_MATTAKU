@extends('layouts.simple.master')
@section('title', 'Formulir Pengajuan Pelatihan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Update</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pengajuan Pelatihan</li>
<li class="breadcrumb-item active">Update</li>
@endsection
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
@section('content')
<div class="container-fluid">
    {{-- <div class="row"> --}}
        <div class="col-sm-12">
            {{-- <div class="card">
                <div class="card-body"> --}}
                    <div class="row">
                        {{-- start --}}
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Pengaju</h5>
                                </div>
                                <div class="card-header">
                                    <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama</td>
                                                <td width="60%">{{ $pengaju['nama'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td>{{ $pengaju['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $pengaju['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat/Divisi/Departemen</td>
                                                <td>{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            {{-- </div>
                        </div> --}}
                        {{-- end --}}
                        {{-- start --}}
                        {{-- <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Atasan Pengaju</h5>
                                </div>
                                <div class="card-header">
                                    <a href="{{ asset('assets/images/matta/karyawan/'.$atasan_pengaju['nik'].'/'.$atasan_pengaju['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$atasan_pengaju['nik'].'/'.$atasan_pengaju['nik'].'.jpg') }}" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama</td>
                                                <td width="60%">{{ $atasan_pengaju['nama'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td>{{ $atasan_pengaju['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $atasan_pengaju['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat/Divisi/Departemen</td>
                                                <td>{{ $atasan_pengaju['divisi'] . '/' . $atasan_pengaju['dep'] . '/' . $atasan_pengaju['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                        {{-- end --}}
                        {{-- start --}}
                        {{-- <div class="col-sm-6">
                            <div class="card"> --}}
                                <div class="card-header">
                                    <h5>Peserta</h5>
                                </div>
                                @foreach($arrPeserta as $value)
                                <div class="card-header">
                                    <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama</td>
                                                <td width="60%">{{ $value['nama'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td>{{ $value['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $value['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat/Divisi/Departemen</td>
                                                <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                            {{-- </div>
                        </div> --}}
                        {{-- end --}}
                        {{-- start --}}
                        {{-- <div class="col-sm-6">
                            <div class="card"> --}}
                                <div class="card-header">
                                    <h5>Atasan Peserta</h5>
                                </div>
                                @foreach($arrAtasanPeserta as $value)
                                <div class="card-header">
                                    <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" alt="user"></a>
                                </div>
                                <div class="card-body typography">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td width="40%">Nama</td>
                                                <td width="60%">{{ $value['nama'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td>{{ $value['nik'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td>{{ $value['jabatan'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Direktorat/Divisi/Departemen</td>
                                                <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- end --}}
                        {{-- start --}}
                        <div class="col-sm-6 notification">
                            <div class="card">
                                <div class="card-header">
                                    <h5>History</h5>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        @foreach($history as $_history)
                                        <li class="d-flex">
                                            @if ($loop->first)
                                            <div class="activity-dot-success"></div>
                                            @elseif ($loop->last)
                                            <div class="activity-dot-warning"></div>
                                            @else
                                            <div class="activity-dot-primary"></div>
                                            @endif
                                            <div class="ms-3">
                                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                                <h6>{{ $_history->_status->status_pengajuan }}<span class="dot-notification"></span></h6>
                                                <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                        @method('PATCH')
                                        @csrf
                                        {{-- <div class="row">
                                            <div class="col-sm-6"> --}}
                                                <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                                                <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @foreach($status_pengajuan as $data)
                                                    <option value="{{ $data->id }}" @if($data->id == $trx->status_pengajuan) selected @endif >{{ $data->status_pengajuan }}</option>
                                                    @endforeach
                                                </select>
                                            {{-- </div>
                                        </div> --}}
                                        <button class="btn btn-success mt-2 mb-2" type="submit">Update</button> | <a href="{{ route('pelatihan.index') }}" class="btn btn-warning mt-2 mb-2" type="button">Back</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- end --}}
                    </div>
                {{-- </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
// 
</script>
@endsection