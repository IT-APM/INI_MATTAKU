@extends('layouts.simple.master')

@section('title', 'Home')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>To Do List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Home</li>
<li class="breadcrumb-item active">To Do List</li>
@endsection

@section('content')
<div class="container-fluid">
    <!--  -->
    <div class="row widget-grid">
    {{-- <div class="row widget-grid align-items-center"> --}}
        <div class="col-xxl-6 col-sm-6 box-col-6">
            <div class="card profile-box">
                <div class="card-body bg-warning">
                    <div class="media">
                        <div class="media-body">
                            <div class="greeting-user">
                                <h6 class="f-w-600">Hi {{ auth()->user()->name }},<br/>Welcome to MATTA Reborn</h6>
                                <p>Please check your account frequently!</p>
                                <!-- <div class="whatsnew-btn"><a class="btn btn-outline-white">Whats New !</a></div> -->
                                <div class="whatsnew-btn">&nbsp;</div>
                            </div>
                        </div>
                        <div>
                            <div class="clockbox">
                                <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                                    <g id="face">
                                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                        <path class="hour-marks"
                                            d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                                        </path>
                                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                    </g>
                                    <g id="hour">
                                        <path class="hour-hand" d="M300.5 298V142"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="minute">
                                        <path class="minute-hand" d="M300.5 298V67"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="second">
                                        <path class="second-hand" d="M300.5 350V55"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"> </circle>
                                    </g>
                                </svg>
                            </div>
                            <div class="badge f-10 p-0" id="txt"></div>
                        </div>
                    </div>
                    <div class="cartoon">
                        <img class="img-fluid" src="{{ asset('assets/images/dashboard/cartoon.svg') }}" alt="i am">
                        <!-- <img class="img-fluid" src="{{ asset('assets/images/matta/karyawan/01031908799.jpg') }}" alt="i am"> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-sm-6 box-col-6">
        {{-- <div class="col-xxl-6 col-sm-6 box-col-6 d-flex align-items-center"> --}}
            {{-- <img class="img-fluid rounded mx-auto d-block" style="width:80%;float:none;" src="{{ asset('assets/images/login/trust1.png') }}" alt="trust"> --}}
            {{-- <img class="img-fluid rounded mx-auto d-block" style="width:80%;float:none;" src="{{ asset('assets/images/login/trust2.jpg') }}" alt="trust"> --}}
            {{-- <div class="card profile-box">
                <div class="card-body" style="background-color: white;">
                    tes
                </div>
            </div> --}}
        </div>
    </div>
    <!--  -->
    <div class="row">
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.lembur0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxOvertime)>0) class="badge bg-danger" @else class="badge bg-success" @endif>{{ count($trxOvertime) }}</span><br/><i class="fa fa-file-text-o"></i> OVERTIME</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.penilaian0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxPenilaian)>0) class="badge bg-danger" @else class="badge bg-primary" @endif>{{ count($trxPenilaian) }}</span><br/><i class="fa fa-file-text-o"></i> EVALUATION</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('asm')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.rekomendasi0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxRekomendasi)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($trxRekomendasi) }}</span><br/><i class="fa fa-file-text-o"></i> RECOMMENDATION</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.cuti0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxCuti)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($trxCuti) }}</span><br/><i class="fa fa-file-text-o"></i> LEAVE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.izin0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxIzin)>0) class="badge bg-danger" @else class="badge bg-success" @endif>{{ count($trxIzin) }}</span><br/><i class="fa fa-file-text-o"></i> PERMIT</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.nonaktif0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxNonAktif)>0) class="badge bg-danger" @else class="badge bg-primary" @endif>{{ count($trxNonAktif) }}</span><br/><i class="fa fa-file-text-o"></i> EXIT CLEARANCE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.reimburse0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxReimburse)>0) class="badge bg-danger" @else class="badge bg-primary" @endif>{{ count($trxReimburse) }}</span><br/><i class="fa fa-file-text-o"></i> REIMBURSEMENT</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('asm')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.kebutuhan0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxKebutuhan)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($trxKebutuhan) }}</span><br/><i class="fa fa-file-text-o"></i> ADD EMPLOYEES</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @can('spv')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.pelatihan0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxPelatihan)>0) class="badge bg-danger" @else class="badge bg-success" @endif>{{ count($trxPelatihan) }}</span><br/><i class="fa fa-file-text-o"></i> TRAINING</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @can('bpd')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.bpd0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxBpd)>0) class="badge bg-danger" @else class="badge bg-success" @endif>{{ count($trxBpd) }}</span><br/><i class="fa fa-file-text-o"></i> TRAVEL EXPENSE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.sakit0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($trxSakit)>0) class="badge bg-danger" @else class="badge bg-primary" @endif>{{ count($trxSakit) }}</span><br/><i class="fa fa-file-text-o"></i> SICK LEAVE</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @can('spv')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.recruitment0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($recruitment)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($recruitment) }}</span><br/><i class="fa fa-file-text-o"></i> RECRUITMENT</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @can('notif_letter')
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.penyesuaian_upah0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($notif_letter)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($notif_letter) }}</span><br/><i class="fa fa-file-text-o"></i> NOTIFICATION LETTER</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        <div class="col-xl-3 col-sm-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="media faq-widgets">
                        <div class="media-body text-center">
                            <a href="{{ route('todolist.show', [0, 'filename' => 'emails.karyawan_bulanan0']) }}" style="color: #FFFFFF;">
                                <h5><span @if (count($karyawan_bulanan)>0) class="badge bg-danger" @else class="badge bg-warning" @endif>{{ count($karyawan_bulanan) }}</span><br/><i class="fa fa-file-text-o"></i> CONFIRMATION</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
