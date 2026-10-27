<?php $__env->startSection('title', 'Dokumen'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Dokumen</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Kebutuhan Karyawan</li>
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
                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kebutuhan Karyawan</th>
                                    <th>Nama Calon Karyawan</th>
                                    <th>Tanggal Interview</th>
                                    <th>File Interview</th>
                                    <th>Tanggal Psikotes</th>
                                    <th>File Psikotes</th>
                                    <th>Tanggal Negosiasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(++$no); ?></td>
                                    <td><?php echo e($data->_posisi->pekerjaan); ?></td>
                                    <td><?php echo e($data->nama_karyawan); ?></td>
                                    <td><?php if(!empty($data->tanggal_interview)): ?> <?php echo e(\Carbon\Carbon::parse($data->tanggal_interview)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->file_interview)): ?> <a href="<?php echo e(asset('assets/kebutuhan/'.$data->file_interview)); ?>" target="_blank">File Interview</a> <?php endif; ?></td>
                                    <td><?php if(!empty($data->tanggal_psikotes)): ?> <?php echo e(\Carbon\Carbon::parse($data->tanggal_psikotes)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td><?php if(!empty($data->file_psikotes)): ?> <a href="<?php echo e(asset('assets/kebutuhan/'.$data->file_psikotes)); ?>" target="_blank">File Psikotes</a> <?php endif; ?></td>
                                    <td><?php if(!empty($data->tanggal_negosiasi)): ?> <?php echo e(\Carbon\Carbon::parse($data->tanggal_negosiasi)->format('d-m-Y')); ?> <?php endif; ?></td>
                                    <td>
                                        <a href="<?php echo e(route('kebutuhan_detail.edit', (new App\Http\Controllers\OpenDataController)->encryptWithKey($data->id))); ?>" data-toggle="tooltip" title="Document"><i class="fa fa-file-text-o"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Kebutuhan Karyawan</th>
                                    <th>Nama Calon Karyawan</th>
                                    <th>Tanggal Interview</th>
                                    <th>File Interview</th>
                                    <th>Tanggal Psikotes</th>
                                    <th>File Psikotes</th>
                                    <th>Tanggal Negosiasi</th>
                                    <th>Detail</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <a href="<?php echo e(route('kebutuhan.index')); ?>" class="btn btn-warning" type="button">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/kebutuhan/doc_index.blade.php ENDPATH**/ ?>