@extends('layouts.simple.master')
@section('title', 'Formulir Karyawan Non Aktif')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Karyawan Non Aktif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            @if(Session::has('msg'))
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6>{{ session('msg') }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Karyawan</h5>
                            </div>
                            <div class="card-header">
                                <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $pengaju['nama'] }}</td>
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
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Non Aktif</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Alasan Non Aktif</td>
                                            <td width="60%">{{ $trxM->_alasan->alasan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Bergabung</td>
                                            <td>@if(!empty($karyawan_properties->tanggal_bergabung)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_bergabung)->format('d-m-Y') }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Berakhir</td>
                                            <td>@if(!empty($trxM->tanggal)) {{ \Carbon\Carbon::parse($trxM->tanggal)->format('d-m-Y') }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>{!! $trxM->keterangan !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Serah Terima</h5>
                            </div>
                            @foreach ($trxD as $value)
                            <div class="card-body typography">
                                @php
                                $isReject = false;
                                if($value->_into_ == 'di'){
                                    $lable = 'Direktorat / Divisi / Departemen';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 6 = Reject by PIC DIVISI
                                    // 7 = Resubmit to PIC DIVISI
                                    // 8 = Approve by PIC DIVISI
                                    $whereIn = [6, 7, 8];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 6){
                                        $isReject = true;
                                    }
                                }elseif($value->_into_ == 'ga'){
                                    $lable = 'General Affairs';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 10 = Reject by PIC GA
                                    // 11 = Resubmit to PIC GA
                                    // 12 = Approve by PIC GA
                                    $whereIn = [10, 11, 12];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 10){
                                        $isReject = true;
                                    }
                                }elseif($value->_into_ == 'it'){
                                    $lable = 'IT';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 14 = Reject by PIC IT
                                    // 15 = Resubmit to PIC IT
                                    // 16 = Approve by PIC IT
                                    $whereIn = [14, 15, 16];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 14){
                                        $isReject = true;
                                    }
                                }elseif($value->_into_ == 'ko'){
                                    $lable = 'Koperasi Karyawan';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 18 = Reject by PIC KOPERASI
                                    // 19 = Resubmit to PIC KOPERASI
                                    // 20 = Approve by PIC KOPERASI
                                    $whereIn = [18, 19, 20];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 18){
                                        $isReject = true;
                                    }
                                }elseif($value->_into_ == 'hc'){
                                    $lable = 'People & Culture';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 22 = Reject by PIC HC
                                    // 23 = Resubmit to PIC HC
                                    // 24 = Approve by PIC HC
                                    $whereIn = [22, 23, 24];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 22){
                                        $isReject = true;
                                    }
                                }elseif($value->_into_ == 'am'){
                                    $lable = 'Application Management';
                                    $where = ['id_trx' => $value->id_trx, 'nik_pic' => $value->kepada];
                                    // 28 = Reject by PIC Application Management
                                    // 29 = Resubmit to PIC Application Management
                                    // 30 = Approve by PIC Application Management
                                    $whereIn = [28, 29, 30];
                                    $log = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', $whereIn)->orderBy('id', 'desc')->first();
                                    if(!empty($log) && $log->id_status == 28){
                                        $isReject = true;
                                    }
                                }
                                @endphp
                                <h6>{{ $lable }} @if($isReject && auth()->user()->nik == $trxM->pengaju)<a href="{{ route('nonaktif.edit', [$value->id, 'back_url' => URL::full()]) }}" title="Edit"><i class="icon-pencil-alt"></i></a>@endif</h6>
                                <table class="table table-striped">
                                    <tbody>
                                        @php $kepada = json_decode($value->kepada_property, true) @endphp
                                        <tr>
                                            <td>Kepada</td>
                                            <td>{{ $kepada['nama'] }}/{{ $kepada['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td width="30%">Obyek</td>
                                            <td width="70%">{{ $value->_obyek->obyek }}</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>{!! $value->keterangan !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Catatan PIC</td>
                                            <td>@if(!empty($log)){{ $log->keterangan }}@endif</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                        </div><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 notification box-col-6">
                    <div class="card height-equal">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                @php
                                    // status
                                    // 31=Statement Of Compliance
                                    // 1=Submit to All PIC
                                    // 8=Approve by PIC DIVISI
                                    // 12=Approve by PIC GA
                                    // 16=Approve by PIC IT
                                    // 20=Approve by PIC KOPERASI
                                    // 24=Approve by PIC P&C
                                    // 30=Approve by PIC Application Management
                                    // 4=Done All
                                    $disable=[4,8,12,16,20,24];
                                    if($trxD->where(['id_trx'=>$trxM->id, '_into_'=>'am'])->isNotEmpty()){
                                        $disable=[4,8,12,16,20,24,30];
                                    }
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    $remains = array_values($remains);
                                @endphp
                                @foreach ($remains as $remain)
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\NonaktifStatus::find($remain)->status }}</h6>
                                        @if($remain == 4)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->atasan)->first()->nama }} / {{ $trxM->atasan }}</p>
                                        @elseif($remain == 8)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'di')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
                                        @elseif($remain == 12)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'ga')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
                                        @elseif($remain == 16)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'it')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
                                        @elseif($remain == 20)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'ko')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
                                        @elseif($remain == 24)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'hc')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
                                        @elseif($remain == 30)
                                            @foreach ($trxD as $value)
                                            @if($value->_into_ == 'am')
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                            @endif
                                            @endforeach
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
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background">{{ $_history->created_at }}</span>
                                        </p>
                                        <h6>{{ $_history->_status->status }}<span class="dot-notification"></span>
                                        </h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                            {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection