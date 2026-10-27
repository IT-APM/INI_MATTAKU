<?php $__env->startSection('title', 'Formulir Penilaian Karyawan'); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Penilaian Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><img class="img-fluid img-100 rounded-circle" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="matta"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">
                                                        <?php echo e($penilaian->_tipe->tipe_penilaian); ?><br />
                                                        <?php echo e($penilaian->_status->status_penilaian); ?><br />
                                                        Status Perpanjang:
                                                        <?php echo e($penilaian->_perpanjang->informasi); ?> <?php if($penilaian->is_perpanjang == 1): ?> <?php echo e($penilaian->bulan); ?> Bulan <?php endif; ?>
                                                    </h6>
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilaian->nik_karyawan.'/'.$penilaian->nik_karyawan.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6><span class="f-light m-10"><?php echo e($karyawan['nama']); ?> / <?php echo e($karyawan['nik']); ?><br /><?php echo e($karyawan['jabatan']); ?><br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_1.'/'.$penilaian->nik_penilai_1.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 1</h6>
                                                    <span class="f-light m-10"><?php echo e($penilai1['nama']); ?> / <?php echo e($penilai1['nik']); ?><br /><?php echo e($penilai1['jabatan']); ?><br /><?php echo e($penilai1['divisi']); ?> / <?php echo e($penilai1['dep']); ?> / <?php echo e($penilai1['sub_dep']); ?>

                                                    <br />Kesimpulan dan Rekomendasi: <?php echo e($penilaian->kes_rek_1); ?><br/><?php echo e($txt_perpanjang1); ?></span>
                                                </td>
                                            </tr>
                                            <?php if(!empty($penilaian->nik_penilai_2)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_2.'/'.$penilaian->nik_penilai_2.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 2</h6>
                                                    <span class="f-light m-10"><?php echo e($penilai2['nama']); ?> / <?php echo e($penilai2['nik']); ?><br /><?php echo e($penilai2['jabatan']); ?><br /><?php echo e($penilai2['divisi']); ?> / <?php echo e($penilai2['dep']); ?> / <?php echo e($penilai2['sub_dep']); ?>

                                                    <br />Kesimpulan dan Rekomendasi: <?php echo e($penilaian->kes_rek_2); ?><br/><?php echo e($txt_perpanjang2); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!empty($penilaian->nik_penilai_3)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$penilaian->nik_penilai_3.'/'.$penilaian->nik_penilai_3.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Penilai 3</h6>
                                                    <span class="f-light m-10"><?php echo e($penilai3['nama']); ?> / <?php echo e($penilai3['nik']); ?><br /><?php echo e($penilai3['jabatan']); ?><br /><?php echo e($penilai3['divisi']); ?> / <?php echo e($penilai3['dep']); ?> / <?php echo e($penilai3['sub_dep']); ?>

                                                    <br />Kesimpulan dan Rekomendasi: <?php echo e($penilaian->kes_rek_3); ?><br/><?php echo e($txt_perpanjang3); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
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
                                    // tipe
                                    // 1=Penilaian 180
                                    // 4=Penilaian 180-Reguler
                                    // 5=Penilaian 180-Assesment
                                    // 2=Penilaian 360-Reguler
                                    // 3=Penilaian 360-Assesment
                                    // 6=Penilaian Outsourcing
                                    // status
                                    // 1=Send to Employee
                                    // 2=Done by Employee
                                    // 3=Done By Reviewer 1
                                    // 4=Done by P&C (180)
                                    // 6=Done By Reviewer 2
                                    // 7=Done By Reviewer 3
                                    // 8=Done by P&C (360)
                                    // untuk tipe=[1,4,5] maka status=[1,2,3]
                                    $disable=[1,2,3];
                                    // untuk tipe=[2,3] maka status=[1,2,3,6,7]
                                    if(in_array($penilaian->id_tipe, [2,3])){
                                        $disable=[1,2,3,6,7];
                                        if(empty($penilaian->nik_penilai_3)){
                                            $disable=[1,2,3,6];
                                        }
                                    }
                                    // untuk tipe=[6] maka status=[3,6,7]
                                    elseif(in_array($penilaian->id_tipe, [6])){
                                        $disable=[3,6,7];
                                        if(empty($penilaian->nik_penilai_3)){
                                            $disable=[3,6];
                                        }
                                    }
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    rsort($remains);
                                    // $nik_hco = App\Models\MtrUtility::find(37)->informasi;
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\MtrPenilaianStatus::find($remain)->status_penilaian); ?></h6>
                                        <?php if($remain == 7): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_3)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_3); ?></p>
                                        <?php elseif($remain == 6): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_2)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_2); ?></p>
                                        <?php elseif($remain == 3): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_penilai_1)->first()->nama); ?> / <?php echo e($penilaian->nik_penilai_1); ?></p>
                                        <?php elseif($remain == 2): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$penilaian->nik_karyawan)->first()->nama); ?> / <?php echo e($penilaian->nik_karyawan); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </li>
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
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                        <h6><?php echo e($_history->_status->status_penilaian); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/view.blade.php ENDPATH**/ ?>