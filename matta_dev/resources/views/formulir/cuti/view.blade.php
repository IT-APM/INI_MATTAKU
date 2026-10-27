@extends('layouts.simple.master')
@section('title', 'Formulir Permohonan Cuti')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
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
                                            <td width="40%">Tipe Cuti</td>
                                            <td width="60%">{{ $trxCuti->_tipe->tipe_cuti }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tahun Periode Cuti</td>
                                            <td>{{ $trxCuti->periode_cuti }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mengajukan cuti pada</td>
                                            <td>{{ $arrTanggal['tanggal_mulai'] }}<br />sampai
                                                dengan<br />{{ $arrTanggal['tanggal_akhir'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah hari cuti</td>
                                            <td>{{ $trxCuti->jumlah_hari }}</td>
                                        </tr>
                                        {{-- 12=Ibadah Umroh (7 hari) --}}
                                        @if($trxCuti->tipe_cuti == 12)
                                        {{-- 5 hari sebelum --}}
                                        <tr>
                                            <td>Tanggal sebelum</td>
                                            <td>
                                                @for ($x = 0; $x <5; $x++)
                                                    {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                                @endfor
                                            </td>
                                        </tr>
                                        {{-- 7 hari umroh --}}
                                        <tr>
                                            <td>Tanggal umroh</td>
                                            <td>
                                                @for ($x = 5; $x <12; $x++)
                                                    {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                                @endfor
                                            </td>
                                        </tr>
                                        {{-- 3 hari sesudah --}}
                                        <tr>
                                            <td>Tanggal sesudah</td>
                                            <td>
                                                @for ($x = 12; $x <15; $x++)
                                                    {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                                @endfor
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>Tanggal cuti</td>
                                            <td>
                                                @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                                {{ $value }}<br />
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endif
                                        {{-- <tr>
                                            <td>Tanggal cuti</td>
                                            <td>
                                                @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                                {{ $value }}<br />
                                                @endforeach
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td>Masuk bekerja kembali</td>
                                            <td>{{ $arrTanggal['tanggal_masuk'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alasan pengajuan cuti</td>
                                            <td>{!! $trxCuti->alasan !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>{!! $trxCuti->alamat !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor telepon</td>
                                            <td>{{ $trxCuti->telepon }}</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>{!! $trxCuti->keterangan !!}</td>
                                        </tr>
                                        @php
                                        $where = ['id_trx' => $trxCuti->id];
                                        $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                                        @endphp
                                        <tr>
                                            <td>Catatan Atasan</td>
                                            <td>@if(!empty($catatan)){{ $catatan->keterangan }}@endif</td>
                                        </tr>
                                        @php
                                        $where = ['id_trx' => $trxCuti->id];
                                        $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [8])->orderBy('id', 'desc')->first();
                                        @endphp
                                        @if(!empty($catatan))
                                        <tr>
                                            <td>Catatan {{ App\Models\MtrUtility::find(51)->informasi }}</td>
                                            <td>{{ $catatan->keterangan }}</td>
                                        </tr>
                                        @endif
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
                                    array_push($remains, [4=>$trxCuti->nik_atasan]);
                                    foreach(explode(';', $trxCuti->pic_pengganti) as $value){
                                        array_push($remains, [2=>$value]);
                                    }
                                    array_push($remains, [1=>$trxCuti->nik_karyawan]);
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
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                        <h6>{{ $_history->_status->status_cuti }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    {{-- pengajuan cuti dapat ditolak oleh hco jika semua approval selesai dan belum masuk waktunya cuti --}}
                    @php
                    $where = ['id_trx' => $trxCuti->id];
                    $catatan = App\Models\TrxCutiLog::where($where)->whereIn('id_status', [4,5])->orderBy('id', 'desc')->first();
                    $isPast = Carbon\Carbon::parse($trxCuti->tanggal_mulai)->isPast();
                    @endphp
                    @can('hc')
                    @if(!empty($catatan) && !$isPast)
                    <div class="card">
                        <div class="card-body typography">
                            <h5>Catatan</h5>
                            <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ route('cuti.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxCuti->id)) }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                                @method('PATCH')
                                @csrf
                                <input type="hidden" id="nik" name="nik" value="{{ auth()->user()->nik }}">
                                <input type="hidden" id="pic" name="pic" value="reject_hco">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                <br/>
                                <button type="submit" name="status_cuti" value="8" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endcan
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
                                                <td><a href="{{ asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg') }}"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="{{ asset('assets/images/matta/karyawan/'.$trxCuti->nik_karyawan.'/'.$trxCuti->nik_karyawan.'.jpg') }}"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span
                                                        class="f-light m-10">{{ $karyawan['nama'] }} / {{ $karyawan['nik'] }}<br />{{ $karyawan['jabatan'] }}
                                                        <br />{{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="{{ asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg') }}"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="{{ asset('assets/images/matta/karyawan/'.$trxCuti->nik_atasan.'/'.$trxCuti->nik_atasan.'.jpg') }}"
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