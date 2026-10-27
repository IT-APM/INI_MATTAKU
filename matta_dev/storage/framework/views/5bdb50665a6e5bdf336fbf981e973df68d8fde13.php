<?php $__env->startSection('title', 'Posting'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/dropzone.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Posting</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Posting</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body add-post">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h5>ERROR: <?php echo e(session('error')); ?></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
					<div class="col-sm-12">
						<div class="mb-3">
							<h5>Position</h5>
							<input class="form-control" type="text" value="<?php echo e($trx->_pekerjaan->pekerjaan); ?>">
						</div>
					</div>
                    <form action="<?php echo e(route('recruitment.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="id_kebutuhan" name="id_kebutuhan" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
						<div class="row mb-3">
                            <div class="theme-form">
                                <h5>Content</h5>
                                <textarea class="form-control" id="text-box" name="job_description" cols="10" rows="5" required=""><?php if(!empty($_trx)): ?><?php echo $_trx->job_description; ?><?php else: ?> <?php echo e($default); ?> <?php endif; ?></textarea>
                            </div>
						</div>
						<button class="btn btn-primary" type="submit">Post</button>
						<a href="<?php echo e(route('recruitment.index')); ?>" class="btn btn-light" type="button">Discard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/editor/ckeditor/ckeditor.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/editor/ckeditor/adapters/jquery.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/email-app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/lowongan.blade.php ENDPATH**/ ?>