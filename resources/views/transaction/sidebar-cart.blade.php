<div class="transaction fixed">
    <div class="container">
        <div class="content pull-left">
            <div class="title">Total Harga</div>
            <div class="price" id="transaction-summary"></div>
        </div>

        <div class="button pull-right">
        @if(!empty(Auth::user()->kabupaten))
            <a href="{{ route('checkout') }}" class="btn btn-primary btn-rounded">Checkout</a>
            {{-- @if ($totalPriceAll > 10000)
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-rounded">Checkout</a>
            @else
                <button type="button" class="btn btn-default btn-rounded">Minimal Transaksi Rp 10.000</button>
            @endif --}}
        @else
            <button type="button" class="btn btn-default btn-rounded">Checkout</button>
        @endif
        </div>
    </div>
</div>