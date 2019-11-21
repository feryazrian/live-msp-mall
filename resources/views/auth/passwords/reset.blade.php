@extends('layouts.auth')

@section('title'){{ str_replace('[TITLE]', 'Reset Password', $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', 'Reset Password', $seo_description) }}@endsection

@section('content')

<section class="bg-brand auth">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <a href="{{ route('home') }}" class="pt-5 pb-4 d-block">
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                </a>

                <small class="text-white">Masukkan alamat <b>E-Mail dan Password Baru</b> yang anda inginkan pada formulir di bawah ini untuk melakukan Reset Password</small>

            @if (session('status'))
                <div class="alert alert-success mt-2">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('status') }}
                </div>
            @endif
        
            @if (session('warning'))
                <div class="alert alert-danger mt-2">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('warning') }}
                </div>
            @endif

                <form method="POST" action="{{ route('password.request') }}" class="mt-4">
                    
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" autofocus required value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-white">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
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

                    <button type="submit" class="btn btn-rounded btn-brand-white btn-block">Reset Password</button>

                </form>

            </div>
        </div>
    </div>
</section>

@endsection
