@extends('layouts.auth')

@section('title'){{ str_replace('[TITLE]', 'Autentikasi '.$provider, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', 'Autentikasi '.$provider, $seo_description) }}@endsection

@section('content')

<section class="bg-brand auth">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <a href="{{ route('home') }}" class="pt-5 pb-4 d-block">
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                </a>

                <small class="text-white">Daftar melalui <b>{{ $provider }}</b></small>

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

                <form method="POST" action="{{ url($provider.'/'.$userToken.'/submit') }}" class="mt-4">
                    
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="provider_id" value="{{ $userId }}" />
                    <input type="hidden" name="provider" value="{{ $provider }}" />
                    <input type="hidden" name="provider_token" value="{{ $userToken }}" />

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" required value="{{ $userEmail }}">
                
                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-white">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="name" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama Lengkap" required value="{{ $userName }}">
                    
                    @if ($errors->has('name'))
                        <small id="name" class="form-text text-white">
                            {{ $errors->first('name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                        <input type="username" name="username" class="form-control" id="username" aria-describedby="username" placeholder="Username" required @if(!empty($userUsername)) value="{{ $userUsername }}" @else value="{{ str_slug($userName) }}" @endif>
                    
                    @if ($errors->has('username'))
                        <small id="username" class="form-text text-white">
                            {{ $errors->first('username') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" id="password" aria-describedby="password" placeholder="Password" required>
                
                    @if ($errors->has('password'))
                        <small id="password" class="form-text text-white">
                            {{ $errors->first('password') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" id="password" aria-describedby="password" placeholder="Konfirmasi Password" required>
                    </div>

                    <button type="submit" class="btn btn-rounded btn-brand-white btn-block">Daftar Sekarang</button>

                </form>

            </div>
        </div>
    </div>
</section>

@endsection
