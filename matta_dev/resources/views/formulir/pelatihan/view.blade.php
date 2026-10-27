@extends('layouts.simple.master')

@section('title', 'Formulir Pengajuan Pelatihan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
{{--  --}}
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- <div class="row project-cards"> --}}
        <div class="col-sm-12">
            @if(Session::has('msg'))
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6>{{ session('msg') }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="row">
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        {{-- start --}}
                        <div class="card-header">
                            <h5>Pengaju</h5>
                        </div>
                        <div class="card-header">
                            <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%">{{ $pengaju['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>{{ $pengaju['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>{{ $pengaju['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td>{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- end --}}
                        {{-- start --}}
                        <div class="card-header">
                            <h5>Peserta</h5>
                        </div>
                        @foreach($arrPeserta as $value)
                        <div class="card-header">
                            <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%">{{ $value['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>{{ $value['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>{{ $value['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        {{-- end --}}
                        {{-- start --}}
                        <div class="card-header">
                            <h5>Atasan Peserta</h5>
                        </div>
                        @foreach($arrAtasanPeserta as $value)
                        <div class="card-header">
                            <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%">{{ $value['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>{{ $value['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>{{ $value['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        {{-- end --}}
                    </div>
                </div>
                <!--  -->
                {{-- <div class="col-sm-4">
                    <div class="card">
                        
                    </div>
                </div> --}}
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        {{-- start --}}
                        <div class="card-header">
                            {{-- <h5>Pelatihan @can('hc')<a data-bs-toggle="modal" data-bs-target="#modal_edit" href="#modal_edit" title="Edit"><i class="icon-pencil-alt"></i></a>@endcan</h5> --}}
                            <h5>Pelatihan @can('hc') <a href="{{ route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('edit'), 'back_url' => url()->full()]) }}" data-toggle="tooltip" title="Edit"><i class="icon-pencil-alt"></i></a> @endcan</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Pengajuan Pelatihan ke</td>
                                        <td width="60%">{{ $trx->ke }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td>{{ $trx->tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>
                                            @foreach($arrJenis as $value)
                                                {{ $value->jenis }} @if(!$loop->last) <br/> @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @if(!empty($trx->int_ext))
                                    <tr>
                                        <td>Mode</td>
                                        <td>
                                            @foreach($int_ext as $key => $value)
                                            @if($key == $trx->int_ext) {{ $value }} @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Judul</td>
                                        <td>{{ $trx->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penyelenggara</td>
                                        <td>{{ $trx->penyelenggara }}</td>
                                    </tr>
                                    @php
                                    // $tanggal_range = [];
                                    // if(!empty($trx->tanggal_range)){
                                        $tanggal_range = json_decode($trx->tanggal_range, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_range);
                                    // }
                                    @endphp
                                    <tr>
                                        <td>Tanggal Pelaksanaan</td>
                                        {{-- @if(empty($trx->tanggal_range))
                                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y') }} s/d {{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y') }}</td>
                                        @else --}}
                                        <td>@foreach($formatted_ranges as $_tanggal_range){{ $_tanggal_range }} @if(!$loop->last) <br/> @endif @endforeach</td>
                                        {{-- @endif --}}
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td>{{ $trx->tempat }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>Rp. {{ number_format($trx->biaya, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Benefit</td>
                                        <td>
                                            @foreach($arrBenefit as $value)
                                                {{ $value->benefit }} @if(!$loop->last) <br/> @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td>{{ $trx->_anggaran->anggaran }}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>{!! $trx->keterangan !!}</td>
                                    </tr>
                                    @php $filePath = public_path('assets/pelatihan/' . $trx->lampiran); @endphp
                                    <tr>
                                        <td>Lampiran</td>
                                        <td>
                                            @if(!empty($trx->lampiran) && file_exists($filePath))
                                                <a href="{{ asset('assets/pelatihan/' . $trx->lampiran) }}" target="_blank">Lihat Lampiran</a>
                                            @else
                                                Tidak ada lampiran
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Catatan</td>
                                        <td>{!! $trx->catatan !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- end --}}
                    </div>
                    <div class="card">
                        {{-- start --}}
                        <div class="card-header">
                            <h5>History</h5>
                        </div>
                        <div class="card-body notification">
                            <ul>
                                @php
                                    // status
                                    // 1=Submit by Requestor
                                    // 2=Approve by L1 Requestor
                                    // 5=Approve by L1 Participants
                                    // 10=Approve by Reviewer 1
                                    // 14=Approve by Reviewer 2
                                    // 11=Done by HC
                                    $remains = [];
                                    if(!empty($trx->reviewer2)){
                                        array_push($remains, [14=>$trx->reviewer2]);
                                    }
                                    if(!empty($trx->reviewer1)){
                                        array_push($remains, [10=>$trx->reviewer1]);
                                    }
                                    foreach(explode(';', $trx->atasan_peserta) as $value){
                                        array_push($remains, [5=>$value]);
                                    }
                                    array_push($remains, [2=>$trx->atasan_pengaju]);
                                @endphp
                                @foreach ($remains as $remain)
                                    @foreach($remain as $key => $value)
                                    @if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty())
                                    <li class="d-flex">
                                        <div class="activity-dot-dark"></div>
                                        <div class="ms-3">
                                            <h6 class="f-light" style="color: #adb5bd;">{{ App\Models\PelatihanStatus::find($key)->status_pengajuan }}</h6>
                                            <p class="f-light" style="color: #adb5bd;">Oleh {{ App\Models\Karyawan::where('nik',$value)->first()->nama }} / {{ $value }}</p>
                                        </div>
                                    </li>
                                    @endif
                                    @endforeach
                                @endforeach
                            </ul>
                            <ul>
                                @foreach($history as $_history)
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
                                        <h6>{{ $_history->_status->status_pengajuan }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- end --}}
                    </div>
                    @can('hc')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id)) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @method('PATCH')
                            @csrf
                            {{-- <div class="row">
                                <div class="col-sm-4"> --}}
                                    <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                                    <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach($status_pengajuan as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $trx->status_pengajuan) selected @endif >{{ $data->status_pengajuan }}</option>
                                        @endforeach
                                    </select>
                                {{-- </div>
                            </div> --}}
                            <button class="btn btn-success mt-2 mb-2" type="submit">Update</button> | <a href="{{ route('pelatihan.index') }}" class="btn btn-warning mt-2 mb-2" type="button">Back</a>
                            </form>
                        </div>
                    </div>
                    @endcan
                </div>
                <!--  -->
                {{-- <div class="col-12">
                    <div class="appointment">
                        <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div> --}}
            </div>
        </div>
    {{-- </div> --}}
</div>
{{-- <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
        <form action="{{ route('pelatihan.save_update', [$trx->id, 'back_url' => url()->full()]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate onsubmit="return confirm('Are you sure want to submit this data?');">
        @csrf
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel2">Edit</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-6">
                <div class="col-md-12 pb-2">
                    <label class="form-label" for="peserta[]">Peserta*</label>
                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="peserta[]" name="peserta[]" required="">
                        @foreach($karyawan as $data)
                        <option @if(in_array($data->nik, explode(';', $trx->peserta))) selected @endif>{{ $data->nama }} - {{ $data->nik }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback txt-danger">Peserta is required.</div>
                </div>
                <div class="col-md-12 pb-2">
                    <label class="form-label" for="tahun">Tahun*</label>
                    <input min=0 type="number" name="tahun" id="tahun" class="form-control numbers" required="" value="{{ $trx->tahun }}">
                    <div class="invalid-feedback txt-danger">Tahun is required.</div>
                </div>
                <div class="col-md-12 pb-2">
                    <label class="form-label" for="jenis[]">Jenis Pelatihan*</label>
                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="jenis[]" name="jenis[]" required="">
                        @foreach($jenis as $data)
                        <option value="{{ $data->id }}" @if(in_array($data->id, explode(';', $trx->jenis))) selected @endif>{{ $data->jenis }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback txt-danger">Jenis Pelatihan is required.</div>
                </div>
                <div class="col-md-12 pb-2">
                    <label class="form-label" for="judul">Judul Pelatihan / Sertifikasi*</label>
                    <input name="judul" id="judul" class="form-control" type="text" required="" value="{{ $trx->judul }}">
                    <div class="invalid-feedback txt-danger">Judul Pelatihan / Sertifikasi is required.</div>
                </div>
                <div class="col-md-12 pb-2">
                    <label class="form-label" for="penyelenggara">Penyelenggara*</label>
                    <input name="penyelenggara" id="penyelenggara" class="form-control" type="text" required="" value="{{ $trx->penyelenggara }}">
                    <div class="invalid-feedback txt-danger">Penyelenggara is required.</div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Tanggal Pelaksanaan*</label>
                </div>
                <div class="row" id="tanggal-container">
                    <div class="col-md-12 pb-2 tanggal-group">
                        <input type="text" name="tanggal_range[]" id="tanggal_range[]" class="form-control tanggal-range" required readonly>
                        <div class="invalid-feedback txt-danger">Tanggal Pelaksanaan is required.</div>
                        <button type="button" class="btn btn-primary mt-2" id="add-tanggal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer justify-content-start">
             <button class="btn btn-success" type="submit">Submit</button> | <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
       </div>
    </div>
 </div> --}}
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
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
    node.value = value.toLocaleString('en');  // or other formatting
}
// 
</script>
@endsection