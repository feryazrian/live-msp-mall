<div class="product-card">
    <?php if(!empty($item->productphoto[0])): ?>
    <a href="<?php echo e(route('product.detail', ['slug' => $item->slug])); ?>" class="image">
        <img src="<?php echo e(asset('uploads/products/medium-'.$item->productphoto[0]->photo)); ?>">
    </a>
    <?php endif; ?>

    <div class="label">
    <?php if(!empty($item->sale)): ?>
        <div class="flashsale">Flash Sale</div>
    <?php endif; ?>
    <?php if(!empty($item->preorder)): ?>
        <div class="preorder">Group Buy</div>
    <?php endif; ?>
    <?php if($item->type_id == 2): ?>
        <div class="preorder">E-Voucher</div>
    <?php endif; ?>
    </div>
    
    <div class="content">
        <a href="<?php echo e(route('product.detail', ['slug' => $item->slug])); ?>" class="title"><?php echo e(str_limit($item->name, 25)); ?></a>

    <?php if(!empty($item->discount)): ?>
        <div class="price"><?php echo e('Rp '.number_format($item->price,0,',','.')); ?><strike><?php echo e('Rp '.number_format($item->discount,0,',','.')); ?></strike></div>
    <?php else: ?>
        <div class="price"><?php echo e('Rp '.number_format($item->price,0,',','.')); ?></div>
    <?php endif; ?>

        <div class="stars">
            <?php echo str_repeat('<i class="fas fa-star"></i>', $item->rating); ?>

            <?php echo str_repeat('<i class="fas fa-star inactive"></i>', 5 - $item->rating); ?>

        

            <span class="stats ml-1">(<?php echo e($item->review); ?>)</span>
        </div>
    
    <?php if(!empty($item->user->place_birth)): ?>
        <div class="location"><?php echo e($item->user->kabupaten->name); ?></div>
    <?php endif; ?>

    <?php if(!empty($cardType)): ?>

        <?php if($cardType == 'wishlist'): ?>
        <div class="button mt-3">

            <form method="post" action="<?php echo e(route('wishlist.delete')); ?>">
                <?php echo e(csrf_field()); ?>


                <input type="hidden" name="product_id" value="<?php echo e($item->id); ?>" />
                
                <button type="submit" class="btn btn-remove"><i class="icon"></i></button>
            </form>

            <form method="post" action="<?php echo e(route('cart.add')); ?>">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" value="<?php echo e($item->id); ?>" />
                <input type="hidden" name="redirect" value="2" />
                <input type="hidden" name="product_id" value="<?php echo e($item->id); ?>" />
                
                <button type="submit" class="btn buy btn-outline-primary btn-rounded">Beli</button>
            </form>
        </div>
        <?php endif; ?>
        
    <?php endif; ?>
    </div>
</div>