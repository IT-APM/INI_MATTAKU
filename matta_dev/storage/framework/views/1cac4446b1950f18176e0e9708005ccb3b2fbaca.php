<?php $__env->startSection('title', 'Formulir Permohonan Izin Sakit'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Izin Sakit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Tahun Izin Sakit</td>
                                            <td><?php echo e($trxSakit->periode_sakit); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mengajukan izin sakit pada</td>
                                            <td><?php echo e($arrTanggal['tanggal_mulai']); ?><br />sampai dengan<br /><?php echo e($arrTanggal['tanggal_akhir']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal izin sakit</td>
                                            <td>
                                                <?php $__currentLoopData = $arrTanggal['arrTanggalSakit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($value); ?><br />
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Masuk bekerja kembali</td>
                                            <td><?php echo e($arrTanggal['tanggal_masuk']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alasan pengajuan izin sakit</td>
                                            <td><?php echo $trxSakit->alasan; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td><?php echo $trxSakit->alamat; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nomor telepon</td>
                                            <td><?php echo e($trxSakit->telepon); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Lampiran</td>
                                            <td><a href="<?php echo e(asset('assets/sakit/'.$trxSakit->lampiran)); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 notification box-col-6">
                    <div class="card height-equal">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                <?php
                                    // status
                                    // 1=Pengajuan oleh Karyawan
                                    // 2=Disetujui oleh PIC Pengganti
                                    // 4=Disetujui oleh Layer 1
                                    // 6=Selesai
                                    $remains = [];
                                    array_push($remains, [4=>$trxSakit->nik_atasan]);
                                    foreach(explode(';', $trxSakit->pic_pengganti) as $value){
                                        array_push($remains, [2=>$value]);
                                    }
                                    array_push($remains, [1=>$trxSakit->nik_karyawan]);
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $remain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty()): ?>
                                    <li class="d-flex">
                                        <div class="activity-dot-dark"></div>
                                        <div class="ms-3">
                                            <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\SakitStatus::find($key)->status_sakit); ?></h6>
                                            <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$value)->first()->nama); ?> / <?php echo e($value); ?></p>
                                        </div>
                                    </li>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
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
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                        </p>
                                        <h6><?php echo e($_history->_status->status_sakit); ?><span class="dot-notification"></span>
                                        </h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                            <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxSakit->nik_karyawan.'/'.$trxSakit->nik_karyawan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxSakit->nik_karyawan.'/'.$trxSakit->nik_karyawan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span
                                                        class="f-light m-10"><?php echo e($karyawan['nama']); ?> / <?php echo e($karyawan['nik']); ?><br /><?php echo e($karyawan['jabatan']); ?>

                                                        <br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$trxSakit->nik_atasan.'/'.$trxSakit->nik_atasan.'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxSakit->nik_atasan.'/'.$trxSakit->nik_atasan.'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan</h6><span
                                                        class="f-light m-10"><?php echo e($atasan['nama']); ?> / <?php echo e($atasan['nik']); ?><br /><?php echo e($atasan['jabatan']); ?>

                                                        <br /><?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> / <?php echo e($atasan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php $__currentLoopData = $arrPicProperty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                        target="_blank"><img class="img-thumbnail"
                                                            src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                                            alt="user"></a></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">PIC Pengganti</h6><span
                                                        class="f-light m-10"><?php echo e($value['nama']); ?> / <?php echo e($value['nik']); ?><br /><?php echo e($value['jabatan']); ?>

                                                        <br /><?php echo e($value['divisi']); ?> / <?php echo e($value['dep']); ?> / <?php echo e($value['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/sakit/view.blade.php ENDPATH**/ ?>