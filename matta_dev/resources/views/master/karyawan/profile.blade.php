@extends('layouts.simple.master')
@section('title', 'Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Profile</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Users</li>
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            @if ($message = Session::get('success'))
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6>{{ $message }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (count($errors) > 0)
            <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                <h6>Upload Foto is ERROR</h6>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Organisasi @if ($karyawan->is_aktif == 1) <span class="badge badge-success">Aktif</span> @else <span class="badge badge-danger">Non Aktif</span> @endif</h4>
                        <!-- <div class="card-options">
                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <!--  -->
                            <div class="row mb-2">
                                <div class="profile-title">
                                    <div class="media">
                                        <!-- <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}" target="_blank">
                                            <img class="img-100 rounded-3" alt="" src="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}">
                                        </a> -->
                                        @if (file_exists(public_path().'/assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg'))
                                            <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}" target="_blank">
                                                <img class="img-100 rounded-3" alt="" src="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}">
                                            </a>
                                        @else
                                            <a href="{{ asset('assets/images/matta/karyawan/user.png') }}" target="_blank">
                                                <img class="img-100 rounded-3" alt="" src="{{ asset('assets/images/matta/karyawan/user.png') }}">
                                            </a>
                                        @endif
                                        <div class="media-body">
                                            <h5 class="mb-1">{{ $karyawan->nama }}</h5>
                                            <p>{{ $karyawan->_jabatan->jabatan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Organisasi</label>
                                <textarea class="form-control" rows="6">Perusahaan: {{ $karyawan->_perusahaan->nama }}&#13;Direktorat: {{ $karyawan->_divisi->nama }}&#13;Divisi: {{ $karyawan->_departemen->nama }}&#13;departemen: {{ $karyawan->_subdepartemen->nama }}&#13;Level: {{ $karyawan->_level->level }}&#13;Jabatan: {{ $karyawan->_jabatan->jabatan }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Layer 1</label>
                                    <input class="form-control" value="{{ $karyawan->_layer1->nama }}">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Layer 2</label>
                                    <input class="form-control" value="{{ $karyawan->_layer2->nama }}">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Layer 3</label>
                                    <input class="form-control" value="{{ $karyawan->_layer3->nama }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" value="{{ $karyawan->email }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input class="form-control" value="{{ $karyawan->nik }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Status Kerja</label>
                                    <input class="form-control" value="{{ $karyawan->_status->status_karyawan }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Grup Kerja</label>
                                    <input class="form-control" value="{{ $karyawan->_wg->wg_name }}">
                                    </div>
                                </div>
                            </div>
                        <!--  -->
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <form id="form" class="card" action="{{ route('karyawan.update_profile', (new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik)) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                @method('POST')
                @csrf
                    <div class="card-header">
                        <h4 class="card-title mb-0">Data Pribadi</h4>
                        <!-- <div class="card-options">
                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input name="tanggal_lahir" id="tanggal_lahir" class="form-control" type="date" value="{{ $karyawan->tanggal_lahir }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text" value="{{ $karyawan->tempat_lahir }}" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Domisili</label>
                                    <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="2" >{{ $karyawan->alamat_domisili }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat KTP</label>
                                    <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="2" >{{ $karyawan->alamat_ktp }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat NPWP</label>
                                    <textarea name="alamat_npwp" id="alamat_npwp" class="form-control" rows="2" >{{ $karyawan->alamat_npwp }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Telepon 1</label>
                                    <input name="telepon1" id="telepon1" class="form-control" type="text" value="{{ $karyawan->telepon1 }}" >
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Telepon 2</label>
                                    <input name="telepon2" id="telepon2" class="form-control" type="text" value="{{ $karyawan->telepon2 }}" >
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Telepon 3</label>
                                    <input name="telepon3" id="telepon3" class="form-control" type="text" value="{{ $karyawan->telepon3 }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK KTP</label>
                                    <input name="no_ktp" id="no_ktp" class="form-control" type="text" value="{{ $karyawan->no_ktp }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NPWP</label>
                                    <input name="no_npwp" id="no_npwp" class="form-control" type="text" value="{{ $karyawan->no_npwp }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No BPJS Kesehatan</label>
                                    <input name="no_bpjs" id="no_bpjs" class="form-control" type="text" value="{{ $karyawan->no_bpjs }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No BPJS Ketenagakerjaan</label>
                                    <input name="no_bpjstk" id="no_bpjstk" class="form-control" type="text" value="{{ $karyawan->no_bpjstk }}" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="kode_kelamin" name="kode_kelamin" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($kelamin as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_kelamin) selected @endif >{{ $data->kelamin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Agama</label>
                                    <select class="form-select" id="kode_agama" name="kode_agama" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($agama as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_agama) selected @endif >{{ $data->agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status Sipil</label>
                                    <select class="form-select" id="kode_kawin" name="kode_kawin" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($kawin as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_kawin) selected @endif >{{ $data->status_kawin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pasangan</label>
                                    <input name="nama_pasangan" id="nama_pasangan" class="form-control" type="text" value="{{ $karyawan->nama_pasangan }}" >
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kewarganegaraan</label>
                                    <select class="form-select" id="kode_warga" name="kode_warga" >
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($warga as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_warga) selected @endif >{{ $data->kewarganegaraan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <button class="btn btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- start -->
            <div class="col-sm-12 col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-emp_anak-tab" data-bs-toggle="pill" href="#pills-emp_anak" role="tab" aria-controls="pills-emp_anak" aria-selected="false">Nama Anak</a></li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-emp_edu-tab" data-bs-toggle="pill" href="#pills-emp_edu" role="tab" aria-controls="pills-emp_edu" aria-selected="false">Pendidikan</a></li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-emp_lat-tab" data-bs-toggle="pill" href="#pills-emp_lat" role="tab" aria-controls="pills-emp_lat" aria-selected="false">Pelatihan</a></li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-emp_ser-tab" data-bs-toggle="pill" href="#pills-emp_ser" role="tab" aria-controls="pills-emp_ser" aria-selected="false">Sertifikasi</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-emp_anak" role="tabpanel" aria-labelledby="pills-emp_anak-tab">
                                <p class="mb-0 m-t-30">
                                    <a href="{{ route('karyawananak.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open'] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-anak">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Urutan</th>
                                                    <th>Nama</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($anak as $data)
                                                <tr>
                                                    <td>{{ ++$no_anak }}</td>
                                                    <td>{{ $data->_anakke->anakke }}</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>
                                                        <a href="{{ route('karyawananak.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open']) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_edu" role="tabpanel" aria-labelledby="pills-emp_edu-tab">
                                <p class="mb-0 m-t-30">
                                    <a href="{{ route('karyawanpendidikan.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open'] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-pendidikan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenjang Pendidikan</th>
                                                    <th>Sekolah/Universitas</th>
                                                    <th>Jurusan/Prodi</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pendidikan as $data)
                                                <tr>
                                                    <td>{{ ++$no_pendidikan }}</td>
                                                    <td>{{ $data->_jenjang->jenjang }}</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->jurusan_prodi }}</td>
                                                    <td>
                                                        <a href="{{ route('karyawanpendidikan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open']) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_lat" role="tabpanel" aria-labelledby="pills-emp_lat-tab">
                            <p class="mb-0 m-t-30">
                                    <a href="{{ route('karyawanpelatihan.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open'] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-pelatihan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pelatihan</th>
                                                    <th>Keterangan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pelatihan as $data)
                                                <tr>
                                                    <td>{{ ++$no_pelatihan }}</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    <td>
                                                        <a href="{{ route('karyawanpelatihan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open']) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_ser" role="tabpanel" aria-labelledby="pills-emp_ser-tab">
                            <p class="mb-0 m-t-30">
                                    <a href="{{ route('karyawansertifikasi.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open'] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                                    <div class="table-responsive">
                                        <table class="display" id="basic-sertifikasi">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Sertifikasi</th>
                                                    <th>Keterangan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sertifikasi as $data)
                                                <tr>
                                                    <td>{{ ++$no_sertifikasi }}</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    <td>
                                                        <a href="{{ route('karyawansertifikasi.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->id), 'src'=>'open']) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- finish -->
        <!-- <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a><br/><br/> -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    // 
    $('#basic-pendidikan').DataTable();
    $('#basic-pelatihan').DataTable();
    $('#basic-sertifikasi').DataTable();
    $('#basic-anak').DataTable();
    // 
});
</script>
@endsection