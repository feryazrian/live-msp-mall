@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-md-12 col-lg-12 page-content py-4 mb-5">

                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="cart-lines mb-5 pb-5 full-height">

                @if (empty($transactionAddress->address_id))
                    <div class="notif-info">
                        <div class="alert alert-danger m-0">
                            Pilih Tujuan Pengiriman sebelum Melakukan Pembayaran
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

                @if (empty($transactionProduct))
                    <div class="notfound">Belum ada barang yang masuk di Keranjang Belanja Anda</div>
                @endif

                    <div class="address-info">
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }} m-0">
                            <div class="d-table w-100">
                                <div class="d-table-cell align-top pr-3">
                                    <select name="address" required class="form-control select select-smart select-secondary address-select" data-id="{{ $transactionId }}">
                                        <option value="" selected="selected">Pilih Alamat Tujuan</option>
                                        @foreach ($userAddress as $address)
                                        <option value="{{ $address->id }}" @if(!empty($transactionAddress)) @if ($address->id == $transactionAddress->address_id) selected @endif @endif>
                                            {{
                                                $address->first_name.' '.
                                                $address->last_name.' ('.
                                                $address->address_name.') '.
                                                $address->address.', Kecamatan '.
                                                $address->kecamatan->name.', '.
                                                $address->kabupaten->name.', '.
                                                $address->provinsi->name.', '.
                                                $address->postal_code
                                            }}
                                        </option>
                                        @endforeach
                                    </select>
                                
                                @if ($errors->has('address'))
                                    <small id="address" class="form-text text-danger">
                                        {{ str_replace('address', 'Alamat Tujuan', $errors->first('address')) }}
                                    </small>
                                @endif
                                </div>
                                <div class="d-table-cell align-top text-right">
                                    <button type="button" class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#addressModal">+</button>
                                </div>
                            </div>
                        </div>
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

                                <div class="price">{{ 'Rp '.number_format($transaction->price,0,',','.') }} <span>x {{ $transaction->unit.' Barang' }}</span></div>
                            </div>
                        </div>

                        @endforeach
                        
                        <div class="shipping">
                            @php
                            $number = $transactionSeller->user_id.'0';
                            $shippingPrice = 0;
                            $shippingDescription = null;

                            $transactionShipping = $transactionSeller->transactionshipping;
                            @endphp
                            <div class="form-group m-0" id="storeshipping{{ $transactionSeller->user_id }}">
                                <select name="shipping" required class="form-control select select-smart select-secondary shipping-select shipping-data{{ $transactionSeller->user_id }}" data-id="{{ $transactionId }}" data-store="{{ $transactionSeller->user_id }}">
                                
                                @if ($transactionSeller->user->username == $shipping_username)
                                
                                <!-- Ongkir MSE -->
                                @foreach ($transactionSeller->ongkirmse as $ongkir)

                                    @if ($shippingPrice < 1)
                                        @php
                                            $shippingPrice = $ongkir['price'];
                                            $shippingDescription = $ongkir['description'];
                                        @endphp
                                    @endif
                                    
                                    @php
                                        $class = 'mse';
                                        $number++;
                                    @endphp

                                    <option data-value="{{ $ongkir['price'] }}" data-service="{{ $ongkir['service'] }}" value="{{ $class.$number }}" class="{{ $class }}" @if ($ongkir['description'] == $transactionShipping['description']) selected="selected" @endif>{{ $ongkir['description'] }}</option>

                                @endforeach

                                @endif

                                <!-- Ongkir JNE -->
                                @foreach ($transactionSeller->ongkirjne as $ongkir)

                                    @if ($shippingPrice < 1)
                                        @php
                                            $shippingPrice = $ongkir['price'];
                                            $shippingDescription = $ongkir['description'];
                                        @endphp
                                    @endif

                                    @php
                                        $class = 'jne';
                                        $number++;
                                    @endphp

                                    <option data-value="{{ $ongkir['price'] }}" data-service="{{ $ongkir['service'] }}" value="{{ $class.$number }}" class="{{ $class }}" @if ($ongkir['description'] == $transactionShipping['description']) selected="selected" @endif>{{ $ongkir['description'] }}</option>

                                @endforeach

                                <!-- Ongkir POS -->
                                @foreach ($transactionSeller->ongkirpos as $ongkir)

                                    @if ($shippingPrice < 1)
                                        @php
                                            $shippingPrice = $ongkir['price'];
                                            $shippingDescription = $ongkir['description'];
                                        @endphp
                                    @endif

                                    @php
                                        $class = 'pos';
                                        $number++;
                                    @endphp

                                    <option data-value="{{ $ongkir['price'] }}" data-service="{{ $ongkir['service'] }}" value="{{ $class.$number }}" class="{{ $class }}" @if ($ongkir['description'] == $transactionShipping['description']) selected="selected" @endif>{{ $ongkir['description'] }}</option>

                                @endforeach

                                @foreach ($transactionSeller->ongkirtiki as $ongkir)

                                    @if ($shippingPrice < 1)
                                        @php
                                            $shippingPrice = $ongkir['price'];
                                            $shippingDescription = $ongkir['description'];
                                        @endphp
                                    @endif

                                    @php
                                        $class = 'tiki';
                                        $number++;
                                    @endphp

                                    <option data-value="{{ $ongkir['price'] }}" data-service="{{ $ongkir['service'] }}" value="{{ $class.$number }}" class="{{ $class }}" @if ($ongkir['description'] == $transactionShipping['description']) selected="selected" @endif>{{ $ongkir['description'] }}</option>

                                @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="total">
                            <div class="pull-left">Total per Tagihan</div>
                            <div class="pull-right price" id="storetotal{{$transactionSeller->user_id}}">
                            @if (!empty($transactionSeller->transactionshipping->price))
                                {{ 'Rp '.number_format(($totalPrice + $transactionSeller->transactionshipping->price),0,',','.') }}
                            @endif
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
    @include('transaction.sidebar-checkout')
