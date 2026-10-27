@extends('layouts.simple.master')
@section('title', 'Formulir Kebutuhan Karyawan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Detail</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="{{ route('kebutuhan_detail.update', $trxD->id) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="id_kebutuhan" name="id_kebutuhan" value="{{ $trxD->id_kebutuhan }}">
                    <div class="row">
                        <!-- start -->
                        <div class="col-md-4">
                            <div class="card-header">
                                <h5>PERSONAL</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="nama_karyawan">Nama Calon Karyawan*</label>
                                    <input name="nama_karyawan" id="nama_karyawan" class="form-control" type="text" value="{{ $trxD->nama_karyawan }}" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="alamat">Alamat*</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="4" required="">{{ $trxD->alamat }}</textarea>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir*</label>
                                    <input class="form-control" name="tanggal_lahir" id="tanggal_lahir" type="date" value="{{ $trxD->tanggal_lahir }}" required="">
                                </div>
                                <div class="col-md-12 pb-0">
                                    <label class="form-label" for="id_pendidikan">Pendidikan*</label>
                                    <!-- js-example-basic-single  -->
                                    <select class="form-select col-sm-12" id="id_pendidikan" name="id_pendidikan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($jenjang_pendidikan as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $trxD->id_pendidikan) selected @endif >{{ $data->jenjang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-header">
                                <h5>KELENGKAPAN DOKUMEN</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_cv">Upload CV</label>
                                    <input name="file_cv" id="file_cv" class="form-control" type="file" >
                                    @if(!empty($trxD->file_cv)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_cv) }}" target="_blank">Download CV</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_sertifikat_keahlian">Upload Sertifikat Keahlian</label>
                                    <input name="file_sertifikat_keahlian" id="file_sertifikat_keahlian" class="form-control" type="file" >
                                    @if(!empty($trxD->file_sertifikat_keahlian)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_sertifikat_keahlian) }}" target="_blank">Download Sertifikat Keahlian</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_ktp">Upload KTP</label>
                                    <input name="file_ktp" id="file_ktp" class="form-control" type="file" >
                                    @if(!empty($trxD->file_ktp)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_ktp) }}" target="_blank">Download KTP</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_kk">Upload Kartu Keluarga</label>
                                    <input name="file_kk" id="file_kk" class="form-control" type="file" >
                                    @if(!empty($trxD->file_kk)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_kk) }}" target="_blank">Download Kartu Keluarga</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_npwp">Upload NPWP</label>
                                    <input name="file_npwp" id="file_npwp" class="form-control" type="file" >
                                    @if(!empty($trxD->file_npwp)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_npwp) }}" target="_blank">Download NPWP</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_bpjs_tk">Upload BPJS Ketenagakerjaan</label>
                                    <input name="file_bpjs_tk" id="file_bpjs_tk" class="form-control" type="file" >
                                    @if(!empty($trxD->file_bpjs_tk)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_bpjs_tk) }}" target="_blank">Download BPJS Ketenagakerjaan</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_bpjs_kes">Upload BPJS Kesehatan</label>
                                    <input name="file_bpjs_kes" id="file_bpjs_kes" class="form-control" type="file" >
                                    @if(!empty($trxD->file_bpjs_kes)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_bpjs_kes) }}" target="_blank">Download BPJS Kesehatan</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_buku_tabungan">Upload Buku Tabungan</label>
                                    <input name="file_buku_tabungan" id="file_buku_tabungan" class="form-control" type="file" >
                                    @if(!empty($trxD->file_buku_tabungan)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_buku_tabungan) }}" target="_blank">Download Buku Tabungan</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_ket_sehat">Upload Surat Keterangan Sehat</label>
                                    <input name="file_ket_sehat" id="file_ket_sehat" class="form-control" type="file" >
                                    @if(!empty($trxD->file_ket_sehat)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_ket_sehat) }}" target="_blank">Download Keterangan Sehat</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_skck">Upload SKCK</label>
                                    <input name="file_skck" id="file_skck" class="form-control" type="file" >
                                    @if(!empty($trxD->file_skck)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_skck) }}" target="_blank">Download SKCK</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_ijazah">Upload Ijazah</label>
                                    <input name="file_ijazah" id="file_ijazah" class="form-control" type="file" >
                                    @if(!empty($trxD->file_ijazah)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_ijazah) }}" target="_blank">Download Ijazah</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_offering">Upload Offering Letter</label>
                                    <input name="file_offering" id="file_offering" class="form-control" type="file" >
                                    @if(!empty($trxD->file_offering)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_offering) }}" target="_blank">Download Offering Letter</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan1">Keterangan</label>
                                    <textarea class="form-control" id="keterangan1" name="keterangan1" rows="4">{{ $trxD->keterangan1 }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-4">
                            <div class="card-header">
                                <h5>INTERVIEW / PSIKOTES</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_interview">Tanggal Interview</label>
                                    <input class="form-control" name="tanggal_interview" id="tanggal_interview" type="date" value="{{ $trxD->tanggal_interview }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_interview">Upload File Interview</label>
                                    <input name="file_interview" id="file_interview" class="form-control" type="file" >
                                    @if(!empty($trxD->file_interview)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_interview) }}" target="_blank">Download File Interview</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_psikotes">Tanggal Psikotes</label>
                                    <input class="form-control" name="tanggal_psikotes" id="tanggal_psikotes" type="date" value="{{ $trxD->tanggal_psikotes }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_psikotes">Upload File Psikotes</label>
                                    <input name="file_psikotes" id="file_psikotes" class="form-control" type="file" >
                                    @if(!empty($trxD->file_psikotes)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_psikotes) }}" target="_blank">Download File Psikotes</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_negosiasi">Tanggal Negosiasi</label>
                                    <input class="form-control" name="tanggal_negosiasi" id="tanggal_negosiasi" type="date" value="{{ $trxD->tanggal_negosiasi }}">
                                </div>
                            </div>
                            <div class="card-header">
                                <h5>BENEFIT</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="take_home_pay">Take Home Pay*</label>
                                    <input type="number" name="take_home_pay" id="take_home_pay" class="form-control numbers" value="{{ $trxD->take_home_pay }}" required="">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="id_benefit[]">Benefit*</label>
                                    <!-- js-example-basic-multiple  -->
                                    <select class="form-select col-sm-12 js-example-basic-multiple" multiple="multiple" id="id_benefit[]" name="id_benefit[]" required="">
                                        <!-- <option value="{{ $data->id }}">{{ $data->benefit }}</option> -->
                                        @foreach ($benefit as $data)
                                        <option value="{{ $data->id }}" @if(in_array($data->id, explode(",", $trxD->id_benefit))) selected @endif >{{ $data->benefit }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Benefit is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan3">Keterangan</label>
                                    <textarea class="form-control" id="keterangan3" name="keterangan3" rows="4">{{ $trxD->keterangan3 }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                        <!-- start -->
                        <div class="col-md-4">
                            <div class="card-header">
                                <h5>ORGANISASI</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-0">
                                    <label class="form-label" for="id_posisi">Posisi</label>
                                    <h6>{{ $trxD->_posisi->pekerjaan }}</h6>
                                </div>
                                <div class="col-md-12 pb-0">
                                    <label class="form-label" for="id_divisi">Divisi</label>
                                    <h6>{{ $trxD->_divisi->nama }}</h6>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="atasan">Atasan*</label>
                                    <select class="form-select col-sm-12 js-example-basic-single" id="atasan" name="atasan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($karyawan as $data)
                                        <option value="{{ $data->nik }}" @if($data->nik == $trxD->atasan) selected @endif >{{ $data->nama }} - {{ $data->nik }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback txt-danger">Atasan is required.</div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_peraturan_perusahaan">Peraturan Perusahaan</label>
                                    <input class="form-control" name="tanggal_peraturan_perusahaan" id="tanggal_peraturan_perusahaan" type="date" value="{{ $trxD->tanggal_peraturan_perusahaan }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_pedoman_prilaku">Pedoman Perilaku</label>
                                    <input class="form-control" name="tanggal_pedoman_prilaku" id="tanggal_pedoman_prilaku" type="date" value="{{ $trxD->tanggal_pedoman_prilaku }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="file_dokumen_ttd">Upload Dokumen TTD</label>
                                    <input name="file_dokumen_ttd" id="file_dokumen_ttd" class="form-control" type="file" >
                                    @if(!empty($trxD->file_dokumen_ttd)) <a href="{{ asset('assets/kebutuhan/'.$trxD->file_dokumen_ttd) }}" target="_blank">Download Dokumen TTD</a> @endif
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_struktur_organisasi">Struktur Organisasi</label>
                                    <input class="form-control" name="tanggal_struktur_organisasi" id="tanggal_struktur_organisasi" type="date" value="{{ $trxD->tanggal_struktur_organisasi }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="keterangan2">Keterangan</label>
                                    <textarea class="form-control" id="keterangan2" name="keterangan2" rows="4">{{ $trxD->keterangan2 }}</textarea>
                                </div>
                            </div><div class="card-header">
                                <h5>KEPEGAWAIAN</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_bergabung">Tanggal Mulai Bergabung*</label>
                                    <input class="form-control" name="tanggal_bergabung" id="tanggal_bergabung" type="date" value="{{ $trxD->tanggal_bergabung }}" required="">
                                </div>
                                <div class="col-md-12 pb-0">
                                    <label class="form-label" for="id_pencatatan_kepegawaian">Pencatatan Kepegawaian*</label>
                                    <select class="form-select col-sm-12" id="id_pencatatan_kepegawaian" name="id_pencatatan_kepegawaian" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($pencatatan_kepegawaian as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $trxD->id_pencatatan_kepegawaian) selected @endif >{{ $data->rencana }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-0">
                                    <label class="form-label" for="id_status_kepegawaian">Status Kepegawaian*</label>
                                    <select class="form-select col-sm-12" id="id_status_kepegawaian" name="id_status_kepegawaian" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        @foreach ($status_kepegawaian as $data)
                                        <option value="{{ $data->id }}" @if($data->id == $trxD->id_status_kepegawaian) selected @endif >{{ $data->status_kerja }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_mulai">Tanggal Kontrak Dari</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" value="{{ $trxD->tanggal_mulai }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_akhir">Tanggal Kontrak Sampai</label>
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" value="{{ $trxD->tanggal_akhir }}">
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label class="form-label" for="tanggal_ttd_kontrak">Tanggal Penandatanganan Kontrak</label>
                                    <input class="form-control" name="tanggal_ttd_kontrak" id="tanggal_ttd_kontrak" type="date" value="{{ $trxD->tanggal_ttd_kontrak }}">
                                </div>
                            </div>
                        </div>
                        <!-- finish -->
                    </div>
                    <hr/>
                    <div class="card-body">
                        <!-- <div class="row g-3"> -->
                            <div class="col-md-12">
                                <!-- onclick="return validateForm()" -->
                                <button class="btn btn-success" type="submit" onsubmit="return confirm('Are you sure want to submit this data?');">Submit</button> | <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                            </div>
                        <!-- </div> -->
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script type="text/javascript">
// $(document).ready(function() {
    // function validateForm() {
        // let nama_karyawan = document.forms["myForm"]["nama_karyawan"].value;
        // if ($("#nama_karyawan").val() == "") {
        //     // $("#nama_karyawan").focus();
        //     // document.getElementById("nama_karyawan").focus();
        //     swal({
        //         title: "ERROR",
        //         text: "Nama Calon Karyawan is required.",
        //         icon: "error"
        //     });
        //     $("#nama_karyawan").focus();
        //     return false;
        // }
        // else if ($("#alamat").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Alamat is required.",
        //         icon: "error"
        //     });
        //     $("#alamat").focus();
        //     return false;
        // }
        // else if ($("#tanggal_lahir").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Tanggal Lahir is required.",
        //         icon: "error"
        //     });
        //     $("#tanggal_lahir").focus();
        //     return false;
        // }
        // else if ($("#id_pendidikan").find(":selected").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Pendidikan is required.",
        //         icon: "error"
        //     });
        //     $("#id_pendidikan").focus();
        //     return false;
        // } else 
        // alert(document.querySelectorAll("input[type='checkbox']:checked"));
        // alert(document.getElementsByName('id_benefit').length);
        // alert($("input[name^='id_benefit']").length);
        // $("input[name^='id_benefit']").each(function () {
        //     alert($(this).val());
        // });
        // if ($("#atasan").find(":selected").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Atasan is required.",
        //         icon: "error"
        //     });
        //     $("#atasan").focus();
        //     return false;
        // }
        // else if ($("#take_home_pay").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Take Home Pay is required.",
        //         icon: "error"
        //     });
        //     $("#take_home_pay").focus();
        //     return false;
        // }
        // else if ($("#id_benefit[]").find(":checked").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Benefit is required.",
        //         icon: "error"
        //     });
        //     $("#id_benefit[]").focus();
        //     return false;
        // }
        // else if ($("#tanggal_bergabung").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Tanggal Mulai Bergabung is required.",
        //         icon: "error"
        //     });
        //     $("#tanggal_bergabung").focus();
        //     return false;
        // }
        // else if ($("#id_pencatatan_kepegawaian").find(":selected").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Pencatatan Kepegawaian is required.",
        //         icon: "error"
        //     });
        //     $("#id_pencatatan_kepegawaian").focus();
        //     return false;
        // }
        // else if ($("#id_status_kepegawaian").find(":selected").val() == "") {
        //     swal({
        //         title: "ERROR",
        //         text: "Status Kepegawaian is required.",
        //         icon: "error"
        //     });
        //     $("#id_status_kepegawaian").focus();
        //     return false;
        // }
    // }
// });
</script>
@endsection