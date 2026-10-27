<?php $__env->startSection('title', 'Formulir Izin'); ?>

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
<h3>Formulir Izin</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Izin</li>
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
                    <a href="<?php echo e(route('izin.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?> | <a href="<?php echo e(route('izin.recap', 0)); ?>" class="btn btn-success" type="button"><i class="fa fa-list" aria-hidden="true"></i> Recap</a> <?php endif; ?>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('izin.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6 mt-2">Tanggal Izin</label>
                                    <br/><input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                    sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status">
                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status[]" name="status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status)): ?> checked <?php endif; ?>> <?php echo e($data->status); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Tipe Izin</label>&nbsp;<input type="checkbox" onClick="toggle_tipe(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="tipe">
                                        <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="tipe[]" name="tipe[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_tipe)): ?> checked <?php endif; ?>> <?php echo e($data->tipe); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="is_aktif">
                                        <?php $__currentLoopData = $is_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="<?php echo e($data['id']); ?>" <?php if(in_array($data['id'], $arr_is_aktif)): ?> checked <?php endif; ?>>
                                            <?php echo e($data['is_aktif']); ?><br />
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
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
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Tipe</th>
                                    <th>Hari</th>
                                    <th>Tanggal</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <?php $no = 0; ?>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $karyawan_property = json_decode($data['karyawan_property'], true);
                                    $atasan_property = json_decode($data['atasan_property'], true);
                                    $layer2_property = json_decode($data['layer2_property'], true);
                                ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="<?php echo e(route('izin.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td><?php echo e($karyawan_property['nama']); ?></td>
                                    <td><?php echo e($karyawan_property['nik']); ?></td>
                                    <td><?php echo e($karyawan_property['divisi']); ?></td>
                                    <td><?php echo e($karyawan_property['dep']); ?></td>
                                    <td><?php echo e($karyawan_property['sub_dep']); ?></td>
                                    <td><?php echo e($atasan_property['nama']); ?></td>
                                    <td><?php if(!empty($data->layer2)): ?><?php echo e($layer2_property['nama']); ?><?php endif; ?></td>
                                    <td>
                                        <?php echo e($data->_tipe->tipe); ?>

                                        <?php if($data->tipe == 2): ?>
                                        <br/>Jam <?php echo e($data->jam_awal); ?> s/d <?php echo e($data->jam_akhir); ?>

                                        <?php endif; ?>
                                    </td>
                                    
                                    <?php
                                        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    ?>
                                    <td><?php echo e($hari[\Carbon\Carbon::parse($data->tanggal)->dayOfWeek]); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')); ?></td>
                                    <td><?php echo $data->alasan; ?></td>
                                    <td><?php echo e($data->_status->status); ?></td>
                                    <td><?php echo e($data->created_at); ?></td>
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
    function toggle_is_aktif(source) {
        checkboxes = document.getElementsByName('is_aktif[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
function toggle_status(source) {
    checkboxes = document.getElementsByName('status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tipe(source) {
    checkboxes = document.getElementsByName('tipe[]');
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/izin/index.blade.php ENDPATH**/ ?>