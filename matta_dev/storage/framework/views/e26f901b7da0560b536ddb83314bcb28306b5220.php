<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
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
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="<?php echo e(asset('assets/images/matta/alita.png')); ?>" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                <?php if(Session::has('msg')): ?>
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h5><?php echo e(session('msg')); ?></h6>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="row g-0">
                    <h3 class="text-center">SURAT PERSETUJUAN DATA PRIBADI PELAMAR KERJA</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-5 p-2 p-md-2 p-xl-2">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?php echo e(asset('assets/recruitment/app_img.jpeg')); ?>" alt="Application Form">
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
                                        <input type="text" class="form-control" name="nama" id="nama" value="<?php echo e($trx->nama); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?php echo e($trx->tempat_lahir); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo e($trx->tanggal_lahir); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="kelamin" class="form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kelamin" id="kelamin" value="<?php echo e($trx->kelamin); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="pendidikan" class="form-label">Pendidikan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pendidikan" id="pendidikan" value="<?php echo e($trx->pendidikan); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="pekerjaan" class="form-label">Pekerjaan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" value="<?php echo e($trx->pekerjaan); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    <div class="col-12">
                                        <label for="ktp" class="form-label">Nomor KTP<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ktp" id="ktp" value="<?php echo e($trx->ktp); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="alamat_ktp" class="form-label">Alamat (Sesuai KTP)<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alamat_ktp" name="alamat_ktp"><?php echo e($trx->alamat_ktp); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="alamat_domisili" class="form-label">Alamat (Domisili)<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alamat_domisili" name="alamat_domisili"><?php echo e($trx->alamat_domisili); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="cv" class="form-label">CV<span class="text-danger">*</span></label>
                                        <a href="<?php echo e(asset('assets/recruitment/upload/'.$trx->cv)); ?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
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
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo e($trx->email); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="agama" class="form-label">Agama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agama" id="agama" value="<?php echo e($trx->agama); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="npwp" class="form-label">Nomor NPWP</label>
                                        <input type="text" class="form-control" name="npwp" id="npwp" value="<?php echo e($trx->npwp); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="telepon" class="form-label">Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon" id="telepon" value="<?php echo e($trx->telepon); ?>">
                                    </div>
                                    <?php if(!empty($trx->telepon_darurat)): ?>
                                    <?php
                                        $telepon_darurat = array_map('trim', explode(',', $trx->telepon_darurat));
                                        $str_telepon_darurat = $telepon_darurat[1].', '.$telepon_darurat[2];
                                        if(count($telepon_darurat) > 3){
                                            $str_telepon_darurat = $telepon_darurat[2].', '.$telepon_darurat[3];
                                        }
                                    ?>
                                    <div class="col-12">
                                        <label for="telepon_darurat" class="form-label">Nomor Telepon Darurat<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon_darurat" id="telepon_darurat" value="<?php echo e($str_telepon_darurat); ?>">
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-12">
                                        <label for="status_hidup" class="form-label">Status<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="status_hidup" id="status_hidup" value="<?php echo e($trx->status_hidup); ?>">
                                        <?php if(!empty($trx->tanggal_status) && in_array($trx->status_hidup, ['Menikah','Cerai Hidup','Cerai Mati'])): ?>
                                        <div class="row mt-2" id="row_status">
                                            <div class="col-6">
                                                <label for="tanggal_status" class="form-label">Sejak</label>
                                                
                                                <input type="text" class="form-control" name="tanggal_status" id="tanggal_status" value="<?php echo e(Carbon\Carbon::parse($trx->tanggal_status)->format('d-m-Y')); ?>">
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
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
                                                        <input type="text" class="form-control" name="kelamin_pasangan" id="kelamin_pasangan" value="<?php echo e($trx->kelamin_pasangan); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_pasangan" id="nama_pasangan" value="<?php echo e($trx->nama_pasangan); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_pasangan" value="<?php echo e($trx->usia_pasangan); ?>" id="usia_pasangan">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="kelamin_pasangan" id="kelamin_pasangan" value="<?php if($trx->kelamin_pasangan == 'Suami'): ?> Laki-laki <?php elseif($trx->kelamin_pasangan == 'Istri'): ?> Perempuan <?php endif; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_pasangan" id="pendidikan_pasangan" value="<?php echo e($trx->pendidikan_pasangan); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_pasangan" id="pekerjaan_pasangan" value="<?php echo e($trx->pekerjaan_pasangan); ?>">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                        $where = ['id_identitas' => $trx->id];
                                        $_trx = App\Models\TrxRecruitmentAnak::where($where)->orderBy('usia', 'desc')->get();
                                        ?>
                                        <?php if(!empty($_trx)): ?>
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
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->nama); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->usia); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->kelamin); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->pendidikan); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->pekerjaan); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
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
                                                        <input type="text" class="form-control" name="nama_bapak" id="nama_bapak" value="<?php echo e($trx->nama_bapak); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_bapak" id="usia_bapak" value="<?php echo e($trx->usia_bapak); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="Laki-laki">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_bapak" id="pendidikan_bapak" value="<?php echo e($trx->pendidikan_bapak); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_bapak" id="pekerjaan_bapak" value="<?php echo e($trx->pekerjaan_bapak); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" value="Ibu">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" value="<?php echo e($trx->nama_ibu); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="usia_ibu" id="usia_ibu" value="<?php echo e($trx->usia_ibu); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="Perempuan">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pendidikan_ibu" id="pendidikan_ibu" value="<?php echo e($trx->pendidikan_ibu); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="pekerjaan_ibu" id="pekerjaan_ibu" value="<?php echo e($trx->pekerjaan_ibu); ?>">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                        $where = ['id_identitas' => $trx->id];
                                        $_trx = App\Models\TrxRecruitmentSaudara::select('nama','usia','kelamin','pendidikan','pekerjaan')->where($where)->orderBy('usia', 'desc')->get()->toArray();
                                        array_push($_trx, ['nama'=>$trx->nama, 'usia'=>$trx->usia, 'kelamin'=>$trx->kelamin, 'pendidikan'=>$trx->pendidikan, 'pekerjaan'=>$trx->pekerjaan]);
                                        $keys = array_column($_trx, 'usia');
                                        array_multisort($keys, SORT_DESC, $_trx);
                                        ?>
                                        <?php if(!empty($_trx)): ?>
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
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php if($data['usia'] > $trx->usia): ?> Kakak <?php elseif($data['usia'] < $trx->usia): ?> Adik <?php else: ?> Diri Sendiri <?php endif; ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data['nama']); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data['usia']); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data['kelamin']); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data['pendidikan']); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data['pekerjaan']); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <hr class="mt-2 mb-2" />
                            <div class="text-center card-body p-2 p-md-2 p-xl-2">
                                <h4>RIWAYAT PENDIDIKAN DAN ORGANISASI</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    
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
                                            <?php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentFormal::where($where)->get();
                                            ?>
                                            <tbody>
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->jenjang); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->nama); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->kota); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->jurusan); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->tahun_masuk); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->tahun_lulus); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->nilai); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
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
                                            <?php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentInformal::where($where)->get();
                                            ?>
                                            <tbody>
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->bidang); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->tahun); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->lembaga); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->sertifikat); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
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
                                            <?php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentOrganisasi::where($where)->get();
                                            ?>
                                            <tbody>
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->nama); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->periode); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->jabatan); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->keterangan); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
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
                                            <?php
                                            $where = ['id_identitas' => $trx->id];
                                            $_trx = App\Models\TrxRecruitmentPekerjaan::where($where)->get();
                                            ?>
                                            <tbody>
                                                <?php $__currentLoopData = $_trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->nama); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->kota); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->tahun_masuk); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->tahun_keluar); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->jabatan); ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php if(!empty($data->gaji)): ?>Rp. <?php echo e(number_format($data->gaji, 0)); ?><?php endif; ?>"></td>
                                                    <td><input type="text" class="form-control" value="<?php echo e($data->alasan); ?>"></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
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
                                        <textarea class="form-control" rows="5" id="hobi" name="hobi"><?php echo e($trx->hobi); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="kelebihan" class="form-label">2. Faktor-faktor apakah yang menjadi kelebihan (Strong Point) Anda?<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="kelebihan" name="kelebihan"><?php echo e($trx->kelebihan); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="peningkatan" class="form-label">3. Hal apa yang perlu ditingkatkan (Areas to Be Improved) dalam diri Anda?<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="peningkatan" name="peningkatan"><?php echo e($trx->peningkatan); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card-body p-2 p-md-2 p-xl-2">
                                <div class="row gy-2 gy-md-2 overflow-hidden">
                                    <div class="col-12">
                                        <label for="is_sakit" class="form-label">4. Apakah Anda menderita sakit keras atau sakit yang berkepanjangan?<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?php echo e($trx->is_sakit); ?>">
                                        <?php if($trx->is_sakit=='Pernah Sakit'): ?>
                                        <div class="row" id="row_sakit">
                                            <label for="is_sakit" class="form-label mt-2">Jika Pernah Sakit:</label>
                                            <div class="col-6">
                                                <label for="sakit_apa" class="form-label">Penyakit:</label>
                                                <input type="text" class="form-control" name="sakit_apa" id="sakit_apa" value="<?php echo e($trx->sakit_apa); ?>">
                                            </div>
                                            <div class="col-6">
                                                <label for="sakit_tahun" class="form-label">Tahun:</label>
                                                <input type="number" class="form-control" name="sakit_tahun" id="sakit_tahun" value="<?php echo e($trx->sakit_tahun); ?>">
                                            </div>
                                            <div class="col-12">
                                                <label for="sakit_kondisi" class="form-label mt-2">Kondisi saat ini apakah sudah sembuh atau masih dalam proses pengobatan?</label>
                                                <textarea class="form-control" rows="5" id="sakit_kondisi" name="sakit_kondisi"><?php echo e($trx->sakit_kondisi); ?></textarea>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12">
                                        <label for="alasan" class="form-label">5. Alasan Anda ingin bergabung di PT. Alita Praya Mitra<span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" id="alasan" name="alasan"><?php echo e($trx->alasan); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="gaji" class="form-label">6. Gaji yang diharapkan (Rupiah)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="gaji" id="gaji" value="Rp. <?php echo e(number_format($trx->gaji, 0)); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
        <div class="col-12 m-2 text-center">
            <a target="_blank" href="<?php echo e(route('recruitment.download_applicant', Crypt::encrypt($trx->id))); ?>" class="btn btn-primary" type="button"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
        </div>
        <?php endif; ?>
        
        
        
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/applicant_item.blade.php ENDPATH**/ ?>