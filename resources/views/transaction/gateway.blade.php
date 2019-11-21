@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<script>
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");
</script>

@if (env('MIDTRANS_PRODUCTION') == true)
<script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@else
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endif

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-md-12 col-lg-12 page-content py-4 mb-5">

                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="cart-lines mb-5 pb-5 full-height">
                
                @if ($paymentMessageStatus)
                    <div class="alert alert-warning">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        <i class="fas fa-info-circle"></i> {{ $paymentMessageStatus }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('status') }}
                    </div>
                @endif
            
                @if (session('warning'))
                    <div class="alert alert-danger">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        <i class="fas fa-exclamation-circle"></i> {{ session('warning') }}
                    </div>
                @endif

                @if (empty($transactionProduct))
                    <div class="notfound">Belum ada barang yang masuk di Keranjang Belanja Anda</div>
                @endif

                    <div class="gateway-info">
                        <div class="form-group{{ $errors->has('gateway') ? ' has-error' : '' }} m-0">
                            
                        @if ($errors->has('gateway'))
                            <label class="control-label" for="inputError">{{ $errors->first('gateway') }}</label>
                        @endif

                            <select name="gateway" required class="form-control select select-smart select-secondary gateway-select" data-id="{{ $transactionId }}">
                                <option value="" selected="selected" disabled>Pilih Metode Pembayaran</option>

                                @foreach ($transactionGateway as $gateway)
                                    @if ($gateway->status == 1)
                                        <option value="{{ $gateway->id }}" @if(!empty($gatewayId)) @if ($gateway->id == $gatewayId) selected @endif @endif>
                                            {{ $gateway->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            
                            @if ($errors->has('gateway'))
                                <small id="gateway" class="form-text text-danger">
                                    {{ str_replace('gateway', 'Metode Pembayaran', $errors->first('gateway')) }}
                                </small>
                            @endif
                        </div>

                        @if (!empty($gatewayId))
                            <div class="notif-info py-1">
                                <div class="m-2">
                                    @if (!empty($gatewayDetail->image_path))
                                        <img src="{{ asset('assets/payments/'.$gatewayDetail->image_path) }}" alt="{{ $gatewayDetail->slug }}" style="max-width:100px;"> 
                                    @endif
                                </div>
                                <div class="alert alert-success">
                                    <div>{!! $gatewayDetail->description !!}</div>
                                </div>
                            </div>

                            @if ($gatewayId == 2 )
                                <div class="balance-info">
                                    <div>Saldo {{ config('app.name') }} Anda</div>
                                    <div class="price">{{ 'Rp '.number_format($myBalance,0,',','.') }}</div>
                                </div>
                            @endif
                            @if ($gatewayId == 3 )
                                <div class="balance-info">
                                    <div>Saldo {{ config('app.name') }} Anda</div>
                                    <div class="price">{{ number_format($myBalance,0,',','.') }}</div>
                                </div>
                            @endif

                            @if ($gatewayId == 4)
                                @if ($kredivoPaymentTypes)
                                    <div class="form-group {{ $errors->has('gateway') ? ' has-error' : '' }}">
                                        <select id="kredivo-payment-type" name="kredivo_payment_type" required class="form-control select select-smart select-secondary">
                                            <option value="" selected="selected" disabled>Pilih Jenis Pembayaran</option>
                                            @foreach ($kredivoPaymentTypes as $payment)
                                                <option value="{{ $payment->id }}" @if(!empty($kredivoPaymentId)) @if ($payment->id == $kredivoPaymentId) selected @endif @endif>
                                                    {{ $payment->name }} (Bunga {{ $payment->rate }}%) - {{ $payment->tenure }} x {{ 'Rp '.number_format($payment->monthly_installment,0,',','.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif
                        @endif

                    </div>

                    @php
                    $totalProduct = 0;
                    $totalPriceAll = 0;
                    @endphp

                    @foreach ($transactionProduct as $transactionSeller)
                    <div class="cart-line">

                        <div class="seller">
                            <span>Dari Penjual</span>
                            <a href="{{ route('user.detail', ['username' => $transactionSeller->product->user->username]) }}">
                                {{ $transactionSeller->product->user->name }}
                            </a>
                        </div>

                        @php
                            $totalPrice = 0;
                            $totalPointPrice = 0;
                        @endphp

                        @foreach ($transactionSeller->transactionproduct as $transaction)

                        @php
                            $totalPrice += ($transaction->unit * $transaction->price);
                            $totalProduct += $transaction->unit;
					        $totalPointPrice += ($transaction->point * $transaction->point_price);
                        @endphp

                        <div class="product">
                            <a href="{{ route('product.detail', ['slug' => $transaction->product->slug]) }}" class="image">
                                <img src="{{ asset('uploads/products/medium-'.$transaction->product->productphoto[0]->photo) }}" />
                            </a>
                            <div class="content">
                                <a href="{{ route('product.detail', ['slug' => $transaction->product->slug]) }}" class="title">
                                    {{ $transaction->product->name }}
                                </a>
                        
                            @if (!empty($transaction->product->preorder))
                                <div class="preorder">Preorder</div>
                            @endif

                                <div class="price">{{ 'Rp '.number_format(($transaction->price*$transaction->unit),0,',','.') }} @if (!empty($transaction->product->point))<span>atau</span>@endif</div>
                                
                            @if (!empty($transaction->product->point))
                            @php
                                // Counting
                                $mspPoint = $transaction->product->point / 100;
                                $mspPrice = $transaction->unit * $transaction->price;

                                $mspMax = $mspPoint * $mspPrice;

                                $msp = $mspMax / $point_price;

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

                                $mspTotal = $mspPrice - $msp_price;
                            @endphp

                                <div class="point">{{ 'Rp '.number_format($mspTotal,0,',','.') }} + {{ $msp }} MSP</div>
                            @endif
                            </div>

                        @if (!empty(Auth::user()->api_msp))
                            @if (!empty($transaction->product->point))
                            <div class="button medium text-center">
                                <div class="caption">Bayar dengan Point</div>
                                <div class="unit">
                                    <div class="action-stock">
                                        <input type="text" id="spinner-01" value="{{ $transaction->point }}" class="form-control spinner order-unit" data-id="{{ $transaction->id }}" data-store="{{ $transaction->user_id }}" min="1" max="{{ $msp }}">
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                        </div>

                        @endforeach

                        <div class="total">
                            <div class="pull-left">Total Harga</div>
                            <div class="pull-right price" id="storeprice{{$transactionSeller->user_id}}">
                            @if (isset($transactionSeller->transactionshipping->price))
                                {{ 'Rp '.number_format($totalPrice,0,',','.') }}
                            @endif
                            </div>
                        </div>

                        <div class="total border-0">
                            <div class="pull-left">Total Ongkos Kirim</div>
                            <div class="pull-right price" id="storeongkir{{$transactionSeller->user_id}}">
                            @if (isset($transactionSeller->transactionshipping->price))
                                {{ 'Rp '.number_format($transactionSeller->transactionshipping->price,0,',','.') }}
                            @endif
                            </div>
                        </div>

                        @if (!empty(Auth::user()->api_msp))
                        <div class="total border-0">
                            <div class="pull-left">Total penggunaan Point</div>
                            <div class="pull-right price" id="storepoint{{$transactionSeller->user_id}}">
                            @if (isset($transactionSeller->transactionshipping->price))
                                - {{ 'Rp '.number_format($totalPointPrice,0,',','.') }}
                            @endif
                            </div>
                        </div>
                        @endif

                        <div class="total border-0">
                            <div class="pull-left">Total per Tagihan</div>
                            <div class="pull-right price" id="storetotal{{$transactionSeller->user_id}}">
                            @if (isset($transactionSeller->transactionshipping->price))
                                {{ 'Rp '.number_format(($totalPrice + $transactionSeller->transactionshipping->price - $totalPointPrice),0,',','.') }}
                            @endif
                            </div>
                        </div>

                        @php
                            $totalPriceAll += ($totalPrice + $transactionSeller->transactionshipping->price - $totalPointPrice);
                        @endphp

                    </div>
                    @endforeach

                    @if (!empty($transactionData->promo))
                        @php
                            $totalPriceAll = $totalPriceAll - $transactionData->promo->price;
                        @endphp
                    @endif
            
                    @if (!empty($gatewayId))
                        @if ($gatewayId == 2 || $gatewayId === 3)
                            @if ($myBalance <= $totalPriceAll)
                                <div class="notif-info">
                                    <div class="alert alert-danger m-0">
                                        Maaf, Saldo Anda Tidak Mencukupi untuk Melakukan Pembayaran
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif

                    <div class="form-line">
                        @if (!empty($transactionData->promo))
                        <div class="total border-0">
                            <div class="pull-left">Kode Promo Aktif : {{ $transactionData->promo->name }} <b class="text-brand">{{ $transactionData->promo->code }}</b></div>
                            <div class="pull-right price" id="promototal">- {{ 'Rp '.number_format($transactionData->promo->price,0,',','.') }}</div>
                        </div>
                        @endif
                        <form method="post" action="{{ route('checkout.promo') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="code" required class="form-control" placeholder="Punya Kode Promo?" id="search-query-3">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-outline-default">Gunakan</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                
            </div>

        </div>

    </div>
</section>

@if (!empty($transactionProduct))
    @include('transaction.sidebar-gateway')
@endif

<script>
    $(document).ready(function(){

    // @if (!empty($transactionProduct))
    //     setInterval(function() {
            $.post('{{ route("checkout.summary") }}', { _token:_token }, function(result) {
                $("#transaction-summary").html(result);
            });
    //     }, 1000);
    // @endif
    
    @if (!empty(Auth::user()->api_msp))
        // Unit
        $(".order-unit").on("keyup", function(){
            var store = $(this).attr('data-store');
            var id = $(this).attr('data-id');
            var point = $(this).val();

            $.post('{{ route("checkout.point") }}', { _token:_token, id:id, point: point }, function(result){
                var response = JSON.parse(result);
                if (response.success == true) {
                    $('#storetotal'+store).html(response.content);
                    $('#storepoint'+store).html(response.point);
                } else {
                    alert(response.content);
                }
            });

            $.post('{{ route("checkout.summary") }}', { _token:_token }, function(result) {
                $("#transaction-summary").html(result);
            });

            return true;
        });
        
        $(".action-stock").on("click", function() {
            var point = $(this).find('.order-unit').attr('aria-valuenow');
            var store = $(this).find('.order-unit').attr('data-store');
            var id = $(this).find('.order-unit').attr('data-id');

            $.post('{{ route("checkout.point") }}', { _token:_token, id:id, point:point }, function(result) {
                var response = JSON.parse(result);

                if (response.success == true) {
                    $('#storetotal'+store).html(response.content);
                    $('#storepoint'+store).html(response.point);
                } else {
                    alert(response.content);
                }
            });

            $.post('{{ route("checkout.summary") }}', { _token:_token }, function(result) {
                $("#transaction-summary").html(result);
            });
        });
    @endif
    
        $(".gateway-select").on("change", function(){
            var id = $(this).attr('data-id');
            var gateway = $(this).val();

            $.post('{{ route("gateway.choose") }}', { _token:_token, id:id, gateway: gateway }, function(result) { 
                window.location.href = '{{ route("gateway") }}';
            });

            return true;
        });

        $('#kredivo-payment-type').on("change", function () {
            var id = $(this).val();
            $('#payment-type-id').val(id);
        })
    });

    $('#pay-button').click(function (event) {
        event.preventDefault();
        
        var transaction = $(this).attr('data-transaction');

        $.ajax({

            url: '{{ route("snap.token") }}',
            cache: false,
            type: 'POST',
            data: { _token: _token, transaction: transaction },

            success: function(data) {

                var resultType = document.getElementById('result-type');
                var resultData = document.getElementById('result-data');

                function changeResult(type,data){
                    $("#result-type").val(type);
                    $("#result-data").val(JSON.stringify(data));
                }

                snap.pay(data, {
                    onSuccess: function(result){
                        changeResult('success', result);
                        $("#payment-form").attr('action', '{{ route("payment.success") }}').submit();
                    },
                    onPending: function(result){
                        changeResult('pending', result);
                        $("#payment-form").attr('action', '{{ route("payment.pending") }}').submit();
                    },
                    onError: function(result){
                        changeResult('error', result);
                        $("#payment-form").attr('action', '{{ route("payment.error") }}').submit();
                    }
                });
            }
        });
    });
</script>

@endsection
