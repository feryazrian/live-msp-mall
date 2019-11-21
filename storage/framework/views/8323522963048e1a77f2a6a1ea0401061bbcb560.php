<div id="ppob" class="digital-content">
    <div class="d-table section-head w-100 py-3" id="headPPOB">
        <div class="d-table-cell">Topup & Tagihan</div>
        
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- Start Tabs -->
            <ul class="nav nav-tabs">
                <?php echo $__env->make('digital.ppob.tabs.tab-pulsa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('digital.ppob.tabs.tab-data', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('digital.ppob.tabs.tab-pln', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('digital.ppob.tabs.tab-game', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </ul>

            <span class="nav-tabs-wrapper-border" role="presentation"></span>
            <?php if(session('warning')): ?>
                <div class="alert alert-danger">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    <?php echo e(session('warning')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('danger')): ?>
                <div class="alert alert-danger" style="background-color:red; color:white;">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    <?php echo e(session('danger')); ?>

                </div>
            <?php endif; ?>
            <?php if(auth()->guard()->check()): ?>
                <input type="hidden" name="userRegister" value="<?php echo e(Auth::user()->created_at); ?>" class="userRegister">
            <?php endif; ?>

            <div class="tab-content">
                <div class="text-center loading-container" ></div>
                <?php echo $__env->make('digital.ppob.contents.content-pulsa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('digital.ppob.contents.content-data', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <div class="tab-pane fade" id="telepon">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="pln">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="air">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="game">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane" id="uc">
                    <div class="text-center">
                        <img src="<?php echo e(asset('assets/digital/under-construction.png')); ?>" alt="" class="img-responsive w-50 h-50">
                    </div>
                </div>
            </div>
            <!-- End Tabs -->
        </div>
    </div>
</div>