<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
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
                <div class="row g-0">
                    <h3 class="text-center">SURAT PERSETUJUAN DATA PRIBADI PELAMAR KERJA</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-5 p-2 p-md-2 p-xl-2">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="{{ asset('assets/recruitment/app_img.jpeg') }}" alt="Application Form">
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div>
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
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="setuju" id="setuju" style="width: 20px; height: 20px; border: 2px solid" checked>&nbsp;Saya setuju
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-0" id="isian">
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
                                        <label for="nama" class="form-label"><span class="text-danger">*Nama Lengkap akan digunakan pada dokumen.</span></label>
                                        <input type="text" class="form-control" name="nama" id="nama" value="{{ $trx->nama }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="{{ $trx->tempat_lahir }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="{{ $trx->tanggal_lahir }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="kelamin" class="form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kelamin" id="kelamin" value="{{ $trx->kelamin }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="pendidikan" class="form-label">Pendidikan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pendidikan" id="pendidikan" value="{{ $trx->pendidikan }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="pekerjaan" class="form-label">Pekerjaan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" value="{{ $trx->pekerjaan }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    <div class="col-12">
                                        <label for="ktp" class="form-label">Nomor KTP<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ktp" id="ktp" value="{{ $trx->ktp }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="alamat_ktp" class="form-label">Alamat (Sesuai KTP)<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alamat_ktp" name="alamat_ktp">{{ $trx->alamat_ktp }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="alamat_domisili" class="form-label">Alamat (Domisili)<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alamat_domisili" name="alamat_domisili">{{ $trx->alamat_domisili }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="cv" class="form-label">CV<span class="text-danger">*</span></label>
                                        <a href="{{ asset('assets/recruitment/upload/'.$trx->cv) }}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email Aktif<span class="text-danger">*</span></label>
                                        <label for="email" class="form-label"><span class="text-danger">*Email Aktif akan digunakan untuk korespondensi.</span></label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{ $trx->email }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="agama" class="form-label">Agama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agama" id="agama" value="{{ $trx->agama }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="npwp" class="form-label">Nomor NPWP</label>
                                        <input type="text" class="form-control" name="npwp" id="npwp" value="{{ $trx->npwp }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="telepon" class="form-label">Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon" id="telepon" value="{{ $trx->telepon }}">
                                    </div>
                                    @if(!empty($trx->telepon_darurat))
                                    @php
                                        $telepon_darurat = array_map('trim', explode(',', $trx->telepon_darurat));
                                        $str_telepon_darurat = $telepon_darurat[1].', '.$telepon_darurat[2];
                                        if(count($telepon_darurat) > 3){
                                            $str_telepon_darurat = $telepon_darurat[2].', '.$telepon_darurat[3];
                                        }
                                    @endphp
                                    <div class="col-12">
                                        <label for="telepon_darurat" class="form-label">Nomor Telepon Darurat<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon_darurat" id="telepon_darurat" value="{{ $str_telepon_darurat }}">
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        <label for="status_hidup" class="form-label">Status<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="status_hidup" id="status_hidup" value="{{ $trx->status_hidup }}">
                                        @if(!empty($trx->tanggal_status) && in_array($trx->status_hidup, ['Menikah','Cerai Hidup','Cerai Mati']))
                                        <div class="row mt-2" id="row_status">
                                            <div class="col-6">
                                                <label for="tanggal_status" class="form-label">Sejak</label>
                                                {{-- <input type="date" class="form-control" name="tanggal_status" value="{{ $trx->tanggal_status }}" id="tanggal_status"> --}}
                                                <input type="text" class="form-control" name="tanggal_status" id="tanggal_status" value="{{ Carbon\Carbon::parse($trx->tanggal_status)->format('d-m-Y') }}">
                                            </div>
                                        </div>
                                        @endif
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
                                                        <input type="text" class="form-control" name="kelamin_pasangan" id="kelamin_pasangan" value="{{ $trx->kelamin_pasangan }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_pasangan" id="nama_pasangan" value="{{ $trx->nama_pasangan }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_pasangan" value="{{ $trx->usia_pasangan }}" id="usia_pasangan">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="kelamin_pasangan" id="kelamin_pasangan" value="@if($trx->kelamin_pasangan == 'Suami') Laki-laki @elseif($trx->kelamin_pasangan == 'Istri') Perempuan @endif">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_pasangan" id="pendidikan_pasangan" value="{{ $trx->pendidikan_pasangan }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_pasangan" id="pekerjaan_pasangan" value="{{ $trx->pekerjaan_pasangan }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @php
                                        $where = ['id_identitas' => $trx->id];
                                        $_trx = App\Models\TrxRecruitmentAnak::where($where)->orderBy('usia', 'desc')->get();
                                        @endphp
                                        @if(!empty($_trx))
                                        <table class="table" id="table_anak">
                                            <thead class="text-start">
                                                <tr>
                                                    <th>Anak</th>
                                                    <th>Usia (tahun)</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Pendidikan</th>
                                                    <th>Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $data->nama }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->usia }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->kelamin }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->pendidikan }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->pekerjaan }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                        <div class="col-12">
                            <hr class="mt-2 mb-2" />
                            <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                <h4>SUSUNAN KELUARGA (Termasuk Diri Anda)<span class="text-danger">*</span></h4>
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
                                                        <input type="text" class="form-control" value="Bapak">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_bapak" id="nama_bapak" value="{{ $trx->nama_bapak }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_bapak" id="usia_bapak" value="{{ $trx->usia_bapak }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="Laki-laki">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_bapak" id="pendidikan_bapak" value="{{ $trx->pendidikan_bapak }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_bapak" id="pekerjaan_bapak" value="{{ $trx->pekerjaan_bapak }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" value="Ibu">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" value="{{ $trx->nama_ibu }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_ibu" id="usia_ibu" value="{{ $trx->usia_ibu }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="Perempuan">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_ibu" id="pendidikan_ibu" value="{{ $trx->pendidikan_ibu }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_ibu" id="pekerjaan_ibu" value="{{ $trx->pekerjaan_ibu }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @php
                                        $where = ['id_identitas' => $trx->id];
                                        $_trx = App\Models\TrxRecruitmentSaudara::select('nama','usia','kelamin','pendidikan','pekerjaan')->where($where)->orderBy('usia', 'desc')->get()->toArray();
                                        array_push($_trx, ['nama'=>$trx->nama, 'usia'=>$trx->usia, 'kelamin'=>$trx->kelamin, 'pendidikan'=>$trx->pendidikan, 'pekerjaan'=>$trx->pekerjaan]);
                                        $keys = array_column($_trx, 'usia');
                                        array_multisort($keys, SORT_DESC, $_trx);
                                        @endphp
                                        @if(!empty($_trx))
                                        <table class="table" id="table_saudara">
                                            <thead class="text-start">
                                                <tr>
                                                    <th>Hubungan</th>
                                                    <th>Nama</th>
                                                    <th>Usia (tahun)</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Pendidikan</th>
                                                    <th>Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="@if($data['usia'] > $trx->usia) Kakak @elseif($data['usia'] < $trx->usia) Adik @else Diri Sendiri @endif"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data['nama'] }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data['usia'] }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data['kelamin'] }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data['pendidikan'] }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data['pekerjaan'] }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
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
                                                    <th>Tahun Masuk</th>
                                                    <th>Tahun Lulus<span class="text-danger">*</span></th>
                                                    <th>Nilai IPK/NEM</th>
                                                </tr>
                                            </thead>
                                            @php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentFormal::where($where)->get();
                                            @endphp
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $data->jenjang }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->nama }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->kota }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->jurusan }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->tahun_masuk }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->tahun_lulus }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->nilai }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                                </tr>
                                            </thead>
                                            @php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentInformal::where($where)->get();
                                            @endphp
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $data->bidang }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->tahun }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->lembaga }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->sertifikat }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                                </tr>
                                            </thead>
                                            @php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentOrganisasi::where($where)->get();
                                            @endphp
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $data->nama }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->periode }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->jabatan }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->keterangan }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                                    <th>Upah<span class="text-danger">*</span></th>
                                                    <th>Alasan Keluar<span class="text-danger">*</span></th>
                                                </tr>
                                            </thead>
                                            @php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentPekerjaan::where($where)->get();
                                            @endphp
                                            <tbody>
                                                @foreach($_trx as $data)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $data->nama }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->kota }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->tahun_masuk }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->tahun_keluar }}"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->jabatan }}"></td>
                                                    <td><input type="text" class="form-control" value="@if(!empty($data->gaji))Rp. {{ number_format($data->gaji, 0) }}@endif"></td>
                                                    <td><input type="text" class="form-control" value="{{ $data->alasan }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                        <textarea class="form-control" rows="5" id="hobi" name="hobi">{{ $trx->hobi }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="kelebihan" class="form-label">2. Faktor-faktor apakah yang menjadi kelebihan (Strong Point) Anda?<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="kelebihan" name="kelebihan">{{ $trx->kelebihan }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="peningkatan" class="form-label">3. Hal apa yang perlu ditingkatkan (Areas to Be Improved) dalam diri Anda?<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="peningkatan" name="peningkatan">{{ $trx->peningkatan }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    <div class="col-12">
                                        <label for="is_sakit" class="form-label">4. Apakah Anda menderita sakit keras atau sakit yang berkepanjangan?<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $trx->is_sakit }}">
                                        @if($trx->is_sakit=='Pernah Sakit')
                                        <div class="row" id="row_sakit">
                                            <label for="is_sakit" class="form-label mt-2">Jika Pernah Sakit:</label>
                                            <div class="col-6">
                                                <label for="sakit_apa" class="form-label">Penyakit:</label>
                                                <input type="text" class="form-control" name="sakit_apa" id="sakit_apa" value="{{ $trx->sakit_apa }}">
                                            </div>
                                            <div class="col-6">
                                                <label for="sakit_tahun" class="form-label">Tahun:</label>
                                                <input type="number" class="form-control" name="sakit_tahun" id="sakit_tahun" value="{{ $trx->sakit_tahun }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="sakit_kondisi" class="form-label mt-2">Kondisi saat ini apakah sudah sembuh atau masih dalam proses pengobatan?</label>
                                                <textarea class="form-control" rows="5" id="sakit_kondisi" name="sakit_kondisi">{{ $trx->sakit_kondisi }}</textarea>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label for="alasan" class="form-label">5. Alasan Anda ingin bergabung di PT. Alita Praya Mitra<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alasan" name="alasan">{{ $trx->alasan }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="gaji" class="form-label">6. Gaji yang diharapkan (Rupiah)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="gaji" id="gaji" value="Rp. {{ number_format($trx->gaji, 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  --}}
                    </div>
                    {{-- <div class="row mb-2">
                        <div class="col-5">&nbsp;</div>
                        <div class="col-2">
                            <div class="d-grid">
                                <a class="btn bsb-btn-xl btn-primary" data-bs-toggle="modal" data-bs-target="#modal_edit" href="#modal_edit">Psychotest Send</a>
                            </div>
                        </div>
                        <div class="col-5">&nbsp;</div>
                    </div> --}}
                </div>
            </div>
        </div>
        @can('hc')
        <div class="col-12 m-2 text-center">
            <a target="_blank" href="{{ route('recruitment.download_applicant', Crypt::encrypt($trx->id)) }}" class="btn btn-primary" type="button"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
        </div>
        @endcan
        {{--  --}}
        {{-- <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                <form action="{{ route('recruitment.applicant_save') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ Crypt::encrypt($trx->id) }}">
                <input type="hidden" id="id_status" name="id_status" value="4">
                <input type="hidden" id="back_url" name="back_url" value="{{ URL::full() }}">
                  <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel2">Psychotest Send</h5>
                     <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label" for="check_in">Check_in*</label>
                                <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="" value="{{ $trx->check_in }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="check_out">Check_out*</label>
                                <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" value="{{ $trx->check_out }}" disabled>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                     <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                     <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </form>
               </div>
            </div>
         </div> --}}
        {{--  --}}
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
