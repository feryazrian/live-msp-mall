<?php $__env->startSection('title'); ?><?php echo e(str_replace('[TITLE]', 'Masuk', $seo_title)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('description'); ?><?php echo e(str_replace('[TITLE]', 'Masuk', $seo_description)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-brand auth">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <a href="<?php echo e(route('home')); ?>" class="pt-5 pb-4 d-block">
                    <img src="<?php echo e(asset('uploads/options/'.$logo)); ?>" height="35px">
                </a>

            <?php if(session('status')): ?>
                <div class="alert alert-success">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>
        
            <?php if(session('warning')): ?>
                <div class="alert alert-danger">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    <?php echo e(session('warning')); ?>

                </div>
            <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>" class="mt-4">
                    
                    <?php echo e(csrf_field()); ?>


                    <input type="hidden" name="remember" value="on">

                    <div class="form-group mb-2 <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" autofocus required value="<?php echo e(old('email')); ?>">

                    <?php if($errors->has('email')): ?>
                        <small id="email" class="form-text text-white">
                            <?php echo e($errors->first('email')); ?>

                        </small>
                    <?php endif; ?>
                    </div>

                    <div class="form-group <?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control input-password" id="password" aria-describedby="password" placeholder="Password" required>

                            <span class="input-group-btn clean">
                                <a href="#" class="btn show-password"><i class="far fa-eye"></i></a>
                            </span>
                        </div>
                    
                    <?php if($errors->has('password')): ?>
                        <small id="password" class="form-text text-white">
                            <?php echo e($errors->first('password')); ?>

                        </small>
                    <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-rounded btn-brand-white btn-block">Masuk</button>

                </form>

                <div class="divider text-white my-4">atau</div>

                <div class="social pb-5">
                    <a href="<?php echo e(url('/facebook')); ?>" class="btn btn-rounded btn-inline-block btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?php echo e(url('/google')); ?>" class="btn btn-rounded btn-inline-block btn-google">
                        <i class="fab fa-google"></i>
                    </a>
                </div>

                <div class="text mt-5 mb-3">Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar Sekarang</a></div>

                <a href="<?php echo e(route('password.request')); ?>" class="btn btn-rounded btn-brand-outline-white btn-block mb-5">Reset Password</a>

            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>