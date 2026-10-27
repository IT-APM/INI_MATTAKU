<?php $__env->startSection('title', 'Formulir Karyawan Non Aktif'); ?>

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
<li class="breadcrumb-item">Formulir Karyawan Non Aktif</li>
<li class="breadcrumb-item active">Add</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <form action="<?php echo e(route('nonaktif.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="is_setuju" name="is_setuju" value="<?php echo e($is_setuju); ?>">
                    <input type="hidden" id="created_at" name="created_at" value="<?php echo e($created_at); ?>">
                    <div class="card-header">
                        <h5>Karyawan</h5>
                    </div>
                    <div class="card-body typography">
                        <?php if(Session::has('error')): ?>
                        <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                            <h6><?php echo e(session('error')); ?></h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="alasan">Alasan Non Aktif</label>
                                <select class="form-select" id="alasan" name="alasan" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $alasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e($data->alasan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="tanggal">Tanggal Berakhir</label>
                                <input class="form-control" name="tanggal" id="tanggal" type="date" required="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- start -->
                    <div class="card-header">
                        <h5>Direktorat / Divisi / Departemen</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_di">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada (PIC Handover)</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_di0">
                                                <td>
                                                    <select class="form-select" id="kepada_di[]" name="kepada_di[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_di[]" name="obyek_di[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_di; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_di[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_di[]" name="keterangan_di[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_di1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_di" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_di" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <!-- start -->
                    <div class="card-header">
                        <h5>General Affairs</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_ga">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_ga0">
                                                <td>
                                                    <select class="form-select" id="kepada_ga[]" name="kepada_ga[]" required="">
                                                        <?php $__currentLoopData = $pic_ga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_ga[]" name="obyek_ga[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_ga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_ga[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_ga[]" name="keterangan_ga[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_ga1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_ga" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_ga" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <!-- start -->
                    <div class="card-header">
                        <h5>IT</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_it">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_it0">
                                                <td>
                                                    <select class="form-select" id="kepada_it[]" name="kepada_it[]" required="">
                                                        <?php $__currentLoopData = $pic_it; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_it[]" name="obyek_it[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_it; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_it[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_it[]" name="keterangan_it[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_it1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_it" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_it" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <!-- start -->
                    <div class="card-header">
                        <h5><?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_hc">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_hc0">
                                                <td>
                                                    <select class="form-select" id="kepada_hc[]" name="kepada_hc[]" required="">
                                                        <?php $__currentLoopData = $pic_hc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_hc[]" name="obyek_hc[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_hc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_hc[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_hc[]" name="keterangan_hc[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_hc1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_hc" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_hc" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <!-- start -->
                    <div class="card-header">
                        <h5>Koperasi Karyawan</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_ko">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_ko0">
                                                <td>
                                                    <select class="form-select" id="kepada_ko[]" name="kepada_ko[]" required="">
                                                        <?php $__currentLoopData = $pic_ko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_ko[]" name="obyek_ko[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_ko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_ko[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_ko[]" name="keterangan_ko[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_ko1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_ko" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_ko" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <!-- start -->
                    <div class="card-header">
                        <h5>Application Management</h5>
                    </div>
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="trx_am">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>Kepada</h6>
                                                </th>
                                                <th>
                                                    <h6>Obyek</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="div_am0">
                                                <td>
                                                    <select class="form-select" id="kepada_am[]" name="kepada_am[]" required="">
                                                        <?php $__currentLoopData = $pic_am; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="obyek_am[]" name="obyek_am[]" required="">
                                                        <option selected="" disabled="" value="">Pilih...</option>
                                                        <?php $__currentLoopData = $obyek_am; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->id); ?>"><?php echo e($data->obyek); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    
                                                    <label class="form-label mt-2" for="keterangan_am[]">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan_am[]" name="keterangan_am[]" rows="4"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="div_am1"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="add_row_am" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Add Row</button>
                                <button id="delete_row_am" class="pull-right btn btn-danger"><i class="fa fa-minus"></i> Delete Row</button>
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                    <div class="card-body typography">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Submit</button> | <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
$(document).ready(function() {
    // di
    let row_number_di = 1;
    $("#add_row_di").click(function(e) {
        e.preventDefault();
        let new_row_number_di = row_number_di - 1;
        $('#div_di' + row_number_di).html($('#div_di' + new_row_number_di).html()).find(
            'td:first-child');
        $('#trx_di').append('<tr id="div_di' + (row_number_di + 1) + '"></tr>');
        row_number_di++;
    });
    $("#delete_row_di").click(function(e) {
        e.preventDefault();
        if (row_number_di > 1) {
            $("#div_di" + (row_number_di - 1)).html('');
            row_number_di--;
        }
    });
    // ga
    let row_number_ga = 1;
    $("#add_row_ga").click(function(e) {
        e.preventDefault();
        let new_row_number_ga = row_number_ga - 1;
        $('#div_ga' + row_number_ga).html($('#div_ga' + new_row_number_ga).html()).find(
            'td:first-child');
        $('#trx_ga').append('<tr id="div_ga' + (row_number_ga + 1) + '"></tr>');
        row_number_ga++;
    });
    $("#delete_row_ga").click(function(e) {
        e.preventDefault();
        if (row_number_ga > 1) {
            $("#div_ga" + (row_number_ga - 1)).html('');
            row_number_ga--;
        }
    });
    // it
    let row_number_it = 1;
    $("#add_row_it").click(function(e) {
        e.preventDefault();
        let new_row_number_it = row_number_it - 1;
        $('#div_it' + row_number_it).html($('#div_it' + new_row_number_it).html()).find(
            'td:first-child');
        $('#trx_it').append('<tr id="div_it' + (row_number_it + 1) + '"></tr>');
        row_number_it++;
    });
    $("#delete_row_it").click(function(e) {
        e.preventDefault();
        if (row_number_it > 1) {
            $("#div_it" + (row_number_it - 1)).html('');
            row_number_it--;
        }
    });
    // hc
    let row_number_hc = 1;
    $("#add_row_hc").click(function(e) {
        e.preventDefault();
        let new_row_number_hc = row_number_hc - 1;
        $('#div_hc' + row_number_hc).html($('#div_hc' + new_row_number_hc).html()).find(
            'td:first-child');
        $('#trx_hc').append('<tr id="div_hc' + (row_number_hc + 1) + '"></tr>');
        row_number_hc++;
    });
    $("#delete_row_hc").click(function(e) {
        e.preventDefault();
        if (row_number_hc > 1) {
            $("#div_hc" + (row_number_hc - 1)).html('');
            row_number_hc--;
        }
    });
    // ko
    let row_number_ko = 1;
    $("#add_row_ko").click(function(e) {
        e.preventDefault();
        let new_row_number_ko = row_number_ko - 1;
        $('#div_ko' + row_number_ko).html($('#div_ko' + new_row_number_ko).html()).find(
            'td:first-child');
        $('#trx_ko').append('<tr id="div_ko' + (row_number_ko + 1) + '"></tr>');
        row_number_ko++;
    });
    $("#delete_row_ko").click(function(e) {
        e.preventDefault();
        if (row_number_ko > 1) {
            $("#div_ko" + (row_number_ko - 1)).html('');
            row_number_ko--;
        }
    });
    // am
    let row_number_am = 1;
    $("#add_row_am").click(function(e) {
        e.preventDefault();
        let new_row_number_am = row_number_am - 1;
        $('#div_am' + row_number_am).html($('#div_am' + new_row_number_am).html()).find(
            'td:first-child');
        $('#trx_am').append('<tr id="div_am' + (row_number_am + 1) + '"></tr>');
        row_number_am++;
    });
    $("#delete_row_am").click(function(e) {
        e.preventDefault();
        if (row_number_am > 1) {
            $("#div_am" + (row_number_am - 1)).html('');
            row_number_am--;
        }
    });
    // 
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/nonaktif/insert.blade.php ENDPATH**/ ?>