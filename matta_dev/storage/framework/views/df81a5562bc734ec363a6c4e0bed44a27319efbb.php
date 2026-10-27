<?php $__env->startSection('title', 'Formulir Izin'); ?>

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
<li class="breadcrumb-item active">Izin</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            
                            <div class="card-header">
                                <h5>Karyawan</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>" alt="user"></a>
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
                                            <td><?php echo e($karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Layer 1</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>" alt="user"></a>
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
                                            <td><?php echo e($atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php if(!empty($layer2)): ?>
                            <div class="card-header">
                                <h5>Layer 2</h5><br/>
                                <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>" alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%"><?php echo e($layer2['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td><?php echo e($layer2['nik']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td><?php echo e($layer2['jabatan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td><?php echo e($layer2['divisi'] . '/' . $layer2['dep'] . '/' . $layer2['sub_dep']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 notification box-col-4">
                    <div class="card height-equal">
                        <div class="card-header">
                            <h5>Data Izin</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Tipe Izin</td>
                                        <td width="60%"><?php echo e($trx->_tipe->tipe); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td><?php echo e(App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal)); ?></td>
                                    </tr>
                                    <?php if($trx->tipe == 1 || $trx->tipe == 2): ?>
                                    <tr>
                                        <td>Jam Mulai</td>
                                        <td><?php echo e($trx->jam_awal); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jam Selesai</td>
                                        <td><?php echo e($trx->jam_akhir); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Alasan</td>
                                        <td><?php echo $trx->alasan; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                <?php
                                    // status
                                    // 1=Submit by Requestor
                                    // 2=Approve by L1
                                    // 6=Approve by L2
                                    // 4=Done by P&C
                                    $disable=[1,2,6];
                                    if(empty($trx->layer2)){
                                        $disable=[1,2];
                                    }
                                    $remains = array_diff($disable, $history->pluck('id_status')->toArray());
                                    rsort($remains);
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <div class="activity-dot-dark"></div>
                                    <div class="ms-3">
                                        <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\IjinStatus::find($remain)->status); ?></h6>
                                        <?php if($remain == 2): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trx->atasan)->first()->nama); ?> / <?php echo e($trx->atasan); ?></p>
                                        <?php elseif($remain == 6): ?>
                                        <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$trx->layer2)->first()->nama); ?> / <?php echo e($trx->layer2); ?></p>
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
                                        <h6><?php echo e($_history->_status->status); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- finish -->
            </div>
            <?php
            $isPast = \Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal)->isPast();
            $isToday = \Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal)->isToday();
            // 3=Reject by L1
            // 7=Reject by L2
            // 8=Cancel by Requestor
            $isreject = App\Models\TrxIjinLog::where(['id_trx'=>$trx->id])->whereIn('id_status', [3,7,8])->exists();
            ?>
            <form class="mb-2" id="_form" name="_form" method="post" enctype="multipart/form-data" action="<?php echo e(route('izin.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id))); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                <?php echo method_field('PATCH'); ?>
                <?php echo csrf_field(); ?>
                
                <?php if($karyawan['nik'] == auth()->user()->nik && !($isPast || $isToday) && !$isreject): ?>
                <input type="hidden" id="nik" name="nik" value="<?php echo e(auth()->user()->nik); ?>">
                <input type="hidden" id="pic" name="pic" value="pengaju">
                <h5>Catatan</h5>
                <textarea class="form-control mb-2" id="keterangan" name="keterangan" rows="4" ></textarea>
                <?php endif; ?>
                <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a> <?php if($karyawan['nik'] == auth()->user()->nik && !($isPast || $isToday) && !$isreject): ?> | <button type="submit" id="status" name="status" value="8" class="btn btn-danger">Cancel</button><?php endif; ?>
            </form>
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/izin/view.blade.php ENDPATH**/ ?>