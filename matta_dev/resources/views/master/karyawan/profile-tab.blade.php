@extends('layouts.simple.master')
@section('title', 'Data Master Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>@if ($status == 'edit') Edit @else Add @endif</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Data Master</li>
    <li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- start -->
            @if ($status == 'edit')
            <form id="form" action="{{ route('karyawan.update', $karyawan->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
            @method('PATCH')
            @else
            <form id="form" action="{{ route('karyawan.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
            @endif
            @csrf
            <div class="col-sm-12 col-xxl-12">
                <div class="card">
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
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="pills-emp_profile-tab" data-bs-toggle="pill"
                                    href="#pills-emp_profile" role="tab" aria-controls="pills-emp_profile" aria-selected="false">Data Pribadi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_organization-tab"
                                    data-bs-toggle="pill" href="#pills-emp_organization" role="tab" aria-controls="pills-emp_organization" aria-selected="true">Organisasi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_salary-tab" data-bs-toggle="pill"
                                    href="#pills-emp_salary" role="tab" aria-controls="pills-emp_salary" aria-selected="false">Gaji Pokok</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_level-tab" data-bs-toggle="pill"
                                    href="#pills-emp_level" role="tab" aria-controls="pills-emp_level" aria-selected="false">Level/Jabatan/Tanggal</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_findim-tab" data-bs-toggle="pill"
                                    href="#pills-emp_findim" role="tab" aria-controls="pills-emp_findim" aria-selected="false">FinDim/Cost Allocation</a></li>
                            @if ($status == 'edit')
                            <li class="nav-item"><a class="nav-link" id="pills-emp_history-tab" data-bs-toggle="pill"
                                    href="#pills-emp_history" role="tab" aria-controls="pills-emp_history" aria-selected="false">Perubahan Data</a></li>
                            @endif
                        </ul>
                        <!--  -->
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-emp_profile" role="tabpanel" aria-labelledby="pills-emp_profile-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="lensa">NIP</label>
                                            @if ($status=='edit' )
                                            <input name="nik" id="nik" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->nik }}" @endif readonly="" required="">
                                            @else
                                            <input name="nik" id="nik" class="form-control" type="text" required="">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama">Nama</label>
                                            <input name="nama" id="nama" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->nama }}" @endif required="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                            <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->tempat_lahir }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                            <input name="tanggal_lahir" id="tanggal_lahir" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_lahir }}" @endif >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_kelamin">Jenis Kelamin</label>
                                            <select class="form-select" id="kode_kelamin" name="kode_kelamin" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($kelamin as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_kelamin) selected @endif @endif >{{ $data->kelamin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_agama">Agama</label>
                                            <select class="form-select" id="kode_agama" name="kode_agama" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($agama as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_agama) selected @endif @endif >{{ $data->agama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_warga">Kewarganegaraan</label>
                                            <select class="form-select" id="kode_warga" name="kode_warga" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($warga as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_warga) selected @endif @endif >{{ $data->kewarganegaraan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_ktp">NIK KTP</label>
                                            <input name="no_ktp" id="no_ktp" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->no_ktp }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_npwp">NPWP</label>
                                            <input name="no_npwp" id="no_npwp" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->no_npwp }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_bpjs">No BPJS Kesehatan</label>
                                            <input name="no_bpjs" id="no_bpjs" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->no_bpjs }}" @endif >
                                            <label class="form-label" for="no_bpjstk">No BPJS Ketenagakerjaan</label>
                                            <input name="no_bpjstk" id="no_bpjstk" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->no_bpjstk }}" @endif >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_domisili">Alamat Domisili</label>
                                            <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="3" >@if ($status == 'edit'){{ $karyawan->alamat_domisili }}@endif</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_ktp">Alamat KTP</label>
                                            <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="3" >@if ($status == 'edit'){{ $karyawan->alamat_ktp }}@endif</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_npwp">Alamat NPWP</label>
                                            <textarea name="alamat_npwp" id="alamat_npwp" class="form-control" rows="3" >@if ($status == 'edit'){{ $karyawan->alamat_npwp }}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon1">Telepon 1</label>
                                            <input name="telepon1" id="telepon1" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->telepon1 }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon2">Telepon 2</label>
                                            <input name="telepon2" id="telepon2" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->telepon2 }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon3">Telepon 3</label>
                                            <input name="telepon3" id="telepon3" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->telepon3 }}" @endif >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="telepon_darurat">Telepon Darurat</label>
                                            <input name="telepon_darurat" id="telepon_darurat" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->telepon_darurat }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_kawin">Status Sipil</label>
                                            <select class="form-select" id="kode_kawin" name="kode_kawin" >
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($kawin as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_kawin) selected @endif @endif >{{ $data->status_kawin }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_pasangan">Nama Pasangan</label>
                                            <input name="nama_pasangan" id="nama_pasangan" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->nama_pasangan }}" @endif >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="no_rek">Nomor Rekening</label>
                                            <input name="no_rek" id="no_rek" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->no_rek }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_bank">Nama Bank</label>
                                            <input name="nama_bank" id="nama_bank" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->nama_bank }}" @endif >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="nama_rek">Rekening Atas Nama</label>
                                            <input name="nama_rek" id="nama_rek" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->nama_rek }}" @endif >
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <!--  -->
                            <div class="tab-pane fade" id="pills-emp_organization" role="tabpanel" aria-labelledby="pills-emp_organization-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="email">Email</label>
                                            <input name="email" id="email" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->email }}" @endif required="">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_perusahaan">Perusahaan</label>
                                            <select class="form-select" id="kode_perusahaan" name="kode_perusahaan" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @foreach ($perusahaan as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_perusahaan) selected @endif @endif >{{ $data->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="alamat_kantor">Alamat Kantor</label>
                                            <select class="form-select" id="alamat_kantor" name="alamat_kantor" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @foreach ($alamat_kantor as $data)
                                                <option value="{{ $data['key'] }}" @if ($status=='edit' ) @if($data['key'] == $karyawan->alamat_kantor) selected @endif @endif >{{ $data['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_divisi">Direktorat</label>
                                            <select class="form-select" id="kode_divisi" name="kode_divisi" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @if ($status=='edit' ) 
                                                @foreach ($divisi as $data)
                                                <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_divisi) selected @endif >{{ $data->nama }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_departemen">Divisi</label>
                                            <select class="form-select" id="kode_departemen" name="kode_departemen" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @if ($status=='edit' ) 
                                                @foreach ($dep as $data)
                                                <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_departemen) selected @endif >{{ $data->nama }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_sub_departemen">Departemen</label>
                                            <select class="form-select" id="kode_sub_departemen" name="kode_sub_departemen" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @if ($status=='edit' ) 
                                                @foreach ($sub_dep as $data)
                                                <option value="{{ $data->id }}" @if($data->id == $karyawan->kode_sub_departemen) selected @endif >{{ $data->nama }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer1">Layer 1</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer1" name="layer1" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($layer as $data)
                                                <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $karyawan->layer1) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer2">Layer 2</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer2" name="layer2" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($layer as $data)
                                                <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $karyawan->layer2) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="layer3">Layer 3</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="layer3" name="layer3" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($layer as $data)
                                                <option value="{{ $data->nik }}" @if ($status=='edit' ) @if($data->nik == $karyawan->layer3) selected @endif @endif >{{ $data->nama }} / {{ $data->nik }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="kode_status">Status Karyawan</label>
                                            <select class="form-select id="kode_status" name="kode_status" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($kar_status as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_status) selected @endif @endif >{{ $data->status_karyawan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="wg_id">Grup Kerja</label>
                                            <select class="form-select" id="wg_id" name="wg_id" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($wg as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->wg_id) selected @endif @endif >{{ $data->wg_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="is_aktif">Aktif/Non Aktif</label>
                                            <select class="form-select" id="is_aktif" name="is_aktif" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($is_aktif as $data)
                                                <option value="{{ $data['id'] }}" @if ($status=='edit' ) @if($data['id'] == $karyawan->is_aktif) selected @endif @endif >{{ $data['status'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="id_fp">ID Finger Print</label>
                                            <input id="id_fp" name="id_fp" min="0" class="form-control numbers" type="number" @if ($status=='edit' ) value="{{ $karyawan->id_fp }}" @endif>
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_salary" role="tabpanel" aria-labelledby="pills-emp_salary-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="gaji_pokok">Gaji Pokok</label>
                                            <input id="gaji_pokok" name="gaji_pokok" min="0" class="form-control numbers" type="number" @if ($status=='edit' ) value="{{ $karyawan->gaji_pokok }}" @endif required="">
                                        </div>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_level" role="tabpanel" aria-labelledby="pills-emp_level-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label class="form-label" for="kode_level">Level</label>
                                            <select class="form-select" id="kode_level" name="kode_level" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($level as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_level) selected @endif @endif >{{ $data->level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="id_grade">Grade</label>
                                            {{-- <input name="id_grade" id="id_grade" class="form-control" type="text" @if ($status=='edit' ) value="{{ $karyawan->id_grade }}" @endif > --}}
                                            <select class="form-select" id="id_grade" name="id_grade" required="">
                                                <option selected="" value="">Pilih...</option>
                                                @if ($status=='edit' ) 
                                                @foreach ($id_grade as $data)
                                                <option value="{{ $data->id }}" @if($data->id == $karyawan->id_grade) selected @endif >{{ $data->grade }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="kode_jabatan">Jabatan</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="kode_jabatan" name="kode_jabatan" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($jabatan as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->kode_jabatan) selected @endif @endif >{{ $data->jabatan }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Field is required.</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai Bekerja</label>
                                            <input name="tanggal_mulai" id="tanggal_mulai" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_mulai }}" @endif >
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_bergabung">Tanggal Bergabung</label>
                                            <input name="tanggal_bergabung" id="tanggal_bergabung" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_bergabung }}" @endif >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_berakhir">Tanggal Berakhir</label>
                                            <input name="tanggal_berakhir" id="tanggal_berakhir" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_berakhir }}" @endif >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_karyawan_tetap">Tanggal Karyawan Tetap</label>
                                            <input name="tanggal_karyawan_tetap" id="tanggal_karyawan_tetap" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_karyawan_tetap }}" @endif >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="tanggal_tidak_aktif">Tanggal Tidak Aktif</label>
                                            <input name="tanggal_tidak_aktif" id="tanggal_tidak_aktif" class="form-control" type="date" @if ($status=='edit' ) value="{{ $karyawan->tanggal_tidak_aktif }}" @endif >
                                        </div>
                                    </div>
                                </p>
                            </div>
                            {{--  --}}
                            <div class="tab-pane fade" id="pills-emp_findim" role="tabpanel" aria-labelledby="pills-emp_findim-tab">
                                <p class="mb-0 mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_project">Project</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_project" name="findim_project" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($findim_project as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->findim_project) selected @endif @endif>{{ $data->project_id }} - {{ $data->project_desc }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Project is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_divisi">Divisi</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_divisi" name="findim_divisi" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($findim_divisi as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->findim_divisi) selected @endif @endif>{{ $data->divisi_id }} - {{ $data->divisi_desc }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Divisi is required.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_departemen">Departemen</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_departemen" name="findim_departemen" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($findim_departemen as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->findim_departemen) selected @endif @endif>{{ $data->departemen_id }} - {{ $data->departemen_desc }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Departemen is required.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="findim_subdepartemen">Sub Departemen</label>
                                            <select class="form-select js-example-basic-single col-sm-12" id="findim_subdepartemen" name="findim_subdepartemen" required="">
                                                <option selected="" disabled="" value="">Pilih...</option>
                                                @foreach ($findim_subdepartemen as $data)
                                                <option value="{{ $data->id }}" @if ($status=='edit' ) @if($data->id == $karyawan->findim_subdepartemen) selected @endif @endif>{{ $data->subdepartemen_id }} - {{ $data->subdepartemen_desc }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback txt-danger">Sub Departemen is required.</div>
                                        </div>
                                    </div>
                                </p>
                            </div>
                            {{--  --}}
                            @if ($status == 'edit')
                            <div class="tab-pane fade" id="pills-emp_history" role="tabpanel" aria-labelledby="pills-emp_history-tab">
                                <p class="mb-0 mt-2">
                                    <!-- <div class="row mt-2"> -->
                                    <div class="table-responsive">
                                        <table class="display" id="emp-history">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Karyawan</th>
                                                    <th>Diubah Oleh</th>
                                                    <th>Perubahan Data</th>
                                                    <th>Waktu Perubahan</th>
                                                    <th>Sumber</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($history as $data)
                                                @php
                                                    $_history = json_decode($data->perubahan, true);
                                                    $catatan = json_decode($data->catatan, true);
                                                @endphp
                                                <tr>
                                                    <td>{{ ++$no_history }}</td>
                                                    <td>{{ $data->_nik->nama }} / {{ $data->nik }}</td>
                                                    <td>{{ $data->_insert_by->nama }} / {{ $data->insert_by }}</td>
                                                    <td>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Kolom</th>
                                                                    <th>Sebelum</th>
                                                                    <th>Sesudah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($_history as $_data => $val)
                                                                <tr>
                                                                    <td>{{ $lbl_history[$_data] }}</td>
                                                                    <td>@if(in_array($_data, ['layer1','layer2','layer3'])) {{ App\Models\Karyawan::where('nik', $val['before'])->first()->nama }} / @endif {{ $val['before'] }}</td>
                                                                    <td>@if(in_array($_data, ['layer1','layer2','layer3'])) {{ App\Models\Karyawan::where('nik', $val['after'])->first()->nama }} / @endif {{ $val['after'] }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td>{{ $data->created_at }}</td>
                                                    <td>{{ $catatan['sumber'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- </div> -->
                                </p>
                            </div>
                            @endif
                        </div>
                        <hr/>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="{{ route('karyawan.index') }}" class="btn btn-warning" type="button">Back</a>
                    </div>
                </div>
            </div>
            </form>
            <!-- finish -->
            <!-- start -->
            @if ($status=='edit' )
            <div class="col-sm-12 col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="pills-emp_photo-tab" data-bs-toggle="pill"
                                    href="#pills-emp_photo" role="tab" aria-controls="pills-emp_photo" aria-selected="false">Foto</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_anak-tab" data-bs-toggle="pill"
                                    href="#pills-emp_anak" role="tab" aria-controls="pills-emp_anak" aria-selected="false">Nama Anak</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_edu-tab" data-bs-toggle="pill"
                                    href="#pills-emp_edu" role="tab" aria-controls="pills-emp_edu" aria-selected="false">Pendidikan</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_lat-tab" data-bs-toggle="pill"
                                    href="#pills-emp_lat" role="tab" aria-controls="pills-emp_lat" aria-selected="false">Pelatihan / Training</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_ser-tab" data-bs-toggle="pill"
                                    href="#pills-emp_ser" role="tab" aria-controls="pills-emp_ser" aria-selected="false">Sertifikasi</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_sem-tab" data-bs-toggle="pill"
                                    href="#pills-emp_sem" role="tab" aria-controls="pills-emp_sem" aria-selected="false">Seminar / Workshop</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-emp_penilaian-tab" data-bs-toggle="pill"
                                    href="#pills-emp_penilaian" role="tab" aria-controls="pills-emp_penilaian" aria-selected="false">Penilaian</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-emp_photo" role="tabpanel" aria-labelledby="pills-emp_photo-tab">
                                <p class="mb-0 mt-2">
                                @if (file_exists(public_path().'/assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg'))
                                    <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}" target="_blank">
                                        <img class="rounded" width="300" alt="" src="{{ asset('assets/images/matta/karyawan/'.$karyawan->nik.'/'.$karyawan->nik.'.jpg') }}">
                                    </a>
                                @else
                                    <a href="{{ asset('assets/images/matta/karyawan/user.png') }}" target="_blank">
                                        <img class="rounded" width="300" alt="" src="{{ asset('assets/images/matta/karyawan/user.png') }}">
                                    </a>
                                @endif
                                </p><br/>
                                <form action="{{ route('upload.image.store' ) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row"> <br>   
                                        <div class="col-md-4">
                                            <input type="file" name="image" class="form-control">
                                            <input type="hidden" id="nik" name="nik" value="{{ $karyawan->nik }}">
                                        </div>     
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </div>     
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_anak" role="tabpanel" aria-labelledby="pills-emp_anak-tab">
                                <p class="mb-0 mt-2">
                                    <a href="{{ route('karyawananak.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
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
                                                        <a href="{{ route('karyawananak.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_edu" role="tabpanel" aria-labelledby="pills-emp_edu-tab">
                                <p class="mb-0 mt-2">
                                    <a href="{{ route('karyawanpendidikan.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
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
                                                @php $no=0; @endphp
                                                @foreach ($pendidikan as $data)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $data->_jenjang->jenjang }}</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->jurusan_prodi }}</td>
                                                    <td>
                                                        <a href="{{ route('karyawanpendidikan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            {{-- Pelatihan / Training --}}
                            <div class="tab-pane fade" id="pills-emp_lat" role="tabpanel" aria-labelledby="pills-emp_lat-tab">
                            <p class="mb-0 mt-2">
                                    {{-- <a href="{{ route('karyawanpelatihan.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br /> --}}
                                    <div class="table-responsive">
                                        <table class="display" id="basic-pelatihan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pelatihan</th>
                                                    <th>Keterangan</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no=0; @endphp
                                                @foreach ($pelatihan as $data)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $data->judul }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    {{-- <td>
                                                        <a href="{{ route('karyawanpelatihan.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td> --}}
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            {{-- Sertifikasi --}}
                            <div class="tab-pane fade" id="pills-emp_ser" role="tabpanel" aria-labelledby="pills-emp_ser-tab">
                            <p class="mb-0 mt-2">
                                    {{-- <a href="{{ route('karyawansertifikasi.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br /> --}}
                                    <div class="table-responsive">
                                        <table class="display" id="basic-sertifikasi">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Sertifikasi</th>
                                                    <th>Keterangan</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no=0; @endphp
                                                @foreach ($sertifikasi as $data)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $data->judul }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    {{-- <td>
                                                        <a href="{{ route('karyawansertifikasi.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td> --}}
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            {{-- Seminar / Workshop --}}
                            <div class="tab-pane fade" id="pills-emp_sem" role="tabpanel" aria-labelledby="pills-emp_sem-tab">
                            <p class="mb-0 mt-2">
                                    {{-- <a href="{{ route('karyawansertifikasi.create', ['nik'=>(new App\Http\Controllers\OpenDataController)->encryptWithKey($karyawan->nik), 'karyawan_id'=>Crypt::encrypt($karyawan->id)] ) }}" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br /> --}}
                                    <div class="table-responsive">
                                        <table class="display" id="basic-seminar">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Seminar</th>
                                                    <th>Keterangan</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no=0; @endphp
                                                @foreach ($seminar as $data)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $data->judul }}</td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    {{-- <td>
                                                        <a href="{{ route('karyawansertifikasi.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'karyawan_id'=>Crypt::encrypt($karyawan->id)]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a>
                                                    </td> --}}
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="pills-emp_penilaian" role="tabpanel" aria-labelledby="pills-emp_penilaian-tab">
                                <p class="mb-0 mt-2">
                                    <div class="table-responsive">
                                        <table class="display" id="basic-penilaian">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Penilaian Ke</th>
                                                    <th>Tanggal Selesai PKWT</th>
                                                    <th>Tanggal Selesai Penilaian</th>
                                                    <th>Nilai Total Karyawan</th>
                                                    <th>Nilai Total Penilai</th>
                                                    <th>Durasi Perpanjangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 0; @endphp
                                                @foreach ($penilaian as $data)
                                                @php
                                                    $karyawan_property = json_decode($data->karyawan_property, true);
                                                    $urutan = App\Models\TrxPenilaian::where('nik_karyawan', $karyawan_property['nik'])->where('id', '<=', $data->id)->count();
                                                @endphp
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $data->nik_karyawan }}</td>
                                                    <td>{{ $karyawan_property['nama'] }}</td>
                                                    <td>{{ $urutan }}</td>
                                                    <td>
                                                        @if (!empty($karyawan_property['tanggal_berakhir']))
                                                            {{ Carbon\Carbon::parse($karyawan_property['tanggal_berakhir'])->format('d-m-Y') }}
                                                        @endif
                                                    </td>
                                                    {{-- timestamp hco --}}
                                                    @php
                                                        $created_at = App\Models\TrxPenilaianLog::where(['id_trx' => $data->id])->whereIn('id_status', [4, 8])->orderBy('created_at', 'desc')->first();
                                                    @endphp
                                                    <td>
                                                        @if (!empty($created_at))
                                                            {{ $created_at->created_at }}
                                                        @endif
                                                    </td>
                                                    {{-- Nilai Total Karyawan --}}
                                                    @php
                                                        $skor = App\Models\TrxPenilaianSkor::where(['id_trx' => $data->id])->sum('skor_karyawan');
                                                    @endphp
                                                    <td>@if($skor > 0){{ $skor }}@endif</td>
                                                    {{-- Nilai Total Penilai 1 --}}
                                                    @php
                                                        $skor = App\Models\TrxPenilaianSkor::where(['id_trx' => $data->id])->sum('skor_penilai_1');
                                                    @endphp
                                                    <td>{{ $skor }}</td>
                                                    <td>{{ $data->_perpanjang->informasi }} @if ($data->is_perpanjang == 1){{ $data->bulan }} Bulan @endif</td>
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
            @endif
            <!-- finish -->
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
// $(document).ready(function() {
//     function validateForm() {
//         var nik = $("#nik").val();
//         var nama = $("#nama").val();
//         var email = $("#email").val();
//         var kode_perusahaan = $("#kode_perusahaan").val();
//         var kode_divisi = $("#kode_divisi").val();
//         var kode_departemen = $("#kode_departemen").val();
//         var kode_sub_departemen = $("#kode_sub_departemen").val();
//         var layer1 = $("#layer1").val();
//         var layer2 = $("#layer2").val();
//         var layer3 = $("#layer3").val();
//         if (nik == "") {
//             alert("Field nik is required!");
//             return false;
//         }
//         else if (nama == "") {
//             alert("Field nama is required!");
//             return false;
//         }
//         else if (email == "") {
//             alert("Field email is required!");
//             return false;
//         }
//         else if (kode_perusahaan == "") {
//             alert("Field perusahaan is required!");
//             return false;
//         }
//         else if (kode_divisi == "") {
//             alert("Field divisi is required!");
//             return false;
//         }
//         else if (kode_departemen == "") {
//             alert("Field departemen is required!");
//             return false;
//         }
//         else if (kode_sub_departemen == "") {
//             alert("Field sub departemen is required!");
//             return false;
//         }
//         else if (layer1 == "") {
//             alert("Field layer 1 is required!");
//             return false;
//         }
//         else if (layer2 == "") {
//             alert("Field layer 2 is required!");
//             return false;
//         }
//         else if (layer3 == "") {
//             alert("Field layer 3 is required!");
//             return false;
//         }
//     }
// });
$(document).ready(function() {
    // 
    $('#basic-pendidikan').DataTable();
    $('#basic-pelatihan').DataTable();
    $('#basic-sertifikasi').DataTable();
    $('#basic-seminar').DataTable();
    $('#basic-penilaian').DataTable();
    $('#basic-anak').DataTable();
    $('#emp-history').DataTable();
    // 
    var _kode_perusahaan = 0;
    var _kode_divisi = 0;
    var _kode_departemen = 0;
    var _kode_sub_departemen = 0;
    // 
    $("#kode_level").change(function () {
        var kode_level = $(this).val();
        $('#id_grade').find('option').not(':first').remove();
        @foreach ($id_grade as $data)
            var option = "<option value={{ $data->id }}>{{ $data->grade }}</option>";
            if(kode_level == {{ $data->id }}){
                option = "<option value={{ $data->id }} selected>{{ $data->grade }}</option>";
            }
            $("#id_grade").append(option);
        @endforeach
    });
    // 
    $("#kode_perusahaan").change(function() {
        var _kode_perusahaan = $(this).val();
        // alert(" Value: " + _kode_perusahaan);
        $('#kode_divisi').find('option').not(':first').remove();
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        // AJAX request 
        $.ajax({
            url: "{{ route('get_divisi') }}"+"/"+_kode_perusahaan,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                // alert(" Value: " + response[0]['nama']);
                var len = 0;
                if (response != null) {
                    len = response.length;
                    // alert(" Value: " + len);
                }
                if (len > 0) {
                    // Read data and create <option>
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var nama = response[i]['nama'];
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_divisi").append(option);
                    }
                }
            }
        });
    });
    // 
    $("#kode_divisi").change(function() {
        var _kode_divisi = $(this).val();
        $('#kode_departemen').find('option').not(':first').remove();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        $.ajax({
            url: "{{ route('get_departemen') }}"+"/"+_kode_divisi,
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
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_departemen").append(option);
                    }
                }
            }
        });
    });
    // 
    $("#kode_departemen").change(function() {
        var _kode_departemen = $(this).val();
        $('#kode_sub_departemen').find('option').not(':first').remove();
        $.ajax({
            url: "{{ route('get_sub_departemen') }}"+"/"+_kode_departemen,
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
                        var option = "<option value='" + id + "'>" + nama + "</option>";
                        $("#kode_sub_departemen").append(option);
                    }
                }
            }
        });
    });
    // 
});
</script>
@endsection
