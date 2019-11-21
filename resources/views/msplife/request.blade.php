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

                <form class="mb-5 pb-5" method="POST" action="{{ route('msplife.submit') }}">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" name="email" placeholder="Email" required value="{{ Auth::user()->email }}" style="color:#333;" readonly />
            
                    @if ($errors->has('email'))
                        <small id="email" class="form-text text-danger">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('username') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="username" placeholder="Username" required value="{{ Auth::user()->username }}" style="color:#333;" readonly />
        
                    @if ($errors->has('username'))
                        <small id="username" class="form-text text-danger">
                            {{ $errors->first('username') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" required class="form-control" name="password" placeholder="Password" />
        
                    @if ($errors->has('password'))
                        <small id="password" class="form-text text-danger">
                            {{ $errors->first('password') }}
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
