<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
        <div class="col-sm-12">
            <?php if(Session::has('msg')): ?>
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6><?php echo e(session('msg')); ?></h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="row">
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        
                        <div class="card-header">
                            <h5>Pengaju</h5>
                        </div>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%"><?php echo e($pengaju['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($pengaju['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($pengaju['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <div class="card-header">
                            <h5>Peserta</h5>
                        </div>
                        <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%"><?php echo e($value['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($value['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($value['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        
                        <div class="card-header">
                            <h5>Atasan Peserta</h5>
                        </div>
                        <?php $__currentLoopData = $arrAtasanPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>" alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td width="60%"><?php echo e($value['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($value['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($value['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat/Divisi/Departemen</td>
                                        <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </div>
                </div>
                <!--  -->
                
                <!--  -->
                <div class="col-sm-6">
                    <div class="card">
                        
                        <div class="card-header">
                            
                            <h5>Pelatihan <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?> <a href="<?php echo e(route('pelatihan.show', [(new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id), 'view' => (new App\Http\Controllers\OpenDataController)->encryptWithKey('edit'), 'back_url' => url()->full()])); ?>" data-toggle="tooltip" title="Edit"><i class="icon-pencil-alt"></i></a> <?php endif; ?></h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="40%">Pengajuan Pelatihan ke</td>
                                        <td width="60%"><?php echo e($trx->ke); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td><?php echo e($trx->tahun); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>
                                            <?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <?php if(!empty($trx->int_ext)): ?>
                                    <tr>
                                        <td>Mode</td>
                                        <td>
                                            <?php $__currentLoopData = $int_ext; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($key == $trx->int_ext): ?> <?php echo e($value); ?> <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Judul</td>
                                        <td><?php echo e($trx->judul); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Penyelenggara</td>
                                        <td><?php echo e($trx->penyelenggara); ?></td>
                                    </tr>
                                    <?php
                                    // $tanggal_range = [];
                                    // if(!empty($trx->tanggal_range)){
                                        $tanggal_range = json_decode($trx->tanggal_range, true);
                                        $formatted_ranges = array_map(function($range) {
                                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                                        }, $tanggal_range);
                                    // }
                                    ?>
                                    <tr>
                                        <td>Tanggal Pelaksanaan</td>
                                        
                                        <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td><?php echo e($trx->tempat); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>Rp. <?php echo e(number_format($trx->biaya, 0)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Benefit</td>
                                        <td>
                                            <?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($value->benefit); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td><?php echo e($trx->_anggaran->anggaran); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td><?php echo $trx->keterangan; ?></td>
                                    </tr>
                                    <?php $filePath = public_path('assets/pelatihan/' . $trx->lampiran); ?>
                                    <tr>
                                        <td>Lampiran</td>
                                        <td>
                                            <?php if(!empty($trx->lampiran) && file_exists($filePath)): ?>
                                                <a href="<?php echo e(asset('assets/pelatihan/' . $trx->lampiran)); ?>" target="_blank">Lihat Lampiran</a>
                                            <?php else: ?>
                                                Tidak ada lampiran
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Catatan</td>
                                        <td><?php echo $trx->catatan; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div class="card">
                        
                        <div class="card-header">
                            <h5>History</h5>
                        </div>
                        <div class="card-body notification">
                            <ul>
                                <?php
                                    // status
                                    // 1=Submit by Requestor
                                    // 2=Approve by L1 Requestor
                                    // 5=Approve by L1 Participants
                                    // 10=Approve by Reviewer 1
                                    // 14=Approve by Reviewer 2
                                    // 11=Done by HC
                                    $remains = [];
                                    if(!empty($trx->reviewer2)){
                                        array_push($remains, [14=>$trx->reviewer2]);
                                    }
                                    if(!empty($trx->reviewer1)){
                                        array_push($remains, [10=>$trx->reviewer1]);
                                    }
                                    foreach(explode(';', $trx->atasan_peserta) as $value){
                                        array_push($remains, [5=>$value]);
                                    }
                                    array_push($remains, [2=>$trx->atasan_pengaju]);
                                ?>
                                <?php $__currentLoopData = $remains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $remain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($history->where('id_status', $key)->where('nik_pic', $value)->isEmpty()): ?>
                                    <li class="d-flex">
                                        <div class="activity-dot-dark"></div>
                                        <div class="ms-3">
                                            <h6 class="f-light" style="color: #adb5bd;"><?php echo e(App\Models\PelatihanStatus::find($key)->status_pengajuan); ?></h6>
                                            <p class="f-light" style="color: #adb5bd;">Oleh <?php echo e(App\Models\Karyawan::where('nik',$value)->first()->nama); ?> / <?php echo e($value); ?></p>
                                        </div>
                                    </li>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <ul>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <?php if($loop->first): ?>
                                    <div class="activity-dot-success"></div>
                                    <?php elseif($loop->last): ?>
                                    <div class="activity-dot-warning"></div>
                                    <?php else: ?>
                                    <div class="activity-dot-primary"></div>
                                    <?php endif; ?>
                                    <div class="ms-3">
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background"><?php echo e($_history->created_at); ?></span></p>
                                        <h6><?php echo e($_history->_status->status_pengajuan); ?><span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo e(route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($trx->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo method_field('PATCH'); ?>
                            <?php echo csrf_field(); ?>
                            
                                    <label class="form-label" for="status_pengajuan">Status Pengajuan</label>
                                    <select class="form-select" id="status_pengajuan" name="status_pengajuan" required="">
                                        <option selected="" disabled="" value="">Pilih...</option>
                                        <?php $__currentLoopData = $status_pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->id); ?>" <?php if($data->id == $trx->status_pengajuan): ?> selected <?php endif; ?> ><?php echo e($data->status_pengajuan); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                
                            <button class="btn btn-success mt-2 mb-2" type="submit">Update</button> | <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-warning mt-2 mb-2" type="button">Back</a>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <!--  -->
                
            </div>
        </div>
    
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
  if (node.value == '')
    empty_val = true;
  node.type = 'number';
  if (!empty_val)
    node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
}
// 
function use_text(node) {
  var empty_val = false;
  const value = Number(node.value);
  if (node.value == '')
    empty_val = true;
  node.type = 'text';
  if (!empty_val)
    node.value = value.toLocaleString('en');  // or other formatting
}
// 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/view.blade.php ENDPATH**/ ?>