<?php $__env->startSection('title', 'Formulir Rekomendasi Karyawan'); ?>

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
<li class="breadcrumb-item">Rekomendasi Karyawan</li>
<li class="breadcrumb-item active">Update</li>
<?php $__env->stopSection(); ?>
<!-- https://makitweb.com/auto-populate-dropdown-with-jquery-ajax-in-laravel/ -->
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Para Pihak</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('rekomendasi.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trxM->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo method_field('PATCH'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="card-body pt-0">
                                    <div class="appointment-table table-responsive">
                                        <table class="table table-bordernone">
                                            <tbody>
                                                <!-- karyawan -->
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Karyawan</h6><span
                                                            class="f-light m-10"><?php echo e($karyawan['nama']); ?><br /><?php echo e($karyawan['jabatan']); ?>

                                                            <br /><?php echo e($karyawan['divisi']); ?> / <?php echo e($karyawan['dep']); ?> / <?php echo e($karyawan['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <!-- atasan -->
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Rekomendasi Dari Atasan Langsung</h6><span
                                                            class="f-light m-10"><?php echo e($atasan['nama']); ?><br /><?php echo e($atasan['jabatan']); ?>

                                                            <br /><?php echo e($atasan['divisi']); ?> / <?php echo e($atasan['dep']); ?> / <?php echo e($atasan['sub_dep']); ?></span>
                                                    </td>
                                                    <input type="hidden" id="nik_atasan" name="nik_atasan" value="<?php echo e($atasan['nik']); ?>">
                                                </tr>
                                                <!-- reviewer1 -->
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer1['nik'].'/'.$reviewer1['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Reviewer 1</h6><span
                                                            class="f-light m-10"><?php echo e($reviewer1['nama']); ?><br /><?php echo e($reviewer1['jabatan']); ?>

                                                            <br /><?php echo e($reviewer1['divisi']); ?> / <?php echo e($reviewer1['dep']); ?> / <?php echo e($reviewer1['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <!-- reviewer2 -->
                                                <?php if(!empty($reviewer2['nik'])): ?>
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer2['nik'].'/'.$reviewer2['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Reviewer 2</h6><span
                                                            class="f-light m-10"><?php echo e($reviewer2['nama']); ?><br /><?php echo e($reviewer2['jabatan']); ?>

                                                            <br /><?php echo e($reviewer2['divisi']); ?> / <?php echo e($reviewer2['dep']); ?> / <?php echo e($reviewer2['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                                <!-- reviewer3 -->
                                                <?php if(!empty($reviewer3['nik'])): ?>
                                                <tr>
                                                    <td><a href="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg')); ?>"
                                                            target="_blank"><img class="img-thumbnail"
                                                                src="<?php echo e(asset('assets/images/matta/karyawan/'.$reviewer3['nik'].'/'.$reviewer3['nik'].'.jpg')); ?>"
                                                                alt="user"></a></td>
                                                    <td class="img-content-box">
                                                        <h6 class="m-10">Reviewer 3</h6><span
                                                            class="f-light m-10"><?php echo e($reviewer3['nama']); ?><br /><?php echo e($reviewer3['jabatan']); ?>

                                                            <br /><?php echo e($reviewer3['divisi']); ?> / <?php echo e($reviewer3['dep']); ?> / <?php echo e($reviewer3['sub_dep']); ?></span>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="status_rekomendasi">Status Pengajuan</label>
                                <select class="form-select" id="status_rekomendasi" name="status_rekomendasi"
                                    required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trxM->status_rekomendasi): ?> selected
                                        <?php endif; ?> ><?php echo e($data->status_pengajuan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4" id="div_reviewer2">
                                <label class="form-label" for="reviewer2">Approval 2</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="reviewer2"
                                    name="reviewer2" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <!-- <?php $__currentLoopData = $approval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
                                </select>
                            </div>
                            <div class="col-md-4" id="div_reviewer3">
                                <label class="form-label" for="reviewer3">Approval 3</label>
                                <select class="form-select js-example-basic-single col-sm-12" id="reviewer3"
                                    name="reviewer3" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <!-- <?php $__currentLoopData = $approval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->nik); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="keterangan">Catatan <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ><?php if(!empty($keterangan)): ?> <?php echo e($keterangan->keterangan); ?> <?php endif; ?></textarea>
                            </div>
                        </div>
                        <br />
                        <button class="btn btn-success" type="submit">Update</button> | <a href="<?php echo e(route('rekomendasi.index')); ?>" class="btn btn-warning" type="button">Back</a>
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
    var id = $('#nik_atasan').val();
    $("#reviewer2").prop('disabled', true);
    $("#reviewer3").prop('disabled', true);
    $('#div_reviewer2').hide();
    $('#div_reviewer3').hide();
    $("#status_rekomendasi").change(function() {
        // var textStatus = $(this).find("option:selected").text();
        var valueStatus = $(this).val();
        // Next to Reviewer Approval-2
        if (valueStatus == 6) {
            $("#reviewer2").prop('disabled', false);
            $("#reviewer3").prop('disabled', true);
            $('#div_reviewer2').show();
            $('#div_reviewer3').hide();
            // reviewer2
            $('#reviewer2').find('option').not(':first').remove();
            $.ajax({
                // url: _url + '/matta/rekomendasi/layer/' + id,
                url: "<?php echo e(route('rekomendasi_layer')); ?>" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var layer = response['layer'].layer2;
                    var len = 0;
                    if (response['karyawan'] != null) {
                        len = response['karyawan'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var nik = response['karyawan'][i].nik;
                            var nama = response['karyawan'][i].nama;
                            if (layer == nik) {
                                var option = "<option value='" + nik + "' selected>" +
                                    nama + " - " + nik + "</option>";
                            } else {
                                var option = "<option value='" + nik + "'>" + nama + " - " +
                                    nik + "</option>";
                            }
                            $("#reviewer2").append(option);
                        }
                    }
                }
            });
        }
        // Next to Reviewer Approval-2 & 3
        else if (valueStatus == 11) {
            $("#reviewer2").prop('disabled', false);
            $("#reviewer3").prop('disabled', false);
            $('#div_reviewer2').show();
            $('#div_reviewer3').show();
            // reviewer2
            $('#reviewer2').find('option').not(':first').remove();
            $.ajax({
                // url: _url + '/matta/rekomendasi/layer/' + id,
                url: "<?php echo e(route('rekomendasi_layer')); ?>" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var layer = response['layer'].layer2;
                    var len = 0;
                    if (response['karyawan'] != null) {
                        len = response['karyawan'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var nik = response['karyawan'][i].nik;
                            var nama = response['karyawan'][i].nama;
                            if (layer == nik) {
                                var option = "<option value='" + nik + "' selected>" +
                                    nama + " - " + nik + "</option>";
                            } else {
                                var option = "<option value='" + nik + "'>" + nama + " - " +
                                    nik + "</option>";
                            }
                            $("#reviewer2").append(option);
                        }
                    }
                }
            });
            // reviewer3
            $('#reviewer3').find('option').not(':first').remove();
            $.ajax({
                // url: _url + '/matta/rekomendasi/layer/' + id,
                url: "<?php echo e(route('rekomendasi_layer')); ?>" + "/" + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var layer = response['layer'].layer3;
                    var len = 0;
                    if (response['karyawan'] != null) {
                        len = response['karyawan'].length;
                    }
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var nik = response['karyawan'][i].nik;
                            var nama = response['karyawan'][i].nama;
                            if (layer == nik) {
                                var option = "<option value='" + nik + "' selected>" +
                                    nama + " - " + nik + "</option>";
                            } else {
                                var option = "<option value='" + nik + "'>" + nama + " - " +
                                    nik + "</option>";
                            }
                            $("#reviewer3").append(option);
                        }
                    }
                }
            });
        }
        // else
        else {
            $("#reviewer2").prop('disabled', true);
            $("#reviewer3").prop('disabled', true);
            $('#div_reviewer2').hide();
            $('#div_reviewer3').hide();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/rekomendasi/update.blade.php ENDPATH**/ ?>