@extends('layouts.simple.master')

@section('title', 'Formulir Reimbursement')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Formulir Reimbursement</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Reimbursement</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @can('hc')
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan">
                                <option value="0">Pilih Karyawan...</option>
                                @foreach ($karyawan as $data)
                                <option value="{{ Crypt::encrypt($data->nik) }}">{{ $data->nama }} - {{ $data->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan
                    @php
                        $start_date = '2025-12-16';
                        $end_date = '2026-01-01';
                    @endphp
                    @if($isPlafon)
                    {{-- @if(\Carbon\Carbon::now()->toDateString() >= '2024-12-16' && \Carbon\Carbon::now()->toDateString() <= '2025-01-02') --}}
                    {{-- @if(\Carbon\Carbon::now()->toDateString() >= '2025-03-17' && \Carbon\Carbon::now()->toDateString() <= '2025-04-08') --}}
                    @if(\Carbon\Carbon::now()->toDateString() >= $start_date && \Carbon\Carbon::now()->toDateString() <= $end_date)
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button>
                    @else
                    <a class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | 
                    @endif
                    @endif
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    {{-- Modal --}}
                    <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-body"> 
                              <div class="modal-toggle-wrapper">  
                                <ul class="modal-img ">
                                  <li class="text-center"><img src="{{ asset('assets/images/gif/danger.gif') }}" alt="error" width="200" height="200"></li>
                                </ul>
                                <div class="alert alert-danger">
                                    {{-- <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2024, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal 17 Desember 2024 - 2 Januari 2025 dan akan dibuka kembali tanggal 3 Januari 2025.<br/>Terima kasih atas perhatiannya.</span> --}}
                                    {{-- <span>Dear Insan Alita,<br/>Sehubungan dengan Libur Hari Raya Idul Fitri 1446H, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal 17 Maret 2025 - 8 April 2025 dan akan dibuka kembali tanggal 9 April 2025.<br/>Terima kasih atas perhatiannya.</span> --}}
                                    <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2025, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }} dan akan dibuka kembali pada {{ \Carbon\Carbon::parse($end_date)->addDay()->translatedFormat('d F Y') }}.<br/>Terima kasih atas perhatian dan kerja samanya.</span>
                                </div>
                                <button class="btn btn-danger d-flex m-auto" type="button" data-bs-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    {{--  --}}
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2 mb-2" action="{{ route('reimburse.index') }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_perusahaan">
                                        @foreach ($perusahaan as $data)
                                            <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="{{ $data->id }}" @if (in_array($data->id, $arr_perusahaan)) checked @endif>{{ $data->nama }}<br />
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                        <label class="d-block" for="kode_divisi">
                                            <div id="div_kode_divisi">
                                                @foreach ($divisi as $data)
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="{{ $data->id }}" @if (in_array($data->id, $arr_divisi)) checked @endif>{{ $data->nama }}<br />
                                                @endforeach
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_dari }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="{{ $bulan_tahun_sampai }}">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-3">
                                    <label class="h6">Tahun</label>&nbsp;<input type="checkbox" onClick="toggle_tahun(this)" /> Check All
                                    <label class="d-block" for="tahun">
                                        @foreach ($tahun as $data)
                                        <input class="checkbox_animated" id="tahun[]" name="tahun[]" type="checkbox" value="{{ $data }}" @if(in_array($data, $arr_tahun)) checked @endif> {{ $data }}<br/>
                                        @endforeach
                                    </label> --}}
                                    {{-- tidak digunakan --}}
                                    {{-- <label class="h6">Nama Karyawan</label>&nbsp;<input type="checkbox" onClick="toggle_nik_karyawan(this)" /> Check All
                                    <label class="d-block" for="nik_karyawan">
                                        @foreach ($nik_karyawan as $data)
                                        <input class="checkbox_animated" id="nik_karyawan[]" name="nik_karyawan[]" type="checkbox" value="{{ $data->nik }}" @if(in_array($data->nik, $arr_nik_karyawan)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label> --}}
                                {{-- </div> --}}
                                <div class="col-sm-3">
                                    <label class="h6">Layanan</label>&nbsp;<input type="checkbox" onClick="toggle_layanan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="layanan">
                                        @foreach ($layanan as $data)
                                        <input class="checkbox_animated" id="layanan[]" name="layanan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_layanan)) checked @endif> {{ $data->layanan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_pengajuan">
                                        @foreach ($status_pengajuan as $data)
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_status_pengajuan)) checked @endif> {{ $data->status }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('reimburse.index') }}" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    {{-- <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- @if(\Carbon\Carbon::now()->toDateString() >= '2024-12-16' && \Carbon\Carbon::now()->toDateString() <= '2025-01-02') --}}
                    {{-- @if(\Carbon\Carbon::now()->toDateString() >= '2025-03-06' && \Carbon\Carbon::now()->toDateString() <= '2025-04-08') --}}
                    @if(\Carbon\Carbon::now()->isToday() && \Carbon\Carbon::now()->toDateString() <= $end_date)
                    <div class="mt-2 alert alert-danger">
                        {{-- <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2024, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal 17 Desember 2024 - 2 Januari 2025 dan akan dibuka kembali tanggal 3 Januari 2025.<br/>Terima kasih atas perhatiannya.</span> --}}
                        {{-- <span>Dear Insan Alita,<br/>Sehubungan dengan Libur Hari Raya Idul Fitri 1446H, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal 17 Maret 2025 - 8 April 2025 dan akan dibuka kembali tanggal 9 April 2025.<br/>Terima kasih atas perhatiannya.</span> --}}
                        <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2025, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }} dan akan dibuka kembali pada {{ \Carbon\Carbon::parse($end_date)->addDay()->translatedFormat('d F Y') }}.<br/>Terima kasih atas perhatian dan kerja samanya.</span>
                    </div>
                    @endif
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    @can('hc')
                                    <th>Findim Sub Departemen</th>
                                    <th>Findim Departemen</th>
                                    <th>Findim Divisi</th>
                                    <th>Project</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Status Karyawan</th>
                                    <th>Status Aktif</th>
                                    <th>Jabatan</th>
                                    <th>Level</th>
                                    @endcan
                                    <th>Tahun</th>
                                    {{-- <th>Nominal Klaim</th> --}}
                                    <th>Klaim Kesehatan</th>
                                    <th>Klaim Non Kesehatan</th>
                                    @can('hc')
                                    @php
                                     $non_kesehatan = App\Models\ReimburseLayanan::all();
                                    @endphp
                                    <th>{{ $non_kesehatan->find(2)->layanan }}</th>
                                    <th>{{ $non_kesehatan->find(3)->layanan }}</th>
                                    <th>{{ $non_kesehatan->find(4)->layanan }}</th>
                                    <th>{{ $non_kesehatan->find(5)->layanan }}</th>
                                    <th>{{ $non_kesehatan->find(6)->layanan }}</th>
                                    <th>{{ $non_kesehatan->find(7)->layanan }}</th>
                                    @endcan
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        @if($data->status_pengajuan == 1)<a href="{{ route('reimburse.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>@endif
                                        &nbsp;<a href="{{ route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')]) }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @can('hc')&nbsp;<a href="{{ route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf'), 'isHC' => true]) }}" data-toggle="tooltip" title="PDF HC" target="_blank"><i class="fa fa-file-pdf-o" style="color:orange"></i></a>@endcan
                                        &nbsp;<a href="{{ route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf'), 'isHC' => false]) }}" data-toggle="tooltip" title="PDF NHC" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td>{{ $data->_nik->nama }}</td>
                                    <td>{{ $data->_nik->nik }}</td>
                                    @can('hc')
                                    @php
                                        $karyawan_property = json_decode($data->karyawan_property, true);
                                        $trxD = App\Models\TrxReimburseDetail::where(['id_reimburse'=>$data->id])->get();
                                    @endphp
                                    <td>{{ $karyawan_property['findim_subdepartemen'] }}</td>
                                    <td>{{ $karyawan_property['findim_departemen'] }}</td>
                                    <td>{{ $karyawan_property['findim_divisi'] }}</td>
                                    <td>{{ $karyawan_property['findim_project'] }}</td>
                                    <td>{{ $karyawan_property['divisi'] }}</td>
                                    <td>{{ $karyawan_property['dep'] }}</td>
                                    <td>{{ $karyawan_property['sub_dep'] }}</td>
                                    <td>{{ $karyawan_property['status_karyawan'] }}</td>
                                    <td>@if(App\Models\Karyawan::where(['nik'=>$data->nik_karyawan, 'is_aktif'=>1])->exists()) Aktif @else Non Aktif @endif</td>
                                    <td>{{ $karyawan_property['jabatan'] }}</td>
                                    <td>{{ $karyawan_property['level'] }}</td>
                                    @endcan
                                    <td>{{ $data->periode }}</td>
                                    {{-- <td>Rp. {{ number_format($data->klaim_kesehatan + $data->klaim_non_kesehatan, 0) }}</td> --}}
                                    <td>Rp. {{ number_format($data->klaim_kesehatan, 0) }}</td>
                                    <td>Rp. {{ number_format($data->klaim_non_kesehatan, 0) }}</td>
                                    @can('hc')
                                    <td>@if(!empty($trxD->where('id_layanan', 2)->first())) Rp. {{ number_format($trxD->where('id_layanan', 2)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    <td>@if(!empty($trxD->where('id_layanan', 3)->first())) Rp. {{ number_format($trxD->where('id_layanan', 3)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    <td>@if(!empty($trxD->where('id_layanan', 4)->first())) Rp. {{ number_format($trxD->where('id_layanan', 4)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    <td>@if(!empty($trxD->where('id_layanan', 5)->first())) Rp. {{ number_format($trxD->where('id_layanan', 5)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    <td>@if(!empty($trxD->where('id_layanan', 6)->first())) Rp. {{ number_format($trxD->where('id_layanan', 6)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    <td>@if(!empty($trxD->where('id_layanan', 7)->first())) Rp. {{ number_format($trxD->where('id_layanan', 7)->first()->jumlah_klaim, 0) }} @else Rp. 0 @endif</td>
                                    @endcan
                                    <td>{{ $data->_status->status }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>{{ $data->updated_at }}</td>
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
function PilihKaryawan() {
    var nik_karyawan = "{{ Crypt::encrypt(auth()->user()->nik) }}";
    @can('hc')
    if($('#nik_karyawan').val() != '0'){
        nik_karyawan = $('#nik_karyawan').val();
    }
    @endcan
    location.href = "{{ route('reimburse.create') }}"+"?nik_karyawan="+nik_karyawan;
}
function toggle_status_pengajuan(source) {
    checkboxes = document.getElementsByName('status_pengajuan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_layanan(source) {
    checkboxes = document.getElementsByName('layanan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik_karyawan(source) {
    checkboxes = document.getElementsByName('nik_karyawan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function get_kode_perusahaan() {
    getDivisi();
}
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('kode_divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
function getDivisi() {
    var _kode_perusahaan = [];
    $('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    $('#div_kode_divisi').empty();
    // AJAX request 
    $.ajax({
        url: "{{ route('getDivisi') }}" + "/" + _kode_perusahaan,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_divisi[]' name='kode_divisi[]' type='checkbox' onClick='get_kode_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_divisi").append(option);
                }
            }
        }
    });
}
// 
$('#_datatable').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
</script>
@endsection