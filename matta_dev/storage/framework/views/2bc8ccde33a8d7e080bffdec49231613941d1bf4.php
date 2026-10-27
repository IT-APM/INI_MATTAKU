<?php $__env->startSection('title', 'Daftar Sertifikat'); ?>

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
<h3>Daftar Sertifikat</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Pelatihan</li>
<li class="breadcrumb-item active">Daftar Sertifikat</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    
                    
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2" action="<?php echo e(route('pelatihan_sertifikat.kadaluarsa', 0)); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggle_aktif_non(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="aktif_non">
                                        <?php $__currentLoopData = $aktif_non; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="aktif_non[]" name="aktif_non[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($key, $arr_aktif_non)): ?> checked <?php endif; ?>> <?php echo e($value); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Jenis Pelatihan</label>&nbsp;<input type="checkbox" onClick="toggle_jenis(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="jenis">
                                        <?php $__currentLoopData = $jenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="jenis[]" name="jenis[]" type="checkbox" value="<?php echo e($data->jenis); ?>" <?php if(in_array($data->jenis, $arr_jenis)): ?> checked <?php endif; ?>> <?php echo e($data->jenis); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">Mode Pelatihan</label>&nbsp;<input type="checkbox" onClick="toggle_int_ext(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="int_ext">
                                        <?php $__currentLoopData = $int_ext; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="int_ext[]" name="int_ext[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($key, $arr_int_ext)): ?> checked <?php endif; ?>> <?php echo e($value); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6">KTA</label>&nbsp;<input type="checkbox" onClick="toggle_kta(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kta">
                                        <?php $__currentLoopData = $kta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="kta[]" name="kta[]" type="checkbox" value="<?php echo e($key); ?>" <?php if(in_array($key, $arr_kta)): ?> checked <?php endif; ?>> <?php echo e($value); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('pelatihan_sertifikat.kadaluarsa', 0)); ?>" class="btn btn-info" type="button"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jenis Pelatihan</th>
                                    <th>Mode Pelatihan</th>
                                    <th>Penerbit</th>
                                    <th>Kartu Tanda Anggota</th>
                                    <th>Judul Pelatihan</th>
                                    <th>Lembaga</th>
                                    <th>Biaya</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>No Sertifikat</th>
                                    <th>No Kartu Lisensi</th>
                                    <th>No Kartu Anggota</th>
                                    <th>Tanggal Sertifikat</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            
                                            <?php if(!empty($data->id_trx)): ?>
                                            <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id_trx), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf')])); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                            <?php else: ?>
                                                
                                                
                                                <?php if(!empty($data->formulir_link)): ?>
                                                <a href="<?php echo e($data->formulir_link); ?>" data-toggle="tooltip" title="PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </td>
                                    <?php $is_status = App\Models\Karyawan::where(['nik'=>$data->nik, 'is_aktif'=>1])->exists(); ?>
                                    <td><?php if($is_status): ?> <span class="badge badge-success">Aktif</span> <?php else: ?> <span class="badge badge-danger">Non Aktif</span> <?php endif; ?></td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <td><?php echo e($data->nik); ?></td>
                                    <td><?php echo e($data->jenis); ?></td>
                                    <td><?php echo e($data->int_ext); ?></td>
                                    <td><?php echo e($data->penerbit); ?></td>
                                    <td><?php echo e($data->kta); ?></td>
                                    <td><?php echo e($data->judul); ?><?php if(!empty($data->int_ext)): ?><br/> (<?php echo e($data->int_ext); ?>) <?php endif; ?></td>
                                    <td><?php echo e($data->lembaga); ?></td>
                                    <td>Rp.<?php echo e(number_format($data->biaya, 0)); ?></td>
                                    <td>
                                        <?php if(!empty($data->tanggal_mulai) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_mulai)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_mulai)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($data->tanggal_akhir) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_akhir)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_akhir)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($data->nomor_sertifikat); ?></td>
                                    <td><?php echo e($data->no_kartu_lisensi); ?></td>
                                    <td><?php echo e($data->no_kartu_anggota); ?></td>
                                    <td>
                                        <?php if(!empty($data->tanggal_sertifikat) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_sertifikat)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_sertifikat)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($data->tanggal_kadaluarsa) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data->tanggal_kadaluarsa)): ?>
                                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal_kadaluarsa)->format('d-m-Y')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php if(!empty($data->sertifikat_link)): ?><a href="<?php echo e($data->sertifikat_link); ?>" data-toggle="tooltip" title="Sertifikat" target="_blank"><i class="fa fa-file-text-o"></i></a><?php endif; ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-warning mt-2" type="button">Back</a>
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
function toggle_aktif_non(source) {
    checkboxes = document.getElementsByName('aktif_non[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_jenis(source) {
    checkboxes = document.getElementsByName('jenis[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_int_ext(source) {
    checkboxes = document.getElementsByName('int_ext[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kta(source) {
    checkboxes = document.getElementsByName('kta[]');
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/index_sertifikat_kadaluarsa.blade.php ENDPATH**/ ?>