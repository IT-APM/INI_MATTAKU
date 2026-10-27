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
    <h3>Formulir Karyawan Non Aktif</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Karyawan Non Aktif</li>
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
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        {{-- <a href="{{ route('nonaktif.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | --}}
                        <button id="autoClickBtn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#autoShowModal"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> |
                        @can('hc')
                            <a href="{{ route('nonaktif_auto.index') }}" class="btn btn-success" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> Auto</a> |
                        @endcan
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('nonaktif.index') }}" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Tanggal</label>
                                        <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}">
                                        sampai
                                        <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}">
                                        <label class="h6 mt-2" for="nama">Karyawan</label>
                                        <input name="nama" id="nama" class="form-control" type="text" value="{{ $nama }}">
                                        @if (auth()->user()->userrole == 'hc')
                                            <label class="h6 mt-2" for="atasan">Layer 1</label>
                                            <input name="atasan" id="atasan" class="form-control" type="text" value="{{ $atasan }}">
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="perusahaan"></label>
                                        @foreach ($perusahaan as $data)
                                            <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_perusahaan)) checked @endif>{{ $data->nama }}<br/>
                                        @endforeach
                                        </div>
                                        <label class="h6 mt-2">Status Perpanjangan</label>&nbsp;<input type="checkbox" onClick="toggle_perpanjangan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="perpanjangan"></label>
                                        @foreach ($perpanjangan as $data)
                                            <input class="checkbox_animated" id="perpanjangan[]" name="perpanjangan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_perpanjangan)) checked @endif>{{ $data->informasi }}<br/>
                                        @endforeach
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Status Formulir</label>&nbsp;<input type="checkbox" onClick="toggle_status(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="status"></label>
                                        @foreach ($status as $data)
                                            <input class="checkbox_animated" id="status[]" name="status[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_status)) checked @endif>{{ $data->status }}<br/>
                                        @endforeach
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Alasan Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_alasan(this)" /> Check All
                                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="alasan"></label>
                                        @foreach ($alasan as $data)
                                            <input class="checkbox_animated" id="alasan[]" name="alasan[]" type="checkbox" value="{{ $data->id }}" @if (in_array($data->id, $arr_alasan)) checked @endif>{{ $data->alasan }}<br/>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i>Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="display nowrap" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Action</th>
                                        <th>Status Perpanjangan</th>
                                        <th>Karyawan</th>
                                        <th>NIP Karyawan</th>
                                        <th>Layer 1</th>
                                        <th>NIP Layer 1</th>
                                        <th>Perusahaan</th>
                                        <th>Alasan</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Belum Disetujui</th>
                                        <th>Status Formulir</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trx as $data)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->id }}</td>
                                            @php
                                                $check = App\Models\TrxNonaktifLog::where(['id_trx'=>$data->id, 'id_status'=>31])->exists();
                                            @endphp
                                            <td>
                                                <a href="{{ route('nonaktif.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                                &nbsp;<a href="{{ route('nonaktif.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')]) }}" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                @if($check)&nbsp;<a href="{{ route('nonaktif.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('sanggup')]) }}" data-toggle="tooltip" title="Surat Pernyataan Kesanggupan" target="_blank"><i class="fa fa-file-pdf-o" style="color:orange"></i></a>@endif
                                                @can('hc')
                                                @if(auth()->user()->nik == App\Models\MtrUtility::find(15)->informasi)
                                                <form action="{{ route('nonaktif.destroy', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <ul class="action">
                                                        <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                                    </ul>
                                                </form>
                                                @endif
                                                @endcan
                                            </td>
                                            <td>
                                                @php
                                                    $where = ['nik_karyawan' => $data->pengaju];
                                                    $status = App\Models\TrxPenilaian::where($where)
                                                        ->whereIn('id_status', [4, 8])
                                                        ->orderBy('id', 'desc')
                                                        ->first();
                                                    if (request()->get('filter') == 'true') {
                                                        $status = App\Models\TrxPenilaian::where($where)
                                                            ->whereIn('id_status', [4, 8])
                                                            ->whereIn('is_perpanjang', $arr_perpanjangan)
                                                            ->orderBy('id', 'desc')
                                                            ->first();
                                                    }
                                                @endphp
                                                @if (!empty($status))
                                                    {{ $status->_perpanjang->informasi }}
                                                    @if ($status->is_perpanjang == 1)
                                                        {{ $status->bulan }} Bulan
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $data->_pengaju->nama }}</td>
                                            <td>{{ $data->pengaju }}</td>
                                            <td>{{ $data->_atasan->nama }}</td>
                                            <td>{{ $data->atasan }}</td>
                                            <td>
                                                @php
                                                    $pengaju_property = json_decode($data['pengaju_property'], true);
                                                @endphp
                                                {{ $pengaju_property['perusahaan'] }}
                                            </td>
                                            <td>{{ $data->_alasan->alasan }}</td>
                                            <td>
                                                @if (!empty($data->tanggal))
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $where = ['id_trx' => $data->id];
                                                    // 8 = Approve by PIC DIVISI
                                                    // 12 = Approve by PIC GA
                                                    // 16 = Approve by PIC IT
                                                    // 20 = Approve by PIC KOPERASI
                                                    // 24 = Approve by PIC HC
                                                    $done_by = App\Models\TrxNonaktifLog::where($where)->whereIn('id_status', [8, 12, 16, 20, 24])->orderBy('id', 'desc')->get();
                                                    $arr = [];
                                                    foreach ($done_by as $_done_by) {
                                                        array_push($arr, $_done_by->nik_pic);
                                                    }
                                                    $not_yet = App\Models\TrxNonaktifDetail::where($where)->whereNotIn('kepada', $arr)->orderBy('id', 'desc')->get();
                                                @endphp
                                                @foreach ($not_yet as $_not_yet)
                                                    {{ $_not_yet->_kepada->nama }}<br/>
                                                @endforeach
                                            </td>
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
    {{--  --}}
    <div class="modal fade bd-example-modal-lg" id="autoShowModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title w-100 text-center" id="myLargeModalLabel">SURAT PERNYATAAN KESANGGUPAN</h4>
                </div>
                @php
                    $pengaju = App\Models\Karyawan::where(['nik'=>auth()->user()->nik])->first()
                @endphp
                <div class="modal-body">
                    <div class="row" style="text-align: justify;font-size: 15px;">
                        <div class="col-12">
                            <label>Yang menyatakan di bawah ini :</label>
                        </div>
                        <div class="col-12">
                            <table width="100%">
                                <tbody valign="top">
                                    <tr>
                                        <td width="2%">&nbsp;</td>
                                        <td width="20%">Nama</td>
                                        <td width="1%">:</td>
                                        <td width="78%">{{ $pengaju->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td>{{ $pengaju->_jabatan->jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td>{{ $pengaju->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Direktorat</td>
                                        <td>:</td>
                                        <td>{{ $pengaju->_divisi->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Divisi</td>
                                        <td>:</td>
                                        <td>{{ $pengaju->_departemen->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Departemen</td>
                                        <td>:</td>
                                        <td>{{ $pengaju->_subdepartemen->nama }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 mt-2">
                            <label>Adalah subjek hukum yang terikat sebagai karyawan <b>PT ALITA PRAYA MITRA</b>.</label>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <label><b><u>MENYATAKAN</u></b></label>
                        </div>
                        <div class="col-12 mt-2">
                            <label>Dalam hal berakhirnya hubungan kerja sebagai Karyawan <b>PT ALITA PRAYA MITRA</b> tersebut di atas maka,<br/>SANGGUP UNTUK :</label>
                        </div>
                        <div class="col-12">
                            <table width="100%">
                                <tbody valign="top">
                                    <tr>
                                        <td width="2%">&nbsp;</td>
                                        <td width="3%">A.</td>
                                        <td width="95%">Menjaga Seluruh Kerahasiaan Pekerjaan yang telah dilakukannya di <b>PT ALITA PRAYA MITRA</b>, termasuk di dalamnya hal-hal yang terkait dengan Produk, Harga, Teknologi, Sistem Kerja serta Organisasi, dan tidak memberikan informasi terkait hal-hal tersebut di atas kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>B.</td>
                                        <td>Menyerahkan kembali seluruh Alat Kerja milik <b>PT ALITA PRAYA MITRA</b> yang telah diberikan sebagai penunjang pekerjaan, dalam keadaan dan fungsi yang baik, dibuktikan dengan Membuat Berita Acara Serah Terima Alat Kerja;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>C.</td>
                                        <td>Menjaga Seluruh Kerahasiaan Data dan Informasi <b>PT ALITA PRAYA MITRA</b> atas berakhirnya masa Kerja, dan tidak memberikan informasi terkait hal-hal tersebut kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>D.</td>
                                        <td>Dituntut sesuai hukum yang berlaku, apabila melanggar seluruh ketentuan dalam Surat Pernyataan Kesanggupan ini.</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- <ol>
                                <li>Menjaga Seluruh Kerahasiaan Pekerjaan yang telah dilakukannya di <b>PT ALITA PRAYA MITRA</b>, termasuk di dalamnya hal-hal yang terkait dengan Produk, Harga, Teknologi, Sistem Kerja serta Organisasi, dan tidak memberikan informasi terkait hal-hal tersebut di atas kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</li>
                                <li>Menyerahkan kembali seluruh Alat Kerja milik <b>PT ALITA PRAYA MITRA</b> yang telah diberikan sebagai penunjang pekerjaan, dalam keadaan dan fungsi yang baik, dibuktikan dengan Membuat Berita Acara Serah Terima Alat Kerja;</li>
                                <li>Menjaga Seluruh Kerahasiaan Data dan Informasi <b>PT ALITA PRAYA MITRA</b> atas berakhirnya masa Kerja, dan tidak memberikan informasi terkait hal-hal tersebut kepada siapapun, dalam bentuk dan cara apapun selama sedikitnya 5 (lima) tahun sejak tanggal Surat Pernyataan Kesanggupan ini;</li>
                                <li>Dituntut sesuai hukum yang berlaku, apabila melanggar seluruh ketentuan dalam Surat Pernyataan Kesanggupan ini.</li>
                            </ol> --}}
                        </div>
                        <div class="col-12 mt-2">
                            <label>Pernyataan ini dibuat secara sadar, tanpa ada tekanan dari pihak manapun dan akan dipatuhi dan dilaksanakan secara penuh tanggung jawab, dan sesuai dengan peraturan Perundangan yang berlaku.</label>
                        </div>
                        {{-- <div class="col-12 text-center">
                            @php
                                $created_at = \Carbon\Carbon::now();
                                $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                            @endphp
                            <label><b>PT ALITA PRAYA MITRA</b><br/>Jakarta, {{ \Carbon\Carbon::parse($created_at)->format('d') }} {{ $bulan[\Carbon\Carbon::parse($created_at)->format('m')-1] }} {{ \Carbon\Carbon::parse($created_at)->format('Y') }}</label>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('nonaktif.create') }}" method="get" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="is_setuju" name="is_setuju" value="1">
                        <input type="hidden" id="created_at" name="created_at" value="{{ Crypt::encrypt(\Carbon\Carbon::now()) }}">
                        {{-- <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}"> --}}
                        <button class="btn btn-warning" type="submit">Setuju</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--  --}}
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
        @if(session('show_modal'))
            var myModal = new bootstrap.Modal(document.getElementById('autoShowModal'));
            myModal.show();
        @endif
        function toggle_status(source) {
            checkboxes = document.getElementsByName('status[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

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

        function toggle_alasan(source) {
            checkboxes = document.getElementsByName('alasan[]');
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
                    text: "If you delete this, it will be gone forever.",
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
    </script>
@endsection
