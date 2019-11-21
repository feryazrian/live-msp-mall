<div class="navlist">
    <a href="{{ route('category') }}">
        <span class="icon-category-color"></span> Kategori Produk
    </a>
    <a href="{{ route('digital') }}">
        <span class="icon-topup-color"></span> Topup & tagihan
    </a>
</div>

@guest
<div class="navlist mt-2">
    <a href="{{ route('page') }}">
        <span class="icon-help-color"></span> Bantuan
    </a>
    {{-- <a href="{{ route('login') }}">
        <span class="icon-user-color"></span> Masuk
    </a>
    <a href="{{ route('register') }}">
        <span class="icon-login-color"></span> Daftar
    </a> --}}
</div>

@else

@if (!empty(Auth::user()->role))
<div class="navlist mt-2">
    <a href="{{ route('admin.dashboard') }}">
        <span class="icon-setting-color"></span> Administrator
    </a>
</div>
@endif

<div class="navlist mt-2">
    @if (!empty(Auth::user()->merchant_id))
    <a href="{{ route('product.add', ['type' => 1]) }}">
        <span class="icon-login-color"></span> Jual Produk
    </a>
    <a href="{{ route('product.add', ['type' => 2]) }}">
        <span class="icon-login-color"></span> Jual E-Voucher
    </a>
    <a href="{{ route('product') }}">
        <span class="icon-merchant-color"></span> Daftar Produk
    </a>
    <a href="{{ route('transaction.sell') }}">
        <span class="icon-order-color"></span> Transaksi Penjualan
        <span class="sell-count count"></span>
    </a>
    <a href="{{ route('merchant.store.edit') }}">
        <span class="icon-setting-color"></span> Pengaturan Merchant
    </a>
    @else
    <a href="{{ route('merchant.join') }}">
        <span class="icon-merchant-color"></span> Menjadi Merchant
    </a>
    @endif
    <a href="{{ route('ads.request') }}">
        <span class="icon-ads-color"></span> Beriklan Sekarang
    </a>
</div>

<div class="navlist mt-2">
    <a href="{{ route('message') }}">
        <span class="icon-message-color"></span> Pesan Masuk
        <span class="message-count count"></span>
    </a>
    <a href="{{ route('wishlist') }}">
        <span class="icon-wishlist-color"></span> Wishlist Saya
    </a>
    <a href="{{ route('transaction.buy') }}">
        <span class="icon-order-color"></span> Pembelian Saya
        <span class="buy-count count"></span>
    </a>
    <a href="{{ route('balance') }}">
        <span class="icon-wallet-color"></span> Mons Wallet
    </a>
    <a href="{{ route('user.detail', ['username' => Auth::user()->username]) }}">
        <span class="icon-user-color"></span> Profil Saya
    </a>
    <a href="{{ route('setting') }}">
        <span class="icon-setting-color"></span> Pengaturan
    </a>
</div>

@if (empty(Auth::user()->api_msp))
<div class="navlist mt-2">
    <a href="{{ route('loyalty.request') }}">
        <span class="icon-gift-color"></span> Gabung Member Loyalty
    </a>
</div>
@endif

@if (!empty(Auth::user()->api_msp))
<div class="navlist mt-2">
    <a href="{{ route('point.welcome') }}">
        <span class="icon-gift-color"></span> My Gift
    </a>
    <a href="{{ route('point') }}">
        <span class="icon-image-color"></span> My QR
    </a>
</div>
@endif

<div class="navlist mt-2">
    <a href="{{ route('contact') }}">
        <span class="icon-contact-color"></span> Hubungi Kami
    </a>
    <a href="{{ route('page') }}">
        <span class="icon-help-color"></span> Bantuan
    </a>
    <a href="http://forum.mymspmall.id" target="_blank">
        <span class="icon-ads-color"></span> MSP Forum
    </a>
    <a href="{{ route('user.logout') }}">
        <span class="icon-logout-color"></span> Keluar
    </a>
</div>
@endguest