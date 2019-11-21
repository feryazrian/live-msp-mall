<footer id="footer">
    <!-- top footer -->
    <div class="footer-section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-4 col-4">
                    <div class="footer">
                        <div class="header-logo flex-1 justify-flex-start">
                            <a href="<?php echo e(route('home')); ?>" class="logo">
                                <img src="<?php echo e(asset('uploads/options/'.$logo_color)); ?>" alt="MYMSPMALL">
                            </a>
                        </div>
                        <div class="row section">
                            <div class="col-md-3">
                                <img src="<?php echo e(asset('assets/img/v2/cs.png')); ?>" alt="cs">
                            </div>
                            <div class="col-md-9">
                                <span class="small">Butuh Bantuan? Hubungi</span>
                                <p><b>061 6644799</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-2">
                    <div class="footer">
                        <h3 class="footer-title"><?php echo e($footer_one->name); ?></h3>
                        <a href="<?php echo e(route('ads.request')); ?>">Beriklan Sekarang</a>

                        <ul class="footer-links">
                            <?php $__currentLoopData = $footer_one->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-2">
                    <div class="footer">
                        <h3 class="footer-title"><?php echo e($footer_two->name); ?></h3>

                        <ul class="footer-links">
                            <?php $__currentLoopData = $footer_two->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="clearfix d-block d-sm-none"></div>
                <div class="col-md-2 col-2">
                    <div class="footer">
                            <h3 class="footer-title"><?php echo e($footer_three->name); ?></h3>

                            <a href="<?php echo e(route('merchant.join')); ?>">Menjadi Merchant</a>

                            <ul class="footer-links">
                                <?php $__currentLoopData = $footer_three->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                    </div>
                </div>
                <div class="col-md-2 col-2">
                    <div class="footer">
                        <h3 class="footer-title"><?php echo e($footer_four->name); ?></h3>

                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('password.request')); ?>">Reset Password</a>
                        <?php endif; ?>

                        <ul class="footer-links">
                            <?php $__currentLoopData = $footer_four->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /row -->
            <!-- row -->
            <div class="row">
                    <div class="col-md-4 col-4">
                        <div class="footer">
                            
                            <div class="d-flex">
                                <a href="https://www.facebook.com/mymspmall.id/" target="_blank" class="d-flex m-auto">
                                    <span class="fab fa-facebook-square social-icon"></span>
                                </a>
                                <a href="https://www.instagram.com/mymspmall.id" target="_blank" class="d-flex m-auto">
                                    <span class="fab fa-instagram social-icon"></span>
                                </a>
                                <a href="https://www.youtube.com/channel/UCblejBpzeaAATIt8JgAJVGw" target="_blank" class="d-flex m-auto">
                                    <span class="fab fa-youtube social-icon"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-4">
                        <div class="footer">
                            <h3 class="title-lower">Metode Pembayaran</h3>
                            <div>
                                <img src="<?php echo e(asset('assets/img/v2/gopay.png')); ?>" alt="gopay" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/midtrans.png')); ?>" alt="midtrans" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/kredivo.png')); ?>" alt="kredivo" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/mastercard.png')); ?>" alt="mastercard" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/visa.png')); ?>" alt="visa" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/mandiri.png')); ?>" alt="mandiri" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/bni.png')); ?>" alt="bni" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/permata.png')); ?>" alt="permata" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/life.png')); ?>" alt="life" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/monswallet.png')); ?>" alt="monswallet" class="lower-img">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-4">
                        <div class="footer">
                            <h3 class="title-lower">Jasa Pengiriman</h3>
                            <div>
                                <img src="<?php echo e(asset('assets/img/v2/jne.png')); ?>" alt="jne" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/tiki.png')); ?>" alt="tiki" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/pos.png')); ?>" alt="pos" class="lower-img">
                                <img src="<?php echo e(asset('assets/img/v2/mspexpress.png')); ?>" alt="mspexpress" class="lower-img">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /top footer -->
    <!-- bottom footer -->
    <div id="bottom-footer">
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-lg-12 my-4">
                    <span class="copyright text-center">
                        &#xA9; <?php echo e(now()->year); ?> All rights reserved | Designed & coded with 💛 by MSP Mall
                    </span>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /bottom footer -->
</footer>