@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="profile-detail bg-white">
    <div class="container pb-5">

        <div class="head d-table w-100">
            <div class="d-table-cell">
                <div class="d-table-cell align-middle">
                    <a href="{{ route('user.detail', ['username' => $user->username]) }}" class="btn user">
                        <img src="{{ asset('uploads/photos/large-'.$user->photo) }}">
                    </a>
                </div>
                <div class="d-table-cell align-middle pr-3">
                    <div class="name">{{ $user->name }}</div>

                    @if (!empty($user->place_birth))
                        <div class="location">{{ $user->kabupaten->name }}</div>
                    @endif
                    
                    <div class="location mt-2 text-warning">{{ $loyalty->name }}</div>
                </div>
            </div>
            <div class="d-table-cell align-middle text-right">
            @auth
                @if (Auth::user()->username != $user->username)
                <a href="{{ route('message.detail', ['username' => $user->username]) }}" class="btn message"><i class="far fa-envelope"></i></a>
                @endif
            @else
                <a href="{{ route('message.detail', ['username' => $user->username]) }}" class="btn message"><i class="far fa-envelope"></i></a>
            @endauth
            </div>
        </div>

        <div class="smarttab">
            <!-- Tabs -->

            <div class="scroll">
                <ul class="nav nav-tabs smart product bg-white">
                    <li>
                        <a href="#tab1" class="active">Produk @if($products->count() > 0) ({{ $products->count() }}) @endif</a>
                    </li>
                    <li>
                        <a href="#tab2">Ulasan @if($reviews->count() > 0) ({{ $reviews->count() }}) @endif</a>
                    </li>
                    <li>
                        <a href="#tab3">Informasi</a>
                    </li>
                </ul>
            </div>

        @if (session('status'))
            <div class="alert alert-success">
                <button class="close fui-cross" data-dismiss="alert"></button>
                {{ session('status') }}
            </div>
        @endif
    
        @if (session('warning'))
            <div class="alert alert-danger">
                <button class="close fui-cross" data-dismiss="alert"></button>
                {{ session('warning') }}
            </div>
        @endif
        
            <!-- Tab content -->
            <div class="tab-content">
                <div class="tab-pane active product" id="tab1">
                                            
                @if ($products->isEmpty())
                    <div class="notfound mt-3">Belum Ada Produk</div>
                @endif
                    
                    <div class="row">
                    @foreach ($products as $item)
                        @include('layouts.card-product')
                    @endforeach
                    
                    </div>
                    
                    <div class="text-center">
                        {{ $products->links() }}
                    </div>
                
                </div>
                <div class="tab-pane review" id="tab2">
                                    
                @if ($reviews->isEmpty())
                    <div class="notfound mt-3">Belum Ada Ulasan</div>
                @endif

                @foreach ($reviews as $list)
                    @foreach ($list->review_buyer as $item)
                        @include('layouts.list-review')
                    @endforeach
                @endforeach
                
                </div>
                <div class="tab-pane info" id="tab3">
                    
                    <div class="mt-3">

                    @if (!empty($user->merchant_id))

                        @if (!empty($user->merchant->address_id))
                        <div class="mb-3">
                            <div class="font-weight-bold">Alamat</div>
                            <div>{{ $user->merchant->address->address }}, {{ $user->merchant->address->desa->name }}, {{ $user->merchant->address->kecamatan->name }}, {{ $user->merchant->address->kabupaten->name }}, {{ $user->merchant->address->provinsi->name }}, {{ $user->merchant->address->postal_code }}</div>
                        </div>
                        @endif

                    @endif
                    
                    @if (!empty($user->bio))
                        <div class="mb-3">
                            <div class="font-weight-bold">Catatan</div>
                            <div>{{ $user->bio }}</div>
                        </div>
                    @endif
                        
                        <div class="mb-3">
                            <div class="font-weight-bold">Bergabung Pada</div>
                            <div>{{ $user->created_at->format('d F Y') }}</div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

@endsection
