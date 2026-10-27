@extends('layouts.errors.master')
@section('title', 'Formulir Karyawan Non Aktif')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Karyawan Non Aktif</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-4">
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
            <!--  -->
            <div class="col-sm-4">
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
                                    <td>Tanggal Berakhir</td>
                                    <td>{{ $trxM->tanggal }}</td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>{{ $trxM->keterangan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Serah Terima</h5>
                    </div>
                    @foreach ($trxD as $value)
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Obyek</td>
                                    <td width="70%">{{ $value->_obyek->obyek }}</td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>{{ $value->keterangan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
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
                                $trx_detail = App\Models\TrxNonaktifDetail::where(['id_trx'=>$trxM->id])->orderBy('id', 'desc')->get();
                                $disable=[4,8,12,16,20,24];
                                if($trx_detail->where(['id_trx'=>$trxM->id, '_into_'=>'am'])->isNotEmpty()){
                                    $disable=[4,8,12,16,20,24,30];
                                }
                                $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                $remains = array_values($remains);
                                // dd($remains);
                            @endphp
                            @foreach ($remains as $remain)
                            <li class="d-flex">
                                <div class="activity-dot-dark"></div>
                                <div class="ms-3">
                                    <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\NonaktifStatus::find($remain)->status }}</h6>
                                    @if($remain == 4)
                                    <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$trxM->atasan)->first()->nama }} / {{ $trxM->atasan }}</p>
                                    @elseif($remain == 8)
                                        @foreach ($trx_detail as $value)
                                        @if($value->_into_ == 'di')
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                        @endif
                                        @endforeach
                                    @elseif($remain == 12)
                                        @foreach ($trx_detail as $value)
                                        @if($value->_into_ == 'ga')
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                        @endif
                                        @endforeach
                                    @elseif($remain == 16)
                                        @foreach ($trx_detail as $value)
                                        @if($value->_into_ == 'it')
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                        @endif
                                        @endforeach
                                    @elseif($remain == 20)
                                        @foreach ($trx_detail as $value)
                                        @if($value->_into_ == 'ko')
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                        @endif
                                        @endforeach
                                    @elseif($remain == 24)
                                        @foreach ($trx_detail as $value)
                                        @if($value->_into_ == 'hc')
                                        <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value->kepada)->first()->nama }} / {{ $value->kepada }}</p>
                                        @endif
                                        @endforeach
                                    @elseif($remain == 30)
                                        @foreach ($trx_detail as $value)
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
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <h5>Catatan</h5>
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ></textarea>
                    <br/>
                    <button type="submit" name="status" value="{{ $approve }}" class="btn btn-success">Approve</button>
                    <button type="submit" name="status" value="{{ $reject }}" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!--  -->
        <!-- <div class="row">
            <div class="col-sm-6 text-end">
                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalApprove">Approve</button>
            </div>
            <div class="col-sm-6 text-starts">
                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalReject">Reject</button>
            </div>
        </div> -->
        <!--  -->
        <div class="row g-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body typography text-center">
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Karyawan Non Aktif ini.</h6>
                        <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                                height="40" alt="Alita" /></a><br />
                        <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<!-- Approve -->
<!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self">
    @method('PATCH')
    @csrf
    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
    <input type="hidden" id="status" name="status" value="{{ $approve }}">
    <div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                            required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </div>
        </div>
    </div>
</form> -->
<!-- Reject -->
<!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self">
    @method('PATCH')
    @csrf
    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
    <input type="hidden" id="status" name="status" value="{{ $reject }}">
    <div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                            required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>
</form> -->
@endsection

@section('script')
<script type="text/javascript">
// $(document).ready(function() {
//     $("#modalApprove").on('shown.bs.modal', function() {
//         $(this).find('#keterangan').focus();
//     });
//     $("#modalReject").on('shown.bs.modal', function() {
//         $(this).find('#keterangan').focus();
//     });
// });
</script>
@endsection