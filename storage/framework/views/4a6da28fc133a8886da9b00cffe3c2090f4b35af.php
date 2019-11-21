<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('title', __('Not Found')); ?>

<?php $__env->startSection('image'); ?>
<div style="background-image: url(<?php echo e(asset('/svg/404.svg')); ?>);" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __('Maaf, Halaman yang anda cari tidak tersedia.')); ?>

<?php echo $__env->make('errors::illustrated-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>