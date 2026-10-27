<?php $__env->startSection('title', 'Formulir Reimbursement'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Formulir Reimbursement</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5>Diajukan Oleh</h5>
                </div>
                <div class="card-header">
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%"><?php echo e($data['nama']); ?></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td><?php echo e($data['nik']); ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td><?php echo e($data['jabatan']); ?></td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td><?php echo e($data['unit_kerja']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5>Resume</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Pengajuan Ke</td>
                                <td width="60%"><?php echo e($data['ke']); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td><?php echo e($trxM->created_at); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun</td>
                                <td><?php echo e($trxM->periode); ?></td>
                            </tr>
                            <?php if($trxM->plafon>0): ?>
                            <tr>
                                <td>Plafon</td>
                                <td>Ada</td>
                            </tr>
                            <tr>
                                <td>Jumlah Plafon</td>
                                <td>Rp. <?php echo e(number_format($trxM->plafon, 2)); ?></td>
                            </tr>
                            <tr>
                                <td>Plafon Telah Diambil</td>
                                <td>Rp. <?php echo e(number_format($trxM->plafon_diambil, 2)); ?></td>
                            </tr>
                            <tr>
                                <td>Sisa Plafon</td>
                                <td>Rp. <?php echo e(number_format($trxM->sisa_plafon_1, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. <?php echo e(number_format($trxM->klaim_kesehatan, 2)); ?> (Kesehatan)
                                    <br />Rp. <?php echo e(number_format($trxM->klaim_non_kesehatan, 2)); ?> (Non Kesehatan)
                                </td>
                            </tr>
                            <?php if($trxM->plafon>0): ?>
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. <?php echo e(number_format($trxM->sisa_plafon_2, 2)); ?> (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    <?php $__currentLoopData = $lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(asset('assets/reimburse/'.$value->filename)); ?>"
                                        target="_blank"><?php echo e($value->_id_lampiran->lampiran); ?></a><br />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td><?php echo e($trxM->catatan); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  --><div class="col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body typography notification">
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
                                <h6><?php echo e($_history->_id_status->status); ?><span class="dot-notification"></span></h6>
                                <p class="f-light">
                                    Oleh <?php echo e($_history->_nik->nama); ?> / <?php echo e($_history->nik_pic); ?>

                                    <?php if(!empty($_history->keterangan)): ?><br/>Catatan <?php echo e($_history->keterangan); ?><?php endif; ?>
                                    <?php if(!empty($_history->filename)): ?><br/><a href="<?php echo e(asset('assets/reimburse/'.$_history->filename)); ?>" target="_blank">Lampiran</a><?php endif; ?>
                                </p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pasien</h5>
                </div>
                <div class="card-body typography">
                    <!-- start -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Nama Pasien</h6>
                                    </th>
                                    <th>
                                        <h6>Hubungan Dengan Karyawan</h6>
                                    </th>
                                    <th>
                                        <h6>Layanan</h6>
                                    </th>
                                    <th>
                                        <h6>Tanggal Layanan</h6>
                                    </th>
                                    <th>
                                        <h6>Nominal Klaim</h6>
                                    </th>
                                </tr>
                            </thead>
                            <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($value->nama_pasien); ?></td>
                                <td><?php echo e($value->_id_hubungan->hubungan); ?></td>
                                <td><?php echo e($value->_id_layanan->layanan); ?></td>
                                <td><?php echo e($value->tanggal); ?></td>
                                <td>Rp. <?php echo e(number_format($value->jumlah_klaim, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                    <!-- finish -->
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Catatan</h5>
                            <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <h5>Lampiran*</h5>
                            <label class="form-label">Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                            <input name="filename" id="filename" class="form-control" type="file">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if(in_array(request('id_status'), [1,6])): ?><button type="submit" name="status_pengajuan" value="2" class="btn btn-success">Approve</button><?php endif; ?>
                        <?php if(in_array(request('id_status'), [1,2])): ?><button type="submit" name="status_pengajuan" value="6" class="btn btn-primary">Hold</button><?php endif; ?>
                        <?php if(in_array(request('id_status'), [2])): ?><button type="submit" name="status_pengajuan" value="7" class="btn btn-success">Paid</button><?php endif; ?>
                        <?php if(in_array(request('id_status'), [1,6])): ?><button type="submit" name="status_pengajuan" value="3" class="btn btn-danger">Reject</button><?php endif; ?>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Reimbursement ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/reimburse.blade.php ENDPATH**/ ?>