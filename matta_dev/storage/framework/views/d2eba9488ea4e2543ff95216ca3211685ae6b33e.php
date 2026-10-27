<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit <?php echo e($field); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Edit <?php echo e($field); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Edit <?php echo e($field); ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('kebutuhan.post_kolom', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('POST'); ?>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="field" name="field" value="<?php echo e($field); ?>">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Sebelum: Jumlah (Orang)*</label>
                                <input class="form-control" type="text" readonly="" value="<?php echo e($trxM->jumlah); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="jumlah">Sesudah: Jumlah (Orang)*</label>
                                <input type="number" min="1" oninput="validity.valid||(value='');" name="jumlah" id="jumlah" class="form-control numbers" required="">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="form-label" for="keterangan">Catatan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ></textarea>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('kebutuhan.index')); ?>" class="btn btn-warning" type="button">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6 notification">
            <div class="card">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
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
                                <table class="table">
                                    <tr>
                                        <th>Kolom</th>
                                        <td><?php echo e(ucwords($field)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sebelum</th>
                                        <td><?php echo e($_history->sebelum); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sesudah</th>
                                        <td><?php echo e($_history->sesudah); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td><?php echo e($_history->keterangan); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
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
<script type="text/javascript">
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/edit_kolom.blade.php ENDPATH**/ ?>