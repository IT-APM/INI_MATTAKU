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
    @elseif ($trxMaster->luar_dalam == 'reimburse') Reimbursement Perjalanan Dinas Dalam Negeri
    @elseif ($trxMaster->luar_dalam == 'reimburse_luar') Reimbursement Perjalanan Dinas Luar Negeri
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
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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
                            <input class="form-control" value="@if($trxMaster->is_project==1)Project: @else Corporate: @endif {{ $trxMaster->_kelompok->kelompok }}">
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
                                            <th scope="col">Uang saku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trxDetailInfo as $data)
                                        <tr>
                                            <td>{{ ++$no_info }}</td>
                                            @if(in_array($trxMaster->luar_dalam, ['ca_dalam', 'reimburse']))
                                            <td>{{ $data->_tujuan1_dalam->propinsi }} - {{ $data->_tujuan2_dalam->kota }}</td>
                                            @elseif(in_array($trxMaster->luar_dalam, ['ca_luar', 'reimburse_luar']))
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
                                            <th scope="col">&nbsp;</th>
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
                                        <form method="post" enctype="multipart/form-data" action="{{ route('bpd.update_biaya', Crypt::encrypt($trxMaster->id)) }}" target="_self"
                                        class="needs-validation" novalidate=""
                                        onsubmit="return confirm('Are you sure want to update this data?');">
                                        @method('POST')
                                        @csrf
                                        <tr>
                                            <td>{{ ++$no_biaya_info }}</td>
                                            <td>{{ $kode_coa[0] }}</td>
                                            @if (in_array($trxMaster->luar_dalam, ['ca_dalam', 'reimburse']))
                                            <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_dalam->propinsi }}) @endif</td>
                                            @elseif (in_array($trxMaster->luar_dalam, ['ca_luar', 'reimburse_luar']))
                                            <td>{{ $kode_coa[1] }} @if(!empty($data->id_info)) ({{ $data->_info->_tujuan1_luar->negara }}) @endif</td>
                                            @endif
                                            <td>{{ $data->jumlah }}</td>
                                            <td>
                                                @php
                                                    $nominal = App\Http\Controllers\OpenDataController::getUangSaku($data->id);
                                                @endphp
                                                <select class="form-select col-sm-12" id="nominal_{{ $data->id }}" name="nominal_{{ $data->id }}">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    @for ($x = 0; $x < count($nominal); $x++) {
                                                    <option value="{{ $nominal[$x] }}" @if($nominal[$x]==$data->nominal) selected @endif >{{ number_format($nominal[$x], 0) }}</option>
                                                    }
                                                    @endfor
                                                </select>
                                            </td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
                                            <td>
                                                <button type="submit" name="id_data" class="btn btn-success" value="{{ $data->id }}"><i class="fa fa-save"></i></button>
                                            </td>
                                        </tr>
                                        </form>
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
                                            <th scope="col" width="15%">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">&nbsp;</th>
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
                                        <form method="post" enctype="multipart/form-data" action="{{ route('bpd.update_biaya', Crypt::encrypt($trxMaster->id)) }}" target="_self"
                                        class="needs-validation" novalidate=""
                                        onsubmit="return confirm('Are you sure want to update this data?');">
                                        @method('POST')
                                        @csrf
                                        <tr>
                                            <td>{{ ++$no_biaya }}</td>
                                            <td>{{ $kode_coa[0] }}</td>
                                            <td>{{ $kode_coa[1] }}</td>
                                            <td>{{ $data->jumlah }}</td>
                                            <!-- <td>{{ number_format($data->nominal, 0) }}</td> -->
                                            <td>
                                                <input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_{{ $data->id }}" id="nominal_{{ $data->id }}" class="form-control numbers" required="" value="{{ $data->nominal }}">
                                            </td>
                                            <td>{{ $data->satuan }}</td>
                                            <td>{{ number_format($data->nominal * $data->jumlah, 0) }}</td>
                                            <td>
                                                <button type="submit" name="id_data" class="btn btn-success" value="{{ $data->id }}"><i class="fa fa-save"></i></button>
                                            </td>
                                        </tr>
                                        </form>
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
    function use_number(node) {
        var empty_val = false;
        const value = node.value;
        if (node.value == '')
            empty_val = true;
        node.type = 'number';
        if (!empty_val)
            node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
    }
    // 
    function use_text(node) {
        var empty_val = false;
        const value = Number(node.value);
        if (node.value == '')
            empty_val = true;
        node.type = 'text';
        if (!empty_val)
            node.value = value.toLocaleString('en'); // or other formatting
    }
</script>
@endsection
