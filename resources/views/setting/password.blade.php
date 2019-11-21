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
                        <ul class="nav nav-tabs setting bg-white">
                            <li><a href="{{ route('setting') }}">Data Diri</a></li>
                            <li><a href="{{ route('setting.password') }}" class="active">Password</a></li>
                            <li><a href="{{ route('setting.address') }}">Daftar Alamat</a></li>
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

                    <form class="mb-5 pb-5" method="POST" action="{{ route('setting.password.update') }}">

                        {{ csrf_field() }}

                        <div class="form-group mb-2 {{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <input type="password" required class="form-control" name="old_password" placeholder="Password Lama" />
            
                        @if ($errors->has('old_password'))
                            <small id="old_password" class="form-text text-danger">
                                {{ $errors->first('old_password') }}
                            </small>
                        @endif
                        </div>
                        
                        <div class="form-group mb-2 {{ $errors->has('new_password') ? ' has-error' : '' }}">
                            <input type="password" required class="form-control" name="new_password" placeholder="Password Baru" />
                
                        @if ($errors->has('new_password'))
                            <small id="new_password" class="form-text text-danger">
                                {{ $errors->first('new_password') }}
                            </small>
                        @endif
                        </div>
                        
                        <div class="form-group mb-4 {{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                            <input type="password" required class="form-control" name="confirm_password" placeholder="Konfirmasi Password" />
                    
                        @if ($errors->has('confirm_password'))
                            <small id="confirm_password" class="form-text text-danger">
                                {{ $errors->first('confirm_password') }}
                            </small>
                        @endif
                        </div>
                        
                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Ubah Password</button>

                    </form>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection