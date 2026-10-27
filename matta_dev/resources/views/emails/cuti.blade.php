@extends('layouts.errors.master')
@section('title', 'Formulir Permohonan Cuti')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Permohonan Cuti</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>DIAJUKAN OLEH</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                            alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $data['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $data['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $data['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $data['unit_kerja'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>TIPE CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%">{{ $trxCuti->_tipe->tipe_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td>{{ $trxCuti->periode_cuti }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>MASA CUTI</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Mengajukan cuti pada</td>
                                <td width="70%">{{ $arrTanggal['tanggal_mulai'] }}<br />sampai
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
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
                                </td>
                            </tr>
                            {{-- 7 hari umroh --}}
                            <tr>
                                <td>Tanggal umroh</td>
                                <td>
                                    @for ($x = 5; $x <12; $x++)
                                        {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                    @endfor
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
                                </td>
                            </tr>
                            {{-- 3 hari sesudah --}}
                            <tr>
                                <td>Tanggal sesudah</td>
                                <td>
                                    @for ($x = 12; $x <15; $x++)
                                        {{ $arrTanggal['arrTanggalCuti'][$x] }}<br />
                                    @endfor
                                    {{-- @foreach ($arrTanggal['arrTanggalCuti'] as $value)
                                    {{ $value }}<br />
                                    @endforeach --}}
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
                            <tr>
                                <td>Masuk bekerja kembali</td>
                                <td>{{ $arrTanggal['tanggal_masuk'] }}</td>
                            </tr>
                            <tr>
                                <td>Alasan pengajuan cuti</td>
                                <td>{{ $trxCuti->alasan }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $trxCuti->alamat }}</td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td>{{ $trxCuti->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{!! $trxCuti->keterangan !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
                <div class="card-header">
                    <h5>RESUME</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tipe Cuti</td>
                                <td width="70%">{{ $trxCuti->_tipe->tipe_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Periode Cuti</td>
                                <td>{{ $trxCuti->periode_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah hari cuti</td>
                                <td>{{ $trxCuti->jumlah_hari }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>PERSONEL PENGGANTI</h5>
                </div>
                @foreach ($arrPicProperty as $value)
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
                                <td>Email</td>
                                <td>{{ $value['email'] }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>{{ $value['telepon1'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $value['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
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
        </div>
        <!--  -->
        @if($pic == 'pic_pengganti')
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <button type="submit" name="status_cuti" value="2" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_cuti" value="7" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        @elseif($pic == 'nik_atasan')
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
                        <button type="submit" name="status_cuti" value="4" class="btn btn-success">Approve</button>
                        <button type="submit" name="status_cuti" value="5" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        </div>
        @elseif($pic == 'nik_hco')
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <button type="submit" name="status_cuti" value="6" class="btn btn-success">Informed</button>
                </form>
                </div>
            </div>
        </div>
        @endif
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Permohonan Cuti
                        ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                            height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')

@endsection