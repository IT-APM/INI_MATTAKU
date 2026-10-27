@extends('layouts.simple.master')

@section('title', 'Formulir Reimbursement')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Reimbursement</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Diajukan Oleh</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $data['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $data['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $data['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $data['unit_kerja'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6 notification">
            <div class="card height-equal">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5 class="m-0">History</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
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
                                <h6>{{ $_history->_id_status->status }}<span class="dot-notification"></span></h6>
                                <p class="f-light">Oleh {{ $_history->_nik->nama }} / {{ $_history->nik_pic }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-header">
                    <h5>Resume</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Pengajuan Ke</td>
                                <td width="60%">{{ $data['ke'] }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td>{{ $trxM->created_at }}</td>
                            </tr>
                            <tr>
                                <td>Tahun</td>
                                <td>{{ $trxM->periode }}</td>
                            </tr>
                            @if($trxM->plafon>0)
                            <tr>
                                <td>Plafon</td>
                                <td>Ada</td>
                            </tr>
                            <tr>
                                <td>Jumlah Plafon</td>
                                <td>Rp. {{ number_format($trxM->plafon, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Plafon Telah Diambil</td>
                                <td>Rp. {{ number_format($trxM->plafon_diambil, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Sisa Plafon</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_1, 0) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. {{ number_format($trxM->klaim_kesehatan, 0) }} (Kesehatan)
                                    <br />Rp. {{ number_format($trxM->klaim_non_kesehatan, 0) }} (Non Kesehatan)
                                </td>
                            </tr>
                            @if($trxM->plafon>0)
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_2, 0) }} (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    @foreach ($lampiran as $value)
                                    <a href="{{ asset('assets/reimburse/'.$value->filename) }}" target="_blank">{{ $value->_id_lampiran->lampiran }}</a><br />
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{{ $trxM->catatan }}</td>
                            </tr>
                            @php
                                $where = ['id_trx' => $trxM->id, 'nik_pic' => App\Models\MtrUtility::find(12)->informasi];
                                $info = App\Models\TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                            @endphp
                            <tr>
                                <td>Catatan Oleh {{ App\Models\MtrUtility::find(51)->informasi }}</td>
                                <td>@if(!empty($info)) {{ $info->keterangan }} @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Hubungan Dengan Karyawan</th>
                                    <th>Layanan</th>
                                    <th>Tanggal Layanan</th>
                                    <th>Nominal Klaim</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trxD as $value)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $value->nama_pasien }}</td>
                                    <td>{{ $value->_id_hubungan->hubungan }}</td>
                                    <td>{{ $value->_id_layanan->layanan }}</td>
                                    <td>@if(!empty($value->tanggal)) {{ \Carbon\Carbon::parse($value->tanggal)->format('d-m-Y') }} @endif</td>
                                    <td>Rp. {{ number_format($value->jumlah_klaim, 0) }}</td>
                                    <td>
                                        <form action="{{ route('reimburse.updateDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id)) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" id="trxMId" name="trxMId" value="{{ (new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id_reimburse) }}" >
                                            <input type="hidden" id="_id_layanan" name="_id_layanan" value="{{ $value->id_layanan }}" >
                                            <input type="hidden" id="id_hubungan" name="id_hubungan" value="{{ $value->id_hubungan }}" >
                                            <input type="hidden" id="nama_pasien" name="nama_pasien" value="{{ $value->nama_pasien }}" >
                                            <input type="hidden" id="tanggal" name="tanggal" value="{{ $value->tanggal }}" >
                                            <input type="hidden" id="jumlah_klaim" name="jumlah_klaim" value="0" >
                                            <input type="hidden" id="delete" name="delete" value="1" >
                                            <ul class="action">
                                                <li class="edit"><a href="{{ route('reimburse.editDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id)) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br/><a href="{{ route('reimburse.index') }}" class="btn btn-warning" type="button">Back</a>
                </div>
            </div>
    </div>
    <!--  -->
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
</script>
@endsection