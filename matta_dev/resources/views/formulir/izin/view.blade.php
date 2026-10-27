@extends('layouts.simple.master')

@section('title', 'Formulir Izin')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Izin</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            {{-- <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div> --}}
                            <div class="card-header">
                                <h5>Karyawan</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}" alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $karyawan['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $karyawan['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $karyawan['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Layer 1</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}" alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $atasan['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $atasan['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $atasan['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if(!empty($layer2))
                            <div class="card-header">
                                <h5>Layer 2</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}" alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $layer2['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $layer2['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $layer2['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $layer2['divisi'] . '/' . $layer2['dep'] . '/' . $layer2['sub_dep'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 notification box-col-4">
                    <div class="card height-equal">
                        <div class="card-header">
                            <h5>Data Izin</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Tipe Izin</td>
                                        <td width="60%">{{ $trx->_tipe->tipe }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td>{{ App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal) }}</td>
                                    </tr>
                                    @if($trx->tipe == 1 || $trx->tipe == 2)
                                    <tr>
                                        <td>Jam Mulai</td>
                                        <td>{{ $trx->jam_awal }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jam Selesai</td>
                                        <td>{{ $trx->jam_akhir }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Alasan</td>
                                        <td>{!! $trx->alasan !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                @php
                                    // status
                                    // 1=Submit by Requestor
                                    // 2=Approve by L1
                                    // 6=Approve by L2
                                    // 4=Done by P&C
                                    $disable=[1,2,6];
                                    if(empty($trx->layer2)){
                                        $disable=[1,2];
                                    }
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    rsort($remains);
                                @endphp
                                @foreach ($remains as $remain)
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\IjinStatus::find($remain)->status }}</h6>
                                        @if($remain == 2)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trx->atasan)->first()->nama }} / {{ $trx->atasan }}</p>
                                        @elseif($remain == 6)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trx->layer2)->first()->nama }} / {{ $trx->layer2 }}</p>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
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
                                        <h6>{{ $_history->_status->status }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- finish -->
            </div>
            @php
            $isPast = \Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal)->isPast();
            $isToday = \Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal)->isToday();
            // 3=Reject by L1
            // 7=Reject by L2
            // 8=Cancel by Requestor
            $isreject = App\Models\TrxIjinLog::where(['id_trx'=>$trx->id])->whereIn('id_status', [3,7,8])->exists();
            @endphp
            <form class="mb-2" id="_form" name="_form" method="post" enctype="multipart/form-data" action="{{ route('izin.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id)) }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                @method('PATCH')
                @csrf
                {{-- jika karyawan tersebut dan tidak backdate/today dan tidak direject--}}
                @if($karyawan['nik'] == auth()->user()->nik && !($isPast || $isToday) && !$isreject)
                <input type="hidden" id="nik" name="nik" value="{{ auth()->user()->nik }}">
                <input type="hidden" id="pic" name="pic" value="pengaju">
                <h5>Catatan</h5>
                <textarea class="form-control mb-2" id="keterangan" name="keterangan" rows="4" ></textarea>
                @endif
                <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a> @if($karyawan['nik'] == auth()->user()->nik && !($isPast || $isToday) && !$isreject) | <button type="submit" id="status" name="status" value="8" class="btn btn-danger">Cancel</button>@endif
            </form>
            {{-- <p>&nbsp;</p> --}}
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection