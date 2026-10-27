<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelengkapan Dokumen Karyawan Baru</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="{{ asset('assets/images/matta/alita.png') }}" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                @if(Session::has('msg'))
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h5>{{ session('msg') }}</h6>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (count($errors) > 0)
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload file is ERROR</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="row g-0 text-center">
                    <h3>KELENGKAPAN DOKUMEN KARYAWAN BARU</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Personal</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input class="form-control" name="nama" id="nama" type="text" readonly="" value="{{ $_trx->nama }}">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="alamat_ktp">Alamat</label>
                            <input class="form-control" name="alamat_ktp" id="alamat_ktp" type="text" readonly="" value="{{ $_trx->alamat_ktp }}">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="tempat_tanggal_lahir">Tempat / Tanggal Lahir</label>
                            <input class="form-control" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" type="text" readonly="" value="{{ $_trx->tempat_lahir }} / {{ Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y') }}">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="pendidikan">Pendidikan</label>
                            <input class="form-control" name="pendidikan" id="pendidikan" type="text" readonly="" value="{{ $_trx->pendidikan }}">
                        </div>
                        @php
                        $formal = App\Models\TrxRecruitmentFormal::where(['id_identitas'=>$_trx->id, 'jenjang'=>$_trx->pendidikan])->first();
                        @endphp
                        @if(!empty($formal))
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="jurusan">Jurusan</label>
                            <input class="form-control" name="jurusan" id="jurusan" type="text" readonly="" value="{{ $formal->jurusan }}">
                        </div>
                        @endif
                    </div>
                    <hr class="mt-2 mb-2" />
                    {{-- check pedoman prilaku --}}
                    @php
                        $where = ['id_identitas' => $_trx->id, 'nomor' => 1];
                        $cek_satu = App\Models\TrxRecruitmentPedoman::where($where)->whereNull('check_in')->exists();
                        $where = ['id_identitas' => $_trx->id];
                        $check_out = App\Models\TrxRecruitmentPedoman::where($where)->orderBy('nomor', 'asc')->whereNull('check_out')->first();
                    @endphp
                    {{-- page 1 --}}
                    @if($cek_satu)
                        <div class="col-12 col-md-12 text-center">
                            <h4>Pedoman Perilaku</h4>
                            <label class="form-label">Mohon untuk membaca, memahami dan menyetujui Pedoman Perilaku berikut ini dan selanjutnya melakukan upload dokumen lainnya sebagai kelengkapan administrasi.</label>
                        </div>
                        <form action="{{ route('recruitment.pedoman_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to next this?');">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                        <input type="hidden" id="url" name="url" value="{{ URL::full() }}">
                            <div class="row text-center">
                                <div class="col-12 col-md-4">&nbsp;</div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <button type="submit" name="nomor" value="1" class="btn btn-primary">Next</button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">&nbsp;</div>
                            </div>
                        </form>
                    {{-- others page --}}
                    @elseif(!empty($check_out))
                        <div class="col-12 col-md-12 text-center">
                            <h4>Pedoman Perilaku</h4>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="col-12 col-md-12 text-center">
                            <img style="width:80%; height:100%;" src="{{ asset('assets/recruitment/upload/'.$check_out->jenis.'/'.$check_out->filename.'.jpg') }}" class="img-rounded" alt="Pedoman Perilaku" width="304" height="236">
                        </div>
                        <div class="col-12 col-md-12 text-center">
                            <input class="form-check-input" type="checkbox" value="" name="setuju" id="setuju" style="width: 20px; height: 20px; border: 2px solid" required="">&nbsp;Saya setuju*
                            <br/><label class="form-label">*Jika anda setuju dan klik <i>Submit</i> maka akan berlanjut ke halaman berikutnya</label>
                        </div>
                        <div class="col-12 col-md-12 text-center" id="submit" name="submit">
                            <form action="{{ route('recruitment.pedoman_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            @csrf
                                <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                <input type="hidden" id="url" name="url" value="{{ URL::full() }}">
                                <button type="submit" name="nomor" value="{{ $check_out->nomor }}" class="btn btn-primary m-2">Submit</button>
                            </form>
                        </div>
                    @else
                        {{-- start upload file --}}
                        <div class="col-12 col-md-12 text-center">
                            <h4>Upload<span class="text-danger">*</span></h4>
                            @if($_trx->id_status == 19 || (Auth::check() && auth()->user()->userrole == 'hc'))
                            <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                            @endif
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                {{--  --}}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_sertifikat_keahlian">Upload Sertifikat Keahlian</label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_sertifikat_keahlian" id="file_sertifikat_keahlian" class="form-control" type="file" >
                                            @endif
                                            @if(!empty($_trx->file_sertifikat_keahlian))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian) }}" target="_blank">Download Sertifikat Keahlian</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_sertifikat_keahlian">
                                                    <input name="file_sertifikat_keahlian" id="file_sertifikat_keahlian" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_ktp">Upload KTP<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_ktp" id="file_ktp" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_ktp))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_ktp) }}" target="_blank">Download KTP</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_ktp">
                                                    <input name="file_ktp" id="file_ktp" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_kk">Upload Kartu Keluarga<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_kk" id="file_kk" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_kk))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_kk) }}" target="_blank">Download Kartu Keluarga</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_kk">
                                                    <input name="file_kk" id="file_kk" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_npwp">Upload NPWP</label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_npwp" id="file_npwp" class="form-control" type="file">
                                            @endif
                                            @if(!empty($_trx->file_npwp))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_npwp) }}" target="_blank">Download NPWP</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_npwp">
                                                    <input name="file_npwp" id="file_npwp" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_bpjs_tk">Upload BPJS Ketenagakerjaan</label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_bpjs_tk" id="file_bpjs_tk" class="form-control" type="file">
                                            @endif
                                            @if(!empty($_trx->file_bpjs_tk))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_bpjs_tk) }}" target="_blank">Download BPJS Ketenagakerjaan</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_bpjs_tk">
                                                    <input name="file_bpjs_tk" id="file_bpjs_tk" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_bpjs_kes">Upload BPJS Kesehatan</label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_bpjs_kes" id="file_bpjs_kes" class="form-control" type="file">
                                            @endif
                                            @if(!empty($_trx->file_bpjs_kes))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_bpjs_kes) }}" target="_blank">Download BPJS Kesehatan</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_bpjs_kes">
                                                    <input name="file_bpjs_kes" id="file_bpjs_kes" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_buku_tabungan">Upload Buku Tabungan<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_buku_tabungan" id="file_buku_tabungan" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_buku_tabungan))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_buku_tabungan) }}" target="_blank">Download Buku Tabungan</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_buku_tabungan">
                                                    <input name="file_buku_tabungan" id="file_buku_tabungan" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_ket_sehat">Upload Surat Keterangan Sehat<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_ket_sehat" id="file_ket_sehat" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_ket_sehat))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_ket_sehat) }}" target="_blank">Download Keterangan Sehat</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_ket_sehat">
                                                    <input name="file_ket_sehat" id="file_ket_sehat" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_skck">Upload SKCK<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_skck" id="file_skck" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_skck))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_skck) }}" target="_blank">Download SKCK</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_skck">
                                                    <input name="file_skck" id="file_skck" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_ijazah">Upload Ijazah<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_ijazah" id="file_ijazah" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_ijazah))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_ijazah) }}" target="_blank">Download Ijazah</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_ijazah">
                                                    <input name="file_ijazah" id="file_ijazah" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_covid">Upload Sertifikat Vaksin Covid 19</label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_covid" id="file_covid" class="form-control" type="file">
                                            @endif
                                            @if(!empty($_trx->file_covid))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_covid) }}" target="_blank">Download Sertifikat Vaksin Covid 19</a> @else -Tidak Ada- @endif
                                            @if(Auth::check() && auth()->user()->userrole == 'hc')
                                                <form action="{{ route('recruitment.upload_dokumen_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                                    <input type="hidden" id="filename" name="filename" value="file_covid">
                                                    <input name="file_covid" id="file_covid" class="form-control" type="file" required="">
                                                    <button type="submit" name="upload_by" id="upload_by" value="hco" class="btn btn-primary mt-1">Upload</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- tidak digunakan, diganti dengan dibuat persetujuan dalam setiap halaman setelah kandidat membaca --}}
                                    @if($_trx->id_status != 19)
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            <label class="form-label" for="file_dokumen_ttd">Upload Pedoman Perilaku<span class="text-danger">*</span></label>
                                            @if($_trx->id_status == 19)
                                            <input name="file_dokumen_ttd" id="file_dokumen_ttd" class="form-control" type="file" required="">
                                            @endif
                                            @if(!empty($_trx->file_dokumen_ttd))<br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_dokumen_ttd) }}" target="_blank">Download Pedoman Perilaku</a> @else -Tidak Ada- @endif
                                            @if($_trx->id_status == 19)
                                            <label class="form-label">*Mohon untuk mengunduh, membaca, memahami dan menandatangani Pedoman Perilaku terlampir dan di-upload bersama dokumen lainnya sebagai kelengkapan administrasi.</label>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                {{--  --}}
                                @if($_trx->id_status == 19)
                                <div class="row text-center">
                                    <div class="col-12 col-md-4">&nbsp;</div>
                                    <div class="col-12 col-md-4">
                                        <div class="col-12 mb-2">
                                            {{-- 20=COC and File Upload Done --}}
                                            <button type="submit" name="id_status" id="id_status" value="20" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">&nbsp;</div>
                                </div>
                                @endif
                            </form>
                        </div>
                        {{-- end upload file --}}
                    @endif
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
    </section>
</body>
<script type="text/javascript">
$(document).ready(function () {
    $("#submit").hide();
    $("#setuju").change(function () {
        if (this.checked) {
            $("#submit").show();
        } else {
            $("#submit").hide();
        }
    });
});
</script>

</html>
