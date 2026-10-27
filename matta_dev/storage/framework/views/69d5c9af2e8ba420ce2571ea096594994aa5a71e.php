<?php $__env->startSection('title', 'Login-one'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row">
            <div class="col-xl-7 d-flex align-items-center justify-content-center">
                <img style="width:90%;float:none;" src="<?php echo e(asset('assets/images/login/logo_matta.png')); ?>" alt="looginpage">
            </div>
            <div class="col-xl-5 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    
                    
                    <div class="card">
                        <div class="card-body">
                            <?php if(Session::has('msg_error')): ?>
                                <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="text-align: justify;">
                                    <strong><?php echo session('msg_error'); ?></strong>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <div class="login-main">
                                <form class="theme-form" action="<?php echo e(route('loginapp')); ?>" method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <h4>Sign in to account</h4>
                                    <p>Enter your NIP & Password to login</p>
                                    <div class="form-group">
                                        <label class="col-form-label" for="nik">NIP</label>
                                        <input class="form-control" type="text" name="nik" id="nik" required="" placeholder="example: 0123456789" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="password">Password</label>
                                        <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                                        <input type="checkbox" onclick="show_pwd()"> Show Password &nbsp; <a href="<?php echo e(route('login-one-reset')); ?>" class="link-primary" target="_blank">Reset password?</a>
                                    </div>
                                    <div class="form-group mt-0">
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </div>
                                    <div class="form-group mt-0">
                                        <a href="<?php echo e(asset('assets/apk/app-release.apk')); ?>" class="link-primary" target="_blank">Click here to get Android MATTA Reborn</a>
                                        <br/><a href="http://matta-reborn.alita.id/download" class="link-primary" target="_blank">Click here to get User Manual</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div class="modal fade bd-example-modal-lg" id="showPDP" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="overflow:hidden;">
                <img src="<?php echo e(asset('assets/images/matta/uu_pdp_v2.png')); ?>" alt="Pengumuman" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="showTrust" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="overflow:hidden;">
                <img src="<?php echo e(asset('assets/images/login/trust2.jpg')); ?>" alt="Pengumuman" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('showTrust'));
            myModal.show();
        });
        <?php $is_aktif = App\Models\MtrUtility::find(39)->informasi==1; ?>
        <?php if($is_aktif): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('showPDP'));
                myModal.show();
            });
        <?php endif; ?>
        //   
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

<?php echo $__env->make('layouts.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta_dev\resources\views/authentication/login-one.blade.php ENDPATH**/ ?>