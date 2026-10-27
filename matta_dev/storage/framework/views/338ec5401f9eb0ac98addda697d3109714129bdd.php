<?php $__env->startSection('title', 'Payroll'); ?>

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
<h3>Payroll Slip</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Slip</li>
<?php $__env->stopSection(); ?>
<?php
$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(count($payroll)>0 || count($payroll_h)>0): ?><h6 class="text-danger">Catatan: Masukkan tanggal lahir anda dengan format ddmmyyyy sebagai password untuk membuka file pdf slip gaji.<br>Contoh: Jika tanggal lahir anda adalah 2 Maret 2004 maka passwordnya adalah 02032004.</h6><?php endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>
                    <div class="collapse mt-2" id="collapse_filter">
                        <form action="<?php echo e(route('payroll.slip', (new App\Http\Controllers\OpenDataController)->encryptWithKey(auth()->user()->nik))); ?>" method="get" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_dari); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_sampai); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <button class="btn btn-info" id="reset" name="reset" value="true" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="display nowrap" id="_datatable1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Slip Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no=0;
                                    $hco = explode(',', App\Models\MtrUtility::find(32)->informasi);
                                ?>
                                <?php $__currentLoopData = $payroll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->tahun); ?></td>
                                    <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                    <td><?php echo e($data->_nik->nama); ?></td>
                                    <td><?php echo e($data->nik); ?></td>
                                    <?php if(in_array(auth()->user()->nik, $hco)): ?>
                                    <td><a href="<?php echo e(route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                    <?php else: ?>
                                    <td><?php if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday())): ?><a href="<?php echo e(route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf'])); ?>" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a><?php endif; ?></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $payroll_h; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->tahun); ?></td>
                                    <td><?php echo e($bulan[$data->bulan - 1]); ?></td>
                                    <td><?php echo e($data->_nik->nama); ?></td>
                                    <td><?php echo e($data->nik); ?></td>
                                    <?php if(in_array(auth()->user()->nik, $hco)): ?>
                                    <td><a href="<?php echo e(route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h'])); ?>" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                                    <?php else: ?>
                                    <td><?php if((!empty($data->paid_at)) && (Carbon\Carbon::parse($data->paid_at)->isPast() || Carbon\Carbon::parse($data->paid_at)->isToday())): ?><a href="<?php echo e(route('payroll.slip.detail', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => 'pdf_h'])); ?>" data-toggle="tooltip" title="Slip Gaji" target="_blank"><i class="fa fa-file-pdf-o"></i></a><?php endif; ?></td>
                                    <?php endif; ?>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>

<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500,  "All"]]
} );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta_dev\resources\views/payroll/self.blade.php ENDPATH**/ ?>