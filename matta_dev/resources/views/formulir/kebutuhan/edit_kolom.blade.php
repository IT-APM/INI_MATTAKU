@extends('layouts.simple.master')
@section('title', 'Formulir Kebutuhan Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit {{ $field }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Edit {{ $field }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Edit {{ $field }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kebutuhan.post_kolom', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('POST')
                        @csrf
                        <input type="hidden" id="field" name="field" value="{{ $field }}">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Sebelum: Jumlah (Orang)*</label>
                                <input class="form-control" type="text" readonly="" value="{{ $trxM->jumlah }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="jumlah">Sesudah: Jumlah (Orang)*</label>
                                <input type="number" min="1" oninput="validity.valid||(value='');" name="jumlah" id="jumlah" class="form-control numbers" required="">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="form-label" for="keterangan">Catatan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ></textarea>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('kebutuhan.index') }}" class="btn btn-warning" type="button">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6 notification">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($history as $_history)
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
                                <table class="table">
                                    <tr>
                                        <th>Kolom</th>
                                        <td>{{ ucwords($field) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sebelum</th>
                                        <td>{{ $_history->sebelum }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sesudah</th>
                                        <td>{{ $_history->sesudah }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $_history->keterangan }}</td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
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