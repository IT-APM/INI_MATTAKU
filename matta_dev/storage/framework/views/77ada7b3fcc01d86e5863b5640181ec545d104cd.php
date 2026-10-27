<?php $__env->startSection('title', 'Overtime Forms'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Review</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Overtime</li>
<li class="breadcrumb-item active">Review</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5>Detail</h5>
                    <form form action="<?php echo e(route('approval-appject', $overtime->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_maker_at">Created at</label>
                                <input class="form-control digits" name="ot_maker_at" id="ot_maker_at" type="datetime-local" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_maker_at); ?>" <?php endif; ?>>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_maker_by">Maker: <?php echo e($karyawan->nama); ?></label>
                                <input name="ot_maker_by" id="ot_maker_by" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_maker_by); ?>" <?php endif; ?>>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_start_at">Start</label>
                                <input class="form-control digits" name="ot_start_at" id="ot_start_at" type="datetime-local" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_start_at); ?>" <?php endif; ?>>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_title">Title</label>
                                <input name="ot_title" id="ot_title" class="form-control" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_title); ?>" <?php endif; ?>>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_end_at">Finish</label>
                                <input class="form-control digits" name="ot_end_at" id="ot_end_at" type="datetime-local" type="text" required="" <?php if($status=='edit' ): ?> value="<?php echo e($overtime->ot_end_at); ?>" <?php endif; ?>>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ot_desc">Description</label>
                                <textarea class="form-control" id="ot_desc" name="ot_desc" rows="3" required=""><?php if($status == 'edit'): ?> <?php echo e($overtime->ot_desc); ?> <?php endif; ?></textarea>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ot_type_code">Type</label>
                                <select class="form-select" id="ot_type_code" name="ot_type_code" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <?php $__currentLoopData = $type_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($data->id); ?>" <?php if($status=='edit' ): ?> <?php if($data->id == $overtime->ot_type_code): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($data->ot_type); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                        </div>
                        <br />
                        <!-- ($overtime->ot_approver_status == 'Waiting') -->
                        <!-- <a href="<?php echo e(route('approval-appject', [$overtime->id,'res=Terminate'])); ?>" class="btn btn-primary" type="button">Terminate</a> |  -->
                        
                        <?php if(Carbon\Carbon::parse($overtime->ot_start_at) >= Carbon\Carbon::parse(now())): ?>
                        <a href="<?php echo e(route('approval-appject', [$overtime->id,'res=Approve'])); ?>" class="btn btn-success" type="button">Approve</a> | <a href="<?php echo e(route('approval-appject', [$overtime->id,'res=Reject'])); ?>" class="btn btn-danger" type="button">Reject</a> | <a href="<?php echo e(route('approval-appject', [$overtime->id,'res=Expire'])); ?>" class="btn btn-info" type="button">Expire</a> |
                        <?php endif; ?>
                        <a href="<?php echo e(route('approval-index')); ?>" class="btn btn-warning" type="button">Back</a><br />
                    </form>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-4 notification box-col-4">
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
                                <h6><?php echo e($_history->ot_approver_status); ?><span class="dot-notification"></span></h6>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/overtime/approval/review.blade.php ENDPATH**/ ?>