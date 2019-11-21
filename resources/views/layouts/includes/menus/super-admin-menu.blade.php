<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <span class="ks-icon la la-dashboard"></span>
        <span>Dashboard</span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="ks-icon la la-check-circle-o"></span>
        <span>Approval</span>
        <span class="badge badge-pill badge-danger badge-right" id="badgeApproval"></span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin.approve.product') }}">Produk<span class="badge badge-pill badge-danger badge-right" id="badgeApprovalProduct"></span></a>
        <a class="dropdown-item" href="{{ route('admin.approve.sale') }}">Flash Sale</a>
        <a class="dropdown-item" href="{{ route('admin.approve.merchant') }}">Merchant<span class="badge badge-pill badge-danger badge-right" id="badgeApprovalMerchant"></span></a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="ks-icon la la-edit"></span>
        <span>Perubahan Merchant</span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin.merchant.finance') }}">Informasi Administrasi</a>
        <a class="dropdown-item" href="{{ route('admin.merchant.account') }}">Informasi Akun</a>
        <a class="dropdown-item" href="{{ route('admin.merchant.store') }}">Informasi Toko</a>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.user.type') }}">
        <span class="ks-icon la la-building"></span>
        <span>Tipe Penjual</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.withdraw') }}">
        <span class="ks-icon la la-credit-card"></span>
        <span>Penarikan</span>
        <span class="badge badge-pill badge-danger badge-right" id="badgeWithdraw"></span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.ads') }}">
        <span class="ks-icon la la-envelope-o"></span>
        <span>Pengiklan</span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="ks-icon la la-dot-circle-o"></span>
        <span>Point</span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin.point') }}">Point Bonus</a>
        <a class="dropdown-item" href="{{ route('admin.point.product') }}">Point Produk</a>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.loyalty') }}">
        <span class="ks-icon la la-trophy"></span>
        <span>Loyalty Member</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.promo') }}">
        <span class="ks-icon la la-tag"></span>
        <span>Kode Promo</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.season') }}">
        <span class="ks-icon la la-flask"></span>
        <span>Promo Musiman</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.category') }}">
        <span class="ks-icon la la-list-ul"></span>
        <span>Kategori</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.page') }}">
        <span class="ks-icon la la-file-text-o"></span>
        <span>Halaman</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.slide') }}">
        <span class="ks-icon la la-toggle-off"></span>
        <span>Slide</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.shipping') }}">
        <span class="ks-icon la la-archive"></span>
        <span>Pengiriman</span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="ks-icon la la-dot-circle-o"></span>
        <span>PPOB</span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin.pulsa') }}">Pulsa</a>
        <a class="dropdown-item" href="{{ route('admin.data') }}">Data</a>
        <a class="dropdown-item" href="{{ route('admin.banner') }}">Banner</a>

    </div>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.user') }}">
        <span class="ks-icon la la-user"></span>
        <span>Daftar Pengguna</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.option') }}">
        <span class="ks-icon la la-sliders"></span>
        <span>Pengaturan</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.autodebet') }}">
        <span class="ks-icon la la-sliders"></span>
        <span>Auto Debet</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.streaming.live') }}">
        <span class="ks-icon la la-youtube-play"></span>
        <span>Live Streaming</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.payment.index') }}">
        <span class="ks-icon la la-cc-mastercard"></span>
        <span>Payment Method</span>
    </a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="ks-icon la la-sliders"></span>
        <span>Report</span>
    </a>
    <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.salesreport') }}">Sales Report</a>
        <a class="dropdown-item" href="{{ route('admin.vouchertransaction') }}">Voucher Trasaction Report</a>
        <a class="dropdown-item" href="{{ route('admin.monswallethistory') }}">Mons Wallet Report</a>
        <a class="dropdown-item" href="{{ route('admin.balancedeposithistory') }}">Balance Deposit Report</a>
    </div>
</li>


<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.coupon') }}">
        <span class="ks-icon la la-sliders"></span>
        <span>Coupon List</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.lottery') }}">
        <span class="ks-icon la la-sliders"></span>
        <span>Lottery List</span>
    </a>
</li>