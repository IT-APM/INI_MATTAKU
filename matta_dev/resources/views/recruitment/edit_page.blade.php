@extends('layouts.simple.master')

@section('title', 'Recruitment Edit')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp
@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body typography">
                            <h5>Kandidat</h5>
                            <form action="{{ route('recruitment.update', Crypt::encrypt($trx->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" id="back_url" name="back_url" value="{{ $back_url }}">
                            <input type="hidden" id="type" name="type" value="{{ $type }}">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%"><input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" required="" value="{{$trx->nama}}"></td>
                                    </tr>
                                    <tr>
                                        <td><label for="email" class="form-label">Email Aktif<span class="text-danger">*</span></label></td>
                                        <td><input type="email" class="form-control" name="email" id="email" placeholder="email@email.com" required="" value="{{$trx->email}}"></td>
                                    </tr>
                                    <tr>
                                        <td><label for="telepon" class="form-label">Telepon<span class="text-danger">*</span></label></td>
                                        <td><input type="text" class="form-control" name="telepon" id="telepon" placeholder="Telepon" required="" value="{{$trx->telepon}}"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ $back_url }}" class="btn btn-warning" type="button">Back</a>
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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script type="text/javascript">
    function use_number(node) {
      var empty_val = false;
      const value = node.value;
      if (node.value == '')
        empty_val = true;
      node.type = 'number';
      if (!empty_val)
        node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
    }
    // 
    function use_text(node) {
      var empty_val = false;
      const value = Number(node.value);
      if (node.value == '')
        empty_val = true;
      node.type = 'text';
      if (!empty_val)
        node.value = value.toLocaleString('en');  // or other formatting
    }
</script>
@endsection