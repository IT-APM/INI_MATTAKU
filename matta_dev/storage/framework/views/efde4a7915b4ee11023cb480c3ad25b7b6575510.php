<?php $__env->startSection('title', 'Formulir Reimbursement'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Edit</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Reimbursement</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Diajukan Oleh</h5>
                </div>
                <div class="card-header">
                    <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" target="_blank"><img class="img-thumbnail" width="20%" src="<?php echo e(asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg')); ?>" alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%"><?php echo e($data['nama']); ?></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td><?php echo e($data['nik']); ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td><?php echo e($data['jabatan']); ?></td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td><?php echo e($data['unit_kerja']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6 notification">
            <div class="card height-equal">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h5 class="m-0">History</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
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
                                <h6><?php echo e($_history->_id_status->status); ?><span class="dot-notification"></span></h6>
                                <p class="f-light">Oleh <?php echo e($_history->_nik->nama); ?> / <?php echo e($_history->nik_pic); ?></p>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <div class="card-header">
                    <h5>Resume</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Pengajuan Ke</td>
                                <td width="60%"><?php echo e($data['ke']); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td><?php echo e($trxM->created_at); ?></td>
                            </tr>
                            <tr>
                                <td>Tahun</td>
                                <td><?php echo e($trxM->periode); ?></td>
                            </tr>
                            <?php if($trxM->plafon>0): ?>
                            <tr>
                                <td>Plafon</td>
                                <td>Ada</td>
                            </tr>
                            <tr>
                                <td>Jumlah Plafon</td>
                                <td>Rp. <?php echo e(number_format($trxM->plafon, 0)); ?></td>
                            </tr>
                            <tr>
                                <td>Plafon Telah Diambil</td>
                                <td>Rp. <?php echo e(number_format($trxM->plafon_diambil, 0)); ?></td>
                            </tr>
                            <tr>
                                <td>Sisa Plafon</td>
                                <td>Rp. <?php echo e(number_format($trxM->sisa_plafon_1, 0)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. <?php echo e(number_format($trxM->klaim_kesehatan, 0)); ?> (Kesehatan)
                                    <br />Rp. <?php echo e(number_format($trxM->klaim_non_kesehatan, 0)); ?> (Non Kesehatan)
                                </td>
                            </tr>
                            <?php if($trxM->plafon>0): ?>
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. <?php echo e(number_format($trxM->sisa_plafon_2, 0)); ?> (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    <?php $__currentLoopData = $lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(asset('assets/reimburse/'.$value->filename)); ?>" target="_blank"><?php echo e($value->_id_lampiran->lampiran); ?></a><br />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td><?php echo e($trxM->catatan); ?></td>
                            </tr>
                            <?php
                                $where = ['id_trx' => $trxM->id, 'nik_pic' => App\Models\MtrUtility::find(12)->informasi];
                                $info = App\Models\TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                            ?>
                            <tr>
                                <td>Catatan Oleh <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></td>
                                <td><?php if(!empty($info)): ?> <?php echo e($info->keterangan); ?> <?php endif; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Hubungan Dengan Karyawan</th>
                                    <th>Layanan</th>
                                    <th>Tanggal Layanan</th>
                                    <th>Nominal Klaim</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trxD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($value->nama_pasien); ?></td>
                                    <td><?php echo e($value->_id_hubungan->hubungan); ?></td>
                                    <td><?php echo e($value->_id_layanan->layanan); ?></td>
                                    <td><?php if(!empty($value->tanggal)): ?> <?php echo e(\Carbon\Carbon::parse($value->tanggal)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td>Rp. <?php echo e(number_format($value->jumlah_klaim, 0)); ?></td>
                                    <td>
                                        <form action="<?php echo e(route('reimburse.updateDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id))); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('POST'); ?>
                                            <input type="hidden" id="trxMId" name="trxMId" value="<?php echo e((new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id_reimburse)); ?>" >
                                            <input type="hidden" id="_id_layanan" name="_id_layanan" value="<?php echo e($value->id_layanan); ?>" >
                                            <input type="hidden" id="id_hubungan" name="id_hubungan" value="<?php echo e($value->id_hubungan); ?>" >
                                            <input type="hidden" id="nama_pasien" name="nama_pasien" value="<?php echo e($value->nama_pasien); ?>" >
                                            <input type="hidden" id="tanggal" name="tanggal" value="<?php echo e($value->tanggal); ?>" >
                                            <input type="hidden" id="jumlah_klaim" name="jumlah_klaim" value="0" >
                                            <input type="hidden" id="delete" name="delete" value="1" >
                                            <ul class="action">
                                                <li class="edit"><a href="<?php echo e(route('reimburse.editDetail', (new App\Http\Controllers\OpenDataController)->encryptWithKey($value->id))); ?>" data-toggle="tooltip" title='Edit'><i class="icon-pencil-alt"></i></a></li>
                                                <li class="delete"><a href="#" id="btn-delete" name="btn-delete" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></a></li>
                                            </ul>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <br/><a href="<?php echo e(route('reimburse.index')); ?>" class="btn btn-warning" type="button">Back</a>
                </div>
            </div>
    </div>
    <!--  -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script type="text/javascript">
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
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/reimburse/detail/edit.blade.php ENDPATH**/ ?>