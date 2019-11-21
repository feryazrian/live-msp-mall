<div id="ppob" class="digital-content">
    <div class="d-table section-head w-100 py-3" id="headPPOB">
        <div class="d-table-cell">Topup & Tagihan</div>
        {{-- <div class="d-table-cell text-right">
            <a href="{{ url('digital') }}">LIHAT SEMUANYA ></a>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- Start Tabs -->
            <ul class="nav nav-tabs">
                @include('digital.ppob.tabs.tab-pulsa')
                @include('digital.ppob.tabs.tab-data')
                @include('digital.ppob.tabs.tab-pln')
                @include('digital.ppob.tabs.tab-game')
            </ul>

            <span class="nav-tabs-wrapper-border" role="presentation"></span>
            @if (session('warning'))
                <div class="alert alert-danger">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('warning') }}
                </div>
            @endif
            @if (session('danger'))
                <div class="alert alert-danger" style="background-color:red; color:white;">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('danger') }}
                </div>
            @endif
            @auth
                <input type="hidden" name="userRegister" value="{{ Auth::user()->created_at }}" class="userRegister">
            @endauth

            <div class="tab-content">
                <div class="text-center loading-container" ></div>
                @include('digital.ppob.contents.content-pulsa')
                @include('digital.ppob.contents.content-data')
                <div class="tab-pane fade" id="telepon">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="pln">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="air">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane fade" id="game">
                    <p>Coming Soon</p>
                </div>
                <div class="tab-pane" id="uc">
                    <div class="text-center">
                        <img src="{{ asset('assets/digital/under-construction.png') }}" alt="" class="img-responsive w-50 h-50">
                    </div>
                </div>
            </div>
            <!-- End Tabs -->
        </div>
    </div>
</div>