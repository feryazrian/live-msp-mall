@if (!empty(Auth::user()->role))
<div class="navlist px-2">
    <a href="{{ route('admin.dashboard') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-cogs"></span> Administrator
        </b>
    </a>
</div>
@endif

<div class="navlist px-2">
    @if (!empty(Auth::user()->merchant_id))
    <a href="{{ route('product.add', ['type' => 1]) }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-plus"></span> Jual Produk
        </b>
    </a>
    <a href="{{ route('product.add', ['type' => 2]) }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-plus"></span> Jual E-Voucher
        </b>
    </a>
    <a href="{{ route('product') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-shopping-cart"></span> Daftar Produk
        </b>
    </a>
    <a href="{{ route('transaction.sell') }}"class="d-flex justify-content-between align-items-center text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-file-invoice"></span> Transaksi Penjualan
        </b>
        <span class="badge badge-fill badge-primary rounded-circle text-truncate sell-count color m-0 text-center"></span>
    </a>
    <a href="{{ route('merchant.store.edit') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-cog"></span> Pengaturan Merchant
        </b>
    </a>
    @else
    <a href="{{ route('merchant.join') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-users-cog"></span> Menjadi Merchant
        </b>
    </a>
    @endif
    <a href="{{ route('ads.request') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-ad"></span> Beriklan Sekarang
        </b>
    </a>
</div>


<div class="navlist px-2">
    <a href="{{ route('message') }}" class="d-flex justify-content-between align-items-center text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-envelope"></span> Pesan Masuk
        </b>
        <span class="badge badge-fill badge-primary rounded-circle text-truncate message-count color m-0 text-center"></span>
    </a>
    <a href="{{ route('wishlist') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-heart"></span> Wishlist Saya
        </b>
    </a>
    <a href="{{ route('transaction.buy') }}" class="d-flex justify-content-between align-items-center text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-file-invoice"></span> Pembelian Saya
        </b>
        <span class="badge badge-fill badge-primary rounded-circle text-truncate buy-count color m-0 text-center"></span>
    </a>
    <a href="{{ route('balance') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-wallet"></span> Mons Wallet
        </b>
    </a>
    <a href="{{ route('setting') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-cog"></span> Pengaturan
        </b>
    </a>
</div>


@if (empty(Auth::user()->api_msp))
<div class="navlist px-2">
    <a href="{{ route('loyalty.request') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-user-check"></span> Gabung Member Loyalty
        </b>
    </a>
</div>

@endif

@if (!empty(Auth::user()->api_msp))
<div class="navlist px-2">
    <a href="{{ route('point.welcome') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-gifts"></span> My Gift
        </b>
    </a>
    <a href="{{ route('point') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-qrcode"></span> My QR
        </b>
    </a>
</div>

@endif

<div class="navlist px-2">
    <a href="{{ route('contact') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-headset"></span> Hubungi Kami
        </b>
    </a>
    <a href="{{ route('page') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-question-circle"></span> Bantuan
        </b>
    </a>
    <a href="http://forum.mymspmall.id" target="_blank" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-blog"></span> MSP Forum
        </b>
    </a>
    <a href="{{ route('user.logout') }}" class="text-secondary border-bottom p-3">
        <b class="m-0">
            <span class="fa fa-times"></span> Keluar
        </b>
    </a>
</div>