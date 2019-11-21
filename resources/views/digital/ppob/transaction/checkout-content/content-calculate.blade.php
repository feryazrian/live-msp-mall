<input type="hidden" name="promo_code" value="">
<div class="container">
    <hr>
    <div class="row">
        <div class="col-md-8">
            <p class="m-0" >Harga: </p>
        </div>
        <div class="col-md-4">
            <p class="pull-right m-0">Rp <span id="price">{{ $price }}</span></p>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-md-6">
            <p class="m-0" >Biaya Layanan: </p>
        </div>
        <div class="col-md-6">
            <p class="pull-right m-0">Rp <span id="serviceFee">3000</span></p>
        </div>
    </div> --}}
    <div class="row" id="promoCodeBtn">
        <div class="col-md-12 pt-2 mt-1">
            <a class="text-success" href="#" data-toggle="modal" data-target="#promoCodeModal"><span class="fa fa-tags"></span> Punya Kode Promo?</a>
        </div>
    </div>
    <div id="promoCodeValue">
    </div>
</div>