<?php $__env->startSection('title', 'Formulir Kebutuhan Karyawan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Update</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Kebutuhan Karyawan</li>
<li class="breadcrumb-item active">Update</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-7">
            <div class="card">
                <div class="card-header">
                    <h5>Para Pihak</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('kebutuhan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="card-body pt-0">
                                    <div class="appointment-table table-responsive">
                                        <table class="table table-bordernone">
                                            <tbody>
                                                <!-- pengaju -->
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Karyawan</h6>
                                                        <span class="f-light m-10"><?php echo e($pengaju['nama']); ?><br/><?php echo e($pengaju['jabatan']); ?><br/><?php echo e($pengaju['divisi']); ?> / <?php echo e($pengaju['dep']); ?> / <?php echo e($pengaju['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <!-- layer1 -->
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer1['nik'].'/'.$layer1['nik'].'.jpg')); ?>" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Layer 1</h6>
                                                        <span class="f-light m-10"><?php echo e($layer1['nama']); ?><br/><?php echo e($layer1['jabatan']); ?><br/><?php echo e($layer1['divisi']); ?> / <?php echo e($layer1['dep']); ?> / <?php echo e($layer1['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <!-- layer2 -->
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg')); ?>" alt="user"></a>
                                                    </td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Layer 2</h6>
                                                        <span class="f-light m-10"><?php echo e($layer2['nama']); ?><br/><?php echo e($layer2['jabatan']); ?><br/><?php echo e($layer2['divisi']); ?> / <?php echo e($layer2['dep']); ?> / <?php echo e($layer2['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="keterangan_lain">Keterangan Lain</label>
                                <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="4" ><?php echo $trxM->keterangan_lain; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                                <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $status_pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trxM->status_pengajuan): ?> selected <?php endif; ?> ><?php echo e($data->status_pengajuan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br/>
                        <button class="btn btn-success" type="submit">Update</button> | <a href="<?php echo e(route('kebutuhan.index')); ?>" class="btn btn-warning" type="button">Back</a>
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
<script type="text/javascript">
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/update.blade.php ENDPATH**/ ?>