@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<div class="text-center loading-container" ></div>
<section class="page-section">
    <div class="container">
        <div class="row">

            <div class="col-md-12 col-lg-12 page-content py-4 mb-5">

                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="cart-lines mb-5 pb-5 full-height">

                @if(empty(Auth::user()->kabupaten))
                <div class="notif-info">
                    <div class="alert alert-danger m-0">
                        Lengkapi Data Diri sebelum Melanjutkan Transaksi
                    </div>
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
                        {{ session('warning') }}
                    </div>
                @endif
                @if (session('danger'))
                    <div class="alert alert-danger" style="background-color:red; color:white;">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('danger') }}
                    </div>
                @endif

                @if (empty($transactionProduct))
                    <div class="notfound">Belum ada barang yang masuk di Keranjang Belanja Anda</div>
                @endif

                    @php
                    $transactionId = 0;
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
                            $transactionId = $transactionSeller->id;
                        @endphp

                        @foreach ($transactionSeller->transactionproduct as $transaction)

                        @php
                            $totalPrice += ($transaction->unit * $transaction->price);
                            $totalProduct += $transaction->unit;
                            $totalPriceAll += ($transaction->unit * $transaction->price);
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

                                <div class="price">{{ 'Rp '.number_format($transaction->price,0,',','.') }}</div>
                            </div>
                            <div class="button text-center">
                                <div class="unit">
                                    <div class="action-stock">
                                        <input type="text" id="spinner-01" value="{{ $transaction->unit }}" class="numeric form-control spinner order-unit" data-id="{{ $transaction->id }}" data-store="{{ $transaction->user_id }}" min="1" @if ($transaction->product->sale == 1) max="1" @else max="{{ $transaction->product->stock }}" @endif>
                                    </div>
                                </div>
                                <div class="delete">
                                    <form method="post" action="{{ route('cart.delete') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $transaction->id }}" />
                                        <button type="submit" class="btn btn-link btn-xs">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="notes">
                            <input type="text" name="notes" id="order-notes{{ $transaction->id }}" placeholder="Catatan (Optional)" class="form-control order-notes" data-id="{{ $transaction->id }}" value="{{ $transaction->notes }}">
                        </div>

                        @endforeach

                        <div class="total">
                            <div class="pull-left">Total per Tagihan</div>
                            <div class="pull-right price" id="storetotal{{$transactionSeller->user_id}}">
                                {{ 'Rp '.number_format(($totalPrice),0,',','.') }}
                            </div>
                        </div>

                    </div>
                    @endforeach

                </div>
                
            </div>

        </div>

    </div>
</section>

@if (!empty($transactionProduct))
    @include('transaction.sidebar-cart')
@endif

<script>
    $(document).ready(function(){
        // Token
        var _token = $("meta[name=csrf-token]").attr("content");

        // Height
        $('.full-height').css('min-height',($(window).height()));

        summaryUpdate(_token);

        // Notes
        $(".order-notes").on("keyup", function(){
            var id = $(this).attr('data-id');
            var notes = $(this).val();

            $.post('{{ route("cart.notes") }}', { _token:_token, id:id, notes:notes }, function(result){ });

            return true;
        });

        // Unit
        $(document).on("change", ".order-unit", function(){
            var store = $(this).attr('data-store');
            var id = $(this).attr('data-id');
            var unit = $(this).val();
            const params = {_token:_token, store:store, id:id, unit:unit};
            subtotalUpdate(params);
        });

        $(document).on("click", ".action-stock", function() {
            var unit = $(this).find('.order-unit').attr('aria-valuenow');
            var store = $(this).find('.order-unit').attr('data-store');
            var id = $(this).find('.order-unit').attr('data-id');
            const params = {_token:_token, store:store, id:id, unit:unit};
            subtotalUpdate(params);
        });
    });

    function subtotalUpdate(params) {
        $.post('{{ route("cart.unit") }}', params)
            .done(function (result) {
                var response = JSON.parse(result);
                if (response.success == true) {
                    summaryUpdate(params._token)
                    .then(res => $('#storetotal'+params.store).html(response.content))
                    .catch(e => alert(e.statusText));
                } else {
                    alert(res.content);
                }
            })
            .fail(function (err) {
                alert(err.statusText)
            })
    }

    function summaryUpdate(_token) {
        return new Promise((resolve, reject) => {
            try {
                $.post('{{ route("cart.summary") }}', { _token:_token }, function(result) {
                    $("#transaction-summary").html(result);
                    resolve(result);
                });
            } catch (error) {
                reject(error)
            }
        });
    }

</script>

@endsection