@endif

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Alamat Pengiriman</h4>
                <button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

            <form method="post" role="form" action="{{ route('setting.address.store') }}" class="smartform">

                {{ csrf_field() }}

                <input type="hidden" name="checkout" value="{{ $transactionId }}" />

                <div class="form-group mb-4 {{ $errors->has('address_name') ? ' has-error' : '' }}">
                    <input type="text" name="address_name" class="form-control" id="address_name" aria-describedby="address_name" placeholder="Nama Alamat (Kantor, Kontrakan, Kosan)" required value="{{ old('address_name') }}">
                
                @if ($errors->has('address_name'))
                    <small id="address_name" class="form-text text-danger">
                        {{ str_replace('address_name', 'Nama Alamat', $errors->first('address_name')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <input type="text" name="first_name" class="form-control" id="first_name" aria-describedby="first_name" placeholder="Nama Depan Penerima" required value="{{ old('first_name') }}">
                
                @if ($errors->has('first_name'))
                    <small id="first_name" class="form-text text-danger">
                        {{ str_replace('first_name', 'Nama Depan Penerima', $errors->first('first_name')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <input type="text" name="last_name" class="form-control" id="last_name" aria-describedby="last_name" placeholder="Nama Belakang Penerima" required value="{{ old('last_name') }}">
                
                @if ($errors->has('last_name'))
                    <small id="last_name" class="form-text text-danger">
                        {{ str_replace('last_name', 'Nama Belakang Penerima', $errors->first('last_name')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-4 {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <input type="text" name="phone" class="numeric form-control" id="phone" aria-describedby="phone" placeholder="Nomor Telepon" required value="{{ old('phone') }}">
                
                @if ($errors->has('phone'))
                    <small id="phone" class="form-text text-danger">
                        {{ $errors->first('phone') }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('address') ? ' has-error' : '' }}">
                    <textarea name="address" class="form-control" id="address" aria-describedby="address" placeholder="Alamat" required rows="5">{{ old('address') }}</textarea>
                
                @if ($errors->has('address'))
                    <small id="address" class="form-text text-danger">
                        {{ $errors->first('address') }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('provinsi_id') ? ' has-error' : '' }}">
                    <select id="provinsi" name="provinsi_id" class="form-control select select-smart select-secondary select-block">
                        <option value="">Provinsi</option>
                        
                    @foreach ($dataProvinsi as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach

                    </select>
                
                @if ($errors->has('provinsi_id'))
                    <small id="provinsi_id" class="form-text text-danger">
                        {{ str_replace('provinsi_id', 'Provinsi', $errors->first('provinsi_id')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('kabupaten_id') ? ' has-error' : '' }}">
                    <select id="kabupaten" name="kabupaten_id" class="form-control select select-smart select-secondary select-block">
                        <option value="">Kota / Kabupaten</option>
                    </select>
                
                @if ($errors->has('kabupaten_id'))
                    <small id="kabupaten_id" class="form-text text-danger">
                        {{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('kecamatan_id') ? ' has-error' : '' }}">
                    <select id="kecamatan" name="kecamatan_id" class="form-control select select-smart select-secondary select-block">
                        <option value="">Kecamatan</option>
                    </select>
                
                @if ($errors->has('kecamatan_id'))
                    <small id="kecamatan_id" class="form-text text-danger">
                        {{ str_replace('kecamatan_id', 'Kecamatan', $errors->first('kecamatan_id')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-2 {{ $errors->has('desa_id') ? ' has-error' : '' }}">
                    <select id="desa" name="desa_id" class="form-control select select-smart select-secondary select-block">
                        <option value="">Kelurahan / Desa</option>
                    </select>
                
                @if ($errors->has('desa_id'))
                    <small id="desa_id" class="form-text text-danger">
                        {{ str_replace('desa_id', 'Kelurahan / Desa', $errors->first('desa_id')) }}
                    </small>
                @endif
                </div>

                <div class="form-group mb-4 {{ $errors->has('postal_code') ? ' has-error' : '' }}">
                    <input type="text" name="postal_code" class="form-control" id="postal_code" aria-describedby="postal_code" placeholder="Kode Pos" required value="{{ old('postal_code') }}">
                
                @if ($errors->has('postal_code'))
                    <small id="postal_code" class="form-text text-danger">
                        {{ $errors->first('postal_code') }}
                    </small>
                @endif
                </div>

                <button type="submit" class="btn btn-rounded btn-primary btn-block">Tambahkan Alamat</button>
            </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");
    
    $(document).ready(function(){
        summaryUpdate({_token:_token, position:'checkout'});

        $(".address-select").on("change", function(){
            var data = $(this).attr('data-id');
            var address = $(this).val();

            $.post('{{ route("checkout.address") }}', { _token:_token, data:data, address: address }, function(result) { 
                window.location.href = '{{ route("checkout") }}';
            });
        });

        $(document).on("change", ".shipping-select", function(){
            var data = $(this).attr('data-id');
            var store = $(this).attr('data-store');

            var selected = $('.shipping-data'+store+' option:selected');
            var price = selected.attr('data-value');
            var courier = selected.attr('class');
            var service = selected.attr('data-service');
            var description = selected.text();

            $.post('{{ route("checkout.shipping") }}', { _token:_token, data:data, store:store, description:description, price:price, courier:courier, service:service }, function(result) {
                var response = JSON.parse(result);
                if (response.success == true) {
                    summaryUpdate({_token:_token, position:'checkout'})
                    .then(res => $('#storetotal'+store).html(response.content))
                    .catch(e => alert(e.statusText))
                } else {
                    alert(response.content);
                }
            });
        });
    });

    function summaryUpdate(params) {
        return new Promise((resolve, reject) => {
            try {
                $.post('{{ route("checkout.summary") }}', params, function(result) {
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
