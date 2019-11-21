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

                <small class="text-white"><b>Lupa Password ?</b> Masukkan alamat E-Mail anda pada formulir di bawah ini untuk melakukan Reset Password</small>

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

                <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="email" placeholder="Alamat E-mail" autofocus required value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-white">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-brand-white btn-block">Kirim Tautan Reset Password</button>

                </form>

            </div>
        </div>
    </div>
</section>

@endsection
