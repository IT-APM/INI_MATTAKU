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
<li class="breadcrumb-item">Pelatihan</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('_pelatihan_master.update', $pelatihan_sub->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php else: ?>
                        <form action="<?php echo e(route('_pelatihan_master.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php endif; ?>
                            <?php echo csrf_field(); ?>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="id_pelatihan">Jenis</label>
                                    <select class="form-select" id="id_pelatihan" name="id_pelatihan" required="">
                                        <option selected="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $pelatihan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $pelatihan_sub->id_pelatihan): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->pelatihan); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label class="form-label" for="sub_pelatihan">Pelatihan</label>
                                    <input name="sub_pelatihan" id="sub_pelatihan" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($pelatihan_sub->sub_pelatihan); ?>" <?php endif; ?>>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('_pelatihan_master.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/pelatihan/upsert.blade.php ENDPATH**/ ?>