</div>
{{--  --}}
<div class="modal fade bd-example-modal-lg" id="autoShowModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
{{-- <div class="modal fade" id="autoShowModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"> --}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title w-100 text-center" id="myLargeModalLabel">SURAT PERSETUJUAN PEMROSESAN DATA PRIBADI</h4>
            {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div style="text-align: justify;font-size: 15px;">
                            <ol>
                                <li>Saya memberikan persetujuan kepada PT Alita Praya Mitra (“<b>Alita</b>”) untuk melakukan pemrosesan Data Pribadi yang melekat pada identitas diri (“<b>Data Pribadi</b>”) yang telah saya berikan dan meratifikasi seluruh pemrosesan Data Pribadi yang telah dilakukan sebelum tanggal persetujuan ini.</li>
                                <li>Bahwa Alita akan menggunakan Data Pribadi tersebut untuk kebutuhan penyimpanan sebagai <i>database</i>, pelaporan kepada pihak ketiga yang diwajibkan oleh Peraturan Perundang-undangan di Indonesia dan/atau kegiatan-kegiatan lain yang berkaitan dengan kepentingan bisnis Alita.</li>
                                <li>Alita akan menyimpan Data Pribadi tersebut di sistem MATTA  dan sistem database yang lain milik Alita dan Alita menjamin tidak ada penyalahgunaan atau penggunaan di luar kebutuhan yang tercantum dalam Ayat 2 di atas.</li>
                                <li>Saya telah mengetahui dan/atau menyetujui bahwa terdapat <i>Closed-Circuit Television (CCTV)</i> yang terpasang di lingkungan kerja Alita untuk kepentingan Perlindungan Data Pribadi Alita.</li>
                                <li>Saya memahami dan setuju bahwa Alita dapat mengungkapkan Data Pribadi tersebut kepada pihak ketiga lainnya dengan tujuan dan tata cara yang telah diatur dalam Peraturan Perundang-undangan yang berlaku, dengan pemberitahuan terlebih dahulu oleh Alita.</li>
                                <li>Alita akan melakukan penyimpanan Data Pribadi saya selama 10 (sepuluh) tahun sejak saya menyerahkan Data Pribadi saya.</li>
                                <li>Alita menjamin hak saya sebagai subjek Data Pribadi sesuai Undang-Undang Perlindungan Data Pribadi No. 27/2022, termasuk dengan Peraturan Perundang-undangan turunannya.</li>
                                <li>Saya menyatakan bahwa Data Pribadi dan dokumen yang saya berikan adalah benar dan sesuai dengan kondisi terkini.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('pdp.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @csrf
                    {{-- <button class="btn btn-warning" type="submit" data-bs-dismiss="modal">Setuju</button> --}}
                    <input type="hidden" id="is_setuju" name="is_setuju" value="1">
                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                    <button class="btn btn-warning" type="submit">Setuju</button>
                </form>
             </div>
        </div>
    </div>
</div>
<button id="autoClickBtn" type="button" class="btn btn-warning d-none" data-bs-toggle="modal" data-bs-target="#autoShowModal"></button>
{{-- <button id="autoClickBtn" class="btn btn-primary" onclick="alert('Button was clicked!')">
    Click Me
  </button> --}}
{{--  --}}
<div class="modal fade bd-example-modal-lg" id="autoShowSurvey" tabindex="-1" role="dialog" aria-labelledby="labelSurvey" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title w-100 text-center" id="labelSurvey">SURVEY CABANG OLAHRAGA PORA 2026</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div style="text-align: justify;font-size: 15px;">
                            <p>Dear Insan ALITA,<br/>Semangat Pagi!!!</p>
                            <p>Menjelang gelaran acara Pekan Olahraga Alita (PORA) 2026, mohon partisipasi dari seluruh Insan ALITA untuk mengikuti survey pemilihan 4 (empat) cabang olahraga yang akan diadakan dalam PORA 2026. Survey ini akan dibuka dari <b>Senin 02 Februari s/d Kamis 05 Februari 2026</b>.<br/>Terimakasih atas partisipasinya.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="text-align: justify;font-size: 15px;">
                            <form id="survey_pora" action="{{ route('survey_pora') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td width="5%"><b>1</b></td>
                                        <td><b>Cabor GROUP (Pilih 2)</b><br/>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="futsal" id="opsi1[]" name="opsi1[]">Futsal (5 vs 5)
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="voli" id="opsi1[]" name="opsi1[]">Voli (6 vs 6)
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="basket" id="opsi1[]" name="opsi1[]">Basket (3 on 3)
                                            </div>
                                            <small id="error_opsi1" class="text-danger" style="display:none;">Wajib Pilih 2</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>2</b></td>
                                        <td><b>Cabor INDIVIDU / DUO (Pilih 1)</b><br/>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="badminton" id="opsi2[]" name="opsi2[]">Badminton (Ganda Putra & Ganda Putri)
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="tenis" id="opsi2[]" name="opsi2[]">Tenis Meja (Ganda Putra)
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="renang" id="opsi2[]" name="opsi2[]">Renang (Individu Putra & Putri)
                                            </div>
                                            <small id="error_opsi2" class="text-danger" style="display:none;">Wajib Pilih 1</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>3</b></td>
                                        <td><b>Cabor E-SPORT (Pilih 1)</b><br/>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ml" id="opsi3[]" name="opsi3[]">Mobile Legend (5 vs 5)
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="fifa" id="opsi3[]" name="opsi3[]">FIFA 26 (2 vs 2)
                                            </div>
                                            <small id="error_opsi3" class="text-danger" style="display:none;">Wajib Pilih 1</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                            <button class="btn btn-warning mt-2" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<button id="btnShowSurvey" type="button" class="btn btn-warning d-none" data-bs-toggle="modal" data-bs-target="#autoShowSurvey"></button>
{{--  --}}
<script type="text/javascript">
document.getElementById('survey_pora').addEventListener('submit', function(e){
    e.preventDefault();
    const checked_opsi1 = document.querySelectorAll('input[name="opsi1[]"]:checked');
    const checked_opsi2 = document.querySelectorAll('input[name="opsi2[]"]:checked');
    const checked_opsi3 = document.querySelectorAll('input[name="opsi3[]"]:checked');
    let valid = true;
    if(checked_opsi1.length !== 2){
        document.getElementById('error_opsi1').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi1').style.display = 'none';
    }
    if(checked_opsi2.length !== 1){
        document.getElementById('error_opsi2').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi2').style.display = 'none';
    }
    if(checked_opsi3.length !== 1){
        document.getElementById('error_opsi3').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi3').style.display = 'none';
    }
    if(valid){
        e.target.submit();
    }
});
@if(!$is_survey_pora && now()->lessThanOrEqualTo(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2026-02-05 19:00:00')))
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnShowSurvey').click();
});
@endif
@if(!$is_pdp['is_pdp'] && $is_pdp['is_aktif'])
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('autoClickBtn').click();
});
@endif
var session_layout = '{{ session()->get('layout ') }}';
</script>
@endsection

@section('script')
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
@endsection