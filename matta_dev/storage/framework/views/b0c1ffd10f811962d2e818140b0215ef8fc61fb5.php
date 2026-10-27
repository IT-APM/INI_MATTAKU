<?php $__env->startSection('title', 'Recruitment'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Process</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Process</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <?php
                    $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
                    $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
                    $catatan = App\Models\TrxRecruitmentAtributeLog::where(['id_identitas'=>$trx->id])->orderBy('id', 'desc')->get();
                ?>
                <div class="card-header">
                    <h5>Add Employee Atribute</h5>
                </div>
                <div class="card-body">
                    <?php if(Session::has('msg_post_atribute')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg_post_atribute')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <form action="<?php echo e(route('recruitment.post_atribute')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                    <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="id_pekerjaan" class="form-label">Nama Jabatan</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_pekerjaan" name="id_pekerjaan" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $pekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $kebutuhan_atribute['id_pekerjaan']): ?> selected <?php endif; ?> ><?php echo e($data->pekerjaan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="id_area">Area Kerja</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_area" name="id_area" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $kebutuhan_atribute['id_area']): ?> selected <?php endif; ?>><?php echo e($data->propinsi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="id_kota">Area Kerja Kota</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_kota" name="id_kota" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $kota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $kebutuhan_atribute['id_kota']): ?> selected <?php endif; ?>><?php echo e($data->kota); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="id_level">Level</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_level" name="id_level" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $kebutuhan_atribute['id_level']): ?> selected <?php endif; ?> ><?php echo e($data->level); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="atasan">Atasan Langsung Kandidat</label>
                            <select class="form-select js-example-basic-single col-sm-12" id="atasan" name="atasan" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $atasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $kebutuhan_atribute['atasan']): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="id_perusahaan_kandidat">Perusahaan Kandidat</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_perusahaan_kandidat" name="id_perusahaan_kandidat" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>" <?php if($data->id == $kebutuhan_atribute['id_perusahaan_kandidat']): ?> selected <?php endif; ?>><?php echo e($data->nama); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="id_divisi_kandidat">Direktorat Kandidat</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_divisi_kandidat" name="id_divisi_kandidat" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="id_dep_kandidat">Divisi Kandidat</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_dep_kandidat" name="id_dep_kandidat" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label class="form-label" for="id_sub_dep_kandidat">Departemen Kandidat</label>
                            <select class="form-select col-sm-12 js-example-basic-single" id="id_sub_dep_kandidat" name="id_sub_dep_kandidat" required="">
                                <option selected="" disabled="" value="">Pilih...</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-12 mt-2">
                            <label class="form-label" for="catatan">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="4" required=""></textarea>
                        </div>
                        <div class="col-12 col-md-12 mt-2 notification">
                            <label class="form-label">Catatan Histori:</label><br/>
                            <span>
                                
                                
                                 <ul>
                                    <?php $__currentLoopData = $catatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_catatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="d-flex">
                                        <?php if($loop->first): ?>
                                        <div class="activity-dot-success"></div>
                                        <?php elseif($loop->last): ?>
                                        <div class="activity-dot-warning"></div>
                                        <?php else: ?>
                                        <div class="activity-dot-primary"></div>
                                        <?php endif; ?>
                                        <div class="ms-3">
                                            <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_catatan->created_at); ?> oleh <?php echo e(App\Models\Karyawan::where('nik', $_catatan->inserted_by)->first()->nama); ?>/<?php echo e($_catatan->inserted_by); ?></span></p>
                                            <p class="f-light"><?php echo e($_catatan->catatan); ?></p>
                                        </div>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4 mt-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <hr/>
                <div class="card-header">
                    <h5>Process</h5>
                </div>
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
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
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label for="id" class="form-label">#ID</label>
                                <input type="text" class="form-control" name="id" id="id" placeholder="ID" value="<?php echo e($trx->id_kebutuhan); ?>/<?php echo e($trx->id); ?>" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label for="nama" class="form-label">Nama / Email Kandidat</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama/Email" value="<?php echo e($trx->nama); ?> / <?php echo e($trx->email); ?>" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(in_array($trx->id_status, [17,18])): ?>
                        <?php if(!empty($nego)): ?>
                        <?php
                        // 17=Negotiation Deal by Candidate
                        // 18=Negotiation No Deal by Candidate
                        $nego_log = App\Models\TrxRecruitmentNegosiasiLog::where(['id_identitas'=>$nego->id_identitas])->whereIn('id_status', [17,18])->orderBy('id', 'desc')->first();
                        $atasan = json_decode($trxM->atasan_property, true);
                        $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
                        $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
                        ?>
                        <?php if(!empty($nego_log)): ?>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">ORGANISASI</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="posisi">Posisi / Jabatan</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e(App\Models\PekerjaanSub::find($kebutuhan_atribute['id_pekerjaan'])->pekerjaan); ?>">
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="level">Level</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e(App\Models\Level::find($kebutuhan_atribute['id_level'])->level); ?>">
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="area_kerja">Area Kerja</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e(App\Models\Propinsi::find($kebutuhan_atribute['id_area'])->propinsi); ?>">
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="ddd_kandidat">Direktorat / Divisi / Departemen Kandidat</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e(App\Models\Divisi::find($kebutuhan_atribute['id_divisi_kandidat'])->nama); ?> / <?php echo e(App\Models\Departemen::find($kebutuhan_atribute['id_dep_kandidat'])->nama); ?> / <?php echo e(App\Models\SubDepartemen::find($kebutuhan_atribute['id_sub_dep_kandidat'])->nama); ?>">
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="atasan">Atasan Langsung Kandidat</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['nama']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['nik']); ?>">
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                                
                                <input class="form-control" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['divisi']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['dep']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['sub_dep']); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">BENEFIT</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="thp">Take Home Pay (THP)</label>
                                <input class="form-control" type="text" readonly="" value="Rp. <?php echo e(number_format($nego_log->thp, 0)); ?>">
                            </div>
                            <?php
                            $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $nego_log->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                            $benefit_utama = implode('&#13;&#10;', $benefit_utama);
                            ?>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="benefit_utama">Benefit</label>
                                <textarea class="form-control" rows="5" readonly=""><?php echo $benefit_utama; ?></textarea>
                            </div>
                            <div class="col-12 col-md-12 mb-2">
                                <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                
                                <textarea class="form-control" rows="5" readonly=""><?php echo e($nego_log->benefit_lainnya); ?></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">KEPEGAWAIAN</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung</label>
                                    <input class="form-control" type="text" readonly="" value="<?php echo e(Carbon\Carbon::parse($nego_log->tgl_bergabung)->format('d-m-Y')); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian</label>
                                    <input class="form-control" type="text" readonly="" value="<?php echo e($nego_log->_catat_pegawai->pencatatan); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="id_status_pegawai">Status Kepegawaian</label>
                                    <input class="form-control" type="text" readonly="" value="<?php echo e($nego_log->_status_pegawai->status); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)</label>
                                    <input class="form-control" type="text" readonly="" value="<?php echo e($nego_log->masa_kerja); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">CATATAN DAN LAMPIRAN</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="catatan">Catatan</label>
                                    <textarea class="form-control" rows="4" readonly=""><?php echo e($nego_log->catatan); ?></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="col-12 mb-2">
                                    <?php if(!empty($nego_log->lampiran)): ?>Download Lampiran&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$nego_log->lampiran)); ?>" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <h6 class="text-center">RESPON</h6>
                        </div>
                        <hr class="mt-2 mb-2" />
                        <div class="col-12 col-md-12 mb-2">
                            <input class="form-control" name="lbl_id_status" id="lbl_id_status" type="text" readonly="" value="<?php echo e($nego_log->_status->status); ?>">
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if(request()->get('type') == 'extend'): ?>
                    <hr class="mt-2 mb-2" />
                    <label class="form-label">Extend</label>
                    <form action="<?php echo e(route('recruitment.applicant_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                        <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                        <input type="hidden" id="id_status" name="id_status" value="4">
                        <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                        <input type="hidden" id="type" name="type" value="extend">
                        <div class="row mb-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="check_in">Check_in</label>
                                <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="check_out">Check_out</label>
                                <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                    <?php else: ?>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php if(empty($process)): ?>
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_process">Process</label>
                                <select class="form-select col-sm-12" id="id_process" name="id_process" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $id_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->status); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            <a href="<?php echo e(route('recruitment.applicant_list', 0)); ?>" class="btn btn-warning" type="button">Back</a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(request()->get('param') === 'sync'): ?>
                        <div class="row mt-2">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="24">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <input type="hidden" id="param" name="param" value="<?php echo e(request()->get('param')); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Document Sync</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    <?php endif; ?>
                    <hr class="mt-2 mb-2" />
                    
                    <div id="row_4">
                        <form action="<?php echo e(route('recruitment.applicant_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="4">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" disabled>
                                </div>
                            </div>
                            <?php
                            $check1 = App\Models\TrxRecruitmentTiu5::where(['id_identitas'=>$trx->id])->exists();
                            $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas'=>$trx->id])->exists();
                            ?>
                            <div class="row mb-2">
                                <div class="col-12 col-md-12">
                                    <label class="form-label" for="jenis_psikotes">Jenis Psikotes</label>
                                    <select class="form-select col-sm-12" id="jenis_psikotes" name="jenis_psikotes" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php if(!$check1): ?>
                                        <option value="1">TIU 5</option>
                                        <?php endif; ?>
                                        <?php if(!$check2): ?>
                                        <option value="2">EPPS, PAPI Kostick, DISC, BAUM, DAP</option>
                                        <?php endif; ?>
                                        <?php if(!$check1 && !$check2): ?>
                                        <option value="3">TIU 5 & (EPPS, PAPI Kostick, DISC, BAUM, DAP)</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <?php if(!empty($process) && $process=='psikotest_reschedule'): ?>
                        <form action="<?php echo e(route('recruitment.applicant_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="4">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <input type="hidden" id="process" name="process" value="<?php echo e($process); ?>">
                            <h5>Jadwal Ulang Psikotes</h5>
                            <h6>Sebelum</h6>
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Check_in</label>
                                    <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($trx->check_in) ->format('d-m-Y H:i:s')); ?>" disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Check_out</label>
                                    <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($trx->check_out) ->format('d-m-Y H:i:s')); ?>" disabled>
                                </div>
                            </div>
                            <h6>Sesudah</h6>
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <?php if(!empty($process) && $process=='psikotest_revisi'): ?>
                        <form id="myForm" name="myForm" action="<?php echo e(route('recruitment.applicant_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            
                            <input type="hidden" id="id_status" name="id_status" value="4">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <input type="hidden" id="process" name="process" value="<?php echo e($process); ?>">
                            <h5>Revisi Psikotes</h5>
                            <h6>Sebelum</h6>
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Check_in</label>
                                    <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($trx->check_in) ->format('d-m-Y H:i:s')); ?>" disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Check_out</label>
                                    <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($trx->check_out) ->format('d-m-Y H:i:s')); ?>" disabled>
                                </div>
                            </div>
                            <h6>Sesudah</h6>
                            <div class="row mb-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" disabled>
                                </div>
                            </div>
                            <h6>Jenis</h6>
                            <div class="row mb-2">
                                <div class="col-12 col-md-12">
                                    <input type="checkbox" class="form-check-input" id="jenis[]" name="jenis[]" value="1">
                                    <label class="form-check-label">EPPS</label>
                                    <input type="checkbox" class="form-check-input" id="jenis[]" name="jenis[]" value="2">
                                    <label class="form-check-label">PAPI Kostick</label>
                                    <input type="checkbox" class="form-check-input" id="jenis[]" name="jenis[]" value="3">
                                    <label class="form-check-label">DISC</label>
                                    <input type="checkbox" class="form-check-input" id="jenis[]" name="jenis[]" value="4">
                                    <label class="form-check-label">BAUM</label>
                                    <input type="checkbox" class="form-check-input" id="jenis[]" name="jenis[]" value="5">
                                    <label class="form-check-label">DAP</label>
                                </div>
                                <div id="checkboxError" class="text-danger" style="display: none;">Please checkbox this field</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <?php if(!empty($process) && $process=='histori_revisi'): ?>
                    <?php
                        $n_log = App\Models\TrxRecruitmentPsychotestLog::selectRaw('COUNT(*) as total')->where(['id_identitas'=>$trx->id])->groupBy('urutan')->get()->count();
                    ?>
                        <h5>Histori Revisi Psikotes</h5>
                        <div class="row mb-2">
                            <div class="col-12 col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th width=15%>Histori ke</th>
                                        <th width=85%>Hasil</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i = 1; $i <= $n_log; $i++): ?>
                                    <tr>
                                        <td><?php echo e($i); ?></td>
                                        <?php
                                            $where = ['urutan' => $i, 'id_identitas' => $trx->id, 'jenis' => 1];
                                            $jenis1 = App\Models\TrxRecruitmentPsychotestLog::where($where)->where('nomor', '>', 0)->get();
                                            $where = ['urutan' => $i, 'id_identitas' => $trx->id, 'jenis' => 2];
                                            $jenis2 = App\Models\TrxRecruitmentPsychotestLog::where($where)->where('nomor', '>', 0)->get();
                                            $where = ['urutan' => $i, 'id_identitas' => $trx->id, 'jenis' => 3];
                                            $jenis3 = App\Models\TrxRecruitmentPsychotestLog::where($where)->where('nomor', '>', 0)->get();
                                            $where = ['urutan' => $i, 'id_identitas' => $trx->id, 'jenis' => 4];
                                            $pohon = App\Models\TrxRecruitmentPsychotestLog::where($where)->where('nomor', '>', 0)->get();
                                            $where = ['urutan' => $i, 'id_identitas' => $trx->id, 'jenis' => 5];
                                            $manusia = App\Models\TrxRecruitmentPsychotestLog::where($where)->where('nomor', '>', 0)->get();
                                        ?>
                                        <td>
                                            <?php if(!$jenis1->isEmpty()): ?><a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$trx->id,1,$i])])); ?>" target="_blank" data-toggle="tooltip" title="EPPS"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                            <?php if(!$jenis2->isEmpty()): ?>&nbsp;<a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$trx->id,2,$i])])); ?>" target="_blank" data-toggle="tooltip" title="PAPI Kostick"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                            <?php if(!$jenis3->isEmpty()): ?>&nbsp;<a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$trx->id,3,$i])])); ?>" target="_blank" data-toggle="tooltip" title="DISC"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                            <?php if(!empty($pohon[0]->jawaban1)): ?>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$pohon[0]->jawaban1)); ?>" target="_blank" data-toggle="tooltip" title="BAUM"><i class="fa fa-file-image-o"></i></a><?php endif; ?>
                                            <?php if(!empty($manusia[0]->jawaban1)): ?>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$manusia[0]->jawaban1)); ?>" target="_blank" data-toggle="tooltip" title="DAP"><i class="fa fa-file-image-o"></i></a><?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div id="row_6">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="6">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            
                                            <?php $__currentLoopData = $karyawan_user_hco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_8">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="8">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            
                                            <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_10">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="10">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            
                                            <?php $__currentLoopData = $karyawan_hco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <?php if(!empty($process) && in_array($process, ['interview_user_hco_reschedule','interview_hco_reschedule','interview_user_reschedule'])): ?>
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_interview" name="id_interview" value="<?php echo e(Crypt::encrypt($interview->id)); ?>">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <input type="hidden" id="process" name="process" value=<?php echo e($process); ?>>
                            <?php if($process=='interview_user_hco_reschedule'): ?>
                                <h5>Interview User and <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?> Reschedule</h5>
                                
                                <input type="hidden" id="id_status" name="id_status" value="6">
                            <?php elseif($process=='interview_user_reschedule'): ?>
                                <h5>Interview User Reschedule</h5>
                                
                                <input type="hidden" id="id_status" name="id_status" value="8">
                            <?php elseif($process=='interview_hco_reschedule'): ?>
                                <h5>Interview <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?> Reschedule</h5>
                                
                                <input type="hidden" id="id_status" name="id_status" value="10">
                            <?php endif; ?>
                            <h6>Before</h6>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" disabled>
                                            <?php $__currentLoopData = $karyawan_user_hco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->nik); ?>" <?php if(in_array($data->nik, explode(';', $interview->interviewer))): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <input class="form-control" value="<?php echo e($interview->metode); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($interview->check_in) ->format('d-m-Y H:i:s')); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" value="<?php echo e(\Carbon\Carbon::parse($interview->check_out) ->format('d-m-Y H:i:s')); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <h6>After</h6>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="interviewer">Pewawancara</label>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="">
                                            
                                            <?php if($process=='interview_user_hco_reschedule'): ?>
                                                <?php $__currentLoopData = $karyawan_user_hco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php elseif($process=='interview_user_reschedule'): ?>
                                                <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php elseif($process=='interview_hco_reschedule'): ?>
                                                <?php $__currentLoopData = $karyawan_hco; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="metode">Metode</label>
                                        <select class="form-select col-sm-12" id="metode" name="metode" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_in">Check_in</label>
                                        <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="check_out">Check_out</label>
                                        <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <div id="row_16">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="16">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <?php if(!empty($resume)): ?>
                            <h6 class="text-start">Rekomendasi dan Kesimpulan</h6>
                            <div class="row">
                                
                                <?php $x=0 ?>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                                        <select class="form-select col-sm-12" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="1">Disarankan</option>
                                            <option value="0">Tidak Disarankan</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <?php $x=3 ?>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                                        <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <h6 class="text-start">Uraian Perilaku</h6>
                            <div class="row">
                                <?php $x=1 ?>
                                
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                                        <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""></textarea>
                                    </div>
                                </div>
                                <?php $x=2 ?>
                                
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="jawaban_<?php echo e($resume[$x]->id); ?>"><?php echo e($resume[$x]->pernyataan1); ?></label>
                                        <textarea class="form-control" id="jawaban_<?php echo e($resume[$x]->id); ?>" name="jawaban_<?php echo e($resume[$x]->id); ?>" rows="4" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <?php $x=4 ?>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-start">
                                            <tr>
                                                <th>Core Competence</th>
                                                <th>Standard Competence</th>
                                                <th>Poin (10-100)</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($x = 4; $x < 14; $x++): ?>
                                            <tr>
                                                <td><?php if($resume[$x]->urutan == 1): ?><?php echo e($resume[$x]->pernyataan1); ?><?php endif; ?></td>
                                                <td><?php echo e($resume[$x]->pernyataan2); ?></td>
                                                <td><input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="jawaban_<?php echo e($resume[$x]->id); ?>" id="jawaban_<?php echo e($resume[$x]->id); ?>" placeholder="Poin" required=""></td>
                                                <td><input class="form-control" name="keterangan_<?php echo e($resume[$x]->id); ?>" id="keterangan_<?php echo e($resume[$x]->id); ?>" type="text" placeholder="Keterangan"></td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <button class="btn btn-primary" type="submit">Submit</button>
                            
                            <?php endif; ?>
                        </form>
                    </div>
                    
                    <div id="row_12">
                        <form action="<?php echo e(route('recruitment.negotiation_send')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="12">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <?php
                                $pengaju = json_decode($trxM->pengaju_property, true);
                                $atasan = json_decode($trxM->atasan_property, true);
                                $kebutuhan_atribute = json_decode($trx->kebutuhan_atribute, true);
                                $kebutuhan_atribute_atasan_property = json_decode($kebutuhan_atribute['atasan_property'], true);
                                ?>
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">ORGANISASI</h6>
                                </div>
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
                                <div class="col-12 col-md-12 mb-2">
                                    <label class="form-label" for="atasan">Atasan Langsung Kandidat</label>
                                    
                                    <input class="form-control" name="atasan" id="atasan" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['nama']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['nik']); ?>">
                                </div>
                                <div class="col-12 col-md-12 mb-2">
                                    <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                                    
                                    <input class="form-control" name="divisi" id="divisi" type="text" readonly="" value="<?php echo e($kebutuhan_atribute_atasan_property['divisi']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['dep']); ?> / <?php echo e($kebutuhan_atribute_atasan_property['sub_dep']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">BENEFIT</h6>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="thp">Take Home Pay (THP)<span class="text-danger">*</span></label>
                                        <input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="thp" id="thp" placeholder="Take Home Pay (THP)" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 mb-2">
                                    <label class="form-label" for="benefit_utama">Benefit<span class="text-danger">*</span></label><br/>
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit_utama[]" name="benefit_utama[]" required="">
                                        <?php $__currentLoopData = $benefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->benefit); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mb-2">
                                    <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                    
                                    <textarea class="form-control" rows="5" name="benefit_lainnya" id="benefit_lainnya"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">KEPEGAWAIAN</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_bergabung" id="tgl_bergabung" placeholder="Tanggal Mulai Bergabung" required="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_catat_pegawai" name="id_catat_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $catat_pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->pencatatan); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_status_pegawai">Status Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_status_pegawai" name="id_status_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $status_pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->status); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="masa_kerja" id="masa_kerja" placeholder="Masa Perjanjian Kerja (bulan)" required="">
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_13">
                        
                        
                        <?php if(!empty($nego) || in_array($trx->_kebutuhan->id_status_kerja, [7, 8])): ?>
                        <form action="<?php echo e(route('recruitment.negotiation_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <?php if(!empty($nego)): ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($nego->id)); ?>">
                            <?php endif; ?>
                            <input type="hidden" id="id_identitas" name="id_identitas" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="13">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_dokumen_ttd">Pedoman Perilaku Alita</label>
                                        <select class="form-select col-sm-12" id="file_dokumen_ttd" name="file_dokumen_ttd" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="generik.pdf">Pedoman Perilaku Alita (2026)</option>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <div id="row_14">
                        <?php if(!empty($nego)): ?>
                        <form action="<?php echo e(route('recruitment.negotiation_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($nego->id)); ?>">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="<?php echo e(Crypt::encrypt($nego->id_identitas)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="14">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <div id="row_21">
                        <?php if(!empty($nego)): ?>
                        
                        <form action="<?php echo e(route('recruitment.negotiation_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($nego->id)); ?>">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="<?php echo e(Crypt::encrypt($nego->id_identitas)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="21">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">BENEFIT</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="thp">Take Home Pay (THP)<span class="text-danger">*</span></label>
                                        <input min=1 type="number" oninput="validity.valid||(value='')" onfocus="use_number(this)" onblur="use_text(this)" class="form-control" name="thp" id="thp" placeholder="Take Home Pay (THP)" required="" value="<?php echo e($nego->thp); ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                        
                                        <textarea class="form-control" rows="5" name="benefit_lainnya" id="benefit_lainnya"><?php echo e($nego->benefit_lainnya); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="benefit_utama">Benefit<span class="text-danger">*</span></label><br/>
                                        <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit_utama[]" name="benefit_utama[]" required="">
                                            <?php $__currentLoopData = $benefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, explode(',', $nego->benefit_utama))): ?> selected <?php endif; ?>><?php echo e($data->benefit); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <h6 class="text-center">KEPEGAWAIAN</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_bergabung" id="tgl_bergabung" placeholder="Tanggal Mulai Bergabung" required="" value="<?php echo e($nego->tgl_bergabung); ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_catat_pegawai" name="id_catat_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $catat_pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>" <?php if($data->id == $nego->id_catat_pegawai): ?> selected <?php endif; ?>><?php echo e($data->pencatatan); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_status_pegawai">Status Kepegawaian<span class="text-danger">*</span></label>
                                        <select class="form-select col-sm-12" id="id_status_pegawai" name="id_status_pegawai" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <?php $__currentLoopData = $status_pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>" <?php if($data->id == $nego->id_status_pegawai): ?> selected <?php endif; ?>><?php echo e($data->status); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="masa_kerja" id="masa_kerja" placeholder="Masa Perjanjian Kerja (bulan)" required="" value="<?php echo e($nego->masa_kerja); ?>">
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2" />
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <div id="row_15">
                        <?php if(!empty($nego)): ?>
                        <form action="<?php echo e(route('recruitment.negotiation_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($nego->id)); ?>">
                            <input type="hidden" id="id_identitas" name="id_identitas" value="<?php echo e(Crypt::encrypt($nego->id_identitas)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="15">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <div id="row_22">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="22">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_23">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="23">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_24">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="24">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_join">Status</label>
                                        <select class="js-example-basic-multiple col-sm-12" id="id_join[]" name="id_join[]" required="" multiple="multiple">
                                        
                                            
                                            <?php $__currentLoopData = $status_join; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="row_25">
                        <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                            <input type="hidden" id="id_status" name="id_status" value="25">
                            <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="id_join">Status</label>
                                        <select class="js-example-basic-multiple col-sm-12" id="id_join[]" name="id_join[]" required="" multiple="multiple">
                                        
                                            
                                            <?php $__currentLoopData = $status_not_join; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->nama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="catatan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="lampiran">Lampiran <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                        <input name="lampiran" id="lampiran" class="form-control" type="file">
                                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 notification">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="d-flex">
                            <?php if($loop->first): ?>
                            <div class="activity-dot-success"></div>
                            <?php elseif($loop->last): ?>
                            <div class="activity-dot-warning"></div>
                            <?php else: ?>
                            <div class="activity-dot-primary"></div>
                            <?php endif; ?>
                            <div class="ms-3">
                                <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span></h6>
                                <p class="f-light"><?php echo e($_history->pic); ?>

                                    <?php
                                        $catatan = json_decode($_history->catatan, true);
                                    ?>
                                    
                                    <?php if($_history->id_status == 4): ?>
                                        <?php if(!empty($catatan)): ?>
                                            <?php if(empty($catatan['psikotest'])): ?>
                                                <?php if($catatan['no']==1): ?>
                                                    <span class="f-light"><br/>Send (<?php echo e(\Carbon\Carbon::parse($catatan['check_in']) ->format('d-m-Y H:i:s')); ?> s/d <?php echo e(\Carbon\Carbon::parse($catatan['check_out']) ->format('d-m-Y H:i:s')); ?>)</span>
                                                <?php else: ?>
                                                    <span class="f-light"><br/>Reschedule (<?php echo e(\Carbon\Carbon::parse($catatan['check_in']) ->format('d-m-Y H:i:s')); ?> s/d <?php echo e(\Carbon\Carbon::parse($catatan['check_out']) ->format('d-m-Y H:i:s')); ?>)</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="f-light"><br/><?php echo e($catatan['psikotest']); ?> (<?php echo e(\Carbon\Carbon::parse($catatan['check_in']) ->format('d-m-Y H:i:s')); ?> s/d <?php echo e(\Carbon\Carbon::parse($catatan['check_out']) ->format('d-m-Y H:i:s')); ?>)</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    
                                    
                                    
                                    <?php elseif(in_array($_history->id_status, [6,8,10])): ?>
                                        <?php if(!empty($catatan)): ?>
                                            <?php
                                                if(!empty($catatan['interviewer'])){
                                                    $arr = App\Models\Karyawan::select('nik', 'nama')->whereIn('nik', explode(';', $catatan['interviewer']))->get();
                                                    $interviewer = array();
                                                    foreach($arr as $_arr){
                                                        array_push($interviewer, $_arr->nama.' / '.$_arr->nik);
                                                    }
                                                    $interviewer = implode(', ', $interviewer);
                                                }
                                            ?>
                                            <?php if($catatan['no']==1): ?>
                                            <span class="f-light"><br/>Send (Metode: <?php echo e($catatan['metode']); ?>, Pewawancara: <?php echo e($interviewer); ?>, Waktu: <?php echo e(\Carbon\Carbon::parse($catatan['check_in']) ->format('d-m-Y H:i:s')); ?> s/d <?php echo e(\Carbon\Carbon::parse($catatan['check_out']) ->format('d-m-Y H:i:s')); ?>)</span>
                                            <?php else: ?>
                                            <span class="f-light"><br/>Reschedule (Metode: <?php echo e($catatan['metode']); ?>, Pewawancara: <?php echo e($interviewer); ?>, Waktu: <?php echo e(\Carbon\Carbon::parse($catatan['check_in']) ->format('d-m-Y H:i:s')); ?> s/d <?php echo e(\Carbon\Carbon::parse($catatan['check_out']) ->format('d-m-Y H:i:s')); ?>)</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
