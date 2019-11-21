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

            <div class="col-md-12 page-content col-lg-9 py-4">

                <div class="page-title mb-4">{{ $pageTitle }}</div>

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

                <form class="mb-5 pb-5" method="POST" action="{{ route('ads.store') }}">
                    
                    {{ csrf_field() }}

                    @auth
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                    @endauth

                    <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama Lengkap" required value="{{ old('name') }}">
                    
                    @if ($errors->has('name'))
                        <small id="name" class="form-text text-danger">
                            {{ $errors->first('name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" required value="{{ old('email') }}">
                
                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-danger">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <input type="text" name="phone" class="numeric form-control" id="phone" aria-describedby="phone" placeholder="Nomor Telepon" required value="{{ old('phone') }}">
                    
                    @if ($errors->has('phone'))
                        <small id="phone" class="form-text text-danger">
                            {{ $errors->first('phone') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('position_id') ? ' has-error' : '' }}">
                        <select name="position_id" class="form-control select select-smart select-secondary select-block">
                            <option value="">Posisi Iklan</option>
                            
                        @foreach ($lists as $item)
                            <option value="{{ $item->id }}" @if(old('position_id') == $item->id) selected="selected" @endif>{{ $item->name.' '.$item->resolution }}</option>
                        @endforeach

                        </select>
                    
                    @if ($errors->has('position_id'))
                        <small id="position_id" class="form-text text-danger">
                            {{ str_replace('position_id', 'Posisi Iklan', $errors->first('position_id')) }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('content') ? ' has-error' : '' }}">
                        <textarea name="content" class="form-control" id="content" aria-describedby="content" placeholder="Informasi Produk yang akan di Iklankan" required rows="5">{{ old('content') }}</textarea>
                    
                    @if ($errors->has('content'))
                        <small id="content" class="form-text text-danger">
                            {{ $errors->first('content') }}
                        </small>
                    @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Kirim Sekarang</button>

                </form>

            </div>

        </div>

    </div>
</section>

@endsection
