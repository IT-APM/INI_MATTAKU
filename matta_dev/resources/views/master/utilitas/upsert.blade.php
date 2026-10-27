@extends('layouts.simple.master')
@section('title', 'Utilitas')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Utilitas</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                {{--  --}}
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <h5>Utilitas</h5>
                    <form action="{{ route('utilitas.update', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('PATCH')
                    @csrf
                        <div class="row">
                            <div class="col-md-6">
                                @php $no=1; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6">
                                @php $no=5; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=6; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=7; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=12; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=13; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=14; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=15; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=16; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=17; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=20; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=23; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=18; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=19; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=21; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=22; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=42; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=24; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=46; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=25; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=26; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=27; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=28; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=38; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=31; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=33; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=29; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=37; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=39; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=40; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=41; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=43; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=44; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=53; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=47; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <textarea name="_{{ $no }}" id="_{{ $no }}" class="form-control" rows="5" required="">{{ $utilitas[$no-1]->informasi }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h5>Surat Teguran dan Peringatan</h5>
                    <form action="{{ route('utilitas.update_sp', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('POST')
                    @csrf
                        <div class="row">
                            <div class="col-md-6">
                                @php $no=1; @endphp
                                <label class="form-label" for="sp_{{ $no }}">{{ $reminder[$no-1]->judul }}</label>
                                <input name="sp_{{ $no }}" id="sp_{{ $no }}" class="form-control" type="text" required="" value="{{ $reminder[$no-1]->isi }}">
                            </div>
                            <div class="col-md-6">
                                @php $no=3; @endphp
                                <label class="form-label" for="sp_{{ $no }}">{{ $reminder[$no-1]->judul }}</label>
                                <input name="sp_{{ $no }}" id="sp_{{ $no }}" class="form-control" type="text" required="" value="{{ $reminder[$no-1]->isi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=2; @endphp
                                <label class="form-label" for="sp_{{ $no }}">{{ $reminder[$no-1]->judul }}</label>
                                <textarea name="sp_{{ $no }}" id="sp_{{ $no }}" class="form-control" rows="4" required="">{{ $reminder[$no-1]->isi }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    {{--  --}}
                    @can('payroll')
                    <hr>
                    <h5>Payroll</h5>
                    <form action="{{ route('utilitas.update_payroll', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('POST')
                    @csrf
                        <div class="row">
                            <div class="col-md-6">
                                @php $no=32; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6">
                                @php $no=36; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                @php $no=45; @endphp
                                <label class="form-label" for="_{{ $no }}">{{ $utilitas[$no-1]->keterangan }}</label>
                                <input name="_{{ $no }}" id="_{{ $no }}" class="form-control" type="text" required="" value="{{ $utilitas[$no-1]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    @endcan
                </div>
                {{--  --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
@endsection