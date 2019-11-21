@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section address-list">
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
                        <ul class="nav nav-tabs setting bg-white">
                            <li><a href="{{ route('setting') }}">Data Diri</a></li>
                            <li><a href="{{ route('setting.password') }}">Password</a></li>
                            <li><a href="{{ route('setting.address') }}" class="active">Daftar Alamat</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('setting.address.add') }}" class="btn btn-rounded btn-primary btn-block">Tambah Alamat</a>

                @if (session('status'))
                    <div class="alert alert-success mt-3">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('status') }}
                    </div>
                @endif
            
                @if (session('warning'))
                    <div class="alert alert-danger mt-3">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('warning') }}
                    </div>
                @endif
                    
                    <div class="list">
                        <div class="table">

                        @foreach ($lists as $item)
                            <a href="{{ route('setting.address.edit', ['id' => $item->id]) }}" class="d-table">
                                <div class="mb-2">
                                    <small>Alamat {{ $item->address_name }}</small>
                                </div>
                                <div class="mb-2">
                                    <b>{{ $item->first_name.' '.$item->last_name }}</b>
                                    <div>{{ $item->phone }}</div>
                                </div>
                                <div class="mb-2">
                                    <div>{{ $item->address }}</div>
                                    <div>{{ $item->desa->name.', '.$item->kecamatan->name.', '.$item->kabupaten->name.', '.$item->provinsi->name.', '.$item->postal_code }}</div>
                                </div>
                            </a>
                        @endforeach
                        
                        </div>
                    </div>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection