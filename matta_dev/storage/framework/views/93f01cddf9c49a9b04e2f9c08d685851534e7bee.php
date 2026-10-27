
<?php $__env->startSection('title', 'To Do List'); ?>

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
<h3>To Do List</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">To Do List</li>
<li class="breadcrumb-item active"><?php if(!empty($trx[0])): ?><?php echo e($trx[0]->title); ?> <?php else: ?> NA <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <!-- <div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <?php if(request('filename') == 'emails.penilaian0'): ?>
                                        <th>Perusahaan</th>
                                    <?php endif; ?>
                                    <th>Unit Kerja</th>
                                    <?php if(request('filename') == 'emails.reimburse0'): ?>
                                        <th>Status</th>
                                    <?php endif; ?>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id_trx); ?></td>
                                    <td><?php echo e($data->title); ?></td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <td><?php echo e($data->jabatan); ?></td>
                                    <?php if(request('filename') == 'emails.penilaian0'): ?>
                                        <td><?php echo e(json_decode(App\Models\TrxPenilaian::find($data->id_trx)->karyawan_property, true)['perusahaan']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->unit_kerja); ?></td>
                                    <?php if(request('filename') == 'emails.reimburse0'): ?>
                                        <td><?php echo e(App\Models\ReimburseStatus::find($data->id_status)->status); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td>
                                        <a href="<?php echo e($data->link); ?>" data-toggle="tooltip" title="Detail" target="_blank"><i class="icon-eye"></i></a>
                                        <?php if($data->filename == 'emails.bpd0'): ?>
                                        <a href="<?php echo e(route('bpd.show', [Crypt::encrypt($data->id_trx), 'view'=>'history'])); ?>" data-toggle="tooltip" title="History" target="_blank"><i class="icon-eye" style="color:red"></i></a>
                                        <?php elseif($data->filename == 'emails.penilaian0_outsourcing'): ?>
                                        <a href="<?php echo e(route('penilaian.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php elseif($data->filename == 'emails.izin0'): ?>
                                        <a href="<?php echo e(route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php elseif($data->filename == 'emails.pelatihan0'): ?>
                                        <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php elseif($data->filename == 'emails.recruitment0' && $data->pic != 'hco'): ?>
                                        <a href="<?php echo e(route('recruitment.download_offering', Crypt::encrypt($data->id_trx))); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div><a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/todolist/list.blade.php ENDPATH**/ ?>