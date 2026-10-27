<?php $__env->startSection('title', 'Overtime'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita" />&nbsp;Overtime</h4>
                </div>
            </div>
        </div>
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
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Review</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Created at</td>
                                <td width="60%"><?php echo e(Carbon\Carbon::parse($overtime->ot_maker_at) ->format('d-m-Y H:i:s')); ?></td>
                            </tr>
                            <tr>
                                <td>Title</td>
                                <td><?php echo e($overtime->ot_title); ?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?php echo e($overtime->ot_desc); ?></td>
                            </tr>
                            <tr>
                                <td>Start</td>
                                <td><?php echo e(Carbon\Carbon::parse($overtime->ot_start_at) ->format('d-m-Y H:i:s')); ?></td>
                            </tr>
                            <tr>
                                <td>Finish</td>
                                <td><?php echo e(Carbon\Carbon::parse($overtime->ot_end_at) ->format('d-m-Y H:i:s')); ?></td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td><?php echo e($overtime->_ot_type_code->ot_type); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if(Carbon\Carbon::parse($overtime->ot_start_at) >= Carbon\Carbon::parse(now())): ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="get" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    <?php echo method_field('GET'); ?>
                    <?php echo csrf_field(); ?>
                    <button type="submit" name="res" value="Approve" class="btn btn-success">Approve</button>
                    <button type="submit" name="res" value="Reject" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Overtime ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/overtime.blade.php ENDPATH**/ ?>