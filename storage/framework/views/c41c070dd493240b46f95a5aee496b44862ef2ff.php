<div class="navlist">
    <a href="<?php echo e(route('category')); ?>">
        <span class="icon-category-color"></span> Kategori Produk
    </a>
    <a href="<?php echo e(route('digital')); ?>">
        <span class="icon-topup-color"></span> Topup & tagihan
    </a>
</div>

<?php if(auth()->guard()->guest()): ?>
<div class="navlist mt-2">
    <a href="<?php echo e(route('page')); ?>">
        <span class="icon-help-color"></span> Bantuan
    </a>
    
</div>

<?php else: ?>

<?php if(!empty(Auth::user()->role)): ?>
<div class="navlist mt-2">
    <a href="<?php echo e(route('admin.dashboard')); ?>">
        <span class="icon-setting-color"></span> Administrator
    </a>
</div>
<?php endif; ?>

<div class="navlist mt-2">
    <?php if(!empty(Auth::user()->merchant_id)): ?>
    <a href="<?php echo e(route('product.add', ['type' => 1])); ?>">
        <span class="icon-login-color"></span> Jual Produk
    </a>
    <a href="<?php echo e(route('product.add', ['type' => 2])); ?>">
        <span class="icon-login-color"></span> Jual E-Voucher
    </a>
    <a href="<?php echo e(route('product')); ?>">
        <span class="icon-merchant-color"></span> Daftar Produk
    </a>
    <a href="<?php echo e(route('transaction.sell')); ?>">
        <span class="icon-order-color"></span> Transaksi Penjualan
        <span class="sell-count count"></span>
    </a>
    <a href="<?php echo e(route('merchant.store.edit')); ?>">
        <span class="icon-setting-color"></span> Pengaturan Merchant
    </a>
    <?php else: ?>
    <a href="<?php echo e(route('merchant.join')); ?>">
        <span class="icon-merchant-color"></span> Menjadi Merchant
    </a>
    <?php endif; ?>
    <a href="<?php echo e(route('ads.request')); ?>">
        <span class="icon-ads-color"></span> Beriklan Sekarang
    </a>
</div>

<div class="navlist mt-2">
    <a href="<?php echo e(route('message')); ?>">
        <span class="icon-message-color"></span> Pesan Masuk
        <span class="message-count count"></span>
    </a>
    <a href="<?php echo e(route('wishlist')); ?>">
        <span class="icon-wishlist-color"></span> Wishlist Saya
    </a>
    <a href="<?php echo e(route('transaction.buy')); ?>">
        <span class="icon-order-color"></span> Pembelian Saya
        <span class="buy-count count"></span>
    </a>
    <a href="<?php echo e(route('balance')); ?>">
        <span class="icon-wallet-color"></span> Mons Wallet
    </a>
    <a href="<?php echo e(route('user.detail', ['username' => Auth::user()->username])); ?>">
        <span class="icon-user-color"></span> Profil Saya
    </a>
    <a href="<?php echo e(route('setting')); ?>">
        <span class="icon-setting-color"></span> Pengaturan
    </a>
</div>

<?php if(empty(Auth::user()->api_msp)): ?>
<div class="navlist mt-2">
    <a href="<?php echo e(route('loyalty.request')); ?>">
        <span class="icon-gift-color"></span> Gabung Member Loyalty
    </a>
</div>
<?php endif; ?>

<?php if(!empty(Auth::user()->api_msp)): ?>
<div class="navlist mt-2">
    <a href="<?php echo e(route('point.welcome')); ?>">
        <span class="icon-gift-color"></span> My Gift
    </a>
    <a href="<?php echo e(route('point')); ?>">
        <span class="icon-image-color"></span> My QR
    </a>
</div>
<?php endif; ?>

<div class="navlist mt-2">
    <a href="<?php echo e(route('contact')); ?>">
        <span class="icon-contact-color"></span> Hubungi Kami
    </a>
    <a href="<?php echo e(route('page')); ?>">
        <span class="icon-help-color"></span> Bantuan
    </a>
    <a href="http://forum.mymspmall.id" target="_blank">
        <span class="icon-ads-color"></span> MSP Forum
    </a>
    <a href="<?php echo e(route('user.logout')); ?>">
        <span class="icon-logout-color"></span> Keluar
    </a>
</div>
<?php endif; ?>