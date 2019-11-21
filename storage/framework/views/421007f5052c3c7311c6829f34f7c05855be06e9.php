                        <div class="review-list">
                            <div class="d-table w-100">
                                <a href="<?php echo e(route('user.detail', ['username' => $item->transaction->user->username])); ?>" class="d-table-cell">
                                    <div class="d-inline-block align-middle">
                                        <img src="<?php echo e(asset('uploads/photos/small-'.$item->transaction->user->photo)); ?>" class="img-rounded" />
                                    </div>
                                    <div class="d-inline-block align-middle">
                                        <div class="name"><?php echo e($item->transaction->user->name); ?></div>
                                    </div>
                                </a>
                                <div class="d-table-cell timestamp text-right"><?php echo e(str_replace('yang lalu', '', $item->created_at->diffForHumans())); ?></div>
                            </div>
                            <div class="content"><?php echo e($item->review); ?></div>
                            <div class="stars">
                            <?php for($a=0; $a<$item->rating; $a++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                                
                            <?php
                                $inactive = (5 - $item->rating);
                            ?>
                    
                            <?php for($b=0; $b<$inactive; $b++): ?>
                                <i class="fas fa-star inactive"></i>
                            <?php endfor; ?>
                    
                                <span class="stats ml-1"><?php echo e($item->rating); ?> Bintang</span>
                            </div>
                        </div>