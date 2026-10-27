@extends('layouts.errors.master')
@section('title', 'Formulir Reimbursement')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Reimbursement</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                @if (count($errors) > 0)
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-4">
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
        <div class="col-sm-12 col-xl-5">
            <div class="card">
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
                                <td>Rp. {{ number_format($trxM->plafon, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Plafon Telah Diambil</td>
                                <td>Rp. {{ number_format($trxM->plafon_diambil, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Sisa Plafon</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_1, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. {{ number_format($trxM->klaim_kesehatan, 2) }} (Kesehatan)
                                    <br />Rp. {{ number_format($trxM->klaim_non_kesehatan, 2) }} (Non Kesehatan)
                                </td>
                            </tr>
                            @if($trxM->plafon>0)
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_2, 2) }} (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    @foreach ($lampiran as $value)
                                    <a href="{{ asset('assets/reimburse/'.$value->filename) }}"
                                        target="_blank">{{ $value->_id_lampiran->lampiran }}</a><br />
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{{ $trxM->catatan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  --><div class="col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body typography notification">
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
                                <p class="f-light">
                                    Oleh {{ $_history->_nik->nama }} / {{ $_history->nik_pic }}
                                    @if(!empty($_history->keterangan))<br/>Catatan {{ $_history->keterangan }}@endif
                                    @if(!empty($_history->filename))<br/><a href="{{ asset('assets/reimburse/'.$_history->filename) }}" target="_blank">Lampiran</a>@endif
                                </p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pasien</h5>
                </div>
                <div class="card-body typography">
                    <!-- start -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Nama Pasien</h6>
                                    </th>
                                    <th>
                                        <h6>Hubungan Dengan Karyawan</h6>
                                    </th>
                                    <th>
                                        <h6>Layanan</h6>
                                    </th>
                                    <th>
                                        <h6>Tanggal Layanan</h6>
                                    </th>
                                    <th>
                                        <h6>Nominal Klaim</h6>
                                    </th>
                                </tr>
                            </thead>
                            @foreach ($trxD as $value)
                            <tr>
                                <td>{{ $value->nama_pasien }}</td>
                                <td>{{ $value->_id_hubungan->hubungan }}</td>
                                <td>{{ $value->_id_layanan->layanan }}</td>
                                <td>{{ $value->tanggal }}</td>
                                <td>Rp. {{ number_format($value->jumlah_klaim, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- finish -->
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Catatan</h5>
                            <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <h5>Lampiran*</h5>
                            <label class="form-label">Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                            <input name="filename" id="filename" class="form-control" type="file">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @if(in_array(request('id_status'), [1,6]))<button type="submit" name="status_pengajuan" value="2" class="btn btn-success">Approve</button>@endif
                        @if(in_array(request('id_status'), [1,2]))<button type="submit" name="status_pengajuan" value="6" class="btn btn-primary">Hold</button>@endif
                        @if(in_array(request('id_status'), [2]))<button type="submit" name="status_pengajuan" value="7" class="btn btn-success">Paid</button>@endif
                        @if(in_array(request('id_status'), [1,6]))<button type="submit" name="status_pengajuan" value="3" class="btn btn-danger">Reject</button>@endif
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Reimbursement ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')

@endsection