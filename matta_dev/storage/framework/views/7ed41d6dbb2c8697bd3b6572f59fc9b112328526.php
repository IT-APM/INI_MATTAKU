<?php $__env->startSection('title', 'Attendance'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
        float: none;
        text-align: center;
        color: black;
        background: white;
    }
    .filter-box {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        padding: 10px;
        border-radius: 4px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Attendance Log</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Attendance</li>
<li class="breadcrumb-item active">Attendance Log</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="<?php echo e(route('attendance.index')); ?>" method="get">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="h6">Date</label>
                                        <br/>From <input class="form-control" name="date_from" id="date_from" type="date" required value="<?php echo e($date_from); ?>">
                                        To <input class="form-control" name="date_to" id="date_to" type="date" required value="<?php echo e($date_to); ?>">
                                        <label class="h6 mt-2">Karyawan</label>
                                        <select class="form-select js-example-basic-single col-sm-12" id="str_att_nik_nama" name="str_att_nik_nama">
                                            <option selected disabled value="">Pilih...</option>
                                            <option value="">Semua</option>
                                            <?php $__currentLoopData = $att_nik_nama; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($data->att_nik); ?>" <?php if($data->att_nik == $str_att_nik_nama): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->att_nik); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Action</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_type[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                <?php $__currentLoopData = $att_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" name="att_type[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_type)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                        <label class="h6">Status</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_status[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                <?php $__currentLoopData = $att_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" name="att_status[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_status)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                        <label class="h6">Type</label>&nbsp;<input type="checkbox" onClick="toggleCheck('att_data_src[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                <?php $__currentLoopData = $att_data_src; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" name="att_data_src[]" type="checkbox" value="<?php echo e($data); ?>" <?php if(in_array($data, $arr_att_data_src)): ?> checked <?php endif; ?>> <?php echo e($data); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Working Group</label>&nbsp;<input type="checkbox" onClick="toggleCheck('wg_id[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                <?php $__currentLoopData = $wg_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" name="wg_id[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_wg_id)): ?> checked <?php endif; ?>> <?php echo e($data->wg_name); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="h6">Company</label>&nbsp;<input type="checkbox" onClick="toggleCheck('perusahaan[]', this)" /> Check All
                                        <div class="filter-box">
                                            <label class="d-block">
                                                <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input class="checkbox_animated" name="perusahaan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>> <?php echo e($data->nama); ?><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <ul class="nav nav-tabs nav-primary mt-2" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="rawdata-tab" data-bs-toggle="pill" href="#rawdata" role="tab" aria-controls="rawdata" aria-selected="true">Raw Data</a></li>
                            <li class="nav-item"><a class="nav-link" id="duration-tab" data-bs-toggle="pill" href="#duration" role="tab" aria-controls="duration" aria-selected="false">Duration</a></li>
                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            
                            <div class="tab-pane fade show active" id="rawdata" role="tabpanel" aria-labelledby="rawdata-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display" id="raw_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Timestamp</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>ID_FP</th>
                                                <th>Perusahaan</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Lokasi</th>
                                                <th>Working Group</th>
                                                <th>Action</th>
                                                <th>Longitude</th>
                                                <th>Latitude</th>
                                                <th>Address</th>
                                                <th>Map</th>
                                                <th>Status</th>
                                                <th>Delay</th>
                                                <th>Type</th>
                                                <th>Source</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $att; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                // tanpa query: relasi sudah di-eager load di controller
                                                $att_property = json_decode($data->att_property, true) ?? [];
                                                $karyawan     = $data->_att_nik;

                                                $delay = '';
                                                if ($data->att_type == 'Clock-in' && $data->att_status == 'Late-in' && $data->att_data_src == 'Regular') {
                                                    $clockin = $data->wg_id == 1 ? '09:00:00' : ($data->wg_id > 1 ? ($wdClockin[$data->att_wd_ot_id] ?? null) : null);
                                                    if ($clockin) {
                                                        $totalDetik = (int) abs(\Carbon\Carbon::createFromFormat('H:i:s', \Carbon\Carbon::parse($data->att_timestamp)->format('H:i:s'))
                                                            ->diffInSeconds(\Carbon\Carbon::createFromFormat('H:i:s', \Carbon\Carbon::parse($clockin)->format('H:i:s'))));
                                                        $delay = gmdate('H:i:s', $totalDetik);
                                                    }
                                                } elseif ($data->att_type == 'Clock-out' && $data->att_status == 'Early-out' && $data->att_data_src == 'Regular' && $data->wg_id == 1) {
                                                    $delay = $selisihEO[$data->att_nik.'|'.$data->att_date] ?? '';
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data->att_date)->format('d-m-Y')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data->att_timestamp)->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e($karyawan?->nama); ?></td>
                                                <td><?php echo e($data->att_nik); ?></td>
                                                <td><?php if(!empty($data->id_fp_trx)): ?> <?php echo e($karyawan?->id_fp); ?> <?php endif; ?></td>
                                                <td><?php echo e($att_property['perusahaan'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['divisi'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['dep'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['sub_dep'] ?? ''); ?></td>
                                                <td><?php echo e($karyawan?->alamat_kantor); ?></td>
                                                <td><?php echo e($att_property['wg_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php if($data->att_type == 'Clock-in'): ?>
                                                        <span class="badge rounded-pill badge-light-primary"><?php echo e($data->att_type); ?></span>
                                                    <?php elseif($data->att_type == 'Clock-out'): ?>
                                                        <span class="badge rounded-pill badge-light-success"><?php echo e($data->att_type); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-pill badge-light-warning"><?php echo e($data->att_type); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($data->att_long); ?></td>
                                                <td><?php echo e($data->att_lat); ?></td>
                                                <td><?php echo e($data->att_address); ?></td>
                                                <td><?php if(!empty($data->att_long) && !empty($data->att_lat)): ?><a href="https://www.google.com/maps/place/<?php echo e($data->att_lat); ?>,<?php echo e($data->att_long); ?>" target="_blank"><i class="fa fa-map-marker" style="font-size:25px;color:red;"></i></a><?php endif; ?></td>
                                                <td>
                                                    <?php if($data->att_status == 'Book-in' || $data->att_status == 'Book-out'): ?>
                                                        <span class="badge badge-success"><?php echo e($data->att_status); ?></span>
                                                    <?php elseif($data->att_status == 'Leave'): ?>
                                                        <span class="badge badge-warning"><?php echo e($data->att_status); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger"><?php echo e($data->att_status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($delay); ?></td>
                                                <td>
                                                    <?php if($data->att_data_src == 'Regular'): ?>
                                                        <span class="badge badge-success"><?php echo e($data->att_data_src); ?></span>
                                                    <?php elseif($data->att_data_src == 'Overtime'): ?>
                                                        <span class="badge badge-primary"><?php echo e($data->att_data_src); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><?php echo e($data->att_data_src); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(!empty($data->id_fp_trx) && $data->_fp_trx): ?>
                                                        <span class="badge <?php echo e($data->_fp_trx->id_mesin == 1 ? 'badge-primary' : 'badge-info'); ?>">FINGER-<?php echo e($data->_fp_trx->id_mesin); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">MATTA</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="duration" role="tabpanel" aria-labelledby="duration-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display" id="duration_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Clock-in</th>
                                                <th>Clock-out</th>
                                                <th>Durasi</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Perusahaan</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Lokasi</th>
                                                <th>Working Group</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $duration; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $att_property = json_decode($data['att_property'], true) ?? []; ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['att_date'])->format('d-m-Y')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['Clock-in'])->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($data['Clock-out'])->format('d-m-Y H:i:s')); ?></td>
                                                <td><?php echo e($data['durasi']); ?></td>
                                                <td><?php echo e($data['nama']); ?></td>
                                                <td><?php echo e($data['att_nik']); ?></td>
                                                <td><?php echo e($att_property['perusahaan'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['divisi'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['dep'] ?? ''); ?></td>
                                                <td><?php echo e($att_property['sub_dep'] ?? ''); ?></td>
                                                <td><?php echo e($data['alamat_kantor']); ?></td>
                                                <td><?php echo e($att_property['wg_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php if($data['att_data_src'] == 'Regular'): ?>
                                                        <span class="badge badge-success"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php elseif($data['att_data_src'] == 'Overtime'): ?>
                                                        <span class="badge badge-primary"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><?php echo e($data['att_data_src']); ?></span>
                                                    <?php endif; ?>
                                                </td>
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
// Check All untuk grup checkbox
function toggleCheck(name, source) {
    document.getElementsByName(name).forEach(cb => cb.checked = source.checked);
}

const dtOptions = {
    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
         "<'row'<'col-sm-12'rt>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
    lengthMenu: [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]]
};

const tblRaw = $('#raw_datatable').DataTable(dtOptions);
const tblDur = $('#duration_datatable').DataTable(dtOptions);

// Hitung ulang lebar kolom saat tab dibuka
$('a[data-bs-toggle="pill"]').on('shown.bs.tab', () => {
    tblRaw.columns.adjust();
    tblDur.columns.adjust();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/attendance/index.blade.php ENDPATH**/ ?>