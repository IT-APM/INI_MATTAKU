@extends('layouts.errors.master')
@section('title', 'Formulir Izin')

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
                        <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Izin</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-6">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-6">
                <div class="card">
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
                                    <td>Hari, Tanggal</td>
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
                                    <td>{{ $trx->alasan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
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
            <!--  -->
        </div><!--  -->
        <div class="row">
            <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <h5>Catatan</h5>
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
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Izin ini.</h6>
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