<?php $__env->startSection('title', 'Overtime'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
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
<h3>Data Overtime</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data</li>
<li class="breadcrumb-item active">Overtime</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="ot_maker_by" name="ot_maker_by">
                                <option value="0">Pilih Karyawan...</option>
                                <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e(Crypt::encrypt($data->nik)); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('approval-index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6">Start Date</label>
                                    <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                    To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6" for="ot_maker_by">Maker By</label>
                                    <br/><input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" value="<?php echo e($ot_maker_by); ?>">
                                    <br/><label class="h6" for="ot_approver_by">Approver By</label>
                                    <br/><input name="ot_approver_by" id="ot_approver_by" class="form-control" type="text" value="<?php echo e($ot_approver_by); ?>">
                                    <br/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_ot_approver_status(this)" /> Check All
                                    <label class="d-block" for="ot_approver_status">
                                        <?php $__currentLoopData = $ot_approver_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="ot_approver_status[]" name="ot_approver_status[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_ot_approver_status)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Created at</th>
                                    <th>Created by</th>
                                    <th>Created NIP</th>
                                    <th>Approved by</th>
                                    <th>Approved NIP</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Title</th>
                                    <!-- <th>Desc</th> -->
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $ot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->ot_maker_at) ->format('d-m-Y H:i:s')); ?></td>
                                    <td><?php echo e($data->_ot_maker_by->nama); ?></td>
                                    <td><?php echo e($data->ot_maker_by); ?></td>
                                    <td><?php echo e($data->_ot_approver_by->nama); ?></td>
                                    <td><?php echo e($data->ot_approver_by); ?></td>
                                    <td>
                                        <?php if($data->ot_approver_status == 'Approve'): ?>
                                        <span class="badge badge-success"><?php echo e($data->ot_approver_status); ?></span>
                                        <?php elseif($data->ot_approver_status == 'Waiting'): ?>
                                        <span class="badge badge-warning"><?php echo e($data->ot_approver_status); ?></span>
                                        <?php else: ?>
                                        <span class="badge badge-danger"><?php echo e($data->ot_approver_status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->ot_start_at) ->format('d-m-Y H:i:s')); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->ot_end_at) ->format('d-m-Y H:i:s')); ?></td>
                                    <td><?php echo e($data->ot_title); ?></td>
                                    <!-- <td><?php echo e($data->ot_desc); ?></td> -->
                                    <td><?php echo e($data->_ot_type_code->ot_type); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('approval-review', [$data->id])); ?>" data-toggle="tooltip" title="Review"><i class="icon-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Created at</th>
                                    <th>Created by</th>
                                    <th>Approved by</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Title</th>
                                    <!-- <th>Desc</th> -->
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
function PilihKaryawan() {
    // var ot_maker_by = "<?php echo e(Crypt::encrypt(auth()->user()->nik)); ?>";
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('spv')): ?>
    if($('#ot_maker_by').val() != '0'){
        ot_maker_by = $('#ot_maker_by').val();
        location.href = "<?php echo e(route('overtime.create')); ?>"+"?ot_maker_by="+ot_maker_by;
    }
    else {
        alert('Pilih Karyawan!');
    }
    <?php endif; ?>
}
function toggle_ot_approver_status(source) {
    checkboxes = document.getElementsByName('ot_approver_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/approval/index.blade.php ENDPATH**/ ?>