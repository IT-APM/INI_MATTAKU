@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Izin Sakit')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Izin Sakit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Tahun Izin Sakit</td>
                                            <td>{{ $trxSakit->periode_sakit }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mengajukan izin sakit pada</td>
                                            <td>{{ $arrTanggal['tanggal_mulai'] }}<br />sampai dengan<br />{{ $arrTanggal['tanggal_akhir'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal izin sakit</td>
                                            <td>
                                                @foreach ($arrTanggal['arrTanggalSakit'] as $value)
                                                {{ $value }}<br />
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Masuk bekerja kembali</td>
                                            <td>{{ $arrTanggal['tanggal_masuk'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alasan pengajuan izin sakit</td>
                                            <td>{!! $trxSakit->alasan !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>{!! $trxSakit->alamat !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor telepon</td>
                                            <td>{{ $trxSakit->telepon }}</td>
                                        </tr>
                                        <tr>
                                            <td>Lampiran</td>
                                            <td><a href="{{ asset('assets/sakit/'.$trxSakit->lampiran) }}" target="_blank"><i class="icon-file"></i>&nbsp;Download</a></td>
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
                                    // 1=Pengajuan oleh Karyawan
                                    // 2=Disetujui oleh PIC Pengganti
                                    // 4=Disetujui oleh Layer 1
                                    // 6=Selesai
                                    $remains = [];
                                    array_push($remains, [4=>$trxSakit->nik_atasan]);
                                    foreach(explode(';', $trxSakit->pic_pengganti) as $value){
                                        array_push($remains, [2=>$value]);
                                    }
                                    array_push($remains, [1=>$trxSakit->nik_karyawan]);
                                @endphp
                                @foreach ($remains as $remain)
                                    @foreach($remain as $key => $value)
                                    @if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty())
                                    <li class="d-flex">
                                        <div class="activity-dot-dark"></div>
                                        <div class="ms-3">
                                            <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\SakitStatus::find($key)->status_sakit }}</h6>
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value)->first()->nama }} / {{ $value }}</p>
                                        </div>
                                    </li>
                                    @endif
                                    @endforeach
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
                                        <h6>{{ $_history->_status->status_sakit }}<span class="dot-notification"></span>
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
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><a href="{{ asset('assets/images/matta/karyawan/'.$trxSakit->nik_karyawan.'/'.$trxSakit->nik_karyawan.'.jpg') }}"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="{{ asset('assets/images/matta/karyawan/'.$trxSakit->nik_karyawan.'/'.$trxSakit->nik_karyawan.'.jpg') }}"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span
                                                        class="f-light m-10">{{ $karyawan['nama'] }} / {{ $karyawan['nik'] }}<br />{{ $karyawan['jabatan'] }}
                                                        <br />{{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="{{ asset('assets/images/matta/karyawan/'.$trxSakit->nik_atasan.'/'.$trxSakit->nik_atasan.'.jpg') }}"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="{{ asset('assets/images/matta/karyawan/'.$trxSakit->nik_atasan.'/'.$trxSakit->nik_atasan.'.jpg') }}"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan</h6><span
                                                        class="f-light m-10">{{ $atasan['nama'] }} / {{ $atasan['nik'] }}<br />{{ $atasan['jabatan'] }}
                                                        <br />{{ $atasan['divisi'] }} / {{ $atasan['dep'] }} / {{ $atasan['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @foreach ($arrPicProperty as $value)
                                            <tr>
                                                <td><a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">PIC Pengganti</h6><span
                                                        class="f-light m-10">{{ $value['nama'] }} / {{ $value['nik'] }}<br />{{ $value['jabatan'] }}
                                                        <br />{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
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