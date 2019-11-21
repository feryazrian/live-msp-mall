<?php $__env->startSection('title'); ?><?php echo e(str_replace('[TITLE]', $pageTitle, $seo_title)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('description'); ?><?php echo e(str_replace('[TITLE]', $pageTitle, $seo_description)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    <?php echo $__env->make('layouts.includes.sidenav-mobile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!--
                    <div class="navlist">
                    
                    <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>">
                            <span class="icon-help-color"></span> <?php echo e($item->name); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                    <div class="navlist mt-2">
                        <a href="<?php echo e($link_facebook); ?>">
                            <span class="fab fa-facebook"></span> Facebook
                        </a>
                        <a href="<?php echo e($link_instagram); ?>">
                            <span class="fab fa-instagram"></span> Instagram
                        </a>
                    </div>
                -->
                </div>
            </div>

            <div class="col-md-12 page-content col-lg-9 py-4">

                <div class="page-title mb-4"><?php echo e($page->name); ?></div>

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
                    
                <div class="page-editor mb-5 pb-5">
                    <div><?php echo $page->content; ?></div>
                    <div class="page-datetime mt-4">Terakhir Diperbaruhi <span><?php echo e($page->created_at->diffForHumans()); ?></span></div>
                </div>

            </div>

        </div>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>