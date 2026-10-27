
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Formulir</th>
                                    <th>Nama</th>
                                    <?php if(in_array($trx[0]->filename, ['emails.penilaian0','emails.penilaian0_outsourcing'])): ?>
                                    <th>NIP</th>
                                    <?php endif; ?>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <?php if(in_array($trx[0]->filename, ['emails.penilaian0','emails.penilaian0_outsourcing'])): ?>
                                    <th>Nilai Total Penilai 1</th>
                                    <?php endif; ?>
                                    <th>Created at</th>
                                    <th>Link</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0 ?>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $penilaian = App\Models\TrxPenilaian::find($data->id_trx);
                                    // Nilai Total Penilai 1
                                    $_where = ['id_trx' => $data->id_trx];
                                    $skor_penilai_1 = App\Models\TrxPenilaianSkor::where($_where)->sum('skor_penilai_1');
                                ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id_trx); ?></td>
                                    <td><?php echo e($data->title); ?></td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <?php if(in_array($data->filename, ['emails.penilaian0','emails.penilaian0_outsourcing'])): ?>
                                    <td><?php echo e($penilaian->nik_karyawan); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->jabatan); ?></td>
                                    <td><?php echo e($data->unit_kerja); ?></td>
                                    <?php if(in_array($data->filename, ['emails.penilaian0','emails.penilaian0_outsourcing'])): ?>
                                    <td><?php echo e($skor_penilai_1); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td><a href="<?php echo e($data->link); ?>" data-toggle="tooltip" title="Link" target="_blank"><i class="icon-eye"></i></a></td>
                                    <td><?php echo e($data->link); ?></td>
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/todolist/recap_detail.blade.php ENDPATH**/ ?>