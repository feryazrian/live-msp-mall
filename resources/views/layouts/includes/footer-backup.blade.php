<footer class="bg-brand">
    <div class="links py-5">
        <div class="container">
            <div class="row">

                <div class="col-md-3">
                    <div class="title">{{ $footer_one->name }}</div>

                    <a href="{{ route('ads.request') }}">Beriklan Sekarang</a>
                    <a href="http://forum.mymspmall.id" target="_blank">MSP Forum</a>

                    @foreach ($footer_one->page as $item)
                    <a href="{{ route('page.detail', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                </div>

                <div class="col-md-3">
                    <div class="title">{{ $footer_two->name }}</div>

                    @foreach ($footer_two->page as $item)
                    <a href="{{ route('page.detail', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                </div>

                <div class="col-md-3">
                    <div class="title">{{ $footer_three->name }}</div>

                    <a href="{{ route('merchant.join') }}">Menjadi Merchant</a>

                    @foreach ($footer_three->page as $item)
                    <a href="{{ route('page.detail', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                </div>

                <div class="col-md-3">
                    <div class="title">{{ $footer_four->name }}</div>

                    @guest
                    <a href="{{ route('password.request') }}">Reset Password</a>
                    @endguest

                    @foreach ($footer_four->page as $item)
                    <a href="{{ route('page.detail', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <div class="copyright py-3">
        <div class="container">
            <div class="d-table w-100">

                <a href="{{ route('home') }}" class="d-table-cell align-middle logo w-50">
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                </a>

                <div class="d-table-cell text-right w-50">
                    <a href="{{ $link_facebook }}" class="btn btn-rounded btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ $link_instagram }}" class="btn btn-rounded btn-instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</footer>