@extends('layouts.simple.master')
@section('title', 'Formulir Karyawan Non Aktif')

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
    <h3>Auto Formulir Karyawan Non Aktif</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Auto</li>
    <li class="breadcrumb-item active">Formulir Karyawan Non Aktif</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @if (Session::has('msg'))
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6>{{ session('msg') }}</h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <button class="btn btn-warning mb-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i
                                class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('nonaktif_auto.index') }}" method="get"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6" for="nama">Karyawan</label>
                                        <input name="nama" id="nama" class="form-control" type="text" value="{{ $nama }}">
                                        @if (auth()->user()->userrole == 'hc')
                                            <label class="h6 mt-2" for="atasan">Atasan</label>
                                            <input name="atasan" id="atasan" class="form-control" type="text"
                                                value="{{ $atasan }}">
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6 mt-2">Perusahaan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_perusahaan(this)" /> Check All
                                        <label class="d-block" for="perusahaan"></label>
                                        @foreach ($perusahaan as $data)
                                            <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]"
                                                type="checkbox" value="{{ $data->id }}"
                                                @if (in_array($data->id, $arr_perusahaan)) checked @endif>
                                            {{ $data->nama }}<br />
                                        @endforeach
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Status Perpanjangan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_perpanjangan(this)" /> Check All
                                        <label class="d-block" for="perpanjangan"></label>
                                        @foreach ($perpanjangan as $data)
                                            <input class="checkbox_animated" id="perpanjangan[]" name="perpanjangan[]"
                                                type="checkbox" value="{{ $data->id }}"
                                                @if (in_array($data->id, $arr_perpanjangan)) checked @endif>
                                            {{ $data->informasi }}<br />
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true"
                                            type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                                        | <button class="btn btn-info" id="reset" name="reset" value="true"
                                            type="submit"><i class="fa fa-refresh" aria-hidden="true"></i>
                                            Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form action="{{ route('nonaktif_auto.store') }}" method="post" enctype="multipart/form-data"
                            onsubmit="return confirm('Are you sure want to do this?');">
                            @csrf
                            <a href="{{ route('nonaktif_auto.create') }}" class="btn btn-primary" type="button"><i
                                    class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                            @if (count($trx) > 0)
                                | <button class="btn btn-success" type="submit" name="submit" id="submit"
                                    value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button>
                                |
                                <button class="btn btn-danger" type="submit" name="submit" id="submit"
                                    value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                                <br /><br />
                            @endif
                            <div class="table-responsive">
                                <table class="display" id="_datatable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_check(this)" /></th>
                                            <th>No</th>
                                            <th>Status Perpanjangan</th>
                                            <th>Karyawan</th>
                                            <th>NIP Karyawan</th>
                                            <th>Perusahaan</th>
                                            <th>Atasan</th>
                                            <th>NIP Atasan</th>
                                            <th>Status Formulir</th>
                                            <th>Tanggal Berakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trx as $data)
                                            <tr>
                                                <td><input id="nonaktif[]" name="nonaktif[]" type="checkbox"
                                                        value="{{ $data->id }}"></td>
                                                <td>{{ ++$no }}</td>
                                                <td>
                                                    @php
                                                        $where = ['nik_karyawan' => $data->nik_karyawan];
                                                        $status = App\Models\TrxPenilaian::where($where)
                                                            ->whereIn('id_status', [4, 8])
                                                            ->orderBy('id', 'desc')
                                                            ->first();
                                                    @endphp
                                                    @if (!empty($status))
                                                        {{ $status->_perpanjang->informasi }}
                                                        @if ($status->is_perpanjang == 1)
                                                            {{ $status->bulan }} Bulan
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $data->_karyawan->nama }}</td>
                                                <td>{{ $data->nik_karyawan }}</td>
                                                <td>{{ $data->_karyawan->_perusahaan->nama }}</td>
                                                <td>{{ $data->_atasan->nama }}</td>
                                                <td>{{ $data->nik_atasan }}</td>
                                                <td>Not {{ $data->_status->status }}</td>
                                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->end_date)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
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
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        function toggle_perpanjangan(source) {
            checkboxes = document.getElementsByName('perpanjangan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_perusahaan(source) {
            checkboxes = document.getElementsByName('perusahaan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_status_check(source) {
            checkboxes = document.getElementsByName('nonaktif[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
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
        // 
        $(document).on('click', '#btn-delete', function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            swal({
                    title: "Are you sure to delete this?",
                    text: "If you delete this, it will be gone for this session only.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
        // 
    </script>
@endsection
