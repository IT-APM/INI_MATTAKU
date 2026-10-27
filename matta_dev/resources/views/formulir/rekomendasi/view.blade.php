@extends('layouts.simple.master')

@section('title', 'Formulir Rekomendasi Karyawan')

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
<li class="breadcrumb-item active">Rekomendasi Karyawan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <!-- <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab"> -->
                            <!-- start -->
                            <div class="row">
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Data Karyawan</h5>
                                            </div>
                                            <div class="card-header">
                                                <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                                    target="_blank"><img class="img-thumbnail" width="20%"
                                                        src="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                                        alt="user"></a>
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
                                                            <td>{{ $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal Bergabung</td>
                                                            <td>
                                                                @if(!empty($karyawan['tanggal_bergabung']))
                                                                {{ Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y') }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>{{ $karyawan['status_karyawan'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Level</td>
                                                            <td>{{ $karyawan['level'] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-header">
                                                <h5>Rekomendasi dari atasan langsung</h5>
                                            </div>
                                            <div class="card-header">
                                                <a href="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                                    target="_blank"><img class="img-thumbnail" width="20%"
                                                        src="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                                        alt="user"></a>
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
                                                            <td>{{ $atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
                                                    // 1=Recommended by L1
                                                    // 4=Approved by Approval-1
                                                    // 9=Approved by Approval-2
                                                    // 17=Approved by Approval-3
                                                    $disable=[1,4];
                                                    if(!empty($trxM->nik_reviewer2)){
                                                        $disable=[1,4,9];
                                                    }elseif(!empty($trxM->nik_reviewer3)){
                                                        $disable=[1,4,9,17];
                                                    }
                                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                                    rsort($remains);
                                                @endphp
                                                @foreach ($remains as $remain)
                                                <li class="d-flex">
                                                    <div class="activity-dot-dark"></div>
                                                    <div class="ms-3">
                                                        <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\RekomendasiStatus::find($remain)->status_pengajuan }}</h6>
                                                        @if($remain == 4)
                                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->nik_reviewer1)->first()->nama }} / {{ $trxM->nik_reviewer1 }}</p>
                                                        @elseif($remain == 9)
                                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->nik_reviewer2)->first()->nama }} / {{ $trxM->nik_reviewer2 }}</p>
                                                        @elseif($remain == 17)
                                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->nik_reviewer3)->first()->nama }} / {{ $trxM->nik_reviewer3 }}</p>
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
                                                        <h6>{{ $_history->_status->status_pengajuan }}<span
                                                                class="dot-notification"></span></h6>
                                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                                            {{ $_history->nik_pic }}</p>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-header">
                                            <h5>Para Pihak</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="appointment-table table-responsive">
                                                <table class="table table-bordernone">
                                                    <tbody>
                                                        <!-- karyawan -->
                                                        <tr>
                                                            <td><a href="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Karyawan</h6><span
                                                                    class="f-light m-10">{{ $karyawan['nama'] }} / {{ $karyawan['nik'] }}<br />{{ $karyawan['jabatan'] }}<br />{{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- atasan -->
                                                        <tr>
                                                            <td><a href="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Atasan</h6><span
                                                                    class="f-light m-10">{{ $atasan['nama'] }} / {{ $atasan['nik'] }}<br />{{ $atasan['jabatan'] }}
                                                                    <br />{{ $atasan['divisi'] }} / {{ $atasan['dep'] }} / {{ $atasan['sub_dep'] }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- reviewer1 -->
                                                        <tr>
                                                            <td><a href="{{ asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg') }}"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="{{ asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg') }}"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 1</h6><span
                                                                    class="f-light m-10">{{ $reviewer1['nama'] }} / {{ $reviewer1['nik'] }}<br />{{ $reviewer1['jabatan'] }}
                                                                    <br />{{ $reviewer1['divisi'] }} / {{ $reviewer1['dep'] }} / {{ $reviewer1['sub_dep'] }}</span>
                                                            </td>
                                                        </tr>
                                                        <!-- reviewer2 -->
                                                        @if (!empty($reviewer2['nik']))
                                                        <tr>
                                                            <td><a href="{{ asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg') }}"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="{{ asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg') }}"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 2</h6><span
                                                                    class="f-light m-10">{{ $reviewer2['nama'] }} / {{ $reviewer2['nik'] }}<br />{{ $reviewer2['jabatan'] }}
                                                                    <br />{{ $reviewer2['divisi'] }} / {{ $reviewer2['dep'] }} / {{ $reviewer2['sub_dep'] }}</span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <!-- reviewer3 -->
                                                        @if (!empty($reviewer3['nik']))
                                                        <tr>
                                                            <td><a href="{{ asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg') }}"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="{{ asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg') }}"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 3</h6><span
                                                                    class="f-light m-10">{{ $reviewer3['nama'] }} / {{ $reviewer3['nik'] }}<br />{{ $reviewer3['jabatan'] }}
                                                                    <br />{{ $reviewer3['divisi'] }} / {{ $reviewer3['dep'] }} / {{ $reviewer3['sub_dep'] }}</span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                            <!-- start -->
                            <div class="row">
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Mengajukan</h5>
                                            </div>
                                            <div class="card-body typography">
                                                @php $no=0; @endphp
                                                @foreach ($trxD as $value)
                                                    {{ ++$no }}. {{ $value->jenis_pengajuan }} 
                                                    @if($value->jenis_pengajuan == 'Insentif')
                                                        {{ $value->sebelum }} selama {{ $value->setelah }} bulan <br/> 
                                                    @elseif($value->jenis_pengajuan == 'Nilai Prosentase Kenaikan Gaji')
                                                        {{ $value->setelah }} %<br/> 
                                                    @else
                                                        dari {{ $value->sebelum }} menjadi {{ $value->setelah }} <br/> 
                                                    @endif
                                                @endforeach
                                                {{-- <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <h6>Jenis Pengajuan</h6>
                                                            </th>
                                                            <th>
                                                                <h6>Sebelum</h6>
                                                            </th>
                                                            <th>
                                                                <h6>Setelah</h6>
                                                            </th>
                                                            <th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($trxD as $value)
                                                        <tr>
                                                            <td>{{ $value->jenis_pengajuan }}</td>
                                                            <td>{{ $value->sebelum }}</td>
                                                            <td>{{ $value->setelah }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-xxl-6 box-col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Tanggal</h5>
                                        </div>
                                        <div class="card-body typography">
                                            Terhitung mulai tanggal
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d', $trxM->terhitung_tanggal)->format('d-m-Y') }}

                                        </div>
                                        <div class="card-header">
                                            <h5>Dasar pengajuan</h5>
                                        </div>
                                        <div class="card-body typography">
                                            {!! $trxM->dasar_pengajuan !!}
                                        </div>
                                        <div class="card-header">
                                            <h5>Catatan pengajuan</h5>
                                        </div>
                                        <div class="card-body typography">
                                            {!! $trxM->catatan_pengajuan !!}
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                                        <p>&nbsp;</p>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- end -->
                        <!-- </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection