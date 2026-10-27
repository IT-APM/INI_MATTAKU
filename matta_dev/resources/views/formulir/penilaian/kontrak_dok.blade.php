@extends('layouts.simple.master')
@section('title', 'Kontrak PKWT Karyawan')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
    <style>
        button.dt-button {
            float: none;
            text-align: center;
            color: black;
            background: white;
        }
        /*  */
        .tbl-laporan {
            border-collapse: collapse;
            margin: 5px;
            width: 100%;
        }
        .tbl-laporan th,
        .tbl-laporan td {
            border: 1px solid #dee2e6;
            padding: 6px 10px;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-laporan thead th {
            background-color: #f0f7f3;
            color: #0B3D2E;
            font-weight: 600;
        }
        .tbl-laporan tbody tr:hover {
            background-color: #f0faf4;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Kontrak PKWT Karyawan</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Kontrak</li>
    <li class="breadcrumb-item active">PKWT Karyawan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="_datatable" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Kontrak</th>
                                        <th>PDF</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Jabatan</th>
                                        <th>Level</th>
                                        <th>Tanggal mulai kontrak</th>
                                        <th>Tanggal selesai kontrak</th>
                                        <th>Durasi (bulan)</th>
                                        <th>Tanggal kontrak</th>
                                        <th>Gapok (Rp.)</th>
                                        <th>T. Makan (Rp.)</th>
                                        <th>T. Transportasi (Rp.)</th>
                                        <th>T. Jabatan (Rp.)</th>
                                        <th>T. Telekomunikasi (Rp.)</th>
                                        <th>T. Lainnya (Rp.)</th>
                                        <th>Total (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 0; @endphp
                                    @foreach ($trx as $data)
                                        @php
                                            $total = $data->gapok + $data->t_makan + $data->t_transportasi + $data->t_jabatan + $data->t_telekomunikasi + $data->t_lain;
                                            $angka_romawi = [
                                                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
                                                5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
                                                9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
                                            ];
                                            $no_kontrak = str_pad($data->no_kontrak, 3, '0', STR_PAD_LEFT).'-PKWT/ADM-APM/PC/';
                                            $tanggal_kontrak = \Carbon\Carbon::parse($data->tanggal_kontrak);
                                            $no_kontrak .= $angka_romawi[$tanggal_kontrak->month].'/'.$tanggal_kontrak->year;
                                        @endphp
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>
                                                {{ $no_kontrak }}
                                            </td>
                                            <td>
                                                <a href="{{ route('penilaian.kontrak', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'header'=>true]) }}" data-toggle="tooltip" title="Kontrak PKWT-Header" target="_blank"><i class="fa fa-file-pdf-o text-success"></i></a>
                                                <a href="{{ route('penilaian.kontrak', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'header'=>false]) }}" data-toggle="tooltip" title="Kontrak PKWT" target="_blank"><i class="fa fa-file-pdf-o text-warning"></i></a>
                                            </td>
                                            <td>{{ $data->nik_karyawan }}</td>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->_divisi?->nama ?? '' }}</td>
                                            <td>{{ $data->_departemen?->nama ?? '' }}</td>
                                            <td>{{ $data->_sub_departemen?->nama ?? '' }}</td>
                                            <td>{{ $data->_jabatan?->jabatan ?? '' }}</td>
                                            <td>{{ $data->_level?->level ?? '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_terhitung)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_terhitung)->addMonths($data->bulan - 1)->endOfMonth()->format('d-m-Y') }}</td>
                                            <td>{{ $data->bulan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_kontrak)->format('d-m-Y') }}</td>
                                            <td class="text-end">{{ number_format($data->gapok, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($data->t_makan, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($data->t_transportasi, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($data->t_jabatan, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($data->t_telekomunikasi, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($data->t_lain, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($total, 0, ',', '.') }}</td>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
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
            "pageLength": 50,
            "lengthMenu": [
                [10, 50, 100, 200, 500, -1],
                [10, 50, 100, 200, 500, "All"]
            ]
        });
    </script>
@endsection
