<div class="navlist">
    <a href="<?php echo e(route('home')); ?>">
        <span class="icon-home-white"></span> Home
    </a>
    <a href="<?php echo e(route('category')); ?>">
        <span class="icon-category-white"></span> Kategori Produk
    </a>
    <a href="<?php echo e(route('digital')); ?>">
        <span class="icon-topup-white"></span> Topup & tagihan
    </a>
</div>

<div class="divider"></div>

<?php if(auth()->guard()->guest()): ?>
<div class="navlist">
    <a href="<?php echo e(route('page')); ?>">
        <span class="icon-help-white"></span> Bantuan
    
</div>

<?php else: ?>

<?php if(!empty(Auth::user()->role)): ?>
<div class="navlist">
    <a href="<?php echo e(route('admin.dashboard')); ?>">
        <span class="icon-setting-white"></span> Administrator
    </a>
</div>

<div class="divider"></div>
<?php endif; ?>

<div class="navlist">
    <?php if(!empty(Auth::user()->merchant_id)): ?>
    <a href="<?php echo e(route('product.add', ['type' => 1])); ?>">
        <span class="icon-login-white"></span> Jual Produk
    </a>
    <a href="<?php echo e(route('product.add', ['type' => 2])); ?>">
        <span class="icon-login-white"></span> Jual E-Voucher
    </a>
    <a href="<?php echo e(route('product')); ?>">
        <span class="icon-merchant-white"></span> Daftar Produk
    </a>
    <a href="<?php echo e(route('transaction.sell')); ?>">
        <span class="icon-order-white"></span> Transaksi Penjualan
        <span class="sell-count count"></span>
    </a>
    <a href="<?php echo e(route('merchant.store.edit')); ?>">
        <span class="icon-setting-white"></span> Pengaturan Merchant
    </a>
    <?php else: ?>
    <a href="<?php echo e(route('merchant.join')); ?>">
        <span class="icon-merchant-white"></span> Menjadi Merchant
    </a>
    <?php endif; ?>
    <a href="<?php echo e(route('ads.request')); ?>">
        <span class="icon-ads-white"></span> Beriklan Sekarang
    </a>
</div>

<div class="divider"></div>

<div class="navlist">
    <a href="<?php echo e(route('message')); ?>">
        <span class="icon-message-white"></span> Pesan Masuk
        <span class="message-count count"></span>
    </a>
    <a href="<?php echo e(route('wishlist')); ?>">
        <span class="icon-wishlist-white"></span> Wishlist Saya
    </a>
    <a href="<?php echo e(route('transaction.buy')); ?>">
        <span class="icon-order-white"></span> Pembelian Saya
        <span class="buy-count count"></span>
    </a>
    <a href="<?php echo e(route('balance')); ?>">
        <span class="icon-wallet-white"></span> Mons Wallet
    </a>
    <a href="<?php echo e(route('user.detail', ['username' => Auth::user()->username])); ?>">
        <span class="icon-user-white"></span> Profil Saya
    </a>
    <a href="<?php echo e(route('setting')); ?>">
        <span class="icon-setting-white"></span> Pengaturan
    </a>
</div>

<div class="divider"></div>

<?php if(empty(Auth::user()->api_msp)): ?>
<div class="navlist">
    <a href="<?php echo e(route('loyalty.request')); ?>">
        <span class="icon-gift-white"></span> Gabung Member Loyalty
    </a>
</div>

<div class="divider"></div>
<?php endif; ?>

<?php if(!empty(Auth::user()->api_msp)): ?>
<div class="navlist">
    <a href="<?php echo e(route('point.welcome')); ?>">
        <span class="icon-gift-white"></span> My Gift
    </a>
    <a href="<?php echo e(route('point')); ?>">
        <span class="icon-image-white"></span> My QR
    </a>
</div>

<div class="divider"></div>
<?php endif; ?>

<div class="navlist">
    <a href="<?php echo e(route('contact')); ?>">
        <span class="icon-contact-white"></span> Hubungi Kami
    </a>
    <a href="<?php echo e(route('page')); ?>">
        <span class="icon-help-white"></span> Bantuan
    </a>
    <a href="http://forum.mymspmall.id" target="_blank">
        <span class="icon-ads-white"></span> MSP Forum
    </a>
    <a href="<?php echo e(route('user.logout')); ?>">
        <span class="icon-logout-white"></span> Keluar
    </a>
</div>
<?php endif; ?>