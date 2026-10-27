<?php $__env->startSection('title', 'Recruitment'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Interview</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">Interview</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h5><?php echo e(session('msg')); ?></h6>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload file is ERROR</h6>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <form action="<?php echo e(route('recruitment.interview_save')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($trx->id)); ?>">
                        <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label for="id" class="form-label">#ID</label>
                                    <input type="text" class="form-control" name="id" id="id" placeholder="ID" value="<?php echo e($_trx->id_kebutuhan); ?>/<?php echo e($_trx->id); ?>" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="nama" class="form-label">Nama/Email Kandidat</label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama/Email" value="<?php echo e($_trx->nama); ?>/<?php echo e($_trx->email); ?>" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="interviewer">Pewawancara</label>
                                    
                                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="interviewer[]" name="interviewer[]" required="" disabled>
                                        
                                        <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if(in_array($data->nik, explode(';', $trx->interviewer))): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="metode" class="form-label">Metode</label>
                                    <input type="text" class="form-control" name="metode" id="metode" placeholder="metode" value="<?php echo e($trx->metode); ?>" disabled>
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button> | <a href="<?php echo e(route('recruitment.applicant_list', 0)); ?>" class="btn btn-warning" type="button">Back</a>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="col-12 mb-2">
                                    <label for="jenis" class="form-label">Tipe Wawancara</label>
                                    <input type="text" class="form-control text-uppercase" name="jenis" id="jenis" placeholder="Tipe Wawancara" value="<?php echo e($trx->jenis); ?>" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="check_in">Check_in</label>
                                    <input class="form-control" name="check_in" id="check_in" type="datetime-local" required="" value="<?php echo e($trx->check_in); ?>" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="check_out">Check_out</label>
                                    <input class="form-control" name="check_out" id="check_out" type="datetime-local" required="" value="<?php echo e($trx->check_out); ?>" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label" for="file_interview">File Interview</label>&nbsp;<?php if(!empty($trx->file_interview)): ?><a href="<?php echo e(asset('assets/recruitment/upload/'.$trx->file_interview)); ?>" target="_blank" data-toggle="tooltip" title="File Interpretasi"><i class="fa fa-file-text-o"></i><?php endif; ?>
                                    <input name="file_interview" id="file_interview" class="form-control" type="file">
                                    <label class="form-label">*Maksimal 20 MB (any format)</label>
                                </div>
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
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/interview_item.blade.php ENDPATH**/ ?>