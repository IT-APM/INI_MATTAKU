
<?php $__env->startSection('title', 'title'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/datatable/css/jquery.dataTables.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/datatable/css/buttons.dataTables.min.css')); ?>"> -->
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
<h3>Perubahan Data Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Perubahan</li>
<li class="breadcrumb-item active">Data Karyawan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('error')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.bulanan', 0)); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                    <div class="collapse mt-2 mb-2" id="collapse_filter">
                        <form action="<?php echo e(route('karyawan.bulanan', 1)); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="perusahaan">
                                        <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($data, $arr_perusahaan)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="divisi">
                                        <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="divisi[]" name="divisi[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($data, $arr_divisi)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_dep(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="dep">
                                        <?php $__currentLoopData = $dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="dep[]" name="dep[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($data, $arr_dep)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_sub_dep(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="sub_dep">
                                        <?php $__currentLoopData = $sub_dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="sub_dep[]" name="sub_dep[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($data, $arr_sub_dep)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.bulanan', 1)); ?>" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="display nowrap" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Periode</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Karyawan</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Level</th>
                                    <th>Jabatan</th>
                                    <th>Grup Kerja</th>
                                    <th>Status Kerja</th>
                                    <th>NIP Layer1</th>
                                    <th>Nama Layer1</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0; ?>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $nik_property = json_decode($data->nik_property, true);
                                $layer1_property = json_decode($data->layer1_property, true);
                                ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e(Carbon\Carbon::parse($data->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?></td>
                                    <td><?php echo e($nik_property['nik']); ?></td>
                                    <td>
                                        <?php echo e($nik_property['nama']); ?>

                                        <?php if(!empty($data->perubahan)): ?>
                                        <br/><span style="background-color:#ffc107;"><?php echo e(nl2br($data->perubahan)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($nik_property['perusahaan']); ?></td>
                                    <td><?php echo e($nik_property['divisi']); ?></td>
                                    <td><?php echo e($nik_property['dep']); ?></td>
                                    <td><?php echo e($nik_property['sub_dep']); ?></td>
                                    <td><?php echo e($nik_property['level']); ?></td>
                                    <td><?php echo e($nik_property['jabatan']); ?></td>
                                    <td><?php echo e($nik_property['wg_name']); ?></td>
                                    <td><?php echo e($nik_property['status_karyawan']); ?></td>
                                    <td><?php echo e($layer1_property['nik']); ?></td>
                                    <td><?php echo e($layer1_property['nama']); ?></td>
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
});
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('divisi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_dep(source) {
    checkboxes = document.getElementsByName('dep[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_sub_dep(source) {
    checkboxes = document.getElementsByName('sub_dep[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/bulanan_histori.blade.php ENDPATH**/ ?>