<div class="navlist">
    <a href="{{ route('home') }}">
        <span class="icon-home-white"></span> Home
    </a>
    <a href="{{ route('category') }}">
        <span class="icon-category-white"></span> Kategori Produk
    </a>
    <a href="{{ route('digital') }}">
        <span class="icon-topup-white"></span> Topup & tagihan
    </a>
</div>

<div class="divider"></div>

@guest
<div class="navlist">
    <a href="{{ route('page') }}">
        <span class="icon-help-white"></span> Bantuan
    </a>
    {{-- <a href="{{ route('login') }}">
        <span class="icon-user-white"></span> Masuk
    </a>
    <a href="{{ route('register') }}">
        <span class="icon-login-white"></span> Daftar
    </a> --}}
</div>

@else

@if (!empty(Auth::user()->role))
<div class="navlist">
    <a href="{{ route('admin.dashboard') }}">
        <span class="icon-setting-white"></span> Administrator
    </a>
</div>

<div class="divider"></div>
@endif

<div class="navlist">
    @if (!empty(Auth::user()->merchant_id))
    <a href="{{ route('product.add', ['type' => 1]) }}">
        <span class="icon-login-white"></span> Jual Produk
    </a>
    <a href="{{ route('product.add', ['type' => 2]) }}">
        <span class="icon-login-white"></span> Jual E-Voucher
    </a>
    <a href="{{ route('product') }}">
        <span class="icon-merchant-white"></span> Daftar Produk
    </a>
    <a href="{{ route('transaction.sell') }}">
        <span class="icon-order-white"></span> Transaksi Penjualan
        <span class="sell-count count"></span>
    </a>
    <a href="{{ route('merchant.store.edit') }}">
        <span class="icon-setting-white"></span> Pengaturan Merchant
    </a>
    @else
    <a href="{{ route('merchant.join') }}">
        <span class="icon-merchant-white"></span> Menjadi Merchant
    </a>
    @endif
    <a href="{{ route('ads.request') }}">
        <span class="icon-ads-white"></span> Beriklan Sekarang
    </a>
</div>

<div class="divider"></div>

<div class="navlist">
    <a href="{{ route('message') }}">
        <span class="icon-message-white"></span> Pesan Masuk
        <span class="message-count count"></span>
    </a>
    <a href="{{ route('wishlist') }}">
        <span class="icon-wishlist-white"></span> Wishlist Saya
    </a>
    <a href="{{ route('transaction.buy') }}">
        <span class="icon-order-white"></span> Pembelian Saya
        <span class="buy-count count"></span>
    </a>
    <a href="{{ route('balance') }}">
        <span class="icon-wallet-white"></span> Mons Wallet
    </a>
    <a href="{{ route('user.detail', ['username' => Auth::user()->username]) }}">
        <span class="icon-user-white"></span> Profil Saya
    </a>
    <a href="{{ route('setting') }}">
        <span class="icon-setting-white"></span> Pengaturan
    </a>
</div>

<div class="divider"></div>

@if (empty(Auth::user()->api_msp))
<div class="navlist">
    <a href="{{ route('loyalty.request') }}">
        <span class="icon-gift-white"></span> Gabung Member Loyalty
    </a>
</div>

<div class="divider"></div>
@endif

@if (!empty(Auth::user()->api_msp))
<div class="navlist">
    <a href="{{ route('point.welcome') }}">
        <span class="icon-gift-white"></span> My Gift
    </a>
    <a href="{{ route('point') }}">
        <span class="icon-image-white"></span> My QR
    </a>
</div>

<div class="divider"></div>
@endif

<div class="navlist">
    <a href="{{ route('contact') }}">
        <span class="icon-contact-white"></span> Hubungi Kami
    </a>
    <a href="{{ route('page') }}">
        <span class="icon-help-white"></span> Bantuan
    </a>
    <a href="http://forum.mymspmall.id" target="_blank">
        <span class="icon-ads-white"></span> MSP Forum
    </a>
    <a href="{{ route('user.logout') }}">
        <span class="icon-logout-white"></span> Keluar
    </a>
</div>
@endguest