$('#id_area').on('change', function() {
    const id_propinsi = $(this).val();
    // Reset dan loading
    $('#id_kota').empty().append('<option selected disabled value="">Pilih...</option>');
    $('#id_kota').trigger('change'); // reset select2
    $.ajax({
        url    : `<?php echo e(url('/get_propinsi_kota')); ?>/${id_propinsi}`,
        method : 'GET',
        success: function(data) {
            $('#id_kota').empty().append('<option selected disabled value="">Pilih...</option>');
            $.each(data, function(index, item) {
                $('#id_kota').append(`<option value="${item.id}">${item.kota}</option>`);
            });
            $('#id_kota').trigger('change'); // refresh select2
        },
        error: function() {
            $('#id_kota').empty().append('<option selected disabled value="">Pilih...</option>');
        }
    });
});
// // Helper function load dropdown via AJAX
function loadDropdown(url, targetId, selectedId = null) {
    $(targetId).empty().append('<option selected disabled value="">Pilih...</option>');
    $(targetId).trigger('change');
    $.ajax({
        url    : url,
        method : 'GET',
        success: function(data) {
            // Filter hanya is_active=1
            const filtered = data.filter(item => item.is_active == 1);
            $(targetId).empty().append('<option selected disabled value="">Pilih...</option>');
            $.each(filtered, function(index, item) {
                const selected = item.id == selectedId ? 'selected' : '';
                $(targetId).append(`<option value="${item.id}" ${selected}>${item.nama}</option>`);
            });
            $(targetId).trigger('change');
        },
        error: function() {
            $(targetId).empty().append('<option selected disabled value="">Gagal memuat data</option>');
        }
    });
}
// Perusahaan → Divisi
$('#id_perusahaan_kandidat').on('change', function() {
    const id = $(this).val();
    // Reset dropdown bawahnya
    $('#id_divisi_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    $('#id_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    $('#id_sub_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    if (id) loadDropdown(`<?php echo e(url('/getDivisi')); ?>/${id}`, '#id_divisi_kandidat');
});
// Divisi → Departemen
$('#id_divisi_kandidat').on('change', function() {
    const id = $(this).val();
    // Reset dropdown bawahnya
    $('#id_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    $('#id_sub_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    if (id) loadDropdown(`<?php echo e(url('/getDepartemen')); ?>/${id}`, '#id_dep_kandidat');
});
// Departemen → Sub Departemen
$('#id_dep_kandidat').on('change', function() {
    const id = $(this).val();
    // Reset dropdown bawahnya
    $('#id_sub_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>').trigger('change');
    if (id) loadDropdown(`<?php echo e(url('/getSubDepartemen')); ?>/${id}`, '#id_sub_dep_kandidat');
});
// 
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
// 
$(document).ready(function () {
    const id_perusahaan = '<?php echo e($kebutuhan_atribute["id_perusahaan_kandidat"] ?? ""); ?>';
    const id_divisi     = '<?php echo e($kebutuhan_atribute["id_divisi_kandidat"] ?? ""); ?>';
    const id_dep        = '<?php echo e($kebutuhan_atribute["id_dep_kandidat"] ?? ""); ?>';
    const id_sub_dep    = '<?php echo e($kebutuhan_atribute["id_sub_dep_kandidat"] ?? ""); ?>';

    if (id_perusahaan) {
        $.ajax({
            url    : `<?php echo e(url('/getDivisi')); ?>/${id_perusahaan}`,
            method : 'GET',
            success: function(data) {
                // Filter is_active=1
                const filtered = data.filter(item => item.is_active == 1);

                $('#id_divisi_kandidat').empty().append('<option selected disabled value="">Pilih...</option>');
                $.each(filtered, function(index, item) {
                    const selected = item.id == id_divisi ? 'selected' : '';
                    $('#id_divisi_kandidat').append(`<option value="${item.id}" ${selected}>${item.nama}</option>`);
                });
                $('#id_divisi_kandidat').trigger('change');

                if (id_divisi) {
                    $.ajax({
                        url    : `<?php echo e(url('/getDepartemen')); ?>/${id_divisi}`,
                        method : 'GET',
                        success: function(data) {
                            // Filter is_active=1
                            const filtered = data.filter(item => item.is_active == 1);

                            $('#id_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>');
                            $.each(filtered, function(index, item) {
                                const selected = item.id == id_dep ? 'selected' : '';
                                $('#id_dep_kandidat').append(`<option value="${item.id}" ${selected}>${item.nama}</option>`);
                            });
                            $('#id_dep_kandidat').trigger('change');

                            if (id_dep) {
                                $.ajax({
                                    url    : `<?php echo e(url('/getSubDepartemen')); ?>/${id_dep}`,
                                    method : 'GET',
                                    success: function(data) {
                                        // Filter is_active=1
                                        const filtered = data.filter(item => item.is_active == 1);

                                        $('#id_sub_dep_kandidat').empty().append('<option selected disabled value="">Pilih...</option>');
                                        $.each(filtered, function(index, item) {
                                            const selected = item.id == id_sub_dep ? 'selected' : '';
                                            $('#id_sub_dep_kandidat').append(`<option value="${item.id}" ${selected}>${item.nama}</option>`);
                                        });
                                        $('#id_sub_dep_kandidat').trigger('change');
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }
    // 
    const id_area = $('#id_area').val();
    if (id_area) {
        $.ajax({
            url    : `<?php echo e(url('/get_propinsi_kota')); ?>/${id_area}`,
            method : 'GET',
            success: function(data) {
                $('#id_kota').empty().append('<option selected disabled value="">Pilih...</option>');
                $.each(data, function(index, item) {
                    const selected = item.id == <?php echo e($kebutuhan_atribute['id_kota'] ?? 0); ?> ? 'selected' : '';
                    $('#id_kota').append(`<option value="${item.id}" ${selected}>${item.kota}</option>`);
                });
                $('#id_kota').trigger('change'); // refresh select2
            }
        });
    }
    // 
    $('#myForm').on('submit', function (e) {
        if ($('input[name="jenis[]"]:checked').length === 0) {
            e.preventDefault();
            $('#checkboxError').show();
        } else {
            $('#checkboxError').hide();
        }
    });
    // 
    // 4=Psycotest Send
    // 6=Interview User and PC Send
    // 8=Interview User Send
    // 10=Interview PC Send
    // 16=Interpretation
    // 12=Negotiation Process
    // 13=Negotiation Deal by PC
    // 14=Negotiation No Deal by PC
    // 15=Recruitment Done
    // 21=Re-Negotiation Process
    // 22=Psychotest and Interview Pass
    // 23=Psychotest and Interview Not Pass
    // 24=Join
    // 25=Not Join
    $("#row_4").hide();
    $("#row_6").hide();
    $("#row_8").hide();
    $("#row_10").hide();
    $("#row_16").hide();
    $("#row_12").hide();
    $("#row_13").hide();
    $("#row_14").hide();
    $("#row_15").hide();
    $("#row_21").hide();
    $("#row_22").hide();
    $("#row_23").hide();
    $("#row_24").hide();
    $("#row_25").hide();
    // 
    $("#id_process").change(function () {
        $value = $("#id_process").val();
        // 4=Psychotest Send
        if ($value == 4) {
            $("#row_4").show();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 6=Interview User and PC Send
        else if ($value == 6) {
            $("#row_4").hide();
            $("#row_6").show();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 8=Interview User Send
        else if ($value == 8) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").show();
            $("#row_16").hide();
            $("#row_10").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 10=Interview PC Send
        else if ($value == 10) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").show();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 16=Interpretation
        else if ($value == 16) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").show();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 12=Negotiation Process
        else if ($value == 12) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").show();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 13=Negotiation Deal by PC
        else if ($value == 13) {
        // alert(1);
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").show();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 14=Negotiation No Deal by PC
        else if ($value == 14) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").show();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 21=Re-Negotiation Process
        else if ($value == 21) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").show();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 22=Psychotest and Interview Pass
        else if ($value == 22) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").show();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 23=Psychotest and Interview Not Pass
        else if ($value == 23) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").show();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // 24=Join
        else if ($value == 24) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").show();
            $("#row_25").hide();
        }
        // 25=Not Join
        else if ($value == 25) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").show();
        }
        // 15=Recruitment Done
        else if ($value == 15) {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").show();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
        // lainnya
        else {
            $("#row_4").hide();
            $("#row_6").hide();
            $("#row_8").hide();
            $("#row_10").hide();
            $("#row_16").hide();
            $("#row_12").hide();
            $("#row_13").hide();
            $("#row_14").hide();
            $("#row_15").hide();
            $("#row_21").hide();
            $("#row_22").hide();
            $("#row_23").hide();
            $("#row_24").hide();
            $("#row_25").hide();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/applicant_process.blade.php ENDPATH**/ ?>