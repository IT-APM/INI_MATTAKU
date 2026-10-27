<?php $__env->startSection('title', 'Validation Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Mapping</li>
<li class="breadcrumb-item">Karyawan vs Admin</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if($status == 'edit'): ?>
                    <form action="<?php echo e(route('mtrdatabpd.update', $trx->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('PATCH'); ?>
                    <?php else: ?>
                    <form action="<?php echo e(route('mtrdatabpd.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php endif; ?>
                    <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nik">Karyawan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="nik" name="nik" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $trx->nik): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="admin">Admin</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="admin" name="admin" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $admin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>" <?php if($status=='edit' ): ?> <?php if($data->nik == $trx->admin): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->nama); ?> / <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="id_peran">Golongan</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="id_peran" name="id_peran" required="">
                                    <option selected="" value="">Pilih...</option>
                                    <option value="1" <?php if($status=='edit' ): ?> <?php if(1 == $trx->id_peran): ?> selected <?php endif; ?> <?php endif; ?> >Leader</option>
                                    <option value="2" <?php if($status=='edit' ): ?> <?php if(2 == $trx->id_peran): ?> selected <?php endif; ?> <?php endif; ?> >Member</option>
                                    <option value="3" <?php if($status=='edit' ): ?> <?php if(3 == $trx->id_peran): ?> selected <?php endif; ?> <?php endif; ?> >Management</option>
                                </select>
                                <div class="invalid-feedback txt-danger">Field is required.</div>
                            </div>
                            <div class="col-md-2">
								<label class="form-label" for="is_active">Aktif</label>
                                <div class="media-body text-start icon-state switch-outline">
                                    <label class="switch">
										<input name="is_active" id="is_active" type="checkbox" <?php if($status=='edit' ): ?> <?php if($trx->is_active == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state bg-success"></span>
									</label>
								</div>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('mtrdatabpd.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/bpd/nip_admin/upsert.blade.php ENDPATH**/ ?>