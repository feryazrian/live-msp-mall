<div class="product-card">
    @if (!empty($item->productphoto[0]))
    <a href="{{ route('product.detail', ['slug' => $item->slug]) }}" class="image">
        <img src="{{ asset('uploads/products/medium-'.$item->productphoto[0]->photo) }}">
    </a>
    @endif

    <div class="label">
    @if (!empty($item->sale))
        <div class="flashsale">Flash Sale</div>
    @endif
    @if (!empty($item->preorder))
        <div class="preorder">Group Buy</div>
    @endif
    @if ($item->type_id == 2)
        <div class="preorder">E-Voucher</div>
    @endif
    </div>
    
    <div class="content">
        <a href="{{ route('product.detail', ['slug' => $item->slug]) }}" class="title">{{ str_limit($item->name, 25) }}</a>

    @if (!empty($item->discount))
        <div class="price">{{ 'Rp '.number_format($item->price,0,',','.') }}<strike>{{ 'Rp '.number_format($item->discount,0,',','.') }}</strike></div>
    @else
        <div class="price">{{ 'Rp '.number_format($item->price,0,',','.') }}</div>
    @endif

        <div class="stars">
            {!! str_repeat('<i class="fas fa-star"></i>', $item->rating) !!}
            {!! str_repeat('<i class="fas fa-star inactive"></i>', 5 - $item->rating) !!}
        {{-- @for ($a=0; $a<$item->rating; $a++)
            <i class="fas fa-star"></i>
        @endfor
            
        @php
            $inactive = (5 - $item->rating);
        @endphp

        @for ($b=0; $b<$inactive; $b++)
            <i class="fas fa-star inactive"></i>
        @endfor --}}

            <span class="stats ml-1">({{ $item->review }})</span>
        </div>
    
    @if (!empty($item->user->place_birth))
        <div class="location">{{ $item->user->kabupaten->name }}</div>
    @endif

    @if (!empty($cardType))

        @if ($cardType == 'wishlist')
        <div class="button mt-3">

            <form method="post" action="{{ route('wishlist.delete') }}">
                {{ csrf_field() }}

                <input type="hidden" name="product_id" value="{{ $item->id }}" />
                
                <button type="submit" class="btn btn-remove"><i class="icon"></i></button>
            </form>

            <form method="post" action="{{ route('cart.add') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id }}" />
                <input type="hidden" name="redirect" value="2" />
                <input type="hidden" name="product_id" value="{{ $item->id }}" />
                
                <button type="submit" class="btn buy btn-outline-primary btn-rounded">Beli</button>
            </form>
        </div>
        @endif
        
    @endif
    </div>
</div>