@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    button.dt-button {
        float: none;
        text-align: center;
        color: black;
        background: white;
    }

</style>
@endsection

@section('breadcrumb-title')
<h3>Biaya Perjalanan Dinas</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Biaya Perjalanan Dinas</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Nama / NIP</th>
                                    <th>Atasan / NIP</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Level</th>
                                    <th>Jabatan</th>
                                    <th>Pengajuan</th>
                                    <th>Tahun</th>
                                    <th>Golongan</th>
                                    <th>Kelompok Perjalanan Dinas</th>
                                    <th>Jenis BPD</th>
                                    <th>Project ID</th>
                                    <th>Uraian Tugas Perjalanan Dinas</th>
                                    <th>Lampiran</th>
                                    <th>Informasi Perjalanan Dinas</th>
                                    <th>Biaya Personel</th>
                                    <th>Biaya Lain-lain</th>
                                    <th>Admin / NIP</th>
                                    <th>Project Coordinator / NIP</th>
                                    <th>Layer 1 / NIP</th>
                                    <th>Layer 2 / NIP</th>
                                    <th>{{ App\Models\MtrUtility::find(51)->informasi }} / NIP</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                @php
                                $pengaju_property = json_decode($data['pengaju_property'], true);
                                $admin_property = json_decode($data['admin_property'], true);
                                $atasan_property = json_decode($data['atasan_property'], true);
                                $koordinator_property = json_decode($data['koordinator_property'], true);
                                $layer1_property = json_decode($data['layer1_property'], true);
                                $layer2_property = json_decode($data['layer2_property'], true);
                                $hco_property = json_decode($data['hco_property'], true);
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $pengaju_property['nama'] }} / {{ $pengaju_property['nik'] }}</td>
                                    <td>{{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }}</td>
                                    <td>{{ $pengaju_property['perusahaan'] }}</td>
                                    <td>{{ $pengaju_property['divisi'] }}</td>
                                    <td>{{ $pengaju_property['dep'] }}</td>
                                    <td>{{ $pengaju_property['sub_dep'] }}</td>
                                    <td>{{ $pengaju_property['level'] }}</td>
                                    <td>{{ $pengaju_property['jabatan'] }}</td>
                                    <td>
                                        @if ($data->is_ca == 1 && $data->luar_dalam == 'ca_dalam') CA Dalam Negeri
                                        @elseif ($data->is_ca == 1 && $data->luar_dalam == 'ca_luar') CA Luar Negeri
                                        @elseif ($data->is_ca == 0 && $data->luar_dalam == 'reimburse') Reimbursement
                                        @endif
                                    </td>
                                    <td>{{ $data->periode }}</td>
                                    <td>@if($data->is_leader==1) Leader @elseif($data->is_leader==2) Member @elseif($data->is_leader==3) Management @endif</td>
                                    <td>@if(!empty($data->kelompok_id)) {{ $data->_kelompok->kelompok }} @endif</td>
                                    <td>@if(!empty($data->is_project)) @if($data->is_project == 1) Project @else Corporate @endif @endif</td>
                                    <td>@if(!empty($data->is_project)) {{ $data->_project->project_id }} / {{ $data->_project->name }} / {{ $data->_project->group }} @endif</td>
                                    <td>{{ $data->uraian }}</td>
                                    <td><a href="{{ asset('assets/bpd/'.$data->lampiran) }}" target="_blank"><i class="icon-file"></i>&nbsp;</a></td>
                                    <td style="vertical-align:top">
                                        @php 
                                        $where = ['id_trx' => $data->id];
                                        $trxDetailInfo = App\Models\TrxBpdDetailInfo::where($where)->orderBy('id', 'asc')->get();
                                        @endphp
                                        <table class="table" id="table_info">
                                            <thead>
                                                <tr>
                                                    <th>Jenis</th>
                                                    <th>Tujuan</th>
                                                    <th>Tanggal dari</th>
                                                    <th>Tanggal sampai</th>
                                                    <th>Jumlah hari</th>
                                                    <th>Uang saku</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($trxDetailInfo as $data_info)
                                                <tr>
                                                    <td>{{ $data_info->jenis }}</td>
                                                    @if(in_array($data->luar_dalam, ['ca_dalam''reimburse']))
                                                    <td>{{ $data_info->_tujuan1_dalam->propinsi }} - {{ $data_info->_tujuan2_dalam->kota }}</td>
                                                    @elseif(in_array($data->luar_dalam, ['ca_luar''reimburse_luar']))
                                                    <td>{{ $data_info->_tujuan1_luar->negara }}</td>
                                                    @endif
                                                    <td>{{ \Carbon\Carbon::parse($data_info->tanggal_mulai)->format('d-m-Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($data_info->tanggal_akhir)->format('d-m-Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($data_info->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data_info->tanggal_akhir))+1 }}</td>
                                                    <td>@if($data_info->is_approve==1) Ada @else Tidak ada @endif</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="vertical-align:top">
                                        @php 
                                        $where = ['id_trx' => $data->id];
                                        $trxDetailBiayaPersonel = App\Models\TrxBpdDetailBiaya::where($where)->where('kode_coa', 'LIKE', '%500240%')->orderBy('id', 'asc')->get();
                                        @endphp
                                        <table class="table" id="table_biaya_coa_1">
                                            <thead>
                                                <tr>
                                                    <th>Kode COA</th>
                                                    <th>Keterangan</th>
                                                    <th>Qty</th>
                                                    <th>Nominal</th>
                                                    <th>Satuan</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($trxDetailBiayaPersonel as $biaya_personel)
                                            @php
                                            $kode_coa = explode('-', $biaya_personel->kode_coa);
                                            @endphp
                                            <tr>
                                                <td>{{ $kode_coa[0] }}</td>
                                                @if(in_array($data->luar_dalam, ['ca_dalam''reimburse']))
                                                <td>{{ $kode_coa[1] }} @if(!empty($biaya_personel->id_info)) ({{ $biaya_personel->_info->_tujuan1_dalam->propinsi }}) @endif</td>
                                                @elseif(in_array($data->luar_dalam, ['ca_luar''reimburse_luar']))
                                                <td>{{ $kode_coa[1] }} @if(!empty($biaya_personel->id_info)) ({{ $biaya_personel->_info->_tujuan1_luar->negara }}) @endif</td>
                                                @endif
                                                <td>{{ $biaya_personel->jumlah }}</td>
                                                <td>{{ number_format($biaya_personel->nominal, 0) }}</td>
                                                <td>{{ $biaya_personel->satuan }}</td>
                                                <td>{{ number_format($biaya_personel->nominal * $biaya_personel->jumlah, 0) }}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="vertical-align:top">
                                        @php 
                                        $where = ['id_trx' => $data->id];
                                        $trxDetailBiayaPersonel = App\Models\TrxBpdDetailBiaya::where($where)->where('kode_coa', 'NOT LIKE', '%500240%')->orderBy('id', 'asc')->get();
                                        @endphp
                                        <table class="table" id="table_biaya_coa_2">
                                            <thead>
                                                <tr>
                                                    <th>Kode COA</th>
                                                    <th>Keterangan</th>
                                                    <th>Qty</th>
                                                    <th>Nominal</th>
                                                    <th>Satuan</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($trxDetailBiayaPersonel as $biaya_lain)
                                            @php
                                            $kode_coa = explode('-', $biaya_lain->kode_coa);
                                            @endphp
                                            <tr>
                                                <td>{{ $kode_coa[0] }}</td>
                                                @if(in_array($data->luar_dalam, ['ca_dalam''reimburse']))
                                                <td>{{ $kode_coa[1] }} @if(!empty($biaya_lain->id_info)) ({{ $biaya_lain->_info->_tujuan1_dalam->propinsi }}) @endif</td>
                                                @elseif(in_array($data->luar_dalam, ['ca_luar''reimburse_luar']))
                                                <td>{{ $kode_coa[1] }} @if(!empty($biaya_lain->id_info)) ({{ $biaya_lain->_info->_tujuan1_luar->negara }}) @endif</td>
                                                @endif
                                                <td>{{ $biaya_lain->jumlah }}</td>
                                                <td>{{ number_format($biaya_lain->nominal, 0) }}</td>
                                                <td>{{ $biaya_lain->satuan }}</td>
                                                <td>{{ number_format($biaya_lain->nominal * $biaya_lain->jumlah, 0) }}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>@if(!empty($admin_property)){{ $admin_property['nama'] }} / {{ $admin_property['nik'] }}@endif</td>
                                    <td>@if(!empty($koordinator_property)){{ $koordinator_property['nama'] }} / {{ $koordinator_property['nik'] }}@endif</td>
                                    <td>@if(!empty($layer1_property)){{ $layer1_property['nama'] }} / {{ $layer1_property['nik'] }}@endif</td>
                                    <td>@if(!empty($layer2_property)){{ $layer2_property['nama'] }} / {{ $layer2_property['nik'] }}@endif</td>
                                    <td>@if(!empty($hco_property)){{ $hco_property['nama'] }} / {{ $hco_property['nik'] }}@endif</td>
                                    <td>{{ $data->_status->status }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script type="text/javascript">
    // 
    $('#_datatable').DataTable({
        // dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'rt>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "lengthMenu": [
            [50, 100, 200, 500, -1],
            [50, 100, 200, 500, "All"]
        ]
    });

</script>
@endsection
