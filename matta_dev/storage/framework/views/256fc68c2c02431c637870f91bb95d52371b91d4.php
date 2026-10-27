<?php $__env->startSection('title', 'Formulir Reimbursement'); ?>

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
<h3>Formulir Reimbursement</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Reimbursement</li>
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
                            <select class="form-select js-example-basic-single col-sm-12" id="nik_karyawan" name="nik_karyawan">
                                <option value="0">Pilih Karyawan...</option>
                                <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e(Crypt::encrypt($data->nik)); ?>"><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php
                        $start_date = '2025-12-16';
                        $end_date = '2026-01-01';
                    ?>
                    <?php if($isPlafon): ?>
                    
                    
                    <?php if(\Carbon\Carbon::now()->toDateString() >= $start_date && \Carbon\Carbon::now()->toDateString() <= $end_date): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i class="fa fa-plus" aria-hidden="true"></i> Add new</button>
                    <?php else: ?>
                    <a class="btn btn-primary" type="button" onclick="PilihKaryawan()"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> | 
                    <?php endif; ?>
                    <?php endif; ?>
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                    
                    <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-body"> 
                              <div class="modal-toggle-wrapper">  
                                <ul class="modal-img ">
                                  <li class="text-center"><img src="<?php echo e(asset('assets/images/gif/danger.gif')); ?>" alt="error" width="200" height="200"></li>
                                </ul>
                                <div class="alert alert-danger">
                                    
                                    
                                    <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2025, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal <?php echo e(\Carbon\Carbon::parse($start_date)->translatedFormat('d F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($end_date)->translatedFormat('d F Y')); ?> dan akan dibuka kembali pada <?php echo e(\Carbon\Carbon::parse($end_date)->addDay()->translatedFormat('d F Y')); ?>.<br/>Terima kasih atas perhatian dan kerja samanya.</span>
                                </div>
                                <button class="btn btn-danger d-flex m-auto" type="button" data-bs-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    
                    <div class="collapse" id="collapse_filter">
                        <form class="mt-2 mb-2" action="<?php echo e(route('reimburse.index')); ?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox" onClick="toggle_perusahaan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="kode_perusahaan">
                                        <?php $__currentLoopData = $perusahaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input class="checkbox_animated" id="kode_perusahaan[]" name="kode_perusahaan[]" type="checkbox" onClick="get_kode_perusahaan()" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_perusahaan)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br />
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
                                                    <input class="checkbox_animated" id="kode_divisi[]" name="kode_divisi[]" type="checkbox" onClick="get_kode_divisi()" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_divisi)): ?> checked <?php endif; ?>><?php echo e($data->nama); ?><br />
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="h6" for="nama">Bulan-Tahun</label>
                                    <div>
                                        <input name="bulan_tahun_dari" id="bulan_tahun_dari" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_dari); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                    sampai
                                    <div>
                                        <input name="bulan_tahun_sampai" id="bulan_tahun_sampai" class="form-control digits" type="month" required="" value="<?php echo e($bulan_tahun_sampai); ?>">
                                        <div class="invalid-feedback txt-danger">Field is required.</div>
                                    </div>
                                </div>
                                
                                    
                                    
                                
                                <div class="col-sm-3">
                                    <label class="h6">Layanan</label>&nbsp;<input type="checkbox" onClick="toggle_layanan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="layanan">
                                        <?php $__currentLoopData = $layanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="layanan[]" name="layanan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_layanan)): ?> checked <?php endif; ?>> <?php echo e($data->layanan); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                    <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox" onClick="toggle_status_pengajuan(this)" /> Check All
                                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px;">
                                    <label class="d-block" for="status_pengajuan">
                                        <?php $__currentLoopData = $status_pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input class="checkbox_animated" id="status_pengajuan[]" name="status_pengajuan[]" type="checkbox" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, $arr_status_pengajuan)): ?> checked <?php endif; ?>> <?php echo e($data->status); ?><br/>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" id="filter" name="filter" value="true" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> | <a href="<?php echo e(route('reimburse.index')); ?>" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    
                    <?php if(\Carbon\Carbon::now()->isToday() && \Carbon\Carbon::now()->toDateString() <= $end_date): ?>
                    <div class="mt-2 alert alert-danger">
                        
                        
                        <span>Dear Insan Alita,<br/>Sehubungan dengan tutup buku tahun 2025, kami informasikan bahwa pengajuan Medical Reimbursement akan ditutup sementara mulai tanggal <?php echo e(\Carbon\Carbon::parse($start_date)->translatedFormat('d F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($end_date)->translatedFormat('d F Y')); ?> dan akan dibuka kembali pada <?php echo e(\Carbon\Carbon::parse($end_date)->addDay()->translatedFormat('d F Y')); ?>.<br/>Terima kasih atas perhatian dan kerja samanya.</span>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive mt-2">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#ID</th>
                                    <th>Action</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                    <th>Findim Sub Departemen</th>
                                    <th>Findim Departemen</th>
                                    <th>Findim Divisi</th>
                                    <th>Project</th>
                                    <th>Direktorat</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Status Karyawan</th>
                                    <th>Status Aktif</th>
                                    <th>Jabatan</th>
                                    <th>Level</th>
                                    <?php endif; ?>
                                    <th>Tahun</th>
                                    
                                    <th>Klaim Kesehatan</th>
                                    <th>Klaim Non Kesehatan</th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                    <?php
                                     $non_kesehatan = App\Models\ReimburseLayanan::all();
                                    ?>
                                    <th><?php echo e($non_kesehatan->find(2)->layanan); ?></th>
                                    <th><?php echo e($non_kesehatan->find(3)->layanan); ?></th>
                                    <th><?php echo e($non_kesehatan->find(4)->layanan); ?></th>
                                    <th><?php echo e($non_kesehatan->find(5)->layanan); ?></th>
                                    <th><?php echo e($non_kesehatan->find(6)->layanan); ?></th>
                                    <th><?php echo e($non_kesehatan->find(7)->layanan); ?></th>
                                    <?php endif; ?>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->id); ?></td>
                                    <td>
                                        <?php if($data->status_pengajuan == 1): ?><a href="<?php echo e(route('reimburse.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a><?php endif; ?>
                                        &nbsp;<a href="<?php echo e(route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('detail')])); ?>" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>&nbsp;<a href="<?php echo e(route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf'), 'isHC' => true])); ?>" data-toggle="tooltip" title="PDF HC" target="_blank"><i class="fa fa-file-pdf-o" style="color:orange"></i></a><?php endif; ?>
                                        &nbsp;<a href="<?php echo e(route('reimburse.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('pdf'), 'isHC' => false])); ?>" data-toggle="tooltip" title="PDF NHC" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td><?php echo e($data->_nik->nama); ?></td>
                                    <td><?php echo e($data->_nik->nik); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                    <?php
                                        $karyawan_property = json_decode($data->karyawan_property, true);
                                        $trxD = App\Models\TrxReimburseDetail::where(['id_reimburse'=>$data->id])->get();
                                    ?>
                                    <td><?php echo e($karyawan_property['findim_subdepartemen']); ?></td>
                                    <td><?php echo e($karyawan_property['findim_departemen']); ?></td>
                                    <td><?php echo e($karyawan_property['findim_divisi']); ?></td>
                                    <td><?php echo e($karyawan_property['findim_project']); ?></td>
                                    <td><?php echo e($karyawan_property['divisi']); ?></td>
                                    <td><?php echo e($karyawan_property['dep']); ?></td>
                                    <td><?php echo e($karyawan_property['sub_dep']); ?></td>
                                    <td><?php echo e($karyawan_property['status_karyawan']); ?></td>
                                    <td><?php if(App\Models\Karyawan::where(['nik'=>$data->nik_karyawan, 'is_aktif'=>1])->exists()): ?> Aktif <?php else: ?> Non Aktif <?php endif; ?></td>
                                    <td><?php echo e($karyawan_property['jabatan']); ?></td>
                                    <td><?php echo e($karyawan_property['level']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->periode); ?></td>
                                    
                                    <td>Rp. <?php echo e(number_format($data->klaim_kesehatan, 0)); ?></td>
                                    <td>Rp. <?php echo e(number_format($data->klaim_non_kesehatan, 0)); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                                    <td><?php if(!empty($trxD->where('id_layanan', 2)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 2)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <td><?php if(!empty($trxD->where('id_layanan', 3)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 3)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <td><?php if(!empty($trxD->where('id_layanan', 4)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 4)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <td><?php if(!empty($trxD->where('id_layanan', 5)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 5)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <td><?php if(!empty($trxD->where('id_layanan', 6)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 6)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <td><?php if(!empty($trxD->where('id_layanan', 7)->first())): ?> Rp. <?php echo e(number_format($trxD->where('id_layanan', 7)->first()->jumlah_klaim, 0)); ?> <?php else: ?> Rp. 0 <?php endif; ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->_status->status); ?></td>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td><?php echo e($data->updated_at); ?></td>
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
    location.href = "<?php echo e(route('reimburse.create')); ?>"+"?nik_karyawan="+nik_karyawan;
}
function toggle_status_pengajuan(source) {
    checkboxes = document.getElementsByName('status_pengajuan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_layanan(source) {
    checkboxes = document.getElementsByName('layanan[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
function toggle_tahun(source) {
    checkboxes = document.getElementsByName('tahun[]');
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
function get_kode_perusahaan() {
    getDivisi();
}
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
}
function getDivisi() {
    var _kode_perusahaan = [];
    $('input[id="kode_perusahaan[]"]:checked').each(function() {
        _kode_perusahaan.push($(this).val());
    });
    $('#div_kode_divisi').empty();
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
$('#_datatable').DataTable({
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/index.blade.php ENDPATH**/ ?>