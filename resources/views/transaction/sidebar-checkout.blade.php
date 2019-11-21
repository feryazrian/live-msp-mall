<div class="transaction fixed">
    <div class="container">
        <div class="content pull-left">
            <div class="title">Total Tagihan</div>
            <div class="price" id="transaction-summary"></div>
        </div>

        <div class="button pull-right">
        @if (!empty($transactionAddress->address_id))
            <a href="{{ route('gateway') }}" class="btn btn-primary btn-rounded">Pembayaran</a>
        @else
            <button type="button" class="btn btn-default btn-rounded">Pembayaran</button>
        @endif
        </div>
    </div>
</div>