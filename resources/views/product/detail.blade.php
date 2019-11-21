@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

@auth
    @if ($product->type_id == 2)
        @if (env('MIDTRANS_PRODUCTION') == true)
        <script type="text/javascript"
                src="https://app.midtrans.com/snap/snap.js"
                data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        @else
        <script type="text/javascript"
                src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        @endif
    @endif
@endauth

<section class="bg-grey-light pt-c pb-5 product-detail">
    <div class="container d-table">

        <div class="d-none slide">
            <div class="swiper-container product-slide">
                <div class="swiper-wrapper">
                @php
                    $p1Int = 0;
                @endphp
                
                @foreach ($product->productphoto as $photo)
                @php
                    $p1Int ++;
                @endphp
    
                    <div class="swiper-slide">
                        <img src="{{ asset('uploads/products/'.'large-'.$photo->photo) }}" alt="{{ 'Product Photo '.$p1Int }}" width="100%">
                    </div>
                @endforeach
                </div>
                    <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="d-table-cell gallery">
            <img id="zoomImage" src="{{ asset('uploads/products/'.'large-'.$product->productphoto[0]->photo) }}" data-zoom-image="{{ asset('uploads/products/'.$product->productphoto[0]->photo) }}"/>

            <div id="zoomGallery">
            
            @php
                $p2Int = 0;
            @endphp
            
            @foreach ($product->productphoto as $photo)
            @php
                $p2Int ++;
            @endphp

                <a href="#" data-image="{{ asset('uploads/products/'.'large-'.$photo->photo) }}" data-zoom-image="{{ asset('uploads/products/'.$photo->photo) }}" @if ($p2Int == 1) class="active" @endif >
                    <img id="zoomImage" src="{{ asset('uploads/products/'.'small-'.$photo->photo) }}" />
                </a>
            @endforeach

            </div>
        </div>

        <div class="d-table-cell align-top main">

            <div class="content">

            @if (session('status'))
                <div class="alert alert-success">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('status') }}
                </div>
            @endif
        
            @if (session('warning'))
                <div class="alert alert-danger">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('warning') }}
                </div>
            @endif
            
                <div class="title">
                    <div class="mr-2">{{ $product->name }}</div>
                @if (!empty($product->preorder))
                    <div class="preorder">Group Buy</div>
                @endif
                @if (!empty($product->sale))
                    <div class="flashsale">Flash Sale</div>
                @endif
                </div>

            @if (!empty($product->discount))
                <div class="price mt-2 mb-3">{{ 'Rp '.number_format($product->price,0,',','.') }}<strike>{{ 'Rp '.number_format($product->discount,0,',','.') }}</strike></div>
            @else
                <div class="price mt-2 mb-3">{{ 'Rp '.number_format($product->price,0,',','.') }}</div>
            @endif

            @if (!empty($product->point))
            @php
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
            @endphp

                <div class="point mt-0 mb-3">{{ 'Rp '.number_format($total,0,',','.') }} + {{ $msp }} MSP</div>
            @endif

                <div class="stars">
                @for ($a=0; $a<$product->rating; $a++)
                    <i class="fas fa-star"></i>
                @endfor
                    
                @php
                    $inactive = (5 - $product->rating);
                @endphp
        
                @for ($b=0; $b<$inactive; $b++)
                    <i class="fas fa-star inactive"></i>
                @endfor
        
                    <span class="stats ml-1">({{ $product->review }})</span>
                </div>

                <div class="my-5">
                @auth
                    @if (empty($wishlist))
                    <form method="post" action="{{ route('wishlist.store') }}" class="form-loved d-inline-block">
                        {{ csrf_field() }}
                        
                        <input type="hidden" name="product_id" value="{{ $product->id }}"/>

                        <button type="submit" name="redirect" value="product" class="btn loved btn-outline-primary">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>

                    @else
                    <form method="post" action="{{ route('wishlist.delete') }}" class="form-loved d-inline-block">
                        {{ csrf_field() }}
                        
                        <input type="hidden" name="product_id" value="{{ $product->id }}"/>

                        <button type="submit" name="redirect" value="product" class="btn loved btn-primary">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                    @endif
                    
                    @if (!empty($product->preorder))
                        @if ($product->preorder_expired > Carbon\Carbon::now()->format('Y-m-d H:i:s'))
                            <form method="post" action="{{ route('cart.preorder') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" value="{{ $product->id }}" />

                                <button type="submit" class="btn btn-primary btn-rounded">Group Buy Sekarang</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-default btn-rounded">Masa Group Buy Habis</button>
                        @endif
                    @else
                        @if ($product->stock > 0)
                            @if ($product->type_id == 2)
                            {{-- {{$max}}
                            {{$sum_voucher_unit}} --}}
                            {{-- {{$product->max_amount_per_days}} --}}
                                {{-- {{ProductController -> detail ==> $sum_voucher_unit}} --}}
                                <form method="post" action="#" id="payment-form" class="d-none">
                                    {{ csrf_field() }}
            
                                    <input type="hidden" name="result_type" id="result-type">
                                    <input type="hidden" name="result_data" id="result-data">
                                </form>

                                <input class="data-voucher" type="hidden" name="id" value="{{ $product->id }}" />
                                <input type="text" id="spinner-01" value="1" name="unit" class="form-control numeric spinner mr-2 data-unit" min="1" max="{{$max}}">
                                <input type="text" value="{{$max}}" class="data-max" hidden>
                                @if($user->activated == 2)
                                    <button type="button" class="btn btn-default btn-rounded">Produk Dibatasi</button>
                                @elseif(\Carbon\Carbon::parse($product->voucher_expired) < now())
                                    <button type="button" class="btn btn-default btn-rounded">Voucher sudah expired</button>
                                @elseif($sum_voucher_unit >= $product->max_amount_per_days)
                                    <button type="button" class="btn btn-default btn-rounded">Produk Dibatasi</button>
                                @else
                                     <button type="button" id="pay-button" data-transaction="{{ config('app.voucher_code') }}" class="btn btn-rounded btn-primary">Beli Sekarang</button>
                                @endif

                            @else
                                <form method="post" action="{{ route('cart.add') }}" class="form-buy d-inline-block mb-2">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" value="{{ $product->id }}" />
                                    <input type="hidden" name="redirect" value="2" />

                                    <button type="submit" class="btn btn-primary btn-rounded">Beli Sekarang</button>
                                </form>
                                <form method="post" action="{{ route('cart.add') }}" class="form-add d-inline-block mb-2">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" value="{{ $product->id }}" />

                                    <button type="submit" class="btn btn-outline-primary btn-rounded">Tambah ke Keranjang</button>
                                </form>
                            @endif
                        @else
                            <button type="button" class="btn btn-default btn-rounded">Produk Habis</button>
                        @endif
                    @endif

                @else
                    <a href="{{ route('login') }}" class="btn loved btn-outline-primary mb-2">
                        <i class="far fa-heart"></i>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-rounded mb-2">Beli Sekarang</a>
                    
                    @if ($product->type_id == 1)
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-rounded mb-2">Tambah ke Keranjang</a>
                    @endif
                @endauth
                </div>
            </div>

            <div class="label">Produk ini Dijual Oleh,</div>
            <div class="seller d-table">
                <div class="d-table-cell align-middle">
                    <a href="{{ route('user.detail', ['username' => $product->user->username]) }}" class="btn user">
                        <img src="{{ asset('uploads/photos/'.$product->user->photo) }}">
                    </a>
                </div>
                <div class="d-table-cell align-middle pr-3">
                    <div class="name">{{ $product->user->name }}</div>
                    
                @if (!empty($product->user->place_birth))
                    <div class="location">{{ $product->user->kabupaten->name }}</div>
                @endif
                </div>
                <div class="d-table-cell align-middle">
                @auth
                    @if (Auth::user()->username != $product->user->username)
                    <a href="{{ route('message.detail', ['username' => $product->user->username]) }}" class="btn message"><i class="far fa-envelope"></i></a>
                    @endif
                @else
                    <a href="{{ route('message.detail', ['username' => $product->user->username]) }}" class="btn message"><i class="far fa-envelope"></i></a>
                @endauth
                </div>
            </div>

            <div class="tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="one" aria-selected="true">DESKRIPSI</a>
                    </li>

                    @if ($product->type_id == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="two" aria-selected="false">ULASAN @if($reviews->count() > 0) ({{ $reviews->count() }}) @endif</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" id="three-tab" data-toggle="tab" href="#three" role="tab" aria-controls="three" aria-selected="false">KOMENTAR @if($comments->count() > 0) ({{ $comments->count() }}) @endif</a>
                    </li>

                    @if ($product->type_id == 1)
                    <li class="nav-item">
                        <a class="nav-link" id="four-tab" data-toggle="tab" href="#four" role="tab" aria-controls="four" aria-selected="false">ONGKIR</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="one" role="tabpanel" aria-labelledby="one-tab">
                        
                        {!! nl2br(strip_tags($product->description)) !!}
                        <div class="mt-4">
                            <table class="table table-striped table-responsive w-100">
                                <tr>
                                    <td style="min-width:100px;">Kategori</td>
                                    <td class="w-100">
                                        <a href="{{ route('category.detail', ['slug' => $product->category->slug]) }}">
                                        {{ $product->category->name }}
                                        </a>
                                    </td>
                                </tr>

                                @if ($product->type_id == 1)
                                <tr>
                                    <td>Kondisi</td>
                                    <td>{{ $product->condition->name }}</td>
                                </tr>
                                <tr>
                                    <td>Berat</td>
                                    <td>{{ $product->weight.' gram' }}</td>
                                </tr>
                                @endif

                                <tr>
                                    <td>Stok</td>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                                <tr>
                                    <td>Terjual</td>
                                    <td>{{ $product->sold }}</td>
                                </tr>
                            </table>
                        </div>

                        @if ($product->type_id == 2)
                        <div>
                            <table class="table table-brand table-responsive w-100">
                                <tr>
                                    <td style="min-width:180px;">Batas Waktu Klaim</td>
                                    <td class="w-100">{{ $product->voucher_expired }}</td>
                                </tr>
                            </table>
                        </div>
                        @endif

                        @if (!empty($product->preorder))
                            <div>
                                <table class="table table-brand table-responsive w-100">
                                    <tr>
                                        <td style="min-width:170px;">Target Group Buy</td>
                                        <td class="w-100">{{ $product->preorder_target.' Buah' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Telah Dipesan</td>
                                        <td>{{ $preorders.' Buah' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Batas Waktu</td>
                                        <td>{{ $product->preorder_expired }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>{{ $product->preorder_expired->diffForHumans() }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    
                    @if ($product->type_id == 1)
                    <div class="tab-pane fade" id="two" role="tabpanel" aria-labelledby="two-tab">
                        @if ($reviews->isEmpty())
                            <div class="notfound">Belum Ada Ulasan</div>
                        @endif
        
                        @foreach ($reviews as $list)
                            @foreach ($list->review_buyer as $item)
                                @include('layouts.list-review')
                            @endforeach
                        @endforeach
                    </div>
                    @endif

                    <div class="tab-pane fade" id="three" role="tabpanel" aria-labelledby="three-tab">
                    @auth
                        <div class="form">
                            <input id="product-id" type="hidden" name="id" value="{{ $product->id }}" />
                            <textarea required id="comment-store" class="inline-textarea" name="content" placeholder="Ketikkan komentar yang ingin anda kirim disini (Tekan ENTER untuk kirim)" rows="1"></textarea>
                        </div>
                    @endauth
                        <div id="comment-list" class="product comment">
                        
                            @if ($comments->isEmpty())
                                <div class="notfound comment">Belum Ada Komentar</div>
                            @endif
                            
                            @foreach ($comments as $item)
                                @include('layouts.list-comment')
                            @endforeach

                        </div>
                    </div>

                    @if ($product->type_id == 1)
                    <div class="tab-pane fade" id="four" role="tabpanel" aria-labelledby="four-tab">
                        <div class="form">
                            <div class="form-group">
                                <select class="form-control select select-smart select-secondary select-block ongkir-select" data-id="{{ $product->id }}">
                                    <option value="0">Pilih Alamat Tujuan Pengiriman</option>

                                @php $match=''; @endphp
                                @foreach ($locations as $kabupaten)
                                @if ($match != $kabupaten->provinsi->name)
                                    <optgroup label="{{ $kabupaten->provinsi->name }}">
                                @endif
                                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->name }}</option>
                                @if ($match != $kabupaten->provinsi->name)
                                    </optgroup>
                                @endif
                                @php $match = $kabupaten->provinsi->name; @endphp
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ongkir-list">
                            <div class="notfound">Tentukan Alamat Tujuan Pengiriman Anda</div>
                        </div>
                        
                    </div>
                    @endif
                </div>
            </div>

            <div class="recommend-title">REKOMENDASI PRODUK</div>
            <div class="recommend-content product-list">

                @foreach ($recomendations as $item)
                    @include('layouts.card-product')
                @endforeach

            </div>
        </div>

    </div>
</section>

@auth
    @if ($product->type_id == 2)
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
                    url: '{{ route("snap.token") }}',
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
                                $("#payment-form").attr('action', '{{ route("payment.success") }}').submit();
                            },
                            onPending: function(result){
                                console.log(result);
                                changeResult('pending', result);
                                $("#payment-form").attr('action', '{{ route("payment.pending") }}').submit();
                            },
                            onError: function(result){
                                console.log(result);
                                changeResult('error', result);
                                $("#payment-form").attr('action', '{{ route("payment.error") }}').submit();
                            }
                        });
                    }
                });
            }
        });
    </script>
    @endif
@endauth

<script type="text/javascript">
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");

    @auth
    // Comment Store
    $('#comment-store').on("keydown", function(e) {
        if (e.keyCode == 13 && e.shiftKey) { }
        else if ( e.keyCode == 13 ) {
            var id = $("#product-id").val();
            var content = $(this).val();

            if (content != '') {
                $.post('{{ route("product.comment.store") }}', { _token: _token, id: id, content: content }, function(result) {
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

        $.post('{{ route("product.comment.delete") }}', { _token: _token, id: id }, function(result) {
            $('#comment'+id).remove();
        });
    });
    @endauth

    // Ongkir
    $(".ongkir-select").on("change", function(){
        var id = $(this).attr('data-id');
        var location = $(this).val();

        $(".ongkir-list").html('<div class="text-center">Loading ...</div>');

        $.post('{{ route("json.ongkir") }}', { _token:_token, id:id, location: location }, function(data) { 
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
@endsection
