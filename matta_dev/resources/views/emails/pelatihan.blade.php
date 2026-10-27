@extends('layouts.errors.master')
@section('title', 'Formulir Pengajuan Pelatihan')

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
                        <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Pengajuan Pelatihan</h4>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Pengaju</h5>
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
                                    <td>Divisi/SBU/Unit Kerja</td>
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
                        <h5>Data Peserta</h5>
                    </div>
                    @foreach ($arrPeserta as $value)
                    <!-- start -->
                    <div class="card-header">
                        <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                            target="_blank"><img class="img-thumbnail" width="20%"
                                src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                alt="user"></a>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Nama</td>
                                    <td width="70%">{{ $value['nama'] }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>{{ $value['nik'] }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>{{ $value['jabatan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Direktorat/Divisi/Departemen</td>
                                    <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- end -->
                    @endforeach
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Pelatihan</h5>
                    </div>
                    <div class="card-body typography">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Pengajuan Pelatihan ke</td>
                                    <td width="70%">{{ $trx->ke }}</td>
                                </tr>
                                <tr>
                                    <td>Tahun</td>
                                    <td>{{ $trx->tahun }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td>
                                        @foreach ($arrJenis as $value)
                                        {{ $value->jenis }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @if(!empty($trx->int_ext))
                                <tr>
                                    <td>Mode</td>
                                    <td>
                                        @foreach ($int_ext as $key => $value)
                                        @if($key == $trx->int_ext) {{ $value }} @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Judul</td>
                                    <td>{{ $trx->judul }}</td>
                                </tr>
                                <tr>
                                    <td>Penyelenggara</td>
                                    <td>{{ $trx->penyelenggara }}</td>
                                </tr>
                                @php
                                $tanggal_range = [];
                                if(!empty($trx->tanggal_range)){
                                    $tanggal_range = json_decode($trx->tanggal_range, true);
                                    $formatted_ranges = array_map(function($range) {
                                        [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                        return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                    }, $tanggal_range);
                                }
                                @endphp
                                <tr>
                                    <td>Tanggal Pelaksanaan</td>
                                    {{-- <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y') }} s/d {{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y') }}</td> --}}
                                    @if(empty($trx->tanggal_range))
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y') }} s/d {{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y') }}</td>
                                    @else
                                    <td>@foreach ($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Tempat</td>
                                    <td>{{ $trx->tempat }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya</td>
                                    <td>Rp. {{ number_format($trx->biaya, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>Benefit</td>
                                    <td>
                                        @foreach ($arrBenefit as $value)
                                        {{ $value->benefit }} @if(!$loop->last) <br/> @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anggaran</td>
                                    <td>{{ $trx->_anggaran->anggaran }}</td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>{{ $trx->keterangan }}</td>
                                </tr>
                                @php $filePath = public_path('assets/pelatihan/' . $trx->lampiran); @endphp
                                <tr>
                                    <td>Lampiran</td>
                                    <td>
                                        @if(!empty($trx->lampiran) && file_exists($filePath))
                                            <a href="{{ asset('assets/pelatihan/' . $trx->lampiran) }}" target="_blank">Lihat Lampiran</a>
                                        @else
                                            Tidak ada lampiran
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td>{!! $trx->catatan !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header">
                        <h5>Pelatihan</h5>
                    </div>
                    <div class="card-body typography notification">
                        <ul>
                            @php
                                // status
                                // 1=Submit by Requestor
                                // 2=Approve by L1 Requestor
                                // 5=Approve by L1 Participants
                                // 10=Approve by Reviewer 1
                                // 14=Approve by Reviewer 2
                                // 11=Done by HC
                                $remains = [];
                                if(!empty($trx->reviewer2)){
                                    array_push($remains, [14=>$trx->reviewer2]);
                                }
                                if(!empty($trx->reviewer1)){
                                    array_push($remains, [10=>$trx->reviewer1]);
                                }
                                foreach(explode(';', $trx->atasan_peserta) as $value){
                                    array_push($remains, [5=>$value]);
                                }
                                array_push($remains, [2=>$trx->atasan_pengaju]);
                            @endphp
                            @foreach ($remains as $remain)
                                @foreach($remain as $key => $value)
                                @if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty())
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\PelatihanStatus::find($key)->status_pengajuan }}</h6>
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
                                    <h6>{{ $_history->_status->status_pengajuan }}<span
                                            class="dot-notification"></span></h6>
                                    <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                        {{ $_history->nik_pic }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
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
                    {{-- <input type="hidden" id="keterangan" name="keterangan" value="keterangan"> --}}
                    <textarea class="form-control mb-2" id="keterangan" name="keterangan" rows="4" ></textarea>
                    {{-- <br/> --}}
                    <button type="submit" name="status_pengajuan" value="{{ $approve }}" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_pengajuan" value="{{ $reject }}" class="btn btn-danger">Reject</button>
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
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Rekomendasi
                            Karyawan
                            ini.</h6>
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
    <input type="hidden" id="status_pengajuan" name="status_pengajuan" value="{{ $approve }}">
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
    <input type="hidden" id="status_pengajuan" name="status_pengajuan" value="{{ $reject }}">
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