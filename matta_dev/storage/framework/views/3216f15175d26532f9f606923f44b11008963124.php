<?php $__env->startSection('title', 'Formulir Rekomendasi Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Rekomendasi Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <!-- <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab"> -->
                            <!-- start -->
                            <div class="row">
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Data Karyawan</h5>
                                            </div>
                                            <div class="card-header">
                                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                    target="_blank"><img class="img-thumbnail" width="20%"
                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                        alt="user"></a>
                                            </div>
                                            <div class="card-body typography">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td width="30%">Nama</td>
                                                            <td width="70%"><?php echo e($karyawan['nama']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>NIP</td>
                                                            <td><?php echo e($karyawan['nik']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jabatan</td>
                                                            <td><?php echo e($karyawan['jabatan']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Direktorat / Divisi / Departemen</td>
                                                            <td><?php echo e($karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']); ?>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal Bergabung</td>
                                                            <td>
                                                                <?php if(!empty($karyawan['tanggal_bergabung'])): ?>
                                                                <?php echo e(Carbon\Carbon::parse($karyawan['tanggal_bergabung'])->format('d-m-Y')); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td><?php echo e($karyawan['status_karyawan']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Level</td>
                                                            <td><?php echo e($karyawan['level']); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-header">
                                                <h5>Rekomendasi dari atasan langsung</h5>
                                            </div>
                                            <div class="card-header">
                                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                    target="_blank"><img class="img-thumbnail" width="20%"
                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                        alt="user"></a>
                                            </div>
                                            <div class="card-body typography">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td width="30%">Nama</td>
                                                            <td width="70%"><?php echo e($atasan['nama']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>NIP</td>
                                                            <td><?php echo e($atasan['nik']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jabatan</td>
                                                            <td><?php echo e($atasan['jabatan']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Direktorat / Divisi / Departemen</td>
                                                            <td><?php echo e($atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep']); ?>

                                                            </td>
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
                                                    // 1=Recommended by L1
                                                    // 4=Approved by Approval-1
                                                    // 9=Approved by Approval-2
                                                    // 17=Approved by Approval-3
                                                    $disable=[1,4];
                                                    if(!empty($trxM->nik_reviewer2)){
                                                        $disable=[1,4,9];
                                                    }elseif(!empty($trxM->nik_reviewer3)){
                                                        $disable=[1,4,9,17];
                                                    }
                                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                                    rsort($remains);
                                                ?>
                                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="d-flex">
                                                    <div class="activity-dot-dark"></div>
                                                    <div class="ms-3">
                                                        <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\RekomendasiStatus::find($remain)->status_pengajuan); ?></h6>
                                                        <?php if($remain == 4): ?>
                                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trxM->nik_reviewer1)->first()->nama); ?> / <?php echo e($trxM->nik_reviewer1); ?></p>
                                                        <?php elseif($remain == 9): ?>
                                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trxM->nik_reviewer2)->first()->nama); ?> / <?php echo e($trxM->nik_reviewer2); ?></p>
                                                        <?php elseif($remain == 17): ?>
                                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trxM->nik_reviewer3)->first()->nama); ?> / <?php echo e($trxM->nik_reviewer3); ?></p>
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
                                                        <p class="d-flex justify-content-between mb-2"><span
                                                                class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                                        </p>
                                                        <h6><?php echo e($_history->_status->status_pengajuan); ?><span
                                                                class="dot-notification"></span></h6>
                                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                                            <?php echo e($_history->nik_pic); ?></p>
                                                    </div>
                                                </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <div class="card-header">
                                            <h5>Para Pihak</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="appointment-table table-responsive">
                                                <table class="table table-bordernone">
                                                    <tbody>
                                                        <!-- karyawan -->
                                                        <tr>
                                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Karyawan</h6><span
                                                                    class="f-light m-10"><?php echo e($karyawan['nama']); ?> / <?php echo e($karyawan['nik']); ?><br /><?php echo e($karyawan['jabatan']); ?><br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                            </td>
                                                        </tr>
                                                        <!-- atasan -->
                                                        <tr>
                                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Atasan</h6><span
                                                                    class="f-light m-10"><?php echo e($atasan['nama']); ?> / <?php echo e($atasan['nik']); ?><br /><?php echo e($atasan['jabatan']); ?>

                                                                    <br /><?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> / <?php echo e($atasan['sub_dep']); ?></span>
                                                            </td>
                                                        </tr>
                                                        <!-- reviewer1 -->
                                                        <tr>
                                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg')); ?>"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg')); ?>"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 1</h6><span
                                                                    class="f-light m-10"><?php echo e($reviewer1['nama']); ?> / <?php echo e($reviewer1['nik']); ?><br /><?php echo e($reviewer1['jabatan']); ?>

                                                                    <br /><?php echo e($reviewer1['divisi']); ?> / <?php echo e($reviewer1['dep']); ?> / <?php echo e($reviewer1['sub_dep']); ?></span>
                                                            </td>
                                                        </tr>
                                                        <!-- reviewer2 -->
                                                        <?php if(!empty($reviewer2['nik'])): ?>
                                                        <tr>
                                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg')); ?>"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg')); ?>"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 2</h6><span
                                                                    class="f-light m-10"><?php echo e($reviewer2['nama']); ?> / <?php echo e($reviewer2['nik']); ?><br /><?php echo e($reviewer2['jabatan']); ?>

                                                                    <br /><?php echo e($reviewer2['divisi']); ?> / <?php echo e($reviewer2['dep']); ?> / <?php echo e($reviewer2['sub_dep']); ?></span>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <!-- reviewer3 -->
                                                        <?php if(!empty($reviewer3['nik'])): ?>
                                                        <tr>
                                                            <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg')); ?>"
                                                                    target="_blank"><img class="img-thumbnail"
                                                                        src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg')); ?>"
                                                                        alt="user"></a></td>
                                                            <td class="img-content-box">
                                                                <h6 class="m-10">Reviewer 3</h6><span
                                                                    class="f-light m-10"><?php echo e($reviewer3['nama']); ?> / <?php echo e($reviewer3['nik']); ?><br /><?php echo e($reviewer3['jabatan']); ?>

                                                                    <br /><?php echo e($reviewer3['divisi']); ?> / <?php echo e($reviewer3['dep']); ?> / <?php echo e($reviewer3['sub_dep']); ?></span>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                            <!-- start -->
                            <div class="row">
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Mengajukan</h5>
                                            </div>
                                            <div class="card-body typography">
                                                <?php $no=0; ?>
                                                <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e(++$no); ?>. <?php echo e($value->jenis_pengajuan); ?> 
                                                    <?php if($value->jenis_pengajuan == 'Insentif'): ?>
                                                        <?php echo e($value->sebelum); ?> selama <?php echo e($value->setelah); ?> bulan <br/> 
                                                    <?php elseif($value->jenis_pengajuan == 'Nilai Prosentase Kenaikan Gaji'): ?>
                                                        <?php echo e($value->setelah); ?> %<br/> 
                                                    <?php else: ?>
                                                        dari <?php echo e($value->sebelum); ?> menjadi <?php echo e($value->setelah); ?> <br/> 
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-xxl-6 box-col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Tanggal</h5>
                                        </div>
                                        <div class="card-body typography">
                                            Terhitung mulai tanggal
                                            <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trxM->terhitung_tanggal)->format('d-m-Y')); ?>


                                        </div>
                                        <div class="card-header">
                                            <h5>Dasar pengajuan</h5>
                                        </div>
                                        <div class="card-body typography">
                                            <?php echo $trxM->dasar_pengajuan; ?>

                                        </div>
                                        <div class="card-header">
                                            <h5>Catatan pengajuan</h5>
                                        </div>
                                        <div class="card-body typography">
                                            <?php echo $trxM->catatan_pengajuan; ?>

                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                                    <div class="appointment">
                                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                                        <p>&nbsp;</p>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- end -->
                        <!-- </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/rekomendasi/view.blade.php ENDPATH**/ ?>