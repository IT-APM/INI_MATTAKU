<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="{{ asset('assets/images/matta/alita.png') }}" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                @if (count($errors) > 0)
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h4>ERROR:</h4>
                    <h5>{!! session('error') !!}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="row g-0">
                    <h3 class="text-center">SURAT PERSETUJUAN DATA PRIBADI PELAMAR KERJA</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-5 p-2 p-md-2 p-xl-2">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy"
                            src="{{ asset('assets/recruitment/app_img.jpeg') }}" alt="Application Form">
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h6>
                                        <ol>
                                            <li style="text-align: justify;">Saya bersedia dan menyetujui untuk
                                                memberikan Data Pribadi saya yang tertuang dalam Curriculum Vitae (CV)
                                                dan Formulir Identitas Pelamar Kerja (untuk selanjutnya disebut sebagai
                                                &quot;Data Pribadi&quot;) yang diperlukan dan dipersyaratkan pada saat
                                                melamar di PT Alita Praya Mitra (untuk selanjutnya disebut
                                                &quot;Perusahaan&quot;).</li>
                                            <li style="text-align: justify;">Bahwa Perusahaan akan menggunakan Data
                                                Pribadi saya untuk kebutuhan Proses Rekrutmen, termasuk di dalamnya Tes
                                                Psikologi dan Asesmen, yang dilakukan secara langsung oleh Perusahaan.
                                            </li>
                                            <li style="text-align: justify;">Perusahaan akan menyimpan Data Pribadi saya
                                                di Sistem Rekrutmen Perusahaan dan Perusahaan menjamin tidak ada
                                                penyalahgunaan atau penggunaan di luar kebutuhan Proses Rekrutmen.</li>
                                            <li style="text-align: justify;">Perusahaan akan melakukan penyimpanan Data
                                                Pribadi saya selama 3 (tiga) tahun sejak saya menyerahkan Data Pribadi
                                                saya.</li>
                                            <li style="text-align: justify;">Perusahaan menjamin hak saya sebagai subjek
                                                Data Pribadi sesuai Undang-Undang Perlindungan Data Pribadi No. 27/2022,
                                                termasuk dengan Peraturan Perundang-undangan turunannya.</li>
                                            <li style="text-align: justify;">Saya menyatakan bahwa Data Pribadi dan
                                                dokumen yang saya berikan adalah benar dan sesuai dengan kondisi
                                                terkini.</li>
                                        </ol>
                                    </h6>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="setuju" id="setuju" style="width: 20px; height: 20px; border: 2px solid" required="">&nbsp;Saya setuju*
                                        <p>*Jika anda setuju maka akan berlanjut ke isian berikutnya</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-0" id="isian">
                    {{-- class="needs-validation" novalidate="" --}}
                    <form class="needs-validation" novalidate="" action="{{ route('recruitment.apply_save') }}"
                        method="post" enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure want to submit this data?');">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ $id }}">
                        <hr class="mt-2 mb-2" />
                        <div class="row g-0 text-center">
                            <h3>FORM IDENTITAS PELAMAR KERJA</h3>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>IDENTITAS DIRI</h4>
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="col-12">
                                            <label for="nama" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
                                            <label for="nama" class="form-label"><span class="text-danger">*Nama Lengkap akan digunakan pada dokumen</span></label>
                                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="tempat_lahir" class="form-label">Tempat Lahir<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="kelamin" class="form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                                            <select class="form-select" name="kelamin" id="kelamin" required="">
                                                <option selected disabled value="">-Pilih-</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="pendidikan" class="form-label">Pendidikan<span class="text-danger">*</span></label>
                                            <select class="form-select" name="pendidikan" id="pendidikan" required="">
                                                <option selected disabled value="">-Pilih-</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA/SMK">SMA/SMK</option>
                                                <option value="D1">D1</option>
                                                <option value="D2">D2</option>
                                                <option value="D3">D3</option>
                                                <option value="D4">D4</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="pekerjaan" class="form-label">Pekerjaan<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="col-12">
                                            <label for="ktp" class="form-label">Nomor KTP (16 angka)<span class="text-danger">*</span></label>
                                            <input type="number" minlength="16" maxlength="16" class="form-control" name="ktp" id="ktp" placeholder="Nomor KTP" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="alamat_ktp" class="form-label">Alamat (Sesuai KTP)<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="5" id="alamat_ktp" name="alamat_ktp" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="alamat_domisili" class="form-label">Alamat (Domisili)<span class="text-danger">*</span></label>&nbsp;<input class="form-check-input" type="checkbox" value="" name="sama" id="sama" style="width: 15px; height: 15px; border: 2px solid">&nbsp;Sesuai KTP
                                            <textarea class="form-control" rows="5" id="alamat_domisili" name="alamat_domisili" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="cv" class="form-label">Upload CV<span class="text-danger">*</span></label>
                                            <input name="cv" id="cv" class="form-control" type="file" required="">
                                            <label class="form-label">*Maksimal 5 MB dengan format pdf</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email Aktif<span class="text-danger">*</span></label>
                                            <label for="email" class="form-label"><span class="text-danger">*Email Aktif akan digunakan untuk korespondensi</span></label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="email@email.com" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="agama" class="form-label">Agama<span class="text-danger">*</span></label>
                                            <select class="form-select" name="agama" id="agama" required="">
                                                <option selected disabled value="">-Pilih-</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen Protestan">Kristen Protestan</option>
                                                <option value="Kristen Katolik">Kristen Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddha">Buddha</option>
                                                <option value="Konghucu">Konghucu</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="npwp" class="form-label">Nomor NPWP</label>
                                            <input type="text" class="form-control" name="npwp" id="npwp" placeholder="Nomor NPWP">
                                        </div>
                                        <div class="col-12">
                                            <label for="telepon" class="form-label">Nomor Telepon<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Nomor Telepon" required="">
                                        </div>
                                        <div class="col-12">
                                            <label for="status_hidup" class="form-label">Status<span class="text-danger">*</span></label>
                                            <select class="form-select" name="status_hidup" id="status_hidup" required="">
                                                <option selected disabled value="">-Pilih-</option>
                                                <option value="Belum Menikah">Belum Menikah</option>
                                                <option value="Bertunangan">Bertunangan</option>
                                                <option value="Menikah">Menikah</option>
                                                <option value="Cerai Hidup">Cerai Hidup</option>
                                                <option value="Cerai Mati">Cerai Mati</option>
                                            </select>
                                            <div class="row mt-2" id="row_status">
                                                <div class="col-6">
                                                    <label for="tanggal_status" class="form-label">Sejak</label>
                                                    <input type="date" class="form-control" name="tanggal_status" id="tanggal_status" placeholder="Sejak">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-2" />
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>SUSUNAN KELUARGA (Jika Sudah Menikah)</h4>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Pasangan</th>
                                                        <th>Nama</th>
                                                        <th>Usia (tahun)</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Pendidikan</th>
                                                        <th>Pekerjaan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-select" name="kelamin_pasangan" id="kelamin_pasangan">
                                                                <option selected disabled value="">-Pilih-</option>
                                                                <option value="Suami">Suami</option>
                                                                <option value="Istri">Istri</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="nama_pasangan" id="nama_pasangan" placeholder="Nama Pasangan">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="usia_pasangan" id="usia_pasangan" placeholder="Usia Pasangan">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="kelamin_pasangan_" id="kelamin_pasangan_" placeholder="Jenis Kelamin" disabled>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="pendidikan_pasangan" id="pendidikan_pasangan">
                                                                <option selected disabled value="">-Pilih-</option>
                                                                <option value="SD">SD</option>
                                                                <option value="SMP">SMP</option>
                                                                <option value="SMA/SMK">SMA/SMK</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="D4">D4</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="pekerjaan_pasangan" id="pekerjaan_pasangan" placeholder="Pekerjaan Pasangan">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table" id="table_anak">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Anak</th>
                                                        <th>Usia (tahun)</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Pendidikan</th>
                                                        <th>Pekerjaan</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowAnak" href="#">Add new</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-2" />
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>SUSUNAN KELUARGA<span class="text-danger">*</span></h4>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Orang Tua<span class="text-danger">*</span></th>
                                                        <th>Nama<span class="text-danger">*</span></th>
                                                        <th>Usia (tahun)<span class="text-danger">*</span></th>
                                                        <th>Jenis Kelamin<span class="text-danger">*</span></th>
                                                        <th>Pendidikan<span class="text-danger">*</span></th>
                                                        <th>Pekerjaan<span class="text-danger">*</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" placeholder="Ayah" disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="nama_bapak" id="nama_bapak" placeholder="Nama Ayah" required="">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="usia_bapak" id="usia_bapak" placeholder="Usia Ayah" required="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" placeholder="Laki-laki" disabled>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="pendidikan_bapak" id="pendidikan_bapak" required="">
                                                                <option selected disabled value="">-Pilih-</option>
                                                                <option value="SD">SD</option>
                                                                <option value="SMP">SMP</option>
                                                                <option value="SMA/SMK">SMA/SMK</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="D4">D4</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="pekerjaan_bapak" id="pekerjaan_bapak" placeholder="Pekerjaan Ayah" required="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" placeholder="Ibu" disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" placeholder="Nama Ibu" required="">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="usia_ibu" id="usia_ibu" placeholder="Usia Ibu" required="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" placeholder="Perempuan" disabled>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="pendidikan_ibu" id="pendidikan_ibu" required="">
                                                                <option selected disabled value="">-Pilih-</option>
                                                                <option value="SD">SD</option>
                                                                <option value="SMP">SMP</option>
                                                                <option value="SMA/SMK">SMA/SMK</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="D4">D4</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="pekerjaan_ibu" id="pekerjaan_ibu" placeholder="Pekerjaan Ibu" required="">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table" id="table_saudara">
                                                <thead class="text-start">
                                                    <tr>
                                                        {{-- <th>Hubungan</th> --}}
                                                        <th>Nama</th>
                                                        <th>Usia (tahun)</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Pendidikan</th>
                                                        <th>Pekerjaan</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowSaudara" href="#">Add new</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-2" />
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>RIWAYAT PENDIDIKAN DAN ORGANISASI</h4>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        {{--  --}}
                                        <div class="table-responsive">
                                            <h5>1. Pendidikan Formal*</h5>
                                            <table class="table" id="table_formal">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Jenjang<span class="text-danger">*</span></th>
                                                        <th>Nama Sekolah<span class="text-danger">*</span></th>
                                                        <th>Kota<span class="text-danger">*</span></th>
                                                        <th>Jurusan<span class="text-danger">*</span></th>
                                                        <th>Tahun Masuk</span></th>
                                                        <th>Tahun Lulus<span class="text-danger">*</span></th>
                                                        <th>Nilai IPK/NEM</span></th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowFormal" href="#">Add new</a>
                                        </div>
                                        {{--  --}}
                                        <div class="table-responsive">
                                            <h5>2. Pendidikan Informal (Kursus, Training, Workshop, dll) dan Keterampilan</h5>
                                            <table class="table" id="table_informal">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Bidang</th>
                                                        <th>Tahun</th>
                                                        <th>Tempat/Lembaga</th>
                                                        <th>Sertifikat</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowInformal" href="#">Add new</a>
                                        </div>
                                        {{--  --}}
                                        <div class="table-responsive">
                                            <h5>3. Organisasi</h5>
                                            <table class="table" id="table_organisasi">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Nama Organisasi</th>
                                                        <th>Periode</th>
                                                        <th>Jabatan</th>
                                                        <th>Keterangan</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowOrganisasi" href="#">Add new</a>
                                        </div>
                                        {{--  --}}
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-2" />
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>RIWAYAT PEKERJAAN</h4>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="table-responsive">
                                            <table class="table" id="table_perusahaan">
                                                <thead class="text-start">
                                                    <tr>
                                                        <th>Perusahaan<span class="text-danger">*</span></th>
                                                        <th>Kota<span class="text-danger">*</span></th>
                                                        <th>Tahun Masuk<span class="text-danger">*</span></th>
                                                        <th>Tahun Keluar<span class="text-danger">*</span></th>
                                                        <th>Jabatan<span class="text-danger">*</span></th>
                                                        <th>Gaji/Upah (Rupiah)<span class="text-danger">*</span></th>
                                                        <th>Alasan Keluar<span class="text-danger">*</span></th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <a class="btn btn-success" id="insertRowPerusahaan" href="#">Add new</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-2" />
                                <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                    <h4>LAIN-LAIN</h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="col-12">
                                            <label for="hobi" class="form-label">1. Apakah hobi atau kegemaran Anda?<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="5" id="hobi" name="hobi" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="kelebihan" class="form-label">2. Faktor-faktor apakah yang menjadi kelebihan (Strong Point) Anda?<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="5" id="kelebihan" name="kelebihan" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="peningkatan" class="form-label">3. Hal apa yang perlu ditingkatkan (Areas to Be Improved) dalam diri Anda?<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="5" id="peningkatan" name="peningkatan" required=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="card-body p-2 p-md-2 p-xl-2">
                                    <div class="row gy-2 gy-md-2 overflow-hidden">
                                        <div class="col-12">
                                            <label for="is_sakit" class="form-label">4. Apakah Anda menderita sakit keras atau sakit yang berkepanjangan?<span class="text-danger">*</span></label>
                                            <select class="form-select" name="is_sakit" id="is_sakit" required="">
                                                <option selected disabled value="">-Pilih-</option>
                                                <option value="Pernah Sakit">Pernah Sakit</option>
                                                <option value="Tidak Pernah Sakit">Tidak Pernah Sakit</option>
                                            </select>
                                            <div class="row" id="row_sakit">
                                                <label for="is_sakit" class="form-label mt-2">Jika Pernah Sakit:</label>
                                                <div class="col-6">
                                                    <input type="text" class="form-control" name="sakit_apa" id="sakit_apa" placeholder="Sakit apa?">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" class="form-control" name="sakit_tahun" id="sakit_tahun" placeholder="Tahun berapa?">
                                                </div>
                                                <div class="col-12">
                                                    <label for="sakit_kondisi" class="form-label mt-2">Kondisi saat ini apakah sudah sembuh atau masih dalam proses pengobatan?</label>
                                                    <textarea class="form-control" rows="5" id="sakit_kondisi" name="sakit_kondisi"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="alasan" class="form-label">5. Alasan Anda ingin bergabung di PT. Alita Praya Mitra<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="5" id="alasan" name="alasan" required=""></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="gaji" class="form-label">6. Gaji yang diharapkan (Rupiah)<span class="text-danger">*</span></label>
                                            <input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="gaji" id="gaji" placeholder="Gaji yang diharapkan (Rupiah)" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                        </div>
                        <div class="row mb-2">
                            <div class="col-5">&nbsp;</div>
                            <div class="col-2 d-grid">
                                <button class="btn bsb-btn-xl btn-primary" type="submit">Submit</button>
                            </div>
                            <div class="col-5">&nbsp;</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    function use_number(node) {
      var empty_val = false;
      const value = node.value;
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
    $(document).ready(function () {
        // anak
        var counter_anak = 1;
        $("#insertRowAnak").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            cols += '<td><input type="text" class="form-control" name="nama_anak[]" id="nama_anak[]" placeholder="Nama Anak" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="usia_anak[]" id="usia_anak[]" placeholder="Usia Anak" required=""></td>';
            cols += '<td><select class="form-select" name="kelamin_anak[]" id="kelamin_anak[]" required=""><option selected disabled value="">-Pilih-</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></td>';
            cols += '<td><select class="form-select" name="pendidikan_anak[]" id="pendidikan_anak[]" required=""><option selected disabled value="">-Pilih-</option><option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA/SMK">SMA/SMK</option><option value="D1">D1</option><option value="D2">D2</option><option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option><option value="Lainnya">Lainnya</option></select></td>';
            cols += '<td><input type="text" class="form-control" name="pekerjaan_anak[]" id="pekerjaan_anak[]" placeholder="Pekerjaan Anak" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowAnak">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_anak").append(newRow);
            // Increase counter_anak after each row insertion
            counter_anak++;
        });
        // Remove row when delete btn is clicked
        $("#table_anak").on("click", "#deleteRowAnak", function (event) {
            $(this).closest("tr").remove();
            // counter_anak -= 1;
        });
        // saudara
        var counter_saudara = 1;
        $("#insertRowSaudara").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            // cols += '<td><select class="form-select" name="kakak_adik[]" id="kakak_adik[]" required=""><option selected disabled value="">-Pilih-</option><option value="Kakak">Kakak</option><option value="Adik">Adik</option></select></td>';
            cols += '<td><input type="text" class="form-control" name="nama_saudara[]" id="nama_saudara[]" placeholder="Nama" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="usia_saudara[]" id="usia_saudara[]" placeholder="Usia" required=""></td>';
            cols += '<td><select class="form-select" name="kelamin_saudara[]" id="kelamin_saudara[]" required=""><option selected disabled value="">-Pilih-</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></td>';
            cols += '<td><select class="form-select" name="pendidikan_saudara[]" id="pendidikan_saudara[]" required=""><option selected disabled value="">-Pilih-</option><option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA/SMK">SMA/SMK</option><option value="D1">D1</option><option value="D2">D2</option><option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option><option value="Lainnya">Lainnya</option></select></td>';
            cols += '<td><input type="text" class="form-control" name="pekerjaan_saudara[]" id="pekerjaan_saudara[]" placeholder="Pekerjaan" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowSaudara">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_saudara").append(newRow);
            // Increase counter_saudara after each row insertion
            counter_saudara++;
        });
        // Remove row when delete btn is clicked
        $("#table_saudara").on("click", "#deleteRowSaudara", function (event) {
            $(this).closest("tr").remove();
            // counter_saudara -= 1;
        });
        // formal
        var counter_formal = 1;
        $("#insertRowFormal").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            cols += '<td><select class="form-select" name="jenjang_sekolah[]" id="jenjang_sekolah[]" required=""><option selected disabled value="">-Pilih-</option><option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA/SMK">SMA/SMK</option><option value="D1">D1</option><option value="D2">D2</option><option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option><option value="Lainnya">Lainnya</option></select></td>';
            cols += '<td><input type="text" class="form-control" name="nama_formal[]" id="nama_formal[]" placeholder="Nama Sekolah" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="kota_sekolah[]" id="kota_sekolah[]" placeholder="Nama Kota" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="jurusan[]" id="jurusan[]" placeholder="Jurusan" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="tahun_masuk_sekolah[]" id="tahun_masuk_sekolah[]" placeholder="Tahun Masuk"></td>';
            cols += '<td><input type="number" class="form-control" name="tahun_lulus_sekolah[]" id="tahun_lulus_sekolah[]" placeholder="Tahun Lulus" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="nilai[]" id="nilai[]" placeholder="Nilai"></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowFormal">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_formal").append(newRow);
            // Increase counter_formal after each row insertion
            counter_formal++;
        });
        // Remove row when delete btn is clicked
        $("#table_formal").on("click", "#deleteRowFormal", function (event) {
            $(this).closest("tr").remove();
            // counter_formal -= 1;
        });
        // informal
        var counter_informal = 1;
        $("#insertRowInformal").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            cols += '<td><input type="text" class="form-control" name="bidang_informal[]" id="bidang_informal[]" placeholder="Bidang" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="tahun_informal[]" id="tahun_informal[]" placeholder="Tahun" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="lembaga[]" id="lembaga[]" placeholder="Tempat/Lembaga" required=""></td>';
            cols += '<td><select class="form-select" name="sertifikat[]" id="sertifikat[]" required=""><option selected disabled value="">-Pilih-</option><option value="Ada">Ada</option><option value="Tidak Ada">Tidak Ada</option></select></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowInformal">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_informal").append(newRow);
            // Increase counter_informal after each row insertion
            counter_informal++;
        });
        // Remove row when delete btn is clicked
        $("#table_informal").on("click", "#deleteRowInformal", function (event) {
            $(this).closest("tr").remove();
            // counter_informal -= 1;
        });
        // organisasi
        var counter_organisasi = 1;
        $("#insertRowOrganisasi").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            cols += '<td><input type="text" class="form-control" name="nama_organisasi[]" id="nama_organisasi[]" placeholder="Nama Organisasi" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="periode[]" id="periode[]" placeholder="Periode" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="jabatan_organisasi[]" id="jabatan_organisasi[]" placeholder="Jabatan" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="keterangan[]" id="keterangan[]" placeholder="Keterangan"></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowOrganisasi">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_organisasi").append(newRow);
            // Increase counter_organisasi after each row insertion
            counter_organisasi++;
        });
        // Remove row when delete btn is clicked
        $("#table_organisasi").on("click", "#deleteRowOrganisasi", function (event) {
            $(this).closest("tr").remove();
            // counter_organisasi -= 1;
        });
        // perusahaan
        var counter_perusahaan = 1;
        $("#insertRowPerusahaan").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr>');
            var cols = '';
            // Table columns
            cols += '<td><input type="text" class="form-control" name="nama_perusahaan[]" id="nama_perusahaan[]" placeholder="Nama Perusahaan" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="kota_perusahaan[]" id="kota_perusahaan[]" placeholder="Kota" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="tahun_masuk_perusahaan[]" id="tahun_masuk_perusahaan[]" placeholder="Tahun Masuk" required=""></td>';
            cols += '<td><input type="number" class="form-control" name="tahun_keluar_perusahaan[]" id="tahun_keluar_perusahaan[]" placeholder="Tahun Keluar" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="jabatan_perusahaan[]" id="jabatan_perusahaan[]" placeholder="Jabatan" required=""></td>';
            cols += '<td><input min=1 type="number" oninput="validity.valid||(value="")" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="upah[]" id="upah[]" placeholder="Gaji/Upah" required=""></td>';
            cols += '<td><input type="text" class="form-control" name="alasan_keluar[]" id="alasan_keluar[]" placeholder="Alasan Keluar" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowPerusahaan">Delete</button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_perusahaan").append(newRow);
            // Increase counter_perusahaan after each row insertion
            counter_perusahaan++;
        });
        // Remove row when delete btn is clicked
        $("#table_perusahaan").on("click", "#deleteRowPerusahaan", function (event) {
            $(this).closest("tr").remove();
            // counter_perusahaan -= 1;
        });
        // 
        $("#sama").change(function () {
            if (this.checked) {
                var alamat_ktp = $("#alamat_ktp").val();
                $("#alamat_domisili").val(alamat_ktp);
            } else {
                $("#alamat_domisili").val('');
            }
        });
        // 
        $("#isian").hide();
        // $("#isian").show();
        $("#setuju").change(function () {
            if (this.checked) {
                $("#isian").show();
            } else {
                $("#isian").hide();
            }
        });
        // 
        $("#row_sakit").hide();
        $("#is_sakit").change(function () {
            $value = $("#is_sakit").val();
            if ($value == 'Pernah Sakit') {
                $("#row_sakit").show();
            } else {
                $("#row_sakit").hide();
            }
        });
        // 
        $("#row_status").hide();
        $("#status_hidup").change(function () {
            $value = $("#status_hidup").val();
            if ($value == 'Belum Menikah' || $value == 'Bertunangan') {
                $("#row_status").hide();
            } else {
                $("#row_status").show();
            }
        });
        // 
        $("#kelamin_pasangan").change(function () {
            $value = $("#kelamin_pasangan").val();
            if ($value == 'Suami') {
                $("#kelamin_pasangan_").val('Laki-laki');
            } else {
                $("#kelamin_pasangan_").val('Perempuan');
            }
        });
    });
// 
</script>

</html>
