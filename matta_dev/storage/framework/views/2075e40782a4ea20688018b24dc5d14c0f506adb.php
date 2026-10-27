<?php $__env->startSection('title', 'Formulir Permohonan Izin Sakit'); ?>

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
<li class="breadcrumb-item">Permohonan Izin Sakit</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload image is ERROR</h6>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                        
                        
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label" for="pengaju">Pengaju</label>
                            <input class="form-control" name="pengaju" id="pengaju" type="text" required="" readonly="" value="<?php echo e($pengaju); ?>">
                        </div>
                    </div>
                    <form action="<?php echo e(route('sakit.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="nik_karyawan" name="nik_karyawan" value="<?php echo e($nik_karyawan); ?>">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="">
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="pic_pengganti">PIC Pengganti</label>
                                <select class="js-example-basic-multiple col-sm-12" id="pic_pengganti[]" name="pic_pengganti[]" required="" multiple="multiple">
                                    <!-- <option selected="" disabled="" value="">Pilih...</option> -->
                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="invalid-feedback txt-danger">PIC Pengganti is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="telepon">Telepon Pengaju</label>
                                <input class="form-control m-input digits" type="tel" name="telepon" id="telepon" required="">
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="alasan">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="4" required=""></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required=""></textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lampiran<br/>*Maksimal 2MB dengan format pdf atau gambar(jpg, jpeg dan png)</label>
                                <input name="lampiran" id="lampiran" class="form-control" type="file" required="">
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
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
    $("#jumlah_hari").prop('disabled', true);
    $('#div_jumlah_hari').hide();
    $("#tipe_sakit").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 0) {
            $("#jumlah_hari").prop('disabled', false);
            $('#div_jumlah_hari').show();
        } else {
            $("#jumlah_hari").prop('disabled', true);
            $('#div_jumlah_hari').hide();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/sakit/insert.blade.php ENDPATH**/ ?>