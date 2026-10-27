
<?php $__env->startSection('title', 'Overtime Reporting'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
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
<h3>Overtime Reporting</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Reporting</li>
<li class="breadcrumb-item active">Overtime</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staf_only')): ?>
                    
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> |
                    <?php endif; ?>
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('reporting-index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Start Date</label>
                                    <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                    To <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                    
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6" for="ot_maker_by">Maker By</label>
                                    <br/><input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" value="<?php echo e($ot_maker_by); ?>">
                                    <br/><label class="h6" for="ot_approver_by">Approver By</label>
                                    <br/><input name="ot_approver_by" id="ot_approver_by" class="form-control" type="text" value="<?php echo e($ot_approver_by); ?>">
                                    
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_ot_approver_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="ot_approver_status">
                                        <?php $__currentLoopData = $ot_approver_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="ot_approver_status[]" name="ot_approver_status[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_ot_approver_status)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Company</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="perusahaan">
                                        <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>> <?php echo e($data->nama); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <ul class="nav nav-tabs nav-primary mt-2" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="rawdata-tab" data-bs-toggle="pill" href="#rawdata" role="tab" aria-controls="rawdata" aria-selected="false">Raw Data</a></li>
                        <li class="nav-item"><a class="nav-link" id="recapdata-tab" data-bs-toggle="pill" href="#recapdata" role="tab" aria-controls="recapdata" aria-selected="true">Recap</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade show active" id="rawdata" role="tabpanel" aria-labelledby="rawdata-tab">
                            <div class="table-responsive mt-2">
                                <table class="display" id="raw_datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>#ID</th>
                                            <th>Adj</th>
                                            <th>Company</th>
                                            <th>Directorate</th>
                                            <th>Division</th>
                                            <th>Department</th>
                                            <th>Maker Name</th>
                                            <th>Maker NIP</th>
                                            <th>Approve Name</th>
                                            <th>Approve NIP</th>
                                            <th>Maker at</th>
                                            <th>Approve at</th>
                                            <th>Status</th>
                                            <th>Title</th>
                                            <th>Desc</th>
                                            <th>Type</th>
                                            <th>Start Plan</th>
                                            <th>Finish Plan</th>
                                            <th>Length Plan</th>
                                            <th>Start Real</th>
                                            <th>Finish Real</th>
                                            <th>Length Real</th>
                                            <th>Start Rev</th>
                                            <th>Finish Rev</th>
                                            <th>Length Rev</th>
                                            <th>Basic Sallary</th>
                                            <th>Rp Rev</th>
                                            <th>Rp Rev (Round)</th>
                                            <th>Length Adj</th>
                                            <!-- <th>Length Adj (Seconds)</th> -->
                                            <th>Rp Adj</th>
                                            <th>Rp Adj (Round)</th>
                                            <th>Employee Status</th>
                                            <th>Working Group</th>
                                            <th>Day</th>
                                            <!-- <th>Adj</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php $__currentLoopData = $ot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $_data = json_decode($data->ot_maker_property, true);
                                            $_day = App\Http\Controllers\OvertimeController::isWeekDayWeekEndHoliday($data->ot_start_at, $data->ot_maker_by, $_data['is_reguler'], $_data['wg_id']);
                                            $_isHoliday = App\Http\Controllers\OvertimeController::isHoliday(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isHoliday'];
                                            $_isWeekend = App\Http\Controllers\OvertimeController::isWeekend(\Carbon\Carbon::parse($data->ot_start_at)->format('Y-m-d'))['isWeekend'];
                                            $_arr = json_decode($data->ot_freelance_property, true);
                                        ?>
                                        <tr>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($data->id); ?></td>
                                            <td><a href="<?php echo e(route('adj_ot', [$data->id])); ?>" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td>
                                            <td><?php echo e($_data['perusahaan']); ?></td>
                                            <td><?php echo e($_data['divisi']); ?></td>
                                            <td><?php echo e($_data['dep']); ?></td>
                                            <td><?php echo e($_data['sub_dep']); ?></td>
                                            <td><?php echo e($data->_ot_maker_by->nama); ?></td>
                                            <td><?php echo e($data->ot_maker_by); ?></td>
                                            <td><?php echo e($data->_ot_approver_by->nama); ?></td>
                                            <td><?php echo e($data->ot_approver_by); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->ot_maker_at)->format('d-m-Y H:i:s')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->ot_approver_at)->format('d-m-Y H:i:s')); ?></td>
                                            <td>
                                                <?php if($data->ot_approver_status == 'Approve'): ?>
                                                <span class="badge badge-success"><?php echo e($data->ot_approver_status); ?></span>
                                                <?php elseif($data->ot_approver_status == 'Waiting'): ?>
                                                <span class="badge badge-warning"><?php echo e($data->ot_approver_status); ?></span>
                                                <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e($data->ot_approver_status); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($data->ot_title); ?></td>
                                            <td><?php echo e($data->ot_desc); ?></td>
                                            <td><?php echo e($data->_ot_type_code->ot_type); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->ot_start_at)->format('d-m-Y H:i:s')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->ot_end_at)->format('d-m-Y H:i:s')); ?></td>
                                            
                                            
                                            <td><?php echo e(sprintf('%02d:%02d:%02d', floor($data->ot_length / 3600), floor(($data->ot_length % 3600) / 60), $data->ot_length % 60)); ?></td>
                                            <td><?php if(!empty($data->ot_start_at_real)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_start_at_real)->format('d-m-Y H:i:s')); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->ot_end_at_real)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_end_at_real)->format('d-m-Y H:i:s')); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->ot_length_real)): ?> <?php echo e($data->ot_length_real); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->ot_start_at_rev)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_start_at_rev)->format('d-m-Y H:i:s')); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->ot_end_at_rev)): ?> <?php echo e(\Carbon\Carbon::parse($data->ot_end_at_rev)->format('d-m-Y H:i:s')); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->ot_length_rev)): ?> <?php echo e($data->ot_length_rev); ?> <?php endif; ?></td>
                                            <td class="text-end"><?php if($_data['status_karyawan']=='Freelance'): ?> <?php if($_isHoliday||$_isWeekend): ?> <?php echo e(number_format($_arr[1]['informasi'])); ?> <?php else: ?> <?php echo e(number_format($_arr[0]['informasi'])); ?> <?php endif; ?> <?php else: ?> <?php echo e(number_format($_data['gaji_pokok'])); ?> <?php endif; ?></td>
                                            <td class="text-end"><?php echo e(number_format($data->ot_length_rp)); ?></td>
                                            <td class="text-end"><?php echo e(number_format(round($data->ot_length_rp, -3))); ?></td>
                                            
                                            
                                            <td><?php if(!empty($data->ot_length_adj)): ?> <?php echo e(sprintf('%02d:%02d:%02d', floor($data->ot_length_adj / 3600), floor(($data->ot_length_adj % 3600) / 60), $data->ot_length_adj % 60)); ?> <?php endif; ?></td>
                                            <!-- <td><?php if(!empty($data->ot_length_adj)): ?> <a href="<?php echo e(route('adj_ot', [$data->id])); ?>" data-toggle="tooltip" title='Adjustment'><?php echo e(number_format($data->ot_length_adj)); ?> <i class="icon-pencil-alt"></i></a> <?php endif; ?></td> -->
                                            <td class="text-end"><?php echo e(number_format($data->ot_length_rp_adj)); ?></td>
                                            <td class="text-end"><?php echo e(number_format(round($data->ot_length_rp_adj, -3))); ?></td>
                                            <td><?php echo e($_data['status_karyawan']); ?></td>
                                            <td><?php echo e($_data['wg_name']); ?></td>
                                            <td><?php echo $_day; ?></td>
                                            <!-- <td><a href="<?php echo e(route('adj_ot', [$data->id])); ?>" data-toggle="tooltip" title='Adjustment'> <i class="icon-pencil-alt"></i></a></td> -->
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <?php $max = App\Models\MtrUtility::find(33)->informasi ?>
                        <div class="tab-pane fade" id="recapdata" role="tabpanel" aria-labelledby="recapdata-tab">
                            <div class="table-responsive mt-2">
                                <table class="display" id="recap_datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Directorate</th>
                                            <th>Division</th>
                                            <th>Department</th>
                                            <th>Maker Name</th>
                                            <th>Maker NIP</th>
                                            <th>Approve Name</th>
                                            <th>Rp Adj (Round)</th>
                                            <th>Rp Max Value</th>
                                            <th>Employee Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php $__currentLoopData = $otrecap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $_data = json_decode($data->ot_maker_property, true) ?>
                                        <tr>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e($_data['perusahaan']); ?></td>
                                            <td><?php echo e($_data['divisi']); ?></td>
                                            <td><?php echo e($_data['dep']); ?></td>
                                            <td><?php echo e($_data['sub_dep']); ?></td>
                                            <td><?php echo e($data->_ot_maker_by->nama); ?></td>
                                            <td><?php echo e($data->ot_maker_by); ?></td>
                                            <td><?php echo e($data->_ot_approver_by->nama); ?></td>
                                            <td class="text-end"><?php echo e(number_format(round($data->ot_length_rp_adj, -3))); ?></td>
                                            <td class="text-end"><?php if($max > round($data->ot_length_rp_adj, -3)): ?><?php echo e(number_format(round($data->ot_length_rp_adj, -3))); ?><?php else: ?> <?php echo e(number_format($max)); ?> <?php endif; ?></td>
                                            <td><?php echo e($_data['status_karyawan']); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
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
<script type="text/javascript">
function PilihKaryawan() {
    var ot_maker_by = "<?php echo e(Crypt::encrypt(auth()->user()->nik)); ?>";
    location.href = "<?php echo e(route('overtime.create')); ?>"+"?ot_maker_by="+ot_maker_by;
}
function toggle_ot_approver_status(source) {
    checkboxes = document.getElementsByName('ot_approver_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#raw_datatable').DataTable( {
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
$('#recap_datatable').DataTable( {
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/reporting/index.blade.php ENDPATH**/ ?>