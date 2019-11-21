@extends('layouts.auth')

@section('title'){{ str_replace('[TITLE]', 'Masuk', $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', 'Masuk', $seo_description) }}@endsection

@section('content')

<section class="bg-brand auth">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <a href="{{ route('home') }}" class="pt-5 pb-4 d-block">
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                </a>

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

                <form method="POST" action="{{ route('login') }}" class="mt-4">
                    
                    {{ csrf_field() }}

                    <input type="hidden" name="remember" value="on">

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" autofocus required value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-white">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control input-password" id="password" aria-describedby="password" placeholder="Password" required>

                            <span class="input-group-btn clean">
                                <a href="#" class="btn show-password"><i class="far fa-eye"></i></a>
                            </span>
                        </div>
                    
                    @if ($errors->has('password'))
                        <small id="password" class="form-text text-white">
                            {{ $errors->first('password') }}
                        </small>
                    @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-brand-white btn-block">Masuk</button>

                </form>

                <div class="divider text-white my-4">atau</div>

                <div class="social pb-5">
                    <a href="{{ url('/facebook') }}" class="btn btn-rounded btn-inline-block btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ url('/google') }}" class="btn btn-rounded btn-inline-block btn-google">
                        <i class="fab fa-google"></i>
                    </a>
                </div>

                <div class="text mt-5 mb-3">Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a></div>

                <a href="{{ route('password.request') }}" class="btn btn-rounded btn-brand-outline-white btn-block mb-5">Reset Password</a>

            </div>
        </div>
    </div>
</section>

@endsection
