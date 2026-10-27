
<?php $__env->startSection('title', 'Attendance Clock-in Clock-out'); ?>

<?php $__env->startSection('css'); ?>
<!--  -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Attendance Clock-in Clock-out</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Clock-in Clock-out</li>
<li class="breadcrumb-item active">Attendance</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- <div class="card-body text-center my-auto"> -->
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Server datetime</td>
                                <td width="60%"><?php echo e(date('d-m-Y', strtotime($arr[3]['tgl']))); ?> <?php echo e($arr[3]['jam']); ?></td>
                            </tr>
                            <tr>
                                <td>Type/WG</td>
                                <td><?php echo e($arr[0]['is_reguler']); ?>/<?php echo e($arr[0]['wg_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Working hour</td>
                                <td><?php echo e($arr[0]['clockin']); ?> sd <?php echo e($arr[0]['clockout']); ?></td>
                            </tr>
                            <tr>
                                <td>Nama/NIP</td>
                                <td><?php echo e($karyawan->nama); ?>/<?php echo e($karyawan->nik); ?></td>
                            </tr>
                            <tr>
                                <td>Perusahaan / Direktorat / Divisi / Departemen</td>
                                <td><?php echo e($karyawan->_perusahaan->nama); ?> / <?php echo e($karyawan->_divisi->nama); ?> / <?php echo e($karyawan->_departemen->nama); ?> / <?php echo e($karyawan->_subdepartemen->nama); ?></td>
                            </tr>
                            <?php if($arr[2]['n_clockin'] > 0): ?>
                            <tr>
                                <td>Clock in</td>
                                <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr[5]['dtClockin']))); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($arr[2]['n_clockout'] > 0): ?>
                            <tr>
                                <td>Clock out</td>
                                <td><?php echo e(date('d-m-Y H:i:s', strtotime($arr[5]['dtClockout']))); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($arr[2]['n_checkin'] > 0): ?>
                            <tr>
                                <td>Check in</td>
                                <td>
                                    <?php $__currentLoopData = $arr[5]['dtCheckin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e(date('d-m-Y H:i:s', strtotime($data['att_timestamp']))); ?><br />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <br />
                    <?php if($arr[4]['isClockin']): ?>
                    <form action="<?php echo e(route('cico_att.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-in?');">
                        <?php echo csrf_field(); ?>
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-in">
                        <button class="btn btn-success" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i> Clock In</button>
                    </form>
                    <br />
                    <?php endif; ?>
                    <?php if($arr[4]['isClockout']): ?>
                    <form action="<?php echo e(route('cico_att.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-out?');">
                        <?php echo csrf_field(); ?>
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Clock-out">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> Clock Out</button>
                    </form>
                    <br />
                    <?php endif; ?>
                    <?php if($arr[4]['isCheckin']): ?>
                    <form action="<?php echo e(route('cico_att.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Check-in?');">
                        <?php echo csrf_field(); ?>
                        <input name="att_type" id="att_type" class="form-control" type="hidden" value="Check-in">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-clock-o" aria-hidden="true"></i> Check In</button>
                    </form>
                    <br />
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!--  -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/cico/attendance.blade.php ENDPATH**/ ?>