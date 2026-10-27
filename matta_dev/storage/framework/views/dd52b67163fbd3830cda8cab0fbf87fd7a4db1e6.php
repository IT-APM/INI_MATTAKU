<?php $__env->startSection('title', 'Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>
    <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?> Cash Advance Perjalanan Dinas Dalam Negeri
    <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?> Cash Advance Perjalanan Dinas Luar Negeri
    <?php elseif($trxMaster->luar_dalam == 'reimburse'): ?> Reimbursement Perjalanan Dinas Dalam Negeri
    <?php elseif($trxMaster->luar_dalam == 'reimburse_luar'): ?> Reimbursement Perjalanan Dinas Luar Negeri
    <?php endif; ?>
</h3>
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
                                <h5>Para Pihak</h5>
                            </div>
                            <?php
                            $pengaju_property = json_decode($trxMaster->pengaju_property, true);
                            $atasan_property = json_decode($trxMaster->atasan_property, true);
                            $admin_property = json_decode($trxMaster->admin_property, true);
                            $koordinator_property = json_decode($trxMaster->koordinator_property, true);
                            $layer1_property = json_decode($trxMaster->layer1_property, true);
                            $layer2_property = json_decode($trxMaster->layer2_property, true);
                            $hco_property = json_decode($trxMaster->hco_property, true);
                            ?>
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->pengaju.'/'.$trxMaster->pengaju.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6>
                                                    <span class="f-light m-10"><?php echo e($pengaju_property['nama']); ?> / <?php echo e($pengaju_property['nik']); ?><br /><?php echo e($pengaju_property['jabatan']); ?><br /><?php echo e($pengaju_property['divisi']); ?> / <?php echo e($pengaju_property['dep']); ?> / <?php echo e($pengaju_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->atasan.'/'.$trxMaster->atasan.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan langsung karyawan</h6>
                                                    <span class="f-light m-10"><?php echo e($atasan_property['nama']); ?> / <?php echo e($atasan_property['nik']); ?><br /><?php echo e($atasan_property['jabatan']); ?><br /><?php echo e($atasan_property['divisi']); ?> / <?php echo e($atasan_property['dep']); ?> / <?php echo e($atasan_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php if(!empty($trxMaster->admin)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->admin.'/'.$trxMaster->admin.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Project Admin</h6>
                                                    <span class="f-light m-10"><?php echo e($admin_property['nama']); ?> / <?php echo e($admin_property['nik']); ?><br /><?php echo e($admin_property['jabatan']); ?><br /><?php echo e($admin_property['divisi']); ?> / <?php echo e($admin_property['dep']); ?> / <?php echo e($admin_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!empty($trxMaster->koordinator)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->koordinator.'/'.$trxMaster->koordinator.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Coordinator</h6>
                                                    <span class="f-light m-10"><?php echo e($koordinator_property['nama']); ?> / <?php echo e($koordinator_property['nik']); ?><br /><?php echo e($koordinator_property['jabatan']); ?><br /><?php echo e($koordinator_property['divisi']); ?> / <?php echo e($koordinator_property['dep']); ?> / <?php echo e($koordinator_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!empty($trxMaster->layer1)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->layer1.'/'.$trxMaster->layer1.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Layer 1</h6>
                                                    <span class="f-light m-10"><?php echo e($layer1_property['nama']); ?> / <?php echo e($layer1_property['nik']); ?><br /><?php echo e($layer1_property['jabatan']); ?><br /><?php echo e($layer1_property['divisi']); ?> / <?php echo e($layer1_property['dep']); ?> / <?php echo e($layer1_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!empty($trxMaster->layer2)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->layer2.'/'.$trxMaster->layer2.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Layer 2</h6>
                                                    <span class="f-light m-10"><?php echo e($layer2_property['nama']); ?> / <?php echo e($layer2_property['nik']); ?><br /><?php echo e($layer2_property['jabatan']); ?><br /><?php echo e($layer2_property['divisi']); ?> / <?php echo e($layer2_property['dep']); ?> / <?php echo e($layer2_property['sub_dep']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!empty($trxMaster->hco)): ?>
                                            <tr>
                                                <td><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$trxMaster->hco.'/'.$trxMaster->hco.'.jpg')); ?>" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10"><?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></h6>
                                                    <span class="f-light m-10"><?php echo e($hco_property['nama']); ?> / <?php echo e($hco_property['nik']); ?><br /><?php echo e($hco_property['jabatan']); ?><br /><?php echo e($hco_property['divisi']); ?> / <?php echo e($hco_property['dep']); ?> / <?php echo e($hco_property['sub_dep']); ?></span>
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
                                    // 2=Pengajuan CA
                                    // 3=CA Diproses Admin
                                    // 4=CA Disetujui Admin
                                    // 7=CA Disetujui PC
                                    // 9=CA Disetujui L1
                                    // 11=CA Disetujui L2
                                    // 13=CA Disetujui P&C

                                    // 18=Realisasi CA
                                    // 19=Realisasi Disetujui Admin
                                    // 22=Realisasi Disetujui PC
                                    // 24=Realisasi Disetujui L1
                                    // 26=Realisasi Disetujui L2
                                    // 28=Realisasi Disetujui P&C

                                    // 33=Pengajuan Reimburse
                                    // 34=Reimburse Diproses Admin
                                    // 35=Reimburse Disetujui Admin
                                    // 38=Reimburse Disetujui PC
                                    // 40=Reimburse Disetujui L1
                                    // 42=Reimburse Disetujui L2
                                    // 44=Reimburse Disetujui P&C
                                    
                                    // default Pengajuan Reimburse
                                    // is_ca=0
                                    $remains = [];
                                    if(!empty($trxMaster->hco)){
                                        array_push($remains, [44=>$trxMaster->hco]);
                                    }
                                    if(!empty($trxMaster->layer2)){
                                        array_push($remains, [42=>$trxMaster->layer2]);
                                    }
                                    if(!empty($trxMaster->layer1)){
                                        array_push($remains, [40=>$trxMaster->layer1]);
                                    }
                                    if(!empty($trxMaster->koordinator)){
                                        array_push($remains, [38=>$trxMaster->koordinator]);
                                    }
                                    if(!empty($trxMaster->admin)){
                                        array_push($remains, [35=>$trxMaster->admin]);
                                    }
                                    if(!empty($trxMaster->admin)){
                                        array_push($remains, [34=>$trxMaster->admin]);
                                    }
                                    if(!empty($trxMaster->pengaju)){
                                        array_push($remains, [33=>$trxMaster->pengaju]);
                                    }
                                    // CA is_ca=1
                                    if($trxMaster->is_ca){
                                        // Realisasi
                                        $remains = [];
                                        if(!empty($trxMaster->hco)){
                                            array_push($remains, [28=>$trxMaster->hco]);
                                        }
                                        if(!empty($trxMaster->layer2)){
                                            array_push($remains, [26=>$trxMaster->layer2]);
                                        }
                                        if(!empty($trxMaster->layer1)){
                                            array_push($remains, [24=>$trxMaster->layer1]);
                                        }
                                        if(!empty($trxMaster->koordinator)){
                                            array_push($remains, [22=>$trxMaster->koordinator]);
                                        }
                                        if(!empty($trxMaster->admin)){
                                            array_push($remains, [19=>$trxMaster->admin]);
                                        }
                                        if(!empty($trxMaster->pengaju)){
                                            array_push($remains, [18=>$trxMaster->pengaju]);
                                        }
                                        // Pengajuan CA
                                        if(!empty($trxMaster->hco)){
                                            array_push($remains, [13=>$trxMaster->hco]);
                                        }
                                        if(!empty($trxMaster->layer2)){
                                            array_push($remains, [11=>$trxMaster->layer2]);
                                        }
                                        if(!empty($trxMaster->layer1)){
                                            array_push($remains, [9=>$trxMaster->layer1]);
                                        }
                                        if(!empty($trxMaster->koordinator)){
                                            array_push($remains, [7=>$trxMaster->koordinator]);
                                        }
                                        if(!empty($trxMaster->admin)){
                                            array_push($remains, [4=>$trxMaster->admin]);
                                        }
                                        if(!empty($trxMaster->admin)){
                                            array_push($remains, [3=>$trxMaster->admin]);
                                        }
                                        if(!empty($trxMaster->pengaju)){
                                            array_push($remains, [2=>$trxMaster->pengaju]);
                                        }
                                    }
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $remain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty()): ?>
                                    <li class="d-flex">
                                        <div class="activity-dot-dark"></div>
                                        <div class="ms-3">
                                            <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\BpdStatus::find($key)->status); ?></h6>
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
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                        <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh: <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                                        Catatan: <?php echo e($_history->keterangan); ?>

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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/history.blade.php ENDPATH**/ ?>