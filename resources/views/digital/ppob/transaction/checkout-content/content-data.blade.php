<input type="hidden" name="pulsa_code" value="{{$pulsa_code}}">
<input type="hidden" name="hp" value="{{$phone_number}}">
<div class="container" id="checkoutContent" data-type="{{$type}}">
    <div class="row">
        <div class="col-md-12">
            <p><b>Ringkasan Pesanan</b></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2">
            <div class="m-3">
                @if(strpos(strtolower($operator), 'smartfren') !== false)
                    <img src="/assets/digital/smartfren.png" class="img-opr" style="background-color:#db203f ;border-radius:3px;" />
                @endif
                @if(strpos(strtolower($operator), 'axis') !== false)
                    <img src="/assets/digital/axis.png" class="img-opr"/>
                @endif
                @if(strpos(strtolower($operator), 'xl') !== false)
                    <img src="/assets/digital/xl.png" class="img-opr"/>
                @endif
                @if(strpos(strtolower($operator), 'tri') !== false)
                    <img src="/assets/digital/three.png" class="img-opr"/>
                @endif
                @if(strpos(strtolower($operator), 'indosat') !== false)
                    <img src="/assets/digital/indosat.png" class="img-opr"/>
                @endif
                @if(strpos(strtolower($operator), 'telkomsel') !== false)
                    <img src="/assets/digital/telkomsel.png" class="img-opr"/>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-10">
            <div class="content">
                <p class="m-0">{{ ucfirst($type) }} {{ $operator }} {{ $nominal }}</p>
                <p class="m-0">No Tujuan: {{ $phone_number }}</p>
                <p class="text-primary">Rp {{ number_format($price,0,',','.') }}</p>
            </div>
        </div>
    </div>
</div>