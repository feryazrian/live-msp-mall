<div class="transaction fixed">
    <div class="container">
        <div class="content pull-left">
            <div class="title">Total Tagihan</div>
            <div class="price" id="transaction-summary"></div>
        </div>

        <div class="button pull-right">
        @if (!empty($gatewayId))
            
            @if ($gatewayId == 1)
                <form id="payment-form" method="post" action="{{ route('payment') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="result_type" id="result-type">
                    <input type="hidden" name="result_data" id="result-data">
                    <button type="button" id="pay-button" data-transaction="{{ $transactionId }}" class="btn btn-primary btn-rounded">Bayar</button>
                </form>
            @endif

            @if ($gatewayId == 2)

                @if ($myBalance >= $totalPriceAll)
                    <form method="post" action="{{ route('balance.payment') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $transactionId }}" />
                        <button type="submit" class="btn btn-primary btn-rounded">Bayar</button>
                    </form>

                @else
                    <button type="button" class="btn btn-default btn-rounded">Bayar</button>
                @endif

            @endif
            @if ($gatewayId == 3)

                @if ($myBalance >= $totalPriceAll)
                    <form method="post" action="{{ route('lifepoint.checkout') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $transactionId }}" />
                        <input type="hidden" name="gateway_id" value="{{ $gatewayId }}"/>

                        <button type="submit" class="btn btn-primary btn-rounded">Bayar</button>
                    </form>

                @else
                    <button type="button" class="btn btn-default btn-rounded">Bayar</button>
                @endif

            @endif

            @if ($gatewayId == 4)
				<form method="post" action="{{ route('kredivo.checkout') }}">
					{{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $transactionId }}">
                    <input type="hidden" name="payment_type_id" value="30_days" id="payment-type-id">
					<button type="submit" class="btn btn-primary btn-rounded">Bayar</button>
				</form>
			@endif

        @else
            <button type="button" class="btn btn-default btn-rounded">Bayar</button>
        @endif
        </div>
    </div>
</div>
