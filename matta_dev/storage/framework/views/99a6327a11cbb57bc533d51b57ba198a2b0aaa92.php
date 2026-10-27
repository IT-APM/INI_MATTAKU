
<?php $__env->startSection('title', 'Basic DataTables'); ?>

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
<h3>Project vs Admin</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Mapping</li>
<li class="breadcrumb-item active">Project vs Admin</li>
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
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?php echo e(route('mtrdataprojectadmin.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add new</a><br /><br />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Project ID</th>
                                    <th>Project Name</th>
                                    <th>Division</th>
                                    <th>Customer Name</th>
                                    <th>Group</th>
                                    <th width="15%">Admin</th>
                                    <th>Aktif</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->project_id); ?></td>
                                    <td><?php echo e($data->name); ?></td>
                                    <td><?php echo e($data->divisi); ?></td>
                                    <td><?php echo e($data->customer); ?></td>
                                    <td><?php echo e($data->group); ?></td>
                                    <td>
                                        <?php
                                        $where = ['id_proyek' => $data->id];
                                        $admin = App\Models\BpdProyekAdmin::where($where)->orderBy('id', 'asc')->get();
                                        ?>
                                        <?php $__currentLoopData = $admin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($_admin->_admin->nama); ?> / <?php echo e($_admin->admin); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td><?php if($data->is_active==1): ?> <span class="badge bg-success">Aktif</span> <?php else: ?> <span class="badge bg-danger">Non Aktif</span> <?php endif; ?></td>
                                    <td>
                                        <form action="<?php echo e(route('mtrdataprojectadmin.destroy', Crypt::encrypt($data->id))); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <ul class="action">
                                                <li class="new"><a href="<?php echo e(route('mtrdataprojectadmin.show', Crypt::encrypt($data->id))); ?>" data-toggle="tooltip" title='Admin'><i class="fa fa-user-plus" aria-hidden="true"></i></a>&nbsp;</li>
                                                <li class="edit"><a href="<?php echo e(route('mtrdataprojectadmin.edit', Crypt::encrypt($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
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
});
$(document).on('click', '#btn-delete', function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
            title: "Are you sure to delete this?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta_dev\resources\views/master/bpd/project_admin/index.blade.php ENDPATH**/ ?>