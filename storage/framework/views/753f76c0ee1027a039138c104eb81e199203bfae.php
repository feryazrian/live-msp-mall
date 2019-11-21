<?php $__env->startSection('title'); ?><?php echo e(str_replace('[TITLE]', $pageTitle, $seo_title)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('description'); ?><?php echo e(str_replace('[TITLE]', $pageTitle, $seo_description)); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(auth()->guard()->check()): ?>
    <?php if($product->type_id == 2): ?>
        <?php if(env('MIDTRANS_PRODUCTION') == true): ?>
        <script type="text/javascript"
                src="https://app.midtrans.com/snap/snap.js"
                data-client-key="<?php echo e(env('MIDTRANS_CLIENT_KEY')); ?>"></script>
        <?php else: ?>
        <script type="text/javascript"
                src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="<?php echo e(env('MIDTRANS_CLIENT_KEY')); ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<section class="bg-grey-light pt-c pb-5 product-detail">
    <div class="container d-table">

        <div class="d-none slide">
            <div class="swiper-container product-slide">
                <div class="swiper-wrapper">
                <?php
                    $p1Int = 0;
                ?>
                
                <?php $__currentLoopData = $product->productphoto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $p1Int ++;
                ?>
    
                    <div class="swiper-slide">
                        <img src="<?php echo e(asset('uploads/products/'.'large-'.$photo->photo)); ?>" alt="<?php echo e('Product Photo '.$p1Int); ?>" width="100%">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                    <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="d-table-cell gallery">
            <img id="zoomImage" src="<?php echo e(asset('uploads/products/'.'large-'.$product->productphoto[0]->photo)); ?>" data-zoom-image="<?php echo e(asset('uploads/products/'.$product->productphoto[0]->photo)); ?>"/>

            <div id="zoomGallery">
            
            <?php
                $p2Int = 0;
            ?>
            
            <?php $__currentLoopData = $product->productphoto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $p2Int ++;
            ?>

                <a href="#" data-image="<?php echo e(asset('uploads/products/'.'large-'.$photo->photo)); ?>" data-zoom-image="<?php echo e(asset('uploads/products/'.$photo->photo)); ?>" <?php if($p2Int == 1): ?> class="active" <?php endif; ?> >
                    <img id="zoomImage" src="<?php echo e(asset('uploads/products/'.'small-'.$photo->photo)); ?>" />
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>

        <div class="d-table-cell align-top main">

            <div class="content">

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
            
                <div class="title">
                    <div class="mr-2"><?php echo e($product->name); ?></div>
                <?php if(!empty($product->preorder)): ?>
                    <div class="preorder">Group Buy</div>
                <?php endif; ?>
                <?php if(!empty($product->sale)): ?>
                    <div class="flashsale">Flash Sale</div>
                <?php endif; ?>
                </div>

            <?php if(!empty($product->discount)): ?>
                <div class="price mt-2 mb-3"><?php echo e('Rp '.number_format($product->price,0,',','.')); ?><strike><?php echo e('Rp '.number_format($product->discount,0,',','.')); ?></strike></div>
            <?php else: ?>
                <div class="price mt-2 mb-3"><?php echo e('Rp '.number_format($product->price,0,',','.')); ?></div>
            <?php endif; ?>

            <?php if(!empty($product->point)): ?>
            <?php
                $point = $product->point / 100;
                $price = $product->price;

                $max = $point * $price;

                $msp = $max / $point_price;

                // Floor Point & Min 1
                $msp_before = $msp;
                $msp = floor($msp);
                if ($msp == 0)
                {
                    if ($msp_before > 0 AND $msp_before < 1)
                    {
                        $msp = 1;
                    }
                }
                $msp_price = $msp * $point_price;

                $total = $price - $msp_price;
            ?>

                <div class="point mt-0 mb-3"><?php echo e('Rp '.number_format($total,0,',','.')); ?> + <?php echo e($msp); ?> MSP</div>
            <?php endif; ?>

                <div class="stars">
                <?php for($a=0; $a<$product->rating; $a++): ?>
                    <i class="fas fa-star"></i>
                <?php endfor; ?>
                    
                <?php
                    $inactive = (5 - $product->rating);
                ?>
        
                <?php for($b=0; $b<$inactive; $b++): ?>
                    <i class="fas fa-star inactive"></i>
                <?php endfor; ?>
        
                    <span class="stats ml-1">(<?php echo e($product->review); ?>)</span>
                </div>

                <div class="my-5">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(empty($wishlist)): ?>
                    <form method="post" action="<?php echo e(route('wishlist.store')); ?>" class="form-loved d-inline-block">
                        <?php echo e(csrf_field()); ?>

                        
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>"/>

                        <button type="submit" name="redirect" value="product" class="btn loved btn-outline-primary">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>

                    <?php else: ?>
                    <form method="post" action="<?php echo e(route('wishlist.delete')); ?>" class="form-loved d-inline-block">
                        <?php echo e(csrf_field()); ?>

                        
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>"/>

                        <button type="submit" name="redirect" value="product" class="btn loved btn-primary">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <?php if(!empty($product->preorder)): ?>
                        <?php if($product->preorder_expired > Carbon\Carbon::now()->format('Y-m-d H:i:s')): ?>
                            <form method="post" action="<?php echo e(route('cart.preorder')); ?>">
                                <?php echo e(csrf_field()); ?>


                                <input type="hidden" name="id" value="<?php echo e($product->id); ?>" />

                                <button type="submit" class="btn btn-primary btn-rounded">Group Buy Sekarang</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-default btn-rounded">Masa Group Buy Habis</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($product->stock > 0): ?>
                            <?php if($product->type_id == 2): ?>
                            
                            
                                
                                <form method="post" action="#" id="payment-form" class="d-none">
                                    <?php echo e(csrf_field()); ?>

            
                                    <input type="hidden" name="result_type" id="result-type">
                                    <input type="hidden" name="result_data" id="result-data">
                                </form>

                                <input class="data-voucher" type="hidden" name="id" value="<?php echo e($product->id); ?>" />
                                <input type="text" id="spinner-01" value="1" name="unit" class="form-control numeric spinner mr-2 data-unit" min="1" max="<?php echo e($max); ?>">
                                <input type="text" value="<?php echo e($max); ?>" class="data-max" hidden>
                                <?php if($user->activated == 2): ?>
                                    <button type="button" class="btn btn-default btn-rounded">Produk Dibatasi</button>
                                <?php elseif(\Carbon\Carbon::parse($product->voucher_expired) < now()): ?>
                                    <button type="button" class="btn btn-default btn-rounded">Voucher sudah expired</button>
                                <?php elseif($sum_voucher_unit >= $product->max_amount_per_days): ?>
                                    <button type="button" class="btn btn-default btn-rounded">Produk Dibatasi</button>
                                <?php else: ?>
                                     <button type="button" id="pay-button" data-transaction="<?php echo e(config('app.voucher_code')); ?>" class="btn btn-rounded btn-primary">Beli Sekarang</button>
                                <?php endif; ?>

                            <?php else: ?>
                                <form method="post" action="<?php echo e(route('cart.add')); ?>" class="form-buy d-inline-block mb-2">
                                    <?php echo e(csrf_field()); ?>


                                    <input type="hidden" name="id" value="<?php echo e($product->id); ?>" />
                                    <input type="hidden" name="redirect" value="2" />

                                    <button type="submit" class="btn btn-primary btn-rounded">Beli Sekarang</button>
                                </form>
                                <form method="post" action="<?php echo e(route('cart.add')); ?>" class="form-add d-inline-block mb-2">
                                    <?php echo e(csrf_field()); ?>


                                    <input type="hidden" name="id" value="<?php echo e($product->id); ?>" />

                                    <button type="submit" class="btn btn-outline-primary btn-rounded">Tambah ke Keranjang</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button" class="btn btn-default btn-rounded">Produk Habis</button>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn loved btn-outline-primary mb-2">
                        <i class="far fa-heart"></i>
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-rounded mb-2">Beli Sekarang</a>
                    
                    <?php if($product->type_id == 1): ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-primary btn-rounded mb-2">Tambah ke Keranjang</a>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>

            <div class="label">Produk ini Dijual Oleh,</div>
            <div class="seller d-table">
                <div class="d-table-cell align-middle">
                    <a href="<?php echo e(route('user.detail', ['username' => $product->user->username])); ?>" class="btn user">
                        <img src="<?php echo e(asset('uploads/photos/'.$product->user->photo)); ?>">
                    </a>
                </div>
                <div class="d-table-cell align-middle pr-3">
                    <div class="name"><?php echo e($product->user->name); ?></div>
                    
                <?php if(!empty($product->user->place_birth)): ?>
                    <div class="location"><?php echo e($product->user->kabupaten->name); ?></div>
                <?php endif; ?>
                </div>
                <div class="d-table-cell align-middle">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(Auth::user()->username != $product->user->username): ?>
                    <a href="<?php echo e(route('message.detail', ['username' => $product->user->username])); ?>" class="btn message"><i class="far fa-envelope"></i></a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('message.detail', ['username' => $product->user->username])); ?>" class="btn message"><i class="far fa-envelope"></i></a>
                <?php endif; ?>
                </div>
            </div>

            <div class="tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="one" aria-selected="true">DESKRIPSI</a>
                    </li>

                    <?php if($product->type_id == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="two" aria-selected="false">ULASAN <?php if($reviews->count() > 0): ?> (<?php echo e($reviews->count()); ?>) <?php endif; ?></a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" id="three-tab" data-toggle="tab" href="#three" role="tab" aria-controls="three" aria-selected="false">KOMENTAR <?php if($comments->count() > 0): ?> (<?php echo e($comments->count()); ?>) <?php endif; ?></a>
                    </li>

                    <?php if($product->type_id == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" id="four-tab" data-toggle="tab" href="#four" role="tab" aria-controls="four" aria-selected="false">ONGKIR</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="one" role="tabpanel" aria-labelledby="one-tab">
                        
                        <?php echo nl2br(strip_tags($product->description)); ?>

                        <div class="mt-4">
                            <table class="table table-striped table-responsive w-100">
                                <tr>
                                    <td style="min-width:100px;">Kategori</td>
                                    <td class="w-100">
                                        <a href="<?php echo e(route('category.detail', ['slug' => $product->category->slug])); ?>">
                                        <?php echo e($product->category->name); ?>

                                        </a>
                                    </td>
                                </tr>

                                <?php if($product->type_id == 1): ?>
                                <tr>
                                    <td>Kondisi</td>
                                    <td><?php echo e($product->condition->name); ?></td>
                                </tr>
                                <tr>
                                    <td>Berat</td>
                                    <td><?php echo e($product->weight.' gram'); ?></td>
                                </tr>
                                <?php endif; ?>

                                <tr>
                                    <td>Stok</td>
                                    <td><?php echo e($product->stock); ?></td>
                                </tr>
                                <tr>
                                    <td>Terjual</td>
                                    <td><?php echo e($product->sold); ?></td>
                                </tr>
                            </table>
                        </div>

                        <?php if($product->type_id == 2): ?>
                        <div>
                            <table class="table table-brand table-responsive w-100">
                                <tr>
                                    <td style="min-width:180px;">Batas Waktu Klaim</td>
                                    <td class="w-100"><?php echo e($product->voucher_expired); ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($product->preorder)): ?>
                            <div>
                                <table class="table table-brand table-responsive w-100">
                                    <tr>
                                        <td style="min-width:170px;">Target Group Buy</td>
                                        <td class="w-100"><?php echo e($product->preorder_target.' Buah'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Telah Dipesan</td>
                                        <td><?php echo e($preorders.' Buah'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Batas Waktu</td>
                                        <td><?php echo e($product->preorder_expired); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><?php echo e($product->preorder_expired->diffForHumans()); ?></td>
                                    </tr>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($product->type_id == 1): ?>
                    <div class="tab-pane fade" id="two" role="tabpanel" aria-labelledby="two-tab">
                        <?php if($reviews->isEmpty()): ?>
                            <div class="notfound">Belum Ada Ulasan</div>
                        <?php endif; ?>
        
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $list->review_buyer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('layouts.list-review', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    <div class="tab-pane fade" id="three" role="tabpanel" aria-labelledby="three-tab">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="form">
                            <input id="product-id" type="hidden" name="id" value="<?php echo e($product->id); ?>" />
                            <textarea required id="comment-store" class="inline-textarea" name="content" placeholder="Ketikkan komentar yang ingin anda kirim disini (Tekan ENTER untuk kirim)" rows="1"></textarea>
                        </div>
                    <?php endif; ?>
                        <div id="comment-list" class="product comment">
                        
                            <?php if($comments->isEmpty()): ?>
                                <div class="notfound comment">Belum Ada Komentar</div>
                            <?php endif; ?>
                            
                            <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('layouts.list-comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>

                    <?php if($product->type_id == 1): ?>
                    <div class="tab-pane fade" id="four" role="tabpanel" aria-labelledby="four-tab">
                        <div class="form">
                            <div class="form-group">
                                <select class="form-control select select-smart select-secondary select-block ongkir-select" data-id="<?php echo e($product->id); ?>">
                                    <option value="0">Pilih Alamat Tujuan Pengiriman</option>

                                <?php $match=''; ?>
                                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($match != $kabupaten->provinsi->name): ?>
                                    <optgroup label="<?php echo e($kabupaten->provinsi->name); ?>">
                                <?php endif; ?>
                                        <option value="<?php echo e($kabupaten->id); ?>"><?php echo e($kabupaten->name); ?></option>
                                <?php if($match != $kabupaten->provinsi->name): ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php $match = $kabupaten->provinsi->name; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="ongkir-list">
                            <div class="notfound">Tentukan Alamat Tujuan Pengiriman Anda</div>
                        </div>
                        
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="recommend-title">REKOMENDASI PRODUK</div>
            <div class="recommend-content product-list">

                <?php $__currentLoopData = $recomendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.card-product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>

    </div>
</section>

<?php if(auth()->guard()->check()): ?>
    <?php if($product->type_id == 2): ?>
    <script>
        $('#pay-button').click(function (event) {
            event.preventDefault();

            var _token = $("meta[name=csrf-token]").attr("content");
            var transaction = $(this).attr('data-transaction');
            var voucher = $('.data-voucher').val();
            var unit = $('.data-unit').val();
            var max = $('.data-max').val();
            // var check = parseInt(unit) + parseInt(max);
            // console.log(_token,transaction,voucher,unit,max,check);

            if(parseInt(max) < parseInt(unit)){
                alert("Batas maksimum pembelian produk Anda hari ini tinggal "+max+" pcs.");
            }
           
           
            else{            
                $.ajax({
                    url: '<?php echo e(route("snap.token")); ?>',
                    cache: false,
                    type: 'POST',
                    data: { _token: _token, transaction: transaction, voucher: voucher, unit: unit },

                    success: function(data) {

                        var resultType = document.getElementById('result-type');
                        var resultData = document.getElementById('result-data');

                        function changeResult(type,data){
                            $("#result-type").val(type);
                            $("#result-data").val(JSON.stringify(data));
                        }

                        snap.pay(data, {
                            onSuccess: function(result){
                                console.log(result);
                                changeResult('success', result);
                                $("#payment-form").attr('action', '<?php echo e(route("payment.success")); ?>').submit();
                            },
                            onPending: function(result){
                                console.log(result);
                                changeResult('pending', result);
                                $("#payment-form").attr('action', '<?php echo e(route("payment.pending")); ?>').submit();
                            },
                            onError: function(result){
                                console.log(result);
                                changeResult('error', result);
                                $("#payment-form").attr('action', '<?php echo e(route("payment.error")); ?>').submit();
                            }
                        });
                    }
                });
            }
        });
    </script>
    <?php endif; ?>
<?php endif; ?>

<script type="text/javascript">
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");

    <?php if(auth()->guard()->check()): ?>
    // Comment Store
    $('#comment-store').on("keydown", function(e) {
        if (e.keyCode == 13 && e.shiftKey) { }
        else if ( e.keyCode == 13 ) {
            var id = $("#product-id").val();
            var content = $(this).val();

            if (content != '') {
                $.post('<?php echo e(route("product.comment.store")); ?>', { _token: _token, id: id, content: content }, function(result) {
                    $("#comment-list").prepend(result);
                });
            }
           
            $(this).val('');

            $(".notfound.comment").hide();
            
            return false;
        }
    });

    // Comment Delete
    $(document).on("click",".comment-delete",function(){
        var id = $(this).attr('data-id');

        $.post('<?php echo e(route("product.comment.delete")); ?>', { _token: _token, id: id }, function(result) {
            $('#comment'+id).remove();
        });
    });
    <?php endif; ?>

    // Ongkir
    $(".ongkir-select").on("change", function(){
        var id = $(this).attr('data-id');
        var location = $(this).val();

        $(".ongkir-list").html('<div class="text-center">Loading ...</div>');

        $.post('<?php echo e(route("json.ongkir")); ?>', { _token:_token, id:id, location: location }, function(data) { 
            var content = '';
            
            $.each(data, function (index, element) {
                content += '<tr><td>'+element.name+'</td><td>'+element.duration+'</td><td>'+element.price+'</td></tr>';
            });

            $(".ongkir-list").html('<table class="w-100">'+content+'</table>');
        });

        return false;
    });

    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 0,
        centeredSlides: true,
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>