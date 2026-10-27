<!DOCTYPE html>
<html lang="en">

<head>
    <title>Negotiation Process</title>
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
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload file is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="row g-0 text-center">
                    <h3>FORMULIR PENAWARAN</h3>
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
                            <input class="form-control" name="nama" id="nama" type="text" readonly="" value="<?php echo e($_trx->nama); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="alamat_ktp">Alamat</label>
                                <input class="form-control" name="alamat_ktp" id="alamat_ktp" type="text" readonly="" value="<?php echo e($_trx->alamat_ktp); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="tempat_tanggal_lahir">Tempat / Tanggal Lahir</label>
                            <input class="form-control" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" type="text" readonly="" value="<?php echo e($_trx->tempat_lahir); ?> / <?php echo e(Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y')); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="pendidikan">Pendidikan</label>
                            <input class="form-control" name="pendidikan" id="pendidikan" type="text" readonly="" value="<?php echo e($_trx->pendidikan); ?>">
                        </div>
                        <?php
                        $formal = App\Models\TrxRecruitmentFormal::where(['id_identitas'=>$_trx->id, 'jenjang'=>$_trx->pendidikan])->first();
                        $kebutuhan_atribute = json_decode($_trx->kebutuhan_atribute, true);
                        $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
                        ?>
                        <?php if(!empty($formal)): ?>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="jurusan">Jurusan</label>
                            <input class="form-control" name="jurusan" id="jurusan" type="text" readonly="" value="<?php echo e($formal->jurusan); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Organisasi</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="posisi">Posisi / Jabatan</label>
                            
                            <input class="form-control" name="posisi" id="posisi" type="text" readonly="" value="<?php echo e(App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="level">Level</label>
                            
                            <input class="form-control" name="level" id="level" type="text" readonly="" value="<?php echo e(App\Models\Level::find($kebutuhan_atribute['id_level'])->level); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="area_kerja">Area Kerja</label>
                            
                            <input class="form-control" name="area_kerja" id="area_kerja" type="text" readonly="" value="<?php echo e(App\Models\Propinsi::find($kebutuhan_atribute['id_area'])->propinsi); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="ddd_kandidat">Direktorat / Divisi / Departemen Kandidat</label>
                            
                            <input class="form-control" name="ddd_kandidat" id="ddd_kandidat" type="text" readonly="" value="<?php echo e(App\Models\Divisi::find($kebutuhan_atribute['id_divisi_kandidat'])->nama); ?> / <?php echo e(App\Models\Departemen::find($kebutuhan_atribute['id_dep_kandidat'])->nama); ?> / <?php echo e(App\Models\SubDepartemen::find($kebutuhan_atribute['id_sub_dep_kandidat'])->nama); ?>">
                        </div>
                        <?php
                        $atasan = json_decode($trxM->atasan_property, true);
                        ?>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="atasan">Atasan Langsung Kandidat</label>
                            
                            <input class="form-control" name="atasan" id="atasan" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['nama']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['nik']); ?>">
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                            
                            <input class="form-control" name="divisi" id="divisi" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['divisi']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['dep']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['sub_dep']); ?>">
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Benefit</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="thp">Take Home Pay (THP)</label>
                            <input class="form-control" name="thp" id="thp" type="text" readonly="" value="Rp. <?php echo e(number_format($trx->thp, 2)); ?>">
                        </div>
                        <?php
                        $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $trx->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                        $benefit_utama = implode('&#13;&#10;', $benefit_utama);
                        ?>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="benefit_utama">Benefit</label>
                            <textarea class="form-control" rows="5" id="benefit_utama" name="benefit_utama" readonly=""><?php echo $benefit_utama; ?></textarea>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                            
                            <textarea class="form-control" rows="5" name="benefit_lainnya" id="benefit_lainnya" readonly=""><?php echo e($trx->benefit_lainnya); ?></textarea>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Kepegawaian</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung</label>
                                <input class="form-control" name="tgl_bergabung" id="tgl_bergabung" type="text" readonly="" value="<?php echo e(Carbon\Carbon::parse($trx->tgl_bergabung)->format('d-m-Y')); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian</label>
                                <input class="form-control" name="id_catat_pegawai" id="id_catat_pegawai" type="text" readonly="" value="<?php echo e($trx->_catat_pegawai->pencatatan); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_status_pegawai">Status Kepegawaian</label>
                                <input class="form-control" name="id_status_pegawai" id="id_status_pegawai" type="text" readonly="" value="<?php echo e($trx->_status_pegawai->status); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)</label>
                                <input class="form-control" name="masa_kerja" id="masa_kerja" type="text" readonly="" value="<?php echo e($trx->masa_kerja); ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Catatan Dan Lampiran</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="4" ><?php echo e($log->catatan); ?></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="col-12 mb-2">
                                <?php if(!empty($log->lampiran)): ?>Download Lampiran&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$log->lampiran)); ?>" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <form action="<?php echo e(route('recruitment.negotiation_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="<?php echo e(Crypt::encrypt($trx->id_identitas)); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan Oleh Kandidat</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran Oleh Kandidat</label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <div class="row mb-2 text-center">
                                <div class="col-12 col-md-4">&nbsp;</div>
                                <div class="col-12 col-md-4">
                                    
                                    <button type="submit" name="id_status" value="17" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Setuju</button>
                                    
                                    <button type="submit" name="id_status" value="18" class="btn btn-danger"><i class="fa fa-close" aria-hidden="true"></i> Tidak Setuju</button>
                                </div>
                                <div class="col-12 col-md-4">&nbsp;</div>
                            </div>
                        </form>
                    </div>
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
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/negotiation_process.blade.php ENDPATH**/ ?>