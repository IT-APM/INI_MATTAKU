
<?php $__env->startSection('title', 'Basic DataTables'); ?>

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
<h3>Data Master Karyawan</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item active">Karyawan</li>
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
                    <a href="<?php echo e(route('karyawan.create')); ?>" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | 
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | 
                    <a href="<?php echo e(route('karyawancopy.create')); ?>" class="btn btn-success" type="button"><i class="fa fa-copy" aria-hidden="true"></i> Copy data</a> | 
                    <a href="<?php echo e(route('karyawan.upload_data', 0)); ?>" class="btn btn-info" type="button"><i class="fa fa-upload" aria-hidden="true"></i> Upload data</a> | 
                    <a href="<?php echo e(route('karyawan.download_allphoto', 0)); ?>" class="btn btn-info" type="button"><i class="fa fa-download" aria-hidden="true"></i> Semua Foto</a> | 
                    <a href="<?php echo e(route('karyawan.bulanan', 0)); ?>" class="btn btn-outline-primary" type="button"><i class="fa fa-users" aria-hidden="true"></i> Perubahan bulanan</a>
                    <div class="collapse" id="collapse_filter">
                        <form class="m-4" action="<?php echo e(route('karyawan.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="h6" for="nama">Karyawan</label>
                                    <br/><input name="nama" id="nama" class="form-control" type="text" value="<?php echo e($nama); ?>">
                                    <br/><label class="h6" for="layer1">Layer 1</label>
                                    <br/><input name="layer1" id="layer1" class="form-control" type="text" value="<?php echo e($layer1); ?>">
                                    <br/><label class="h6" for="layer2">Layer 2</label>
                                    <br/><input name="layer2" id="layer2" class="form-control" type="text" value="<?php echo e($layer2); ?>">
                                    <br/><label class="h6" for="layer3">Layer 3</label>
                                    <br/><input name="layer3" id="layer3" class="form-control" type="text" value="<?php echo e($layer3); ?>">
                                    <br/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="is_aktif">
                                        <?php $__currentLoopData = $is_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="<?php echo e($data['id']); ?>" <?php if(in_array($data['id'], $arr_is_aktif)): ?> checked <?php endif; ?>> <?php echo e($data['is_aktif']); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                    <label class="h6">Status Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_kode_status(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_status">
                                        <?php $__currentLoopData = $kode_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="kode_status[]" name="kode_status[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_kode_status)): ?> checked <?php endif; ?>> <?php echo e($data->status_karyawan); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                    <label class="h6">Grup Kerja</label>&nbsp;<input type="checkbox" onClick="toggle_wg_id(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="wg_id">
                                        <?php $__currentLoopData = $wg_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="wg_id[]" name="wg_id[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_wg_id)): ?> checked <?php endif; ?>> <?php echo e($data->wg_name); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_kode_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_perusahaan">
                                        <?php $__currentLoopData = $kode_perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_kode_perusahaan)): ?> checked <?php endif; ?>> <?php echo e($data->nama); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                    <label class="h6">Level</label>&nbsp;<input type="checkbox" onClick="toggle_kode_level(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_level">
                                        <?php $__currentLoopData = $kode_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="kode_level[]" name="kode_level[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_kode_level)): ?> checked <?php endif; ?>> <?php echo e($data->level); ?><br/>
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
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Layer 1</th>
                                    <th>Layer 2</th>
                                    <th>Layer 3</th>
                                    <th>Level</th>
                                    <th>Jabatan</th>
                                    <th>Grup Kerja</th>
                                    <th>Status Kerja</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Project</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!-- <?php $layer_1 = app('App\Http\Controllers\KaryawanController'); ?>
                                <?php $layer_2 = app('App\Http\Controllers\KaryawanController'); ?>
                                <?php $layer_3 = app('App\Http\Controllers\KaryawanController'); ?> -->
                                <!-- $arr = json_decode($layer_1::getLayer1($data->layer1), true) -->
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php if($data->is_aktif == 1): ?> <span class="badge badge-success">Aktif</span> <?php else: ?> <span class="badge badge-danger">Non Aktif</span> <?php endif; ?></td>
                                    <!-- <td><img class="img-30 me-2" src="<?php echo e(asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg')); ?>" alt="#"></td> -->
                                    <!-- <td><img class="img-50 me-2" src="<?php echo e(asset('assets/images/logo/logo_2.jpg')); ?>" alt="#"></td> -->
                                    <td>
                                        <?php if(file_exists(public_path().'/assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg')): ?>
                                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg')); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/'.$data->nik.'/'.$data->nik.'.jpg')); ?>" alt="user"></a>
                                        <?php else: ?>
                                            <a href="<?php echo e(asset('assets/images/matta/karyawan/user.png')); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo e(asset('assets/images/matta/karyawan/user.png')); ?>" alt="user"></a>
                                        <?php endif; ?>
                                    </td>
                                    <!-- <td><?php echo e($data->nik); ?></td> -->
                                    <td><?php echo e($data->nik); ?></td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <td><?php echo e($data->email); ?></td>
                                    <td><?php echo e($data->_perusahaan->nama); ?></td>
                                    <td><?php echo e($data->_divisi->nama); ?></td>
                                    <td><?php echo e($data->_departemen->nama); ?></td>
                                    <td><?php echo e($data->_subdepartemen->nama); ?></td>
                                    <td><?php if(!empty($data->_layer1)): ?> <?php echo e($data->_layer1->nama); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->_layer2)): ?> <?php echo e($data->_layer2->nama); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->_layer3)): ?> <?php echo e($data->_layer3->nama); ?> <?php endif; ?></td>
                                    <td><?php echo e($data->_level->level); ?></td>
                                    <td><?php if(!empty($data->_jabatan->jabatan)): ?> <?php echo e($data->_jabatan->jabatan); ?> <?php endif; ?></td>
                                    <td><?php echo e($data->_wg->wg_name); ?></td>
                                    <td><?php echo e($data->_status->status_karyawan); ?></td>
                                    <td><?php if(!empty($data->tanggal_bergabung)): ?> <?php echo e(\Carbon\Carbon::parse($data->tanggal_bergabung)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->tanggal_berakhir)): ?> <?php echo e(\Carbon\Carbon::parse($data->tanggal_berakhir)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <!-- <td><?php echo e($layer_1::getLayer1($data->layer1)); ?></td>
                                        <td><?php echo e($layer_1::getLayer2($data->layer2)); ?></td>
                                        <td><?php echo e($layer_1::getLayer3($data->layer3)); ?></td> -->
                                    <td><?php echo e($data->_findim_project->project_id); ?>-<?php echo e($data->_findim_project->project_desc); ?></td>
                                    <td>
                                        <!-- <a href="#" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>&nbsp; -->
                                        <form action="<?php echo e(route('karyawan.destroy', Crypt::encrypt($data->id))); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <!-- <a href="<?php echo e(route('plafon.edit', $data->id)); ?>"><i class="icon-pencil-alt"></i></a>&nbsp; -->
                                            <!-- <li class="delete"><a href="#" onclick="return confirm('Yakin mau dihapus?')"><i class="icon-trash"></i></a></li> -->
                                            <!-- <a onClick="return confirm('Are you sure want to delete this data?')"><i class="icon-trash"></i></a> -->
                                            <!-- <a href="#"><i class="icon-trash show_confirm"></i></a> -->
                                            <!-- <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button> -->
                                            <ul class="action">
                                                <li class="edit"><a href="<?php echo e(route('karyawan.edit', Crypt::encrypt($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Perusahaan</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Layer 1</th>
                                    <th>Layer 2</th>
                                    <th>Layer 3</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> -->
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
function toggle_is_aktif(source) {
    checkboxes = document.getElementsByName('is_aktif[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_status(source) {
    checkboxes = document.getElementsByName('kode_status[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_wg_id(source) {
    checkboxes = document.getElementsByName('wg_id[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_kode_level(source) {
    checkboxes = document.getElementsByName('kode_level[]');
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta_dev\resources\views/master/karyawan/index.blade.php ENDPATH**/ ?>