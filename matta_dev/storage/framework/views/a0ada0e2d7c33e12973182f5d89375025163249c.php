<?php $__env->startSection('title', 'Upload Data Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Upload Data Master Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Karyawan</li>
<li class="breadcrumb-item active">Upload Data</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="<?php echo e(route('karyawan.upload_data_xlsx', 0)); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="jenis" name="jenis" value="data_master_karyawan">
                            <label class="h6">Data Master Karyawan</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input name="template" id="template" class="form-control" type="file" required="">
                                    <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                                    <br/><a href="<?php echo e(asset('assets/template/template_upload_karyawan_v4.1.xlsx')); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download template</a>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    // 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/upload_data.blade.php ENDPATH**/ ?>