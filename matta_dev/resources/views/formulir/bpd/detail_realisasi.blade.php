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
    @if ($trxMaster->luar_dalam == 'ca_dalam') Realisasi Perjalanan Dinas Dalam Negeri
    @elseif ($trxMaster->luar_dalam == 'ca_luar') Realisasi Perjalanan Dinas Luar Negeri
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
                    <hr/>
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
                    @php
                    $total_p_idr = 0;
                    $total_p_usd = 0;
                    $total_l_idr = 0;
                    $total_l_usd = 0;
                    $total_p_idr_realisasi = 0;
                    $total_p_usd_realisasi = 0;
                    $total_l_idr_realisasi = 0;
                    $total_l_usd_realisasi = 0;
                    @endphp
                    <!--  -->
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
                        <hr/>
                        <!-- start ca -->
                        <h5>Cash Advance Biaya Perjalanan Dinas</h5>
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
                        <!-- finish ca -->
                        <hr/>
                        <!-- start realisasi -->
                        <h5>Realisasi Biaya Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info_realisasi">
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
                                        @foreach ($trxDetailInfoRealisasi as $data)
                                        <tr>
                                            <td>{{ ++$no_info_realisasi }}</td>
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
                        @if(count($trxDetailBiayaPersonelRealisasi)>0)
                        <h6>Biaya Personel</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_3">
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
                                        @foreach ($trxDetailBiayaPersonelRealisasi as $data)
                                        @php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_p_idr_realisasi += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_p_usd_realisasi += $data->nominal * $data->jumlah;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$no_biaya_info_realisasi }}</td>
                                            @if(count($kode_coa)>1)
                                                <td>{{ $kode_coa[0] }}</td>
                                                @if ($trxMaster->luar_dalam == 'ca_dalam')
                                                <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_dalam->propinsi }}) @endif</td>
                                                @elseif ($trxMaster->luar_dalam == 'ca_luar')
                                                <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_luar->negara }}) @endif</td>
                                                @endif
                                            @else
                                                <td>&nbsp;</td>
                                                <td>{{ $data->biaya }}</td>
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
                                            <th scope="col">{{ number_format($total_p_idr_realisasi, 0) }}<br/>{{ number_format($total_p_usd_realisasi, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                        <!-- jika ada trxDetailBiayaRealisasi -->
                        @if(count($trxDetailBiayaRealisasi)>0)
                            <h6>Biaya Lain-lain</h6>
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="table" id="table_biaya_coa_4">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Bukti</th>
                                                <th scope="col">Kode COA</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Nominal</th>
                                                <th scope="col">Satuan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trxDetailBiayaRealisasi as $data)
                                            @php
                                            $kode_coa = explode('-', $data->kode_coa);
                                            if($data->satuan == 'IDR'){
                                                $total_l_idr_realisasi += $data->nominal * $data->jumlah;
                                            }else if($data->satuan == 'USD'){
                                                $total_l_usd_realisasi += $data->nominal * $data->jumlah;
                                            }
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no_biaya_realisasi }}</td>
                                                <td><a href="{{ asset('assets/bpd/'.$data->lampiran) }}" target="_blank"><i class="icon-file"></i>&nbsp;</a></td>
                                                <td>{{ $kode_coa[0] }}</td>
                                                <td>@if(!empty($kode_coa[1])) {{ $kode_coa[1] }} @else {{ $data->biaya }} @endif</td>
                                                <td>{{ $data->jumlah }}</td>
                                                <td>{{ number_format($data->nominal, 0) }}</td>
                                                <td>{{ $data->satuan }}</td>
                                                <td>{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
                                                <td>@if($data->is_ca == 1) Realisasi @elseif($data->is_ca == 2) Reimbursement @endif</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Realisasi</th>
                                                <th scope="col">IDR<br/>USD</th>
                                                <th scope="col">{{ number_format($total_p_idr_realisasi+$total_l_idr_realisasi, 0) }}<br/>{{ number_format($total_p_usd_realisasi+$total_l_usd_realisasi, 0) }}</th>
                                            </tr>
                                        </tfoot>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Biaya Lain-lain</th>
                                                <th scope="col">IDR<br/>USD</th>
                                                <th scope="col">{{ number_format($total_l_idr_realisasi, 0) }}<br/>{{ number_format($total_l_usd_realisasi, 0) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                        @endif
                        <!--  -->
                            <h5>Kesimpulan</h5>
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="table" id="kesimpulan">
                                        <thead>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Biaya Personel</th>
                                                <th scope="col">Total Biaya Lain-lain</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="col">Cash Advance</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr, 0) }}<br/>USD {{ number_format($total_p_usd, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_l_idr, 0) }}<br/>USD {{ number_format($total_l_usd, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr+$total_l_idr, 0) }}<br/>USD {{ number_format($total_p_usd+$total_l_usd, 0) }}</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Realisasi</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr_realisasi, 0) }}<br/>USD {{ number_format($total_p_usd_realisasi, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_l_idr_realisasi, 0) }}<br/>USD {{ number_format($total_l_usd_realisasi, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr_realisasi+$total_l_idr_realisasi, 0) }}<br/>USD {{ number_format($total_p_usd_realisasi+$total_l_usd_realisasi, 0) }}</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Diterima / Dikembalikan</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr-$total_p_idr_realisasi, 0) }}<br/>USD {{ number_format($total_p_usd-$total_p_usd_realisasi, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_l_idr-$total_l_idr_realisasi, 0) }}<br/>USD {{ number_format($total_l_usd-$total_l_usd_realisasi, 0) }}</th>
                                                <th scope="col">IDR {{ number_format($total_p_idr+$total_l_idr-$total_p_idr_realisasi-$total_l_idr_realisasi, 0) }}<br/>USD {{ number_format($total_p_usd+$total_l_usd-$total_p_usd_realisasi-$total_l_usd_realisasi, 0) }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                        <!--  -->
                        <!-- finish realisasi -->
                    <!--  -->
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
</script>
@endsection
