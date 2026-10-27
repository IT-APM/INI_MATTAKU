<?php $__env->startSection('title', 'Formulir Rekomendasi Karyawan'); ?>

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
<h3>Formulir Rekomendasi Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Rekomendasi Karyawan</li>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_atasan" name="nik_atasan">
                                <option value="0">Pilih Layer 1...</option>
                                <?php $__currentLoopData = $layer1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e(Crypt::encrypt($data->nik)); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    
                    
                    <a class="btn btn-primary" type="button" onclick="pilih_atasan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('rekomendasi.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Tanggal</label>
                                    <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="<?php echo e($tanggal_mulai); ?>">
                                    sampai
                                    <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="<?php echo e($tanggal_akhir); ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_rekomendasi(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_rekomendasi">
                                        <?php $__currentLoopData = $status_rekomendasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status_rekomendasi[]" name="status_rekomendasi[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status_rekomendasi)): ?> checked <?php endif; ?>> <?php echo e($data->status_pengajuan); ?><br/>
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
                    <br/><br/>
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Nama Karyawan</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Pengaju</th>
                                    <th>NIP Pengaju</th>
                                    <th>Direktorat Pengaju</th>
                                    <th>Divisi Pengaju</th>
                                    <th>Departemen Pengaju</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Approval 3</th>
                                    <th>Terhitung Tanggal</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Closed at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                    $atasan_property = json_decode($data['atasan_property'], true);
                                    ?>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('rekomendasi.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="<?php echo e(route('rekomendasi.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                        &nbsp;<a href="<?php echo e(route('rekomendasi.edit', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id)])); ?>" data-toggle="tooltip" title='Update'><i class="icon-pencil-alt"></i></a>
                                        <?php endif; ?>

                                    </td>
                                    <td><?php echo e($data->_karyawan->nama); ?></td>
                                    <td><?php echo e($data->_karyawan->nik); ?></td>
                                    <td><?php echo e($data->_atasan->nama); ?></td>
                                    <td><?php echo e($data->_atasan->nik); ?></td>
                                    <td><?php echo e($atasan_property['divisi']); ?></td>
                                    <td><?php echo e($atasan_property['dep']); ?></td>
                                    <td><?php echo e($atasan_property['sub_dep']); ?></td>
                                    <td><?php echo e($data->_reviewer1->nama); ?></td>
                                    <td><?php if(!empty($data->nik_reviewer2)): ?> <?php echo e($data->_reviewer2->nama); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->nik_reviewer3)): ?> <?php echo e($data->_reviewer3->nama); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->terhitung_tanggal)): ?> <?php echo e(\Carbon\Carbon::parse($data->terhitung_tanggal)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td><?php echo e($data->_status->status_pengajuan); ?></td>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td><?php if($data->status_rekomendasi==5 || $data->status_rekomendasi==10): ?> <?php echo e($data->updated_at); ?> <?php endif; ?></td>
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
<script type="text/javascript">
function pilih_atasan() {
    var nik_atasan = "<?php echo e(Crypt::encrypt(auth()->user()->nik)); ?>";
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
    if($('#nik_atasan').val() != '0'){
        nik_atasan = $('#nik_atasan').val();
    }
    <?php endif; ?>
    location.href = "<?php echo e(route('rekomendasi.create')); ?>"+"?nik_atasan="+nik_atasan;
}
function toggle_status_rekomendasi(source) {
    checkboxes = document.getElementsByName('status_rekomendasi[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik_atasan(source) {
    checkboxes = document.getElementsByName('nik_atasan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_nik_karyawan(source) {
    checkboxes = document.getElementsByName('nik_karyawan[]');
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/rekomendasi/index.blade.php ENDPATH**/ ?>