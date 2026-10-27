@extends('layouts.simple.master')

@section('title', 'Recruitment')

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
<h3>List Of Applicant</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Applicant</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('error') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <button class="btn btn-warning mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mb-2" action="{{ route('recruitment.applicant_list', 0) }}" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="{{ $tanggal_mulai }}">
                                    sampai
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="{{ $tanggal_akhir }}">
                                </div>
                                <div class="col-sm-3">
                                    {{-- <label class="h6">Nama</label>&nbsp;<input type="checkbox" onClick="toggle_nama(this)" /> Check All
                                    <label class="d-block" for="nama">
                                        @foreach ($nama as $data)
                                        <input class="checkbox_animated" id="nama[]" name="nama[]" type="checkbox" value="{{ $data->nama }}" @if(in_array($data->nama, $arr_nama)) checked @endif> {{ $data->nama }}<br/>
                                        @endforeach
                                    </label> --}}
                                    <label class="h6" for="nama">Nama</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nama" name="nama" required="">
                                        {{-- <option selected="" disabled="" value="">Pilih...</option> --}}
                                        <option value="0" @if(in_array('0', $arr_nama)) selected @endif>Semua</option>
                                        @foreach ($nama as $data)
                                        <option value="{{ $data->nama }}" @if(in_array($data->nama, $arr_nama)) selected @endif>{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Field is required.</div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Nama Jabatan</label>&nbsp;<input type="checkbox" onClick="toggle_id_pekerjaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="id_pekerjaan">
                                        @foreach ($pekerjaan as $data)
                                        <input class="checkbox_animated" id="id_pekerjaan[]" name="id_pekerjaan[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_pekerjaan)) checked @endif> {{ $data->pekerjaan }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_id_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="id_status">
                                        @foreach ($status as $data)
                                        <input class="checkbox_animated" id="id_status[]" name="id_status[]" type="checkbox" value="{{ $data->id }}" @if(in_array($data->id, $arr_id_status)) checked @endif> {{ $data->status }}<br/>
                                        @endforeach
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="{{ route('recruitment.applicant_list', 0) }}" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-psychotest-tab" data-bs-toggle="pill" href="#pills-psychotest" role="tab" aria-controls="pills-psychotest" aria-selected="false">Psychotest</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-interview-tab" data-bs-toggle="pill" href="#pills-interview" role="tab" aria-controls="pills-interview" aria-selected="true">Interview</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-negotiation-tab" data-bs-toggle="pill" href="#pills-negotiation" role="tab" aria-controls="pills-negotiation" aria-selected="true">Negotiation</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- Psychotest --}}
                        <div class="tab-pane fade show active" id="pills-psychotest" role="tabpanel" aria-labelledby="pills-psychotest-tab">
                            <p class="mb-0 m-t-10">
                                <form action="{{ route('recruitment.applicant_save') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                @csrf
                                <input type="hidden" id="id_status" name="id_status" value="4">
                                <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                <div class="row mb-2">
                                    <div class="col-12 col-md-3">
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                        <label class="form-label" for="check_in">Check In</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        {{-- <label class="form-label" for="check_in">Check_in</label> --}}
                                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="psychotest_bulk"><i class="fa fa-plus-circle" aria-hidden="true"></i> Psychotest Bulk</button>
                                    </div>
                                </div>
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th><input type="checkbox" onClick="toggle_identitas(this)" /></th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>Hasil</th>
                                                <th>Upload Hasil</th>
                                                <th>Area Kerja</th>
                                                <th>Area Kerja Kota</th>
                                                <th>Pengaju</th>
                                                <th>NIP</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Nama Jabatan</th>
                                                <th>Atasan Langsung Kandidat</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th><div class="d-flex align-items-center"><input type="checkbox" onClick="toggle_cv(this)" />&nbsp; CV &nbsp;<a href="#" onClick="download_cv()" data-toggle="tooltip" title="Download"><i class="fa fa-file-pdf-o"></i></a></div></th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>Interpretasi</th>
                                                <th>Offering Letter</th>
                                                <th>Upload</th>
                                                <th>Status</th>
                                                <th>Detail</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx as $data)
                                            @php
                                                $kebutuhan_atribute = json_decode($data->kebutuhan_atribute, true);
                                                $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
                                                $recruitment = App\Models\TrxKebutuhanMaster::find($data->id_kebutuhan);
                                                $pengaju_property = json_decode($recruitment->pengaju_property, true);
                                                $atasan_property = $recruitment->atasan_property;
                                                $atasan_property = json_decode($atasan_property, true);
                                                $nomor = $data->id_kebutuhan.'/'.$data->id;
                                                $where = ['id_identitas' => $data->id, 'jenis' => 4];
                                                $pohon = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                $where = ['id_identitas' => $data->id, 'jenis' => 5];
                                                $manusia = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                // cek apakah sudah ada soal psikotes
                                                $check1 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->exists();
                                                // cek apakah masih ada yang belum selesai
                                                $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                                // jika ada soal dan sudah selesai maka tampilkan hasilnya
                                                $check = $check1 && !$check2;
                                                // cek apakah sudah ada soal tiu5
                                                $check_tiu51 = App\Models\TrxRecruitmentTiu5::where(['id_identitas' => $data->id])->exists();
                                                // cek apakah masih ada yang belum selesai
                                                $check_tiu52 = App\Models\TrxRecruitmentTiu5::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                                // jika ada soal dan sudah selesai maka tampilkan hasilnya
                                                $check_tiu5 = $check_tiu51 && !$check_tiu52;
                                                // $status = App\Models\TrxRecruitmentIdentitas::find($data->id);
                                                $log = App\Models\TrxRecruitmentLog::where(['id_identitas'=>$data->id])->pluck('id_status')->toArray();
                                                // dd($log);
                                                // check apakah sudah mulai psikotes, jika sudah maka tidak bisa reschedule psikotes
                                                $check3 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id, 'jenis' => 1, 'nomor' => 1])->whereNull('check_out')->exists();
                                                // check apakah masih id_status => 4=Psychotest Send
                                                // $check3 = $data->id_status == 4 ? true : false;
                                                // apakah ada revisi psikotest
                                                $n_log = App\Models\TrxRecruitmentPsychotestLog::selectRaw('COUNT(*) as total')->where(['id_identitas'=>$data->id])->groupBy('urutan')->get()->count();
                                            @endphp
                                            <tr>
                                                {{-- 3=Apply --}}
                                                <td>{{ ++$no }}</td>
                                                <td>@if($data->id_status == 3) <input id="id_identitas[]" name="id_identitas[]" type="checkbox" value="{{ $data->id }}"> @endif</td>
                                                <td>{{ $nomor }}</td>
                                                <td>@can('hc')
                                                    <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a>
                                                    {{-- jika belum 5=Psychotest Done maka bisa diberikan extend --}}
                                                    @if(!in_array(5, $log) && $data->id_status > 3)&nbsp;<a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'type'=>'extend']) }}" data-toggle="tooltip" title='Extend'><i class="fa fa-calendar-plus-o"></i></a>@endif
                                                    <br/><a href="{{ route('recruitment.applicant_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Form' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotest', [Crypt::encrypt(array($data->id, 'psychotest'))]) }}" data-toggle="tooltip" title='Psychotest' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                @endcan</td>
                                                <td>
                                                    {{-- jika ada soal dan sudah selesai maka tampilkan hasilnya --}}
                                                    @if($check_tiu5)
                                                    <a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,6])]) }}" target="_blank" data-toggle="tooltip" title="TIU5"><i class="fa fa-file-text-o"></i></a><br/>
                                                    @endif
                                                    @if($check)
                                                    <a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,1])]) }}" target="_blank" data-toggle="tooltip" title="EPPS"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,2])]) }}" target="_blank" data-toggle="tooltip" title="PAPI Kostick"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,3])]) }}" target="_blank" data-toggle="tooltip" title="DISC"><i class="fa fa-file-text-o"></i></a>
                                                    @if(!empty($pohon[0]->jawaban1))<br/><a href="{{ asset('assets/recruitment/upload/'.$pohon[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="BAUM"><i class="fa fa-file-image-o"></i></a>@endif
                                                    @if(!empty($manusia[0]->jawaban1))&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$manusia[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="DAP"><i class="fa fa-file-image-o"></i></a>@endif
                                                    &nbsp;<a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'psikotest_revisi']) }}" target="_self" data-toggle="tooltip" title='Revisi'><i class="icon-pencil-alt"></i></a>
                                                    @endif
                                                    @if($n_log > 0)<br/><a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'histori_revisi']) }}" target="_self" data-toggle="tooltip" title='Histori Revisi'><i class="fa fa-history"></i></a>@endif
                                                </td>
                                                <td>
                                                    {{-- jika ada soal dan sudah selesai maka tampilkan hasilnya --}}
                                                    @if($check)
                                                    @if(!empty($data->file_epps))<a href="{{ asset('assets/recruitment/upload/'.$data->file_epps) }}" target="_blank" data-toggle="tooltip" title="Download EPPS"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                                    &nbsp;@if(!empty($data->file_papi))<a href="{{ asset('assets/recruitment/upload/'.$data->file_papi) }}" target="_blank" data-toggle="tooltip" title="Download PAPI Kostick"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                                    &nbsp;@if(!empty($data->file_disc))<a href="{{ asset('assets/recruitment/upload/'.$data->file_disc) }}" target="_blank" data-toggle="tooltip" title="Download DISC"><i class="fa fa-file-text-o" style="color: orange;"></i></a>@else<i class="fa fa-file-text-o" style="opacity: 0;"></i>@endif
                                                    <br/>@if(!empty($data->file_baum))<a href="{{ asset('assets/recruitment/upload/'.$data->file_baum) }}" target="_blank" data-toggle="tooltip" title="Download BAUM"><i class="fa fa-file-image-o" style="color: orange;"></i></a>@else<i class="fa fa-file-image-o" style="opacity: 0;"></i>@endif
                                                    &nbsp;@if(!empty($data->file_dap))<a href="{{ asset('assets/recruitment/upload/'.$data->file_dap) }}" target="_blank" data-toggle="tooltip" title="Download DAP"><i class="fa fa-file-image-o" style="color: orange;"></i></a>@else<i class="fa fa-file-image-o" style="opacity: 0;"></i>@endif
                                                    &nbsp;<a href="{{ route('recruitment.upload_psikotest', [Crypt::encrypt([$data->id, 'upload'])]) }}" target="_blank" data-toggle="tooltip" title='Upload'><i class="fa fa-upload" style="color: orange;"></i></a>
                                                    @endif
                                                </td>
                                                {{-- <td>{{ $recruitment->_area->propinsi }}</td> --}}
                                                <td>{{ App\Models\Propinsi::find($kebutuhan_atribute['id_area'])->propinsi }}</td>
                                                {{-- <td>@if (!empty($recruitment->id_kota)) {{ $recruitment->_kota->kota }} @endif</td> --}}
                                                <td>@if(App\Models\PropinsiKota::find($kebutuhan_atribute['id_kota'])?->kota !== null) {{ App\Models\PropinsiKota::find($kebutuhan_atribute['id_kota'])->kota }} @endif</td>
                                                <td>{{ $pengaju_property['nama'] }}</td>
                                                <td>{{ $pengaju_property['nik'] }}</td>
                                                <td>{{ $pengaju_property['divisi'] }}</td>
                                                <td>{{ $pengaju_property['dep'] }}</td>
                                                <td>{{ $pengaju_property['sub_dep'] }}</td>
                                                {{-- <td>{{ $recruitment->_pekerjaan->pekerjaan }}</td> --}}
                                                <td>{{ App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan }}</td>
                                                {{-- <td>@if(!empty($atasan_property)) {{ $atasan_property['nama'] }}/{{ $atasan_property['nik'] }} @endif</td> --}}
                                                <td>{{ $kebutuhan_atribute_atasan_property['nama'] }}/{{ $kebutuhan_atribute_atasan_property['nik'] }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td>{{ $data->email }}&nbsp;<a href="{{ route('recruitment.edit', [Crypt::encrypt($data->id), 'type'=>'email', 'back_url'=>URL::full()]) }}" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt" style="color:green"></i></a></td>
                                                <td>{{ $data->telepon }}</td>
                                                <td><div class="d-flex align-items-center"><input id="id_cv[]" name="id_cv[]" type="checkbox" value="{{ $data->id }}">&nbsp;&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$data->cv) }}" target="_blank" data-toggle="tooltip" title="File CV"><i class="fa fa-file-pdf-o"></i></a></div></td>
                                                {{-- $check3 adalah pengecekan apakah psikotes sudah berjalan, jika belum maka bisa reschedule --}}
                                                <td>@if(!empty($data->check_in)){{ \Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s') }} @if($check3) <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id), 'process'=>'psikotest_reschedule']) }}" target="_self" data-toggle="tooltip" title="Reschedule"><i class="fa fa-calendar" aria-hidden="true"></i></a> @endif @endif</td>
                                                <td>@if(!empty($data->check_out)){{ \Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s') }}@endif</td>
                                                <td>@can('hc')
                                                    {{-- 16=Interpretation --}}
                                                    @if(in_array(16, $log))<a href="{{ route('recruitment.get_interpretation', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="Interpretation"><i class="fa fa-file-text-o"></i></a>@endif
                                                    {{-- @if(!empty($data->interpretasi))<a href="{{ asset('assets/recruitment/upload/'.$data->interpretasi) }}" target="_blank" data-toggle="tooltip" title="File Interpretasi"><i class="fa fa-file-text-o"></i></a>@endif --}}
                                                @endcan</td>
                                                <td>@can('hc')
                                                    {{-- 12=Negotiation Process --}}
                                                    @if(in_array(12, $log))<a href="{{ route('recruitment.get_offering', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="Offering Letter"><i class="fa fa-file-text-o"></i></a>@endif
                                                @endcan</td>
                                                <td>@can('hc')
                                                    {{-- 20=COC and File Upload Done --}}
                                                    @if(in_array(20, $log))<a href="{{ route('recruitment.upload_dokumen_get', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="File Upload"><i class="fa fa-file-text-o"></i></a>@endif
                                                @endcan</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>@if(!empty($data->id_join)) {!! implode('<br/>', App\Models\MtrJoin::whereIn('id', explode(',', $data->id_join))->orderBy('nama', 'asc')->pluck('nama')->toArray()) !!} @endif</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </form>
                            </p>
                        </div>
                        {{-- Interview --}}
                        <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-interview-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>Tipe</th>
                                                <th>Nama</th>
                                                {{-- <th>Email</th> --}}
                                                <th>Pewawancara</th>
                                                <th>Metode</th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>Interview</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx1 as $data)
                                            @php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $nomor }}</td>
                                                <td><a href="{{ route('recruitment.interview_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a></td>
                                                <td class="text-uppercase">{{ $data->jenis }}</td>
                                                <td>{{ $identitas->nama }}</td>
                                                {{-- <td>{{ $identitas->email }}</td> --}}
                                                <td>
                                                    @foreach(explode(';', $data->interviewer) as $_data)
                                                        @php
                                                        $karyawan = App\Models\Karyawan::where(['nik'=>$_data])->first();
                                                        @endphp
                                                        {{ $karyawan->nama }}/{{ $_data }}<br/>
                                                    @endforeach
                                                </td>
                                                <td>{{ $data->metode }}</td>
                                                <td>@if(!empty($data->check_in)){{ \Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s') }} <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id_identitas), 'process'=>'interview_'.$data->jenis.'_reschedule', 'id_interview'=>Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title="Reschedule"><i class="fa fa-calendar" aria-hidden="true"></i></a> @endif</td>
                                                <td>@if(!empty($data->check_out)){{ \Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s') }} @endif</td>
                                                <td>@if(!empty($data->file_interview))<a href="{{ asset('assets/recruitment/upload/'.$data->file_interview) }}" target="_blank" data-toggle="tooltip" title="File Interview"><i class="fa fa-file-text-o"></i></a> @endif</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Negotiation --}}
                        <div class="tab-pane fade" id="pills-negotiation" role="tabpanel" aria-labelledby="pills-negotiation-tab">
                            @can('hc')
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>PIC</th>
                                                <th>THP</th>
                                                <th>Benefit</th>
                                                <th>Mulai Bergabung</th>
                                                <th>Pencatatan Kepegawaian</th>
                                                <th>Status Kepegawaian</th>
                                                <th>Masa Perjanjian Kerja</th>
                                                <th>Catatan</th>
                                                <th>Lampiran</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx2 as $data)
                                            @php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                                $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $data->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                                                $benefit_utama = implode('<br/>', $benefit_utama);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $nomor }}</td>
                                                <td>{{ $data->pic }}</td>
                                                <td>Rp. {{ number_format($data->thp, 2) }}</td>
                                                <td>{!! $benefit_utama !!}
                                                    @if(!empty($data->benefit_lainnya))<br/>{{ $data->benefit_lainnya }}@endif
                                                </td>
                                                <td>{{ Carbon\Carbon::parse($data->tgl_bergabung)->format('d-m-Y') }}</td>
                                                <td>{{ $data->_catat_pegawai->pencatatan }}</td>
                                                <td>{{ $data->_status_pegawai->status }}</td>
                                                <td>{{ $data->masa_kerja }} bulan</td>
                                                <td>{{ $data->catatan }}</td>
                                                <td>@if(!empty($data->lampiran))<a href="{{ asset('assets/recruitment/upload/'.$data->lampiran) }}" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                            @endcan
                        </div>
                        {{--  --}}
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
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script type="text/javascript">
// 
function toggle_identitas(source) {
    checkboxes = document.getElementsByName('id_identitas[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_cv(source) {
    checkboxes = document.getElementsByName('id_cv[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
var arr_id_cv = [];
function download_cv() {
    $('input[name="id_cv[]"]:checked').each(function() {
        // alert('Terpilih: ' + $(this).val());
        arr_id_cv.push($(this).val());
    });
    if(arr_id_cv.length > 0){
        window.location.href = "{{ route('recruitment.download_cv', 0) }}"+"?download_cv="+arr_id_cv;
    }else{
        alert('No CV is selected!');
    }
    arr_id_cv = [];
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
// 
$('#_datatable1').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
// 
$('#_datatable2').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
function toggle_nama(source) {
    checkboxes = document.getElementsByName('nama[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_pekerjaan(source) {
    checkboxes = document.getElementsByName('id_pekerjaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_id_status(source) {
    checkboxes = document.getElementsByName('id_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
@endsection