
<?php $__env->startSection('title', 'Formulir Auto Penilaian Karyawan'); ?>

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
<h3>Auto Formulir Penilaian Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Auto</li>
<li class="breadcrumb-item active">Formulir Penilaian Karyawan</li>
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
                    <ul class="nav nav-tabs nav-success" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-non_outsource-tab" data-bs-toggle="pill" href="#pills-non_outsource" role="tab" aria-controls="pills-non_outsource" aria-selected="false">Reguler</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-outsource-tab" data-bs-toggle="pill" href="#pills-outsource" role="tab" aria-controls="pills-outsource" aria-selected="true">Outsourcing</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-kt-tab" data-bs-toggle="pill" href="#pills-kt" role="tab" aria-controls="pills-kt" aria-selected="true">Karyawan Tetap</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-non_outsource" role="tabpanel" aria-labelledby="pills-non_outsource-tab">
                            <!--  -->
                            
                            <?php if(count($trx_non_outsource)>0): ?>
                            <form action="<?php echo e(route('penilaian_auto.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="is_outsource" name="is_outsource" value="0">
                            
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="display" id="_datatable_non_outsource">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_non_outsource(this)" /></th>
                                            <th>No</th>
                                            <th>Karyawan</th>
                                            <th>NIP</th>
                                            <th>Penilai 1</th>
                                            <th>Penilai 2</th>
                                            <th>Penilai 3</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Tanggal Berakhir</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trx_non_outsource; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><input id="id_non_outsource[]" name="id_non_outsource[]" type="checkbox" value="<?php echo e($data->id); ?>"></td>
                                            <td><?php echo e(++$no_non_outsource); ?></td>
                                            <td><?php echo e($data->_karyawan->nama); ?></td>
                                            <td><?php echo e($data->nik_karyawan); ?></td>
                                            <td><?php echo e($data->_penilai1->nama); ?></td>
                                            <td><?php if(!empty($data->nik_penilai_2)): ?> <?php echo e($data->_penilai2->nama); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->nik_penilai_3)): ?> <?php echo e($data->_penilai3->nama); ?> <?php endif; ?></td>
                                            <td><?php echo e($data->_tipe->tipe_penilaian); ?></td>
                                            <td>Not <?php echo e($data->_status->status_penilaian); ?></td>
                                            <td><?php if(!empty($data->end_date)): ?><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->end_date)->format('d-m-Y')); ?><?php endif; ?></td>
                                            <td>
                                                
                                                    <ul class="action">
                                                        <li class="edit"><a href="<?php echo e(route('penilaian_auto.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                        
                                                    </ul>
                                                
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <!--  -->
                        </div>
                        
                        <div class="tab-pane fade" id="pills-outsource" role="tabpanel" aria-labelledby="pills-outsource-tab">
                            <!--  -->
                            
                            <?php if(count($trx_outsource)>0): ?>
                            <form action="<?php echo e(route('penilaian_auto.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="is_outsource" name="is_outsource" value="1">
                            
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="display" id="_datatable_outsource">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_outsource(this)" /></th>
                                            <th>No</th>
                                            <th>Karyawan</th>
                                            <th>NIP</th>
                                            <th>Penilai 1</th>
                                            <th>Penilai 2</th>
                                            <th>Penilai 3</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Tanggal Berakhir</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trx_outsource; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><input id="id_outsource[]" name="id_outsource[]" type="checkbox" value="<?php echo e($data->id); ?>"></td>
                                            <td><?php echo e(++$no_outsource); ?></td>
                                            <td><?php echo e($data->_karyawan->nama); ?></td>
                                            <td><?php echo e($data->nik_karyawan); ?></td>
                                            <td><?php echo e($data->_penilai1->nama); ?></td>
                                            <td><?php if(!empty($data->nik_penilai_2)): ?> <?php echo e($data->_penilai2->nama); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->nik_penilai_3)): ?> <?php echo e($data->_penilai3->nama); ?> <?php endif; ?></td>
                                            <td><?php echo e($data->_tipe->tipe_penilaian); ?></td>
                                            <td>Not <?php echo e($data->_status->status_penilaian); ?></td>
                                            <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $data->end_date)->format('d-m-Y')); ?></td>
                                            <td>
                                                
                                                    <ul class="action">
                                                        <li class="edit"><a href="<?php echo e(route('penilaian_auto.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                        
                                                    </ul>
                                                
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <!--  -->
                        </div>
                        <div class="tab-pane fade" id="pills-kt" role="tabpanel" aria-labelledby="pills-kt-tab">
                            <!--  -->
                            
                            <?php if(count($trx_kt)>0): ?>
                            <form action="<?php echo e(route('penilaian_auto.store')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to execute this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="is_outsource" name="is_outsource" value="2">
                            
                            <button class="btn btn-success mt-2 mb-2" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger mt-2 mb-2" type="submit" name="submit" id="submit" value="delete"><i class="icon-trash" aria-hidden="true"></i> Delete</button>
                            
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="display" id="_datatable_kt">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" onClick="toggle_status_kt(this)" /></th>
                                            <th>No</th>
                                            <th>Karyawan</th>
                                            <th>NIP</th>
                                            <th>Penilai 1</th>
                                            <th>Penilai 2</th>
                                            <th>Penilai 3</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trx_kt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><input id="id_kt[]" name="id_kt[]" type="checkbox" value="<?php echo e($data->id); ?>"></td>
                                            <td><?php echo e(++$no_kt); ?></td>
                                            <td><?php echo e($data->_karyawan->nama); ?></td>
                                            <td><?php echo e($data->nik_karyawan); ?></td>
                                            <td><?php echo e($data->_penilai1->nama); ?></td>
                                            <td><?php if(!empty($data->nik_penilai_2)): ?> <?php echo e($data->_penilai2->nama); ?> <?php endif; ?></td>
                                            <td><?php if(!empty($data->nik_penilai_3)): ?> <?php echo e($data->_penilai3->nama); ?> <?php endif; ?></td>
                                            <td><?php echo e($data->_tipe->tipe_penilaian); ?></td>
                                            <td>Not <?php echo e($data->_status->status_penilaian); ?></td>
                                            
                                            <td>
                                                
                                                    <ul class="action">
                                                        <li class="edit"><a href="<?php echo e(route('penilaian_auto.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                        
                                                    </ul>
                                                
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <!--  -->
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
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script type="text/javascript">
// 
function toggle_status_non_outsource(source) {
    checkboxes = document.getElementsByName('id_non_outsource[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_outsource(source) {
    checkboxes = document.getElementsByName('id_outsource[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function toggle_status_kt(source) {
    checkboxes = document.getElementsByName('id_kt[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable_non_outsource').DataTable( {
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
$('#_datatable_outsource').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
$('#_datatable_kt').DataTable( {
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
// $(document).on('click', '#btn-delete', function(event) {
//     var form = $(this).closest("form");
//     event.preventDefault();
//     swal({
//             title: "Are you sure to delete this?",
//             text: "If you delete this, it will be gone for this session only.",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {
//             if (willDelete) {
//                 form.submit();
//             }
//         });
// });
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/penilaian/auto/index.blade.php ENDPATH**/ ?>