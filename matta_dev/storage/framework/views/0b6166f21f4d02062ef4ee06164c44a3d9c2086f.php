<?php $__env->startSection('title', 'Formulir Izin'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Add</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Izin</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h4>ERROR:</h4>
                        <h5><?php echo e(session('error')); ?></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <form action="<?php echo e(route('izin.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="tipe">Tipe Izin</label>
                                <select class="form-select" id="tipe" name="tipe" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->tipe); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal">Tanggal</label>
                                <input class="form-control" name="tanggal" id="tanggal" type="date" required="">
                            </div>
                            <div class="col-md-4" id="div_jam">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" for="jam_awal">Jam Mulai</label>
                                        <input class="form-control" name="jam_awal" id="jam_awal" type="time" required="">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="jam_akhir">Jam Selesai</label>
                                        <input class="form-control" name="jam_akhir" id="jam_akhir" type="time" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label class="form-label" for="alasan">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required=""></textarea>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(route('izin.index')); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
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
$(document).ready(function() {
    // var _url = window.location.origin;
    $("#jam_awal").prop('disabled', true);
    $("#jam_akhir").prop('disabled', true);
    $('#div_jam').hide();
    $("#tipe").change(function() {
        var selectedText = $(this).find("option:selected").text();
        var selectedValue = $(this).val();
        // alert("Selected Text: " + selectedText + " Value: " + selectedValue);
        if (selectedValue == 2) {
            $("#div_jam").show();
            $("#jam_awal").prop('disabled', false);
            $("#jam_akhir").prop('disabled', false);
        } else {
            $("#div_jam").hide();
            $("#jam_awal").prop('disabled', true);
            $("#jam_akhir").prop('disabled', true);
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/izin/insert.blade.php ENDPATH**/ ?>