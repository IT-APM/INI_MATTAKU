
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
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.bulanan', 1)); ?>" class="btn btn-primary"><i class="fa fa-history"></i> History</a>
                    <div class="collapse mt-2 mb-2" id="collapse_filter">
                        <form action="<?php echo e(route('karyawan.bulanan', 0)); ?>" method="get" enctype="multipart/form-data">
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
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('karyawan.bulanan', 0)); ?>" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <form class="mt-2" action="<?php echo e(route('karyawan.bulanan_update')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to do this?');">
                        <?php echo csrf_field(); ?>
                        <?php if(count($layer1) > 0): ?>
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button> | <button class="btn btn-danger" type="submit" name="submit" id="submit" value="delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> | <button class="btn btn-info" type="submit" name="submit" id="submit" value="refresh"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="display nowrap" id="_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onClick="toggle_check_all(this); event.stopPropagation();" style="vertical-align:middle; margin-right:3px;"/></th>
                                        <th>No</th>
                                        <th>Periode</th>
                                        <th>NIP Layer1</th>
                                        <th>Nama Layer1</th>
                                        <th>Email Layer1</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Level</th>
                                        <th>Jabatan</th>
                                        <th>Grup Kerja</th>
                                        <th>Status Kerja</th>
                                        <th><input type="checkbox" onClick="toggle_check_cc_layer2(this); event.stopPropagation();" style="vertical-align:middle; margin-right:3px;"/> Cc Layer2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php $__currentLoopData = $layer1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_layer1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $layer1_property = json_decode($_layer1->layer1_property, true);
                                    $layer2_property = json_decode($_layer1->layer2_property, true);
                                    $mailData['title'] = 'Perubahan Data Karyawan';
                                    $mailData['filename'] = 'emails.karyawan_bulanan0';
                                    $mailData['to'] = $layer1_property['email'];
                                    $karyawan = App\Models\KaryawanBulanan::where(['layer1'=>$layer1_property['nik'],'is_berubah'=>0])->get();
                                    $karyawan = $karyawan->sortBy(function ($_data) {
                                        $nik_property = json_decode($_data->nik_property, true);
                                        return $nik_property['nama'] ?? '';
                                    })->values();
                                    $data = ['karyawan' => $karyawan, 'data' => $_layer1, 'link'=> NULL];
                                    $body = view($mailData['filename'], compact('data'));
                                    // cc email ke hco
                                    $ccEmails = explode(',', App\Models\MtrUtility::find(11)->informasi);
                                    // cc email ke layer2
                                    // array_push($ccEmails, $layer2_property['email']);
                                    ?>
                                    <tr>
                                        <td><input id="data_id[<?php echo e($_layer1->id); ?>]" name="data_id[<?php echo e($_layer1->id); ?>]" type="checkbox" value="<?php echo e($_layer1->id); ?>" style="vertical-align:middle; margin-right:3px;"></td>
                                        <td><?php echo e(++$no); ?></td>
                                        <td><?php echo e(Carbon\Carbon::parse($data['data']->tanggal)->locale('id')->isoFormat('MMMM YYYY')); ?></td>
                                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#modalDetail" class="text-primary" onclick="preview_email(this)" data-subject="<?php echo e($mailData['title']); ?>" data-to="<?php echo e($mailData['to']); ?>" data-cc="<?php echo e(implode(',', $ccEmails)); ?>" data-body="<?php echo e(base64_encode($body)); ?>"><?php echo e($layer1_property['nik']); ?></a></td>
                                        <td><?php echo e($layer1_property['nama']); ?></td>
                                        <td><?php echo e($layer1_property['email']); ?></td>
                                        <td><?php echo e($layer1_property['perusahaan']); ?></td>
                                        <td><?php echo e($layer1_property['divisi']); ?></td>
                                        <td><?php echo e($layer1_property['dep']); ?></td>
                                        <td><?php echo e($layer1_property['sub_dep']); ?></td>
                                        <td><?php echo e($layer1_property['level']); ?></td>
                                        <td><?php echo e($layer1_property['jabatan']); ?></td>
                                        <td><?php echo e($layer1_property['wg_name']); ?></td>
                                        <td><?php echo e($layer1_property['status_karyawan']); ?></td>
                                        <td><input id="cc_layer2[<?php echo e($_layer1->id); ?>]" name="cc_layer2[<?php echo e($_layer1->id); ?>]" type="checkbox" value="<?php echo e($layer2_property['email']); ?>" style="vertical-align:middle; margin-right:3px;"><?php echo e($layer2_property['nama']); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="tbl-laporan text-dark">
                    <tbody>
                        <tr>
                            <td class="text-start align-top">Subject</td>
                            <td class="text-start align-top" id="modal-subject"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">To</td>
                            <td class="text-start align-top" id="modal-to"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">Cc</td>
                            <td class="text-start align-top" id="modal-cc"></td>
                        </tr>
                        <tr>
                            <td class="text-start align-top">Body</td>
                            <td class="text-start align-top" id="modal-body"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
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
function preview_email(str) {
    let cc = str.getAttribute('data-cc');
    // Ambil <tr> parent dari link yang diklik
    const tr = str.closest('tr');
    // Cek checkbox cc_layer2 pada baris yang sama saja
    const checkbox = tr.querySelector('input[name^="cc_layer2"]');
    if (checkbox && checkbox.checked) {
        cc = cc ? cc + ',' + checkbox.value : checkbox.value;
    }
    document.getElementById('modal-subject').innerHTML = str.getAttribute('data-subject');
    document.getElementById('modal-to').innerHTML = str.getAttribute('data-to');
    // document.getElementById('modal-cc').innerHTML = str.getAttribute('data-cc');
    document.getElementById('modal-cc').innerHTML = cc;
    document.getElementById('modal-body').innerHTML = atob(str.getAttribute('data-body'));
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
});
// 
function toggle_check_all(source) {
    const checkboxes = document.querySelectorAll('input[name^="data_id"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = source.checked;
    });
}
// 
function toggle_check_cc_layer2(source) {
    const checkboxes = document.querySelectorAll('input[name^="cc_layer2"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = source.checked;
    });
}
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/master/karyawan/bulanan.blade.php ENDPATH**/ ?>