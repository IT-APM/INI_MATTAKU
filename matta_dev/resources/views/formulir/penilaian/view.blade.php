@extends('layouts.simple.master')
@section('title', 'Formulir Penilaian Karyawan')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Penilaian Karyawan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><img class="img-fluid img-100 rounded-circle" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt="matta"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">
                                                        {{ $penilaian->_tipe->tipe_penilaian }}<br />
                                                        {{ $penilaian->_status->status_penilaian }}<br />
                                                        Status Perpanjang:
                                                        {{ $penilaian->_perpanjang->informasi }} @if ($penilaian->is_perpanjang == 1) {{ $penilaian->bulan }} Bulan @endif
                                                    </h6>
                                                </td>
                                                {{-- <td><a href="{{ route('penilaian.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($penilaian->id)) }}" class="btn btn-primary" type="button">Update</a></td> --}}
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$penilaian->nik_karyawan.'/'.$penilaian->nik_karyawan.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span class="f-light m-10">{{ $karyawan['nama'] }} / {{ $karyawan['nik'] }}<br />{{ $karyawan['jabatan'] }}<br />{{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_1.'/'.$penilaian->nik_penilai_1.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 1</h6>
                                                    <span class="f-light m-10">{{ $penilai1['nama'] }} / {{ $penilai1['nik'] }}<br />{{ $penilai1['jabatan'] }}<br />{{ $penilai1['divisi'] }} / {{ $penilai1['dep'] }} / {{ $penilai1['sub_dep'] }}
                                                    <br />Kesimpulan dan Rekomendasi: {{ $penilaian->kes_rek_1 }}<br/>{{ $txt_perpanjang1 }}</span>
                                                </td>
                                            </tr>
                                            @if(!empty($penilaian->nik_penilai_2))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_2.'/'.$penilaian->nik_penilai_2.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 2</h6>
                                                    <span class="f-light m-10">{{ $penilai2['nama'] }} / {{ $penilai2['nik'] }}<br />{{ $penilai2['jabatan'] }}<br />{{ $penilai2['divisi'] }} / {{ $penilai2['dep'] }} / {{ $penilai2['sub_dep'] }}
                                                    <br />Kesimpulan dan Rekomendasi: {{ $penilaian->kes_rek_2 }}<br/>{{ $txt_perpanjang2 }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($penilaian->nik_penilai_3))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_3.'/'.$penilaian->nik_penilai_3.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 3</h6>
                                                    <span class="f-light m-10">{{ $penilai3['nama'] }} / {{ $penilai3['nik'] }}<br />{{ $penilai3['jabatan'] }}<br />{{ $penilai3['divisi'] }} / {{ $penilai3['dep'] }} / {{ $penilai3['sub_dep'] }}
                                                    <br />Kesimpulan dan Rekomendasi: {{ $penilaian->kes_rek_3 }}<br/>{{ $txt_perpanjang3 }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                                    // tipe
                                    // 1=Penilaian 180
                                    // 4=Penilaian 180-Reguler
                                    // 5=Penilaian 180-Assesment
                                    // 2=Penilaian 360-Reguler
                                    // 3=Penilaian 360-Assesment
                                    // 6=Penilaian Outsourcing
                                    // status
                                    // 1=Send to Employee
                                    // 2=Done by Employee
                                    // 3=Done By Reviewer 1
                                    // 4=Done by P&C (180)
                                    // 6=Done By Reviewer 2
                                    // 7=Done By Reviewer 3
                                    // 8=Done by P&C (360)
                                    // untuk tipe=[1,4,5] maka status=[1,2,3]
                                    $disable=[1,2,3];
                                    // untuk tipe=[2,3] maka status=[1,2,3,6,7]
                                    if(in_array($penilaian->id_tipe, [2,3])){
                                        $disable=[1,2,3,6,7];
                                        if(empty($penilaian->nik_penilai_3)){
                                            $disable=[1,2,3,6];
                                        }
                                    }
                                    // untuk tipe=[6] maka status=[3,6,7]
                                    elseif(in_array($penilaian->id_tipe, [6])){
                                        $disable=[3,6,7];
                                        if(empty($penilaian->nik_penilai_3)){
                                            $disable=[3,6];
                                        }
                                    }
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    rsort($remains);
                                    // $nik_hco = App\Models\MtrUtility::find(37)->informasi;
                                @endphp
                                @foreach ($remains as $remain)
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\MtrPenilaianStatus::find($remain)->status_penilaian }}</h6>
                                        @if($remain == 7)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$penilaian->nik_penilai_3)->first()->nama }} / {{ $penilaian->nik_penilai_3 }}</p>
                                        @elseif($remain == 6)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$penilaian->nik_penilai_2)->first()->nama }} / {{ $penilaian->nik_penilai_2 }}</p>
                                        @elseif($remain == 3)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$penilaian->nik_penilai_1)->first()->nama }} / {{ $penilaian->nik_penilai_1 }}</p>
                                        @elseif($remain == 2)
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$penilaian->nik_karyawan)->first()->nama }} / {{ $penilaian->nik_karyawan }}</p>
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
                                        <h6>{{ $_history->_status->status_penilaian }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection