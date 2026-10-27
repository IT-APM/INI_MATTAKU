<?php $__env->startSection('title', 'Attendance Recap'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        button.dt-button {
            float: none;
            text-align: center;
            color: black;
            background: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3>Attendance Recap</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Attendance</li>
    <li class="breadcrumb-item active">Recap</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- State saving Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="<?php echo e(route('recap_attendance.index')); ?>" method="get"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Tanggal Dari</label>
                                        <br/><input class="form-control" name="date_from" id="date_from" type="date" required="" value="<?php echo e($date_from); ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Tanggal Sampai</label>
                                        <br/><input class="form-control" name="date_to" id="date_to" type="date" required="" value="<?php echo e($date_to); ?>">
                                    </div>
                                    
                                        <div class="col-sm-3">
                                            <label class="h6">Aktif/Non Aktif</label>&nbsp;<input type="checkbox" onClick="toggle_is_aktif(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="is_aktif">
                                                <?php $__currentLoopData = $is_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="is_aktif[]" name="is_aktif[]" type="checkbox" value="<?php echo e($data['id']); ?>" <?php if(in_array($data['id'], $arr_is_aktif)): ?> checked <?php endif; ?>><?php echo e($data['is_aktif']); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                    
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                    <div class="row mt-2">
                                        <div class="col-sm-3">
                                            <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="kode_perusahaan">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Direktorat</label>&nbsp;<input type="checkbox" onClick="toggle_divisi(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="kode_divisi">
                                                <div id="div_kode_divisi">
                                                    <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_divisi)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Divisi</label>&nbsp;<input type="checkbox" onClick="toggle_kode_departemen(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="kode_departemen">
                                                <div id="div_kode_departemen">
                                                    <?php $__currentLoopData = $departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input class="checkbox_animated" id="kode_departemen[]" name="kode_departemen[]" type="checkbox" onClick="get_kode_departemen()" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="h6">Departemen</label>&nbsp;<input type="checkbox" onClick="toggle_kode_sub_departemen(this)" /> Check All
                                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                            <label class="d-block" for="kode_sub_departemen">
                                                <div id="div_kode_sub_departemen">
                                                    <?php $__currentLoopData = $sub_departemen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input class="checkbox_animated" id="kode_sub_departemen[]" name="kode_sub_departemen[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_sub_departemen)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br/>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Working Group</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Level</th>
                                        <th>Departemen</th>
                                        <th>Email Karyawan</th>
                                        <th>Nama Layer 1</th>
                                        <th>Email Layer 1</th>
                                        <th>Jumlah Hari Kerja</th>
                                        <th>Jumlah Kehadiran</th>
                                        <th>Persentase Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$no); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($date_from)->format('d-m-Y')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($date_to)->format('d-m-Y')); ?></td>
                                            <td><?php echo e(App\Models\Karyawan::where(['nik' => $data['nik']])->first()->_wg->wg_name); ?></td>
                                            <td><?php echo e($data['nik']); ?></td>
                                            <td><?php echo e(App\Models\Karyawan::where(['nik' => $data['nik']])->first()->nama); ?></td>
                                            <td><?php echo e(App\Models\Karyawan::where(['nik' => $data['nik']])->first()->_level->level); ?></td>
                                            <td><?php echo e(App\Models\Karyawan::where(['nik' => $data['nik']])->first()->_subdepartemen->nama); ?></td>
                                            <td><?php echo e(App\Models\Karyawan::where(['nik' => $data['nik']])->first()->email); ?></td>
                                            <?php
                                                $layer1 = App\Models\Karyawan::where(['nik' => $data['nik']])->first()->layer1;
                                            ?>
                                            <td><?php if(!empty($layer1)): ?><?php echo e(App\Models\Karyawan::where(['nik' => $layer1])->first()->nama); ?><?php endif; ?></td>
                                            <td><?php if(!empty($layer1)): ?><?php echo e(App\Models\Karyawan::where(['nik' => $layer1])->first()->email); ?><?php endif; ?></td>
                                            <td><?php echo e($data['arr_base']); ?></td>
                                            <td><?php echo e($data['arr_clock']); ?></td>
                                            <td><?php echo e(round($data['persentase'])); ?> %</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- State saving Ends-->
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
function get_kode_perusahaan() {
    getDivisi();
}
// 
function get_kode_divisi() {
    getDepartemen();
}
// 
function get_kode_departemen() {
    getSubDepartemen();
}
// 
function toggle_perusahaan(source) {
    checkboxes = document.getElementsByName('kode_perusahaan[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDivisi();
}
// 
function toggle_divisi(source) {
    checkboxes = document.getElementsByName('kode_divisi[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getDepartemen();
}
// 
function toggle_kode_departemen(source) {
    checkboxes = document.getElementsByName('kode_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
    getSubDepartemen();
}
// 
function toggle_kode_sub_departemen(source) {
    checkboxes = document.getElementsByName('kode_sub_departemen[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
function getDivisi() {
    var _kode_perusahaan = [];
    $('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    $('#div_kode_divisi').empty();
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    // AJAX request 
    $.ajax({
        url: "<?php echo e(route('getDivisi')); ?>" + "/" + _kode_perusahaan,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_divisi[]' name='kode_divisi[]' type='checkbox' onClick='get_kode_divisi()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_divisi").append(option);
                }
            }
        }
    });
}
// 
function getDepartemen() {
    var _kode_divisi = [];
    $('input[id="kode_divisi[]"]:checked').each(function() {
        _kode_divisi.push($(this).val());
    });
    $('#div_kode_departemen').empty();
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "<?php echo e(route('getDepartemen')); ?>" + "/" + _kode_divisi,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_departemen[]' name='kode_departemen[]' type='checkbox' onClick='get_kode_departemen()' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_departemen").append(option);
                }
            }
        }
    });
}
// 
function getSubDepartemen() {
    var _kode_departemen = [];
    $('input[id="kode_departemen[]"]:checked').each(function() {
        _kode_departemen.push($(this).val());
    });
    $('#div_kode_sub_departemen').empty();
    $.ajax({
        url: "<?php echo e(route('getSubDepartemen')); ?>" + "/" + _kode_departemen,
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var len = 0;
            if (response != null) {
                len = response.length;
            }
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    var option = "<input class='checkbox_animated' id='kode_sub_departemen[]' name='kode_sub_departemen[]' type='checkbox' value='" + id + "'> " + nama + "<br/>";
                    $("#div_kode_sub_departemen").append(option);
                }
            }
        }
    });
}
// 
function toggle_is_aktif(source) {
    checkboxes = document.getElementsByName('is_aktif[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
// 
$('#_datatable').DataTable({
    // dom: 'lBfrtip',
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'rt>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "lengthMenu": [
        [50, 100, 200, 500, -1],
        [50, 100, 200, 500, "All"]
    ]
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/attendance/recap/index.blade.php ENDPATH**/ ?>