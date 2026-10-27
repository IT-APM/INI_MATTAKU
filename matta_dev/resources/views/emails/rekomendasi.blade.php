@extends('layouts.errors.master')
@section('title', 'Formulir Rekomendasi Karyawan')

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
                        <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30"
                                alt="Alita" />&nbsp;Formulir Rekomendasi Karyawan</h4>
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
                                    <td>Divisi / SBU / Unit Kerja</td>
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
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
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
                                    <td>{{ $atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep'] }}</td>
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
                    <div class="card-header">
                        <h5>History</h5>
                    </div>
                    <div class="card-body typography notification">
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
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Tanggal</h5>
                    </div>
                    <div class="card-body typography">
                        Terhitung mulai tanggal {{ Carbon\Carbon::parse($trxM->terhitung_tanggal)->format('d-m-Y') }}
                        <!-- Carbon\Carbon::createFromFormat('Y-m-d', $trxM->terhitung_tanggal)->format('d-m-Y') -->
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Dasar pengajuan</h5>
                    </div>
                    <div class="card-body typography">
                        {{ $trxM->dasar_pengajuan }}
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Catatan pengajuan</h5>
                    </div>
                    <div class="card-body typography">
                        {{ $trxM->catatan_pengajuan }}
                    </div>
                </div>
            </div>
        </div>
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
                                <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                <br/>
                                <button type="submit" name="status_rekomendasi" value="{{ $approve }}" class="btn btn-success">Approve</button>
                                <button type="submit" name="status_rekomendasi" value="{{ $reject }}" class="btn btn-danger">Reject</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <!-- <div class="row">
            <div class="col-sm-6 text-end">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalApprove">Approve</button>
            </div>
            <div class="col-sm-6 text-starts">
                <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalReject">Reject</button>
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
            <!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}"
                    target="_self">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <input type="hidden" id="status_rekomendasi" name="status_rekomendasi" value="{{ $approve }}">
                <div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                           <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                              <div class="mb-3">
                                 <textarea class="form-control" id="catatan" name="catatan" rows="4" required=""></textarea>
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
            <!-- <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}"
                    target="_self">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <input type="hidden" id="status_rekomendasi" name="status_rekomendasi" value="{{ $reject }}">
                <div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLabel2">Catatan</h5>
                           <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                              <div class="mb-3">
                                 <textarea class="form-control" id="catatan" name="catatan" rows="4" required=""></textarea>
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
<!-- <script type="text/javascript">
function konfirmasi(str) {
  let text;
  let note = prompt("Note", "");
  if (note == null) {
    return false;
  } else {
    if(str = 'approve'){
        document.getElementById("keterangan_approve").value = note;
    }else{
        document.getElementById("keterangan_reject").value = note;
    }
    return true;
  }
}
// $(document).ready(function(){
//     $("#modalApprove").on('shown.bs.modal', function(){
//         $(this).find('#catatan').focus();
//     });
//     $("#modalReject").on('shown.bs.modal', function(){
//         $(this).find('#catatan').focus();
//     });
// });
</script> -->
@endsection