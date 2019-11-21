@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 page-content col-lg-9 pb-4">

                <div class="smarttab">
                    <!-- Tabs -->

                    <div class="scroll">
                        <ul class="nav nav-tabs product bg-white">
                            <li><a href="{{ route('product.stocked') }}">Produk Dijual</a></li>
                            <li><a href="{{ route('product.stockless') }}" class="active">Produk Tidak Dijual</a></li>
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
                        <div class="tab-pane active">
                                                    
                        @if ($productSoldout->isEmpty())
                            <div class="notfound">Belum Ada Produk Tersedia</div>
                        @endif
                            
                            <div class="product-lines">
                            @foreach ($productSoldout as $item)
                                <a href="{{ route('product.edit', ['slug' => $item->slug]) }}" class="product-line">
                                    <div class="image">
                                        <img src="{{ asset('uploads/products/small-'.$item->productphoto[0]->photo) }}">
                                    </div>
                                    <div class="content">
                                        <div class="title">{{ $item->name }}</div>

                                    @if (!empty($item->discount))
                                        <div class="price">{{ 'Rp '.number_format($item->price,0,',','.') }}<strike>{{ 'Rp '.number_format($item->discount,0,',','.') }}</strike></div>
                                    @else
                                        <div class="price">{{ 'Rp '.number_format($item->price,0,',','.') }}</div>
                                    @endif

                                    @switch($item->status)
                                        @case(1)
                                        <div class="status bg-green">{{ $item->action->name.' Disetujui' }}</div>
                                            @break

                                        @case(2)
                                        <div class="status bg-red">{{ $item->action->name.' Ditolak' }}</div>
                                            @break

                                        @default
                                        <div class="status bg-yellow">{{ $item->action->name.' Menunggu' }}</div>
                                    @endswitch

                                    </div>
                                    <div class="stats">
                                        <div class="stock">
                                            <span>Stok</span>
                                            <span>{{ $item->stock }}</span>
                                        </div>
                                        <div class="sold">
                                            <span>Terjual</span>
                                            <span>{{ $item->sold }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            </div>

                            <div class="text-center">
                                {{ $productSoldout->links() }}
                            </div>
                        
                        </div>
                    </div>
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
