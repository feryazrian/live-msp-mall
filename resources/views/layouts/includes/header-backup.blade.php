<header class="bg-brand text-white">
    <div id="sidenav" class="sidenav">
        <div class="head d-table w-100">
            <div class="d-table-cell text-left">

                @auth
                <a href="{{ route('setting') }}" class="btn btn-setting">
                    <span class="icon-setting"></span>
                </a>
                @else
                <div style="width:50px;"></div>
                @endauth

            </div>
            <div class="d-table-cell">

                @auth
                <a href="{{ route('user.detail', ['username' => Auth::user()->username]) }}" class="btn btn-user">
                    <img src="{{ asset('uploads/photos/'.Auth::user()->photo) }}">
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-user">
                    <img src="{{ asset('images/profile.png') }}">
                </a>
                @endauth

            </div>
            <div class="d-table-cell text-right">
                <a href="javascript:void(0)" onclick="closeNav()" class="btn btn-close">
                    <span class="icon-close"></span>
                </a>
            </div>
        </div>
        @include('layouts.nav-white')
    </div>

    <div class="container d-table">

        <div class="d-none left-button">
            <button class="btn btn-menu" onclick="openNav()">
                <span class="icon-menu"></span>
            </button>
        </div>

        <a href="{{ route('home') }}" class="d-table-cell align-top logo">
            <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
            <div class="d-none mobile">
                @if (!empty($headTitle))
                <div class="title">{{ $pageTitle }}</div>
                @else
                <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                @endif
            </div>
        </a>

        <form method="get" action="{{ route('search') }}" class="d-table-cell form-search align-middle pl-4 pr-3 w-100">
            <div class="d-table w-100">

                <div class="d-table-cell select-search align-middle pr-4">
                    <select
                        class="form-control select select-primary select-smart select-block m-0 search-category desktop">
                        <option value="">Kategori</option>

                        @foreach ($categories as $item)
                        <option value="{{ $item->slug }}" @if (!empty($category)) @if ($item->slug == $category->slug)
                            selected @endif @endif @if (!empty($search_category)) @if ($item->slug == $search_category)
                            selected @endif @endif>{{ $item->name }}</option>

                        @foreach ($item->child as $item)
                        <option value="{{ $item->slug }}" @if (!empty($category)) @if ($item->slug == $category->slug)
                            selected @endif @endif @if (!empty($search_category)) @if ($item->slug == $search_category)
                            selected @endif @endif>-- {{ $item->name }}</option>
                        @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="d-table-cell align-middle w-100">
                    <div class="input-search input-group">
                        <input type="text" name="keyword" class="form-control px-4 search-keyword desktop"
                            placeholder="Cari Produk atau toko ..." @if (!empty($search_keyword))
                            value="{{ $search_keyword }}" @endif>

                        <div class="input-group-append px-2">
                            <button type="button" class="btn d-print-inline-block search-button"><span
                                    class="icon-search"></span></button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        @auth
        <div class="d-table-cell align-top action-user">

            <a href="{{ route('cart') }}" class="btn btn-cart mr-1">
                <span class="cart-count count"></span>
                <span class="icon-cart"></span>
            </a>

            <button class="btn btn-user" onclick="openNav()">
                <img src="{{ asset('uploads/photos/'.Auth::user()->photo) }}">
            </button>

            {{-- <button class="btn btn-user dropdownx-button">
                    <img src="{{ asset('uploads/photos/'.Auth::user()->photo) }}">
            </button>
            <div class="navuser dropdownx-menu">
                <div class="arrow-up"></div>
                @include('layouts.nav-white')
            </div> --}}

            <a href="{{ route('search') }}" class="btn btn-search d-none">
                <span class="icon-search-white"></span>
            </a>

        </div>

        @else
        <div class="d-table-cell align-top action-guest">

            <a href="{{ route('register') }}" class="btn btn-primary btn-daftar">
                Daftar
            </a>
            <a href="{{ route('login') }}" class="btn btn-primary btn-masuk">
                Masuk
            </a>

        </div>
        @endauth

    </div>
</header>