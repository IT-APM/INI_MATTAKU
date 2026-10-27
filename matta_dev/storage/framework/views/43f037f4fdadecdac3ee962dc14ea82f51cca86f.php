<?php $__env->startSection('title', 'Login-one'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row">
            <div class="col-xl-5 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    
                    <div class="card">
                        <div class="card-body">
                            <?php if(Session::has('msg_success')): ?>
                                <div class="alert alert-success dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong><?php echo session('msg_success'); ?></strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <?php if(Session::has('msg_error')): ?>
                                <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong><?php echo session('msg_error'); ?></strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <div class="login-main">
                                <form class="theme-form" action="<?php echo e(route('resetpwd')); ?>" method="post"
                                    enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <h4>Reset Password</h4>
                                    <div class="form-group">
                                        <label class="col-form-label" for="nik">Enter your NIP</label>
                                        <input class="form-control" type="text" name="nik" id="nik"
                                            required="" placeholder="example: 0123456789" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="email">Email receive</label>
                                        <select class="form-select" id="email" name="email" required="">
                                            <option selected="" disabled="" value="">Pilih...</option>
                                            <option value="own">Email Sendiri</option>
                                            <option value="hco">Email <?php echo e(App\Models\MtrUtility::find(51)->informasi); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button class="btn btn-primary btn-block" type="submit">Reset</button> | <a
                                            href="<?php echo e(route('login-one')); ?>" class="btn btn-warning">Back</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 d-flex align-items-center justify-content-center">
                <img style="width:90%;float:none;" src="<?php echo e(asset('assets/images/login/logo_matta.png')); ?>" alt="looginpage">
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function show_pwd() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/authentication/login-one-reset.blade.php ENDPATH**/ ?>