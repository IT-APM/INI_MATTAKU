
<?php $__env->startSection('title', 'Formulir Permohonan Cuti'); ?>

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
<h3>Formulir Permohonan Cuti</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Permohonan Cuti</li>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan">
                                <option value="0">Pilih Karyawan...</option>
                                <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e(Crypt::encrypt($data->nik)); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <button class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button> | <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse mt-2" id="collapse_filter">
                        <form action="<?php echo e(route('cuti.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6" for="nik_karyawan">Karyawan</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan">
                                        
                                        <option value="" <?php if("" == $_nik_karyawan): ?> selected <?php endif; ?>>Semua Karyawan</option>
                                        <?php $__currentLoopData = $nik_karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $_nik_karyawan): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if(auth()->user()->userrole == 'hc'): ?>
                                    <label class="h6 mt-2" for="nik_atasan">Layer 1</label>
                                    <select class="form-select js-example-basic-single col-sm-12" id="nik_atasan" name="nik_atasan">
                                        
                                        <option value="" <?php if("" == $_nik_atasan): ?> selected <?php endif; ?>>Semua Layer 1</option>
                                        <?php $__currentLoopData = $nik_atasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->nik); ?>" <?php if($data->nik == $_nik_atasan): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php endif; ?>
                                    
                                    <label class="h6 mt-2">Tanggal Cuti</label>
                                    <br/><input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                    sampai <input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_cuti(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_cuti">
                                        <?php $__currentLoopData = $status_cuti; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status_cuti[]" name="status_cuti[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status_cuti)): ?> checked <?php endif; ?>> <?php echo e($data->status_cuti); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <label class="h6">Tipe Cuti</label>&nbsp;<input type="checkbox" onClick="toggle_tipe_cuti(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="tipe_cuti">
                                        <?php $__currentLoopData = $tipe_cuti; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="tipe_cuti[]" name="tipe_cuti[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_tipe_cuti)): ?> checked <?php endif; ?>> <?php echo e($data->tipe_cuti); ?><br/>
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
                    
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Tipe</th>
                                    <!-- <th>Periode</th> -->
                                    <th>Karyawan</th>
                                    <th>NIP</th>
                                    <th>PIC Pengganti</th>
                                    <th>Layer 1</th>
                                    <th>Tanggal Cuti</th>
                                    <th>Jumlah Hari Cuti</th>
                                    <th>Status</th>
                                    <th>Sisa Cuti</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('cuti.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        &nbsp;<a href="<?php echo e(route('cuti.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td><?php echo e($data->_tipe->tipe_cuti); ?></td>
                                    <!-- <td><?php echo e($data->periode_cuti); ?></td> -->
                                    <td><?php echo e($data->_karyawan->nama); ?></td>
                                    <td><?php echo e($data->_karyawan->nik); ?></td>
                                    <td>
                                        <?php $__currentLoopData = explode(';', $data->pic_property); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e(json_decode($value, true)['nama']); ?> <br />
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td><?php echo e($data->_atasan->nama); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y')); ?><br />s/d<br /><?php echo e(\Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y')); ?></td>
                                    <td><?php echo e($data->jumlah_hari); ?></td>
                                    <td><?php echo e($data->_status->status_cuti); ?></td>
                                    
                                    <td>
                                        
                                        <?php if(\Carbon\Carbon::today()->between(\Carbon\Carbon::now()->year.'-01-01', \Carbon\Carbon::now()->year.'-01-31')): ?>
                                            <?php if(\Carbon\Carbon::now()->subYear()->year == $data->periode_cuti): ?>
                                                <?php echo e(\Carbon\Carbon::now()->year); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaCuti($data->nik_karyawan, \Carbon\Carbon::now()->year, $data->id)); ?>

                                                <br/><?php echo e($data->periode_cuti); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaCuti($data->nik_karyawan, $data->periode_cuti, $data->id)); ?>

                                            <?php else: ?>
                                                <?php echo e($data->periode_cuti); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaCuti($data->nik_karyawan, $data->periode_cuti, $data->id)); ?>

                                            <?php endif; ?>
                                        
                                        <?php else: ?>
                                            <?php echo e($data->periode_cuti); ?>: <?php echo e(App\Http\Controllers\TrxCutiController::getSisaCuti($data->nik_karyawan, $data->periode_cuti, $data->id)); ?>

                                        <?php endif; ?>
                                    </td>
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
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
function PilihKaryawan() {
    var nik_karyawan = "<?php echo e(Crypt::encrypt(auth()->user()->nik)); ?>";
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
    if($('#nik_karyawan').val() != '0'){
        nik_karyawan = $('#nik_karyawan').val();
    }
    <?php endif; ?>
    location.href = "<?php echo e(route('cuti.create')); ?>"+"?nik_karyawan="+nik_karyawan;
}
function toggle_status_cuti(source) {
    checkboxes = document.getElementsByName('status_cuti[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tipe_cuti(source) {
    checkboxes = document.getElementsByName('tipe_cuti[]');
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/cuti/index.blade.php ENDPATH**/ ?>