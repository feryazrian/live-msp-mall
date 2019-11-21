<?php $__env->startSection('title'); ?><?php echo e($home_title); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('description'); ?><?php echo e($home_description); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-brand pt-1 slider">
    <div class="container">

        <div class="d-none d-xl-table-cell align-top category-list">
        <?php $__currentLoopData = $categories->where('id', '!=', 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('category.detail', ['slug' => $item->slug])); ?>">
                <img src="<?php echo e(asset('uploads/categories/'.$item->icon)); ?>">
                <?php echo e($item->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($item->id == 12): ?>
            <a href="<?php echo e(route('category.detail', ['slug' => $item->slug])); ?>" style="margin-top:5px;">
                <img src="<?php echo e(asset('uploads/categories/'.$item->icon)); ?>">
                <?php echo e($item->name); ?>

            </a>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="pl-c d-sm-block d-xl-table-cell slide">
            <div class="swiper-container front-slide">
                <div class="swiper-wrapper">
                <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item->url); ?>" class="swiper-slide">
                        <img src="<?php echo e(asset('uploads/slides/'.$item->photo)); ?>" alt="<?php echo e($item->name); ?>" width="100%">
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                 <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="d-xl-none d-md-block align-top category-icon-list">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('category.detail', ['slug' => $item->slug])); ?>">
                    <img src="<?php echo e(asset('uploads/categories/'.$item->icon)); ?>">
                    <?php echo e($item->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section>

    
        <section class="bg-grey-light product-trend pt-4">
            <div class="container">
                <?php echo $__env->make('digital.ppob.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
        </section>
    

<?php if(!empty($countdown_flashsale)): ?>
<?php if($countdown_flashsale > $now): ?>
<section class="bg-brand flash-sale" <?php if(!empty($bg_flashsale)): ?> style="background: url('<?php echo e(asset('uploads/options/'.$bg_flashsale)); ?>');" <?php endif; ?>>
    <div class="container">
        
        <div class="section-arrow">
            <button data-section="section-flashsale" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-flashsale" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-flashsale">
            <div class="main py-4">

                <div class="product-list">
                    <div class="head">
                        <div class="title">FLASH SALE</div>
                        <div class="countdown">
                            <div class="caption">Berakhir Dalam</div>
                            <div class="counter flashsale-countdown"></div>
                        </div>
                        <a href="<?php echo e(route('search', ['sort' => 'sale'])); ?>" class="link">LIHAT SEMUANYA ></a>
                    </div>
                
                <?php $__currentLoopData = $productSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            </div>
        </div>
        
    </div>
</section>
<?php endif; ?>
<?php endif; ?>

<?php if($seasons->isNotEmpty()): ?>
<?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $season): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<section class="bg-brand flash-sale" <?php if(!empty($season->background)): ?> style="background: url('<?php echo e(asset('uploads/seasons/'.$season->background)); ?>');" <?php endif; ?>>
    <div class="container">
        
        <div class="section-arrow">
            <button data-section="section-season<?php echo e($season->id); ?>" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-season<?php echo e($season->id); ?>" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-season<?php echo e($season->id); ?>">
            <div class="main py-4">

                <div class="product-list">
                    <div class="head">
                        <div class="title"><?php echo e($season->name); ?></div>
                        <div class="countdown">
                            <div class="caption">Berakhir Dalam</div>
                            <div class="counter season-countdown<?php echo e($season->id); ?>"></div>
                        </div>
                        <a href="<?php echo e(route('season', ['slug' => $season->slug])); ?>" class="link">LIHAT SEMUANYA ></a>
                    </div>
                
                    <?php $__currentLoopData = $season->seasonproduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seasonproduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $item = $seasonproduct->product;
                    ?>
                        <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
                    <script type="text/javascript">
                        $(function () {
                            var <?php echo e('ca'.$season->id); ?> = '<?php echo substr($season->expired,0,4); ?>';
                            var <?php echo e('cb'.$season->id); ?> = '<?php echo substr($season->expired,5,2); ?>';
                            var <?php echo e('cc'.$season->id); ?> = '<?php echo substr($season->expired,8,2); ?>';
                            var <?php echo e('cd'.$season->id); ?> = '<?php echo substr($season->expired,11,2); ?>';
                            var <?php echo e('ce'.$season->id); ?> = '<?php echo substr($season->expired,14,2); ?>';
                            var <?php echo e('cf'.$season->id); ?> = '<?php echo substr($season->expired,17,2); ?>';
                
                            var <?php echo e('countdown'.$season->id); ?> = new Date(<?php echo e('ca'.$season->id); ?>, <?php echo e('cb'.$season->id); ?> - 1, <?php echo e('cc'.$season->id); ?>, <?php echo e('cd'.$season->id); ?>, <?php echo e('ce'.$season->id); ?>, <?php echo e('cf'.$season->id); ?>, 0);
                            
                            $('.season-countdown<?php echo e($season->id); ?>').countdown({until: <?php echo e('countdown'.$season->id); ?>, compact: true, format: 'HMS'});
                        });
                    </script>
                </div>

            </div>
        </div>
        
    </div>
</section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if($productPreorder->count() > 0): ?>
<section class="bg-grey-light product-trend" <?php if(!empty($bg_groupbuy)): ?> style="background: url('<?php echo e(asset('uploads/options/'.$bg_groupbuy)); ?>');" <?php endif; ?>>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">GROUP BUY</div>
            <div class="d-table-cell text-right">
                <a href="<?php echo e(route('search', ['sort' => 'preorder'])); ?>">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-groupbuy" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-groupbuy" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-groupbuy">
            <div class="main">
                <div class="product-list">

                <?php $__currentLoopData = $productPreorder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>

    </div>
</section>
<?php endif; ?>

<section class="bg-grey-light product-trend" <?php if(!empty($bg_bestseller)): ?> style="background: url('<?php echo e(asset('uploads/options/'.$bg_bestseller)); ?>');" <?php endif; ?>>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">PALING LARIS</div>
            <div class="d-table-cell text-right">
                <a href="<?php echo e(route('search', ['sort' => 'bestseller'])); ?>">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-popular" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-popular" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-popular">
            <div class="main">
                <div class="product-list">

                <?php $__currentLoopData = $productSold; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>

    </div>
</section>

<section class="bg-grey-light product-trend pb-30" <?php if(!empty($bg_newest)): ?> style="background: url('<?php echo e(asset('uploads/options/'.$bg_newest)); ?>');" <?php endif; ?>>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">PALING BARU</div>
            <div class="d-table-cell text-right">
                <a href="<?php echo e(route('search', ['sort' => 'new'])); ?>">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-newest" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-newest" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-newest">
            <div class="main">
                <div class="product-list">
                    
                <?php $__currentLoopData = $productNew; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>

    </div>
</section>

<?php $__currentLoopData = $categoryHighlight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $highlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<section class="product-category pb-30" <?php if(!empty($highlight->background)): ?> style="background: url('<?php echo e(asset('uploads/categories/'.$highlight->background)); ?>');" <?php endif; ?>>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell"><?php echo e($highlight->name); ?></div>
            <div class="d-table-cell text-right">
                <a href="<?php echo e(route('category.detail', ['slug' => $highlight->slug])); ?>">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-category<?php echo e($highlight->id); ?>" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-category<?php echo e($highlight->id); ?>" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-category<?php echo e($highlight->id); ?>">
            <div class="main">
                <div class="product-list row">

                    <a href="<?php echo e(route('category.detail', ['slug' => $highlight->slug])); ?>" class="category-image">
                        <img src="<?php echo e(asset('uploads/categories/'.$highlight->cover)); ?>">
                    </a>

                    <?php $__currentLoopData = $highlight->product_highlight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>

    </div>
</section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script>
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 10,
        centeredSlides: true,
        autoplay: {
        delay: 2500,
        disableOnInteraction: false,
        },
        pagination: {
        el: '.swiper-pagination',
        clickable: true,
        },
        navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        },
    });
</script>
<script>
    var move = 100;

    $(".rightArrow").click(function() {
        var view = $('#'+$(this).attr('data-section'));
        view.animate({ scrollLeft: view.scrollLeft() + move }, 300);
    });

    $(".leftArrow").click(function() {
        var view = $('#'+$(this).attr('data-section'));
        view.animate({ scrollLeft: view.scrollLeft() - move }, 300);
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
   <!-- Currency JS -->
   <script src="https://unpkg.com/currency.js@1.2.1/dist/currency.min.js"></script>

   <!-- Link all Digital Scripts -->
   <script src="<?php echo e(asset('assets/js/digital/index.js')); ?>"></script>
   <script src="<?php echo e(asset('assets/js/digital/pulsa.js')); ?>"></script>
   <script src="<?php echo e(asset('assets/js/digital/data.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>