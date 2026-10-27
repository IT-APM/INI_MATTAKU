<?php $__env->startSection('title', 'Validation Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Hari Libur Nasional</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('holiday.update', $holiday->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('holiday.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="tanggal">Tanggal</label>
                                    <input class="form-control" name="tanggal" id="tanggal" type="date" required="" <?php if($status=='edit' ): ?> value="<?php echo e($holiday->tanggal); ?>" <?php endif; ?>>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="libur">Hari Libur Nasional</label>
                                    <input name="libur" id="libur" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($holiday->libur); ?>" <?php endif; ?>>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="is_cuti">Cuti Bersama</label>
                                    <div class="media-body text-start icon-state switch-outline">
                                        <label class="switch">
                                            <input name="is_cuti" id="is_cuti" type="checkbox" <?php if($status=='edit' ): ?> <?php if($holiday->is_cuti): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('holiday.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/holiday/upsert.blade.php ENDPATH**/ ?>