@extends('layouts.simple.master')
@section('title', 'Data Master Pekerjaan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Pekerjaan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Data</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Bidang</td>
                                        <td width="60%">{{ $trx->_bidang->bidang }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pekerjaan</td>
                                        <td>{{ $trx->pekerjaan }}</td>
                                    </tr>
                                    {{--
                                    <tr>
                                        <td>Tugas-tugas</td>
                                        <td>{{ $trx->tugas }}</td>
                                    </tr>
                                    <tr>
                                        <td>Wewenang</td>
                                        <td>{{ $trx->wewenang }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggung jawab</td>
                                        <td>{{ $trx->tanggung_jawab }}</td>
                                    </tr>
                                    --}}
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>{{ $trx->keterangan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-12">
                    <div class="appointment">
                        <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
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
@endsection