<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload Hasil Psikotes</title>
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
                    <h3>Upload Hasil Psikotes</h3>
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
                    {{-- start upload file --}}
                    <div class="col-12 col-md-12 text-center">
                        <h4>Upload*</h4>
                        <label class="form-label">*EPPS, PAPI Kostick, DISC maksimal 5 MB dengan format xls/xlsx</label>
                        <br/><label class="form-label">*BAUM, DAP maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_epps">Upload EPPS</label>
                                @if(!empty($_trx->file_epps))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_epps) }}" target="_blank">Download EPPS</a> @else -Tidak Ada- @endif
                                <form action="{{ route('recruitment.upload_psikotest_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                    <input name="file_epps" id="file_epps" class="form-control" type="file" required="">
                                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_papi">Upload PAPI Kostick</label>
                                @if(!empty($_trx->file_papi))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_papi) }}" target="_blank">Download PAPI Kostick</a> @else -Tidak Ada- @endif
                                <form action="{{ route('recruitment.upload_psikotest_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                    <input name="file_papi" id="file_papi" class="form-control" type="file" required="">
                                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_disc">Upload DISC</label>
                                @if(!empty($_trx->file_disc))<br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_disc) }}" target="_blank">Download DISC</a> @else -Tidak Ada- @endif
                                <form action="{{ route('recruitment.upload_psikotest_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                    <input name="file_disc" id="file_disc" class="form-control" type="file" required="">
                                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_baum">Upload BAUM</label>
                                @if(!empty($_trx->file_baum))<br/><i class="fa fa-file-image-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_baum) }}" target="_blank">Download BAUM</a> @else -Tidak Ada- @endif
                                <form action="{{ route('recruitment.upload_psikotest_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                    <input name="file_baum" id="file_baum" class="form-control" type="file" required="">
                                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_dap">Upload DAP</label>
                                @if(!empty($_trx->file_dap))<br/><i class="fa fa-file-image-o"></i></i>&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$_trx->file_dap) }}" target="_blank">Download DAP</a> @else -Tidak Ada- @endif
                                <form action="{{ route('recruitment.upload_psikotest_post') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($_trx->id) }}">
                                    <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                                    <input name="file_dap" id="file_dap" class="form-control" type="file" required="">
                                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- end upload file --}}
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
