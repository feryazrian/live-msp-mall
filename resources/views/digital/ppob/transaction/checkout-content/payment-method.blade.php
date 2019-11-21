<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p><b>Metode Pembayaran</b></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="btn-group btn-group-toggle py-2 bg-wheat" data-toggle="buttons" id="paymentMethod">
                @foreach ($paymentMethod as $key => $method)
                    <label class="btn btn-rounded btn-primary @if($key === 0) active @endif">
                        <input type="radio" name="paymentMethod" id="option{{$method->id}}" autocomplete="off" value="{{$method->id}}" @if($key === 0) checked @endif> {{$method->title}}
                    </label>
                @endforeach
            </div>

            <div id="pay-wallet" style="display:none">
                <div class="notif-info">
                    <div class="m-2">
                        @if (!empty($paymentMethod[0]->image_path))
                            <img src="{{ asset('assets/payments/'.$paymentMethod[0]->image_path) }}" alt="{{ $paymentMethod[0]->slug }}" style="max-width:100px;"> 
                        @endif
                    </div>
                    <div class="alert alert-success">
                        <div>{!! $paymentMethod[0]->description !!}</div>
                    </div>
                </div>
                <p>Saldo Mons Wallet : <b class="text-primary">Rp <span id="monsWallet">{{ $myBalance }}</span></b></p>
                <div class="msg-error-mons-wallet">
                    <span class="text-danger"></span>
                </div>
            </div>
            
            <div id="pay-credit" style="display:none">
                <div class="notif-info">
                    <div class="m-2">
                        @if (!empty($paymentMethod[1]->image_path))
                            <img src="{{ asset('assets/payments/'.$paymentMethod[1]->image_path) }}" alt="{{ $paymentMethod[1]->slug }}" style="max-width:100px;"> 
                        @endif
                    </div>
                    <div class="alert alert-success">
                        <div>{!! $paymentMethod[1]->description !!}</div>
                    </div>
                </div>
                <p>Jumlah Life Point : <b class="text-primary"><span id="lifePoint">{{ $lifePoint->total_point }}</span></b></p>
                <div class="msg-error-life-point">
                    <span class="text-danger"></span>
                </div>
            </div>
        </div>
    </div>
</div>