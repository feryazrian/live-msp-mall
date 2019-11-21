<header>
    <!-- TOP HEADER -->
    {{-- <div id="top-header" class="hidden-xs d-none d-md-block">
        <div class="container">
            <ul class="header-links text-center">
                <li>
                    <span><b>30 Hari</b> Pengembalian Barang</span>
                </li>
                <li>
                    <span><b>24/7</b> Customer Service</span>
                </li>
                <li>
                    <a href="#"><b>Histeria Vaganza</b> Promo</a>
                </li>
            </ul>
        </div>
    </div> --}}
    <!-- /TOP HEADER -->
    <!-- MAIN HEADER -->
    <div id="header">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- LEFT HEADER -->
                <div class="col-lg-2 col-md-3 col-2">
                    <div class="header-ctn d-flex justify-content-around align-items-center">
                        <!-- Logo -->
                            <div class="header-logo d-none d-md-flex justify-content-center align-items-center">
                                <a href="{{ route('home') }}" class="logo">
                                    <img src="{{ asset('uploads/options/'.$logo_color) }}" alt="MYMSPMALL">
                                </a>
                            </div>
                        <!-- /Logo -->

                        <!-- Menu Toogle -->
                        <div class="menu-toggle dropdown d-flex justify-content-center align-items-center" id="menu">
                                <a class="dropdown-toggle text-secondary" data-toggle="dropdown" aria-expanded="true">
                                    <span class="fa fa-list-ul menu-icon"></span>
                                </a>
                                <!-- Menu Content -->
                                <div class="menu-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="menu-list">
                                        <div class="d-table-cell align-top category-list">
                                                <div class="d-md-none">
                                                    <a href="{{ route('home') }}" class="text-dark">
                                                        Home
                                                    </a>
                                                </div>
                                                <hr style="border-top: 1px solid #eee;margin: 0px 12px;">
                                                <a href="{{ route('digital') }}" class="text-dark">
                                                    Topup & Tagihan
                                                </a>
                                                <hr style="border-top: 1px solid #eee;margin: 0px 12px;">
                                            @foreach ($categories->where('id', '!=', 12) as $i => $item)
                                                <a href="{{ route('category.detail', ['slug' => $item->slug]) }}" class="text-dark">
                                                    {{ $item->name }}
                                                </a>
                                                <hr style="border-top: 1px solid #eee;margin: 0px 12px;">
                                            @endforeach
                                    
                                            @foreach ($categories as $item)
                                                @if ($item->id == 12)
                                                    <a href="{{ route('category.detail', ['slug' => $item->slug]) }}" class="text-dark">
                                                        {{ $item->name }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Menu Content -->
                            </div>
                        <!-- /Menu Toogle -->
                    </div>
                </div>
                <!-- /LEFT HEADER -->
                <!-- CENTER HEADER -->
                <div class="col-lg-8 col-md-6 col-8">
                    <div class="header-ctn d-flex justify-content-center">
                        <div class="header-search">
                            <!-- Search Form -->
                            <form method="get" action="{{ route('search') }}">
                                <input name="keyword" class="input" placeholder="Cari Produk atau Toko" @if (!empty($search_keyword)) value="{{ $search_keyword }}" @endif>
                                <button class="search-btn text-white">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                            <!-- /Search Form -->
                        </div>
                    </div>
                </div>
                <!-- /CENTER HEADER -->
                <!-- RIGHT HEADER -->
                <div class="col-lg-2 col-md-3 col-2">
                    <div class="header-ctn d-flex justify-content-around align-items-center">
                        @auth
                            <!-- Cart -->
                            <div class="d-none d-md-flex justify-content-center align-items-center" id="cart">
                                <a href="{{ route('cart') }}">
                                    <img src="{{ asset('assets/img/v2/cart.png') }}" alt="facebook" class="cart-icon">
                                    <div class="qty">
                                        <span class="cart-count count"></span>
                                    </div>
                                </a>
                            </div>
                            <!-- /Cart -->
                            <!-- Account -->
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="account">
                                    <a href="{{ route('setting') }}" class="d-none d-lg-flex">
                                        <img data-src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" >
                                    </a>
                                    <a href="#" onclick="openNav()" class="d-flex d-lg-none">
                                        <img data-src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" >
                                    </a>
                                </div>
                            </div>
                            <!-- /Account -->
                        @endauth
                        @guest
                            <div class="d-none d-md-flex justify-content-center align-items-center">
                                <a href="/login" class="btn btn-rounded btn-outline-primary signin-btn" >
                                    Masuk
                                </a>
                            </div>
                            <div class="d-flex d-md-none justify-content-center align-items-center">
                                <a href="/login" class="text-secondary signin-small">
                                    <span class="fa fa-sign-in-alt"></span>
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
                <!-- /RIGHT HEADER -->
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- /MAIN HEADER -->
    <div id="sidenav" class="bg-white sidenav">
        <div class="head d-flex flex-1 justify-content-between align-items-center px-4 border-bottom border-secondary">
            <div class="d-flex">
                <a href="{{ route('cart') }}" class="btn btn-cart d-md-none" id="cart">
                    <span class="fas fa-shopping-cart text-secondary"></span>
                    @auth
                    <span class="badge badge-primary rounded-circle text-truncate p-0"><span class="cart-count count"></span></span>
                    @endauth
                </a>
            </div>
            <div class="d-flex">
                <a href="javascript:void(0)" onclick="closeNav()" class="btn btn-close">
                    <span class="far fa-times-circle text-secondary"></span>
                </a>
            </div>
        </div>
        @include('layouts.includes.sidenav-mobile')
    </div>
</header>