@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>
    @if ($trxMaster->luar_dalam == 'ca_dalam') Cash Advance Perjalanan Dinas Dalam Negeri
    @elseif ($trxMaster->luar_dalam == 'ca_luar') Cash Advance Perjalanan Dinas Luar Negeri
    @endif
</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <!-- 8=CA Ditolak PC, 10=CA Ditolak L1, 12=CA Ditolak L2, 14=CA Ditolak PC -->
                    @if($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14)
                    <h5>Catatan Penolakan</h5>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <textarea class="form-control" rows="4">{{ $catatan }}</textarea>
                        </div>
                    </div>
                    <hr />
                    @endif
                    <h5>Pelaksana Perjalanan Dinas</h5>
                    @php
                    $pengaju_property = json_decode($trxMaster->pengaju_property, true);
                    $atasan_property = json_decode($trxMaster->atasan_property, true);
                    @endphp
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Karyawan</label>
                            <input class="form-control" value="{{ $pengaju_property['nama'] }} / {{ $pengaju_property['nik'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Atasan Langsung</label>
                            <input class="form-control" value="{{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Level</label>
                            <input class="form-control" value="{{ $pengaju_property['id_level'] }} - {{ $pengaju_property['level'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" value="{{ $pengaju_property['jabatan'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Perusahaan</label>
                            <input class="form-control" value="{{ $pengaju_property['perusahaan'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Direktorat</label>
                            <input class="form-control" value="{{ $pengaju_property['divisi'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Divisi</label>
                            <input class="form-control" value="{{ $pengaju_property['dep'] }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Departemen</label>
                            <input class="form-control" value="{{ $pengaju_property['sub_dep'] }}">
                        </div>
                    </div>
                    <hr />
                    <h5>Informasi Perjalanan Dinas</h5>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label">Tahun Pengajuan</label>
                            <input class="form-control" value="{{ $trxMaster->periode }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Golongan</label>
                            <input class="form-control" value="@if($trxMaster->is_leader==1) Leader @elseif($trxMaster->is_leader==2) Member @elseif($trxMaster->is_leader==3) Management @endif">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Kelompok Perjalanan Dinas</label>
                            <input class="form-control" @if(empty($trxMaster->kelompok_id)) value="Project: {{ $kelompok['project'] }} / Corporate: {{ $kelompok['nonproject'] }}" @else value="@if($trxMaster->is_project==1)Project: @else Corporate: @endif {{ $trxMaster->_kelompok->kelompok }}" @endif>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8">
                            <label class="form-label">Uraian Tugas Perjalanan Dinas</label>
                            <textarea class="form-control" rows="2">{{ $trxMaster->uraian }}</textarea>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Lampiran</label><br />
                            <a href="{{ asset('assets/bpd/'.$trxMaster->lampiran) }}" target="_blank"><i class="icon-file"></i>&nbsp;Download Lampiran</a>
                        </div>
                    </div>
                    <!-- 4=CA Disetujui Admin, 7=CA Disetujui PC, 9=CA Disetujui L1, 11=CA Disetujui L2, 13=CA Disetujui PC, 15=Perbaikan CA oleh Admin -->
                    @if($trxMaster->status_id==4 || $trxMaster->status_id==7 || $trxMaster->status_id==9 || $trxMaster->status_id==11 || $trxMaster->status_id==13 || $trxMaster->status_id==15)
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Tanggal dari</th>
                                            <th scope="col">Tanggal sampai</th>
                                            <th scope="col">Jumlah hari</th>
                                            <th scope="col">Uang saku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailInfo as $data)
                                        <tr>
                                            <td>{{ ++$no_info }}</td>
                                            @if($trxMaster->luar_dalam=='ca_dalam')
                                            <td>{{ $data->_tujuan1_dalam->propinsi }} - {{ $data->_tujuan2_dalam->kota }}</td>
                                            @elseif($trxMaster->luar_dalam=='ca_luar')
                                            <td>{{ $data->_tujuan1_luar->negara }}</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1 }}</td>
                                            <td>@if($data->is_approve==1) Ada @else Tidak ada @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-2">
                                <label class="form-label">Jenis BPD</label>
                                <input class="form-control" @if($trxMaster->is_project == 1) value="Project" @else value="Corporate" @endif">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Project ID</label>
                                <input class="form-control" value="{{ $trxMaster->_project->project_id }} / {{ $trxMaster->_project->name }} / {{ $trxMaster->_project->group }}">
                            </div>
                            @if($trxMaster->is_project == 1)
                            @php
                            $koordinator_property = json_decode($trxMaster->koordinator_property, true);
                            @endphp
                            <div class="col-sm-4">
                                <label class="form-label">Coordinator</label>
                                <input class="form-control" value="{{ $koordinator_property['nama'] }} / {{ $koordinator_property['nik'] }}">
                            </div>
                            @endif
                        </div>
                        <hr />
                        <h5>Biaya Perjalanan Dinas</h5>
                        @php
                        $total_p_idr = 0;
                        $total_p_usd = 0;
                        @endphp
                        @if(count($trxDetailBiayaPersonel)>0)
                        <h6>Biaya Personel</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_1">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col" width="40%">Keterangan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailBiayaPersonel as $data)
                                        @php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_p_idr += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_p_usd += $data->nominal * $data->jumlah;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$no_biaya_info }}</td>
                                            <td>{{ $kode_coa[0] }}</td>
                                            @if ($trxMaster->luar_dalam == 'ca_dalam')
                                            <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_dalam->propinsi }}) @endif</td>
                                            @elseif ($trxMaster->luar_dalam == 'ca_luar')
                                            <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_luar->negara }}) @endif</td>
                                            @endif
                                            <td>{{ $data->jumlah }}</td>
                                            <td>{{ number_format($data->nominal, 0) }}</td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Biaya Personel</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col">{{ number_format($total_p_idr, 0) }}<br/>{{ number_format($total_p_usd, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                        @php
                        $total_l_idr = 0;
                        $total_l_usd = 0;
                        @endphp
                        @if(count($trxDetailBiaya)>0)
                        <h6>Biaya Lain-lain</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_2">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col" width="40%">Keterangan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailBiaya as $data)
                                        @php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_l_idr += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_l_usd += $data->nominal * $data->jumlah;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$no_biaya }}</td>
                                            <td>{{ $kode_coa[0] }}</td>
                                            <td>{{ $kode_coa[1] }}</td>
                                            <td>{{ $data->jumlah }}</td>
                                            <td>{{ number_format($data->nominal, 0) }}</td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Cash Advance</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col">{{ number_format($total_p_idr+$total_l_idr, 0) }}<br/>{{ number_format($total_p_usd+$total_l_usd, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Biaya Lain-lain</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col">{{ number_format($total_l_idr, 0) }}<br/>{{ number_format($total_l_usd, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                        <form method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self"
                            class="needs-validation" novalidate=""
                            onsubmit="return confirm('Are you sure want to submit this data?');">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                            <h5>Catatan</h5>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <button type="submit" name="status_id" class="btn btn-success" value="{{ $approve }}">Approve</button>
                                    <button type="submit" name="status_id" class="btn btn-danger" value="{{ $reject }}">Reject</button>
                                    <!-- <a class="btn btn-warning" href="{{ route('bpd.index') }}">Back</a> -->
                                </div>
                            </div>
                        </form>
                    @endif
                    <!-- 2=status Pengajuan CA, 6=Perbaikan CA oleh Pengaju -->
                    @if($trxMaster->status_id == 2 || $trxMaster->status_id == 6)
                    <div class="row mb-3">
                        <div class="table-responsive">
                            <table class="table" id="table_info">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tujuan</th>
                                        <th scope="col">Tanggal dari</th>
                                        <th scope="col">Tanggal sampai</th>
                                        <th scope="col">Jumlah hari</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trxDetailInfo as $data)
                                    <tr>
                                        <td>{{ ++$no_info }}</td>
                                        @if($trxMaster->luar_dalam=='ca_dalam')
                                        <td>{{ $data->_tujuan1_dalam->propinsi }} - {{ $data->_tujuan2_dalam->kota }}</td>
                                        @elseif($trxMaster->luar_dalam=='ca_luar')
                                        <td>{{ $data->_tujuan1_luar->negara }}</td>
                                        @endif
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self"
                        class="needs-validation" novalidate=""
                        onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                        <!-- jika ada isian biaya -->
                        @if(count($trxDetailBiaya)>0)
                        <h5>Biaya Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Biaya</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailBiaya as $data)
                                        <tr>
                                            <td>{{ ++$no_biaya }}</td>
                                            <td>{{ $data->biaya }}</td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal, 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr />
                        @endif
                        <h5>Catatan</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <button type="submit" name="status_id" class="btn btn-success" value="{{ $approve }}">Process</button>
                                <button type="submit" name="status_id" class="btn btn-danger" value="{{ $reject }}">Reject</button>
                                <!-- <a class="btn btn-warning" href="{{ route('bpd.index') }}">Back</a> -->
                            </div>
                        </div>
                    </form>
                    <!-- 3=CA Diproses Admin, 8=CA Ditolak PC, 10=CA Ditolak L1, 12=CA Ditolak L2, 14=CA Ditolak PC -->
                    @elseif($trxMaster->status_id == 3 || $trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14)
                    <form method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self"
                        class="needs-validation" novalidate=""
                        onsubmit="return confirm('Are you sure want to submit this data?');">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                        <!--  -->
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Tanggal dari</th>
                                            <th scope="col">Tanggal sampai</th>
                                            <th scope="col">Jumlah hari</th>
                                            <th scope="col">Uang saku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailInfo as $data)
                                        <tr>
                                            <td>{{ ++$no_info }}</td>
                                            @if($trxMaster->luar_dalam=='ca_dalam')
                                            <td>{{ $data->_tujuan1_dalam->propinsi }} - {{ $data->_tujuan2_dalam->kota }}</td>
                                            @elseif($trxMaster->luar_dalam=='ca_luar')
                                            <td>{{ $data->_tujuan1_luar->negara }}</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1 }}</td>
                                            <td>
                                            <select class="form-select col-sm-12" id="uangsaku_{{ $data->id }}" name="uangsaku_{{ $data->id }}">
                                                <option value="1" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($data->is_approve==1) selected @endif @endif >Ada</option>
                                                <option value="0" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($data->is_approve==0) selected @endif @endif >Tidak ada</option>
                                            </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--  -->
                        <h5>Administrasi Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label class="form-label">Jenis BPD</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="is_project" name="is_project" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @if($trxMaster->luar_dalam == 'ca_dalam')
                                    <option value="1" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($trxMaster->is_project==1) selected @endif @endif >Project</option>
                                    @endif
                                    <option value="0" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($trxMaster->is_project==0) selected @endif @endif >Corporate</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Required.</div>
                            </div>
                            <div class="col-sm-6" id="div_koordinator">
                                <label class="form-label">Coordinator</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="koordinator" name="koordinator" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @foreach ($koordinator as $data)
                                    <option value="{{ $data->nik }}" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($trxMaster->koordinator==$data->nik) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback txt-danger">Required.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label class="form-label">Golongan pelaku perjalanan dinas</label>
                                <select class="form-select col-sm-12" id="is_leader" name="is_leader" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="1" @if($trxMaster->is_leader==1) selected @endif >Leader</option>
                                    <option value="2" @if($trxMaster->is_leader==2) selected @endif >Member</option>
                                    <option value="3" @if($trxMaster->is_leader==3) selected @endif >Management</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Project ID</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="project_id" name="project_id" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14)
                                    <option value="{{ $trxMaster->project_id }}" selected>{{ $trxMaster->_project->project_id }} / {{ $trxMaster->_project->name }} / {{ $trxMaster->_project->group }}</option>
                                    @endif
                                </select>
                                <div class="invalid-feedback txt-danger">Required.</div>
                            </div>
                        </div>
                        <hr />
                        <h5>Biaya Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_3">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Biaya</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col" width="40%">Kode COA</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailBiaya as $data)
                                        <tr>
                                            <td>{{ ++$no_biaya }}</td>
                                            <td>{{ $data->biaya }}</td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal, 0) }}</td>
                                            <td>
                                                <select class="form-select col-sm-12" id="biayacoa_{{ $data->id }}" name="biayacoa_{{ $data->id }}" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14)
                                                    <option value="{{ $data->id_coa }}" selected>{{ $data->kode_coa }}</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                            <select class="form-select col-sm-12" id="isapprove_{{ $data->id }}" name="isapprove_{{ $data->id }}">
                                                <option value="1" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($data->is_approve==1) selected @endif @endif >Approve</option>
                                                <option value="0" @if ($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) @if($data->is_approve==0) selected @endif @endif >Reject</option>
                                            </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr />
                        <h5>Tambahan Biaya</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" width="40%">Biaya</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <a class="btn btn-primary" id="insertRowbiaya" href="#">Add new</a>
                        </div>
                        <hr />
                        <h5>Catatan</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <!-- <button type="submit" name="status_id" class="btn btn-primary" value="0">Preview</button> -->
                                <button type="submit" name="status_id" class="btn btn-success" value="{{ $approve }}">Submit</button>
                                @if($trxMaster->status_id <> 3)
                                <button type="submit" name="status_id" class="btn btn-danger" value="{{ $reject }}">Reject</button>
                                @endif
                                <!-- <a class="btn btn-warning" href="{{ route('bpd.index') }}">Back</a> -->
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" height="40" alt="Alita" /></a>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
    // 
    $(document).ready(function () {
        // Biaya Perjalanan Dinas
        var counter_biaya = 1;
        $("#insertRowbiaya").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr id="tr_biaya">');
            var id = $("#is_project").val();
            var cols = '';
            // Table columns
            cols += '<th scrope="row">' + counter_biaya + '</th>';
            if (id == 1) {
                cols += '<td><select class="form-select col-sm-12" id="biaya[]" name="biaya[]" required=""><option selected="" disabled="" value="">Pilih...</option>@foreach ($coa->coa_1 as $data) <option value="{{ $data->id }}">{{ $data->account }}-{{ $data->name }}</option> @endforeach</select></td>';
            } else if (id == 0) {
                cols += '<td><select class="form-select col-sm-12" id="biaya[]" name="biaya[]" required=""><option selected="" disabled="" value="">Pilih...</option>@foreach ($coa->coa_0 as $data) <option value="{{ $data->id }}">{{ $data->account }}-{{ $data->name }}</option> @endforeach</select></td>';
            } else {
                cols += '<td><select class="form-select col-sm-12" id="biaya[]" name="biaya[]" required=""><option selected="" disabled="" value="">Pilih...</option></select></td>';
            }
            cols += '<td><select class="form-select col-sm-12" id="satuan[]" name="satuan[]" required=""><option selected="" disabled="" value="">Pilih...</option><option value="IDR">IDR</option><option value="USD">USD</option></select></td>';
            cols += '<td><input min=1 type="number" name="jumlah[]" id="jumlah[]" class="form-control numbers" required=""></td>';
            cols += '<td><input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_' + counter_biaya + '" id="nominal_' + counter_biaya + '" class="form-control numbers" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowbiaya"><i class="fa fa-trash"></i></button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_biaya").append(newRow);
            // Increase counter_biaya after each row insertion
            counter_biaya++;
        });
        // Remove row when delete btn is clicked
        $("#table_biaya").on("click", "#deleteRowbiaya", function (event) {
            $(this).closest("tr").remove();
            // counter_biaya -= 1
        });
        // 
        div_koordinator(0);
        @if(($trxMaster->status_id == 8 || $trxMaster->status_id == 10 || $trxMaster->status_id == 12 || $trxMaster->status_id == 14) && $trxMaster->is_project==1)
            div_koordinator(1);
        @endif
        $("#is_project").change(function (event) {
            $('#project_id').find('option').remove();
            $("#project_id").append('<option selected="" disabled="" value="">Pilih...</option>');
            var id = $("#is_project").val();
            if (id == 1) {
                div_koordinator(1);
                $('table#table_biaya tr#tr_biaya').remove();
                counter_biaya = 1;
            } else {
                div_koordinator(0);
                $('table#table_biaya tr#tr_biaya').remove();
                counter_biaya = 1;
            }
            // alert(id);
            $.ajax({
                url: "{{ route('bpd_project') }}" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var len = 0;
                    if (response['project_id'] != null) {
                        len = response['project_id'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['project_id'][i].id;
                            var project_id = response['project_id'][i].project_id;
                            var name = response['project_id'][i].name;
                            var group = response['project_id'][i].group;
                            var option = "<option value='" + id + "'>" + project_id + " / " + name + " / " + group + "</option>";
                            $("#project_id").append(option);
                        }
                    }
                }
            });
            @foreach($trxDetailBiaya as $data)
            getData({{ $data->id }}, 0);
            @endforeach
        });

        function div_koordinator(cek) {
            // jika 1 maka aktif
            if (cek == 1) {
                $('#div_koordinator').show();
                $("#koordinator").prop('disabled', false);
            }
            // jika 0 maka non aktif
            else {
                $('#div_koordinator').hide();
                $("#koordinator").prop('disabled', true);
            }
        }
    });
    // 
    function use_number(node) {
        var empty_val = false;
        const value = node.value;
        if (node.value == '')
            empty_val = true;
        node.type = 'number';
        if (!empty_val)
            node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
    }

    function use_text(node) {
        var empty_val = false;
        const value = Number(node.value);
        if (node.value == '')
            empty_val = true;
        node.type = 'text';
        if (!empty_val)
            node.value = value.toLocaleString('en'); // or other formatting
    }
    // get data coa
    function getData(_id, _project_id) {
        $('#biayacoa_' + _id).find('option').remove();
        $("#biayacoa_" + _id).append('<option selected="" disabled="" value="">Pilih...</option>');
        var id = $("#is_project").val();
        var coa = 0;
        if (id == 1) {
            coa = 1;
        }
        // alert(id);
        $.ajax({
            url: "{{ route('bpd_coa') }}" + "/" + _project_id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                // alert(response);
                var len = 0;
                if (response['account'] != null) {
                    len = response['account'].length;
                }
                if (len > 0) {
                    // Read data and create <option >
                    for (var i = 0; i < len; i++) {
                        var id = response['account'][i].id;
                        var account = response['account'][i].account;
                        var name = response['account'][i].name;
                        var option = "<option value='" + id + "'>" + account + "-" + name + "</option>";
                        $("#biayacoa_" + _id).append(option);
                    }
                }
            }
        });
    }
    // 
    $("#project_id").change(function (event) {
        var _project_id = $("#project_id").val();
        @foreach($trxDetailBiaya as $data)
        getData({{ $data->id }}, _project_id);
        @endforeach
    });

</script>
@endsection
