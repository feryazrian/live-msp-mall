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
                            <li><a href="{{ route('setting') }}" class="active">Data Diri</a></li>
                            <li><a href="{{ route('setting.password') }}">Password</a></li>
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

                @if(empty(Auth::user()->place_birth))
                    <div class="alert alert-danger">
                        Harap segera melengkapi Data Diri Anda untuk menikmati sepenuhnya Layanan {{ config('app.name') }}
                    </div>
                @endif

                    <form class="mb-5 pb-5" method="POST" action="{{ route('setting.profile.update') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}

                        <div class="form-group text-center mb-4 {{ $errors->has('photo') ? ' has-error' : '' }}">
                            <div class="fileinput fileinput-new filephoto" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                    <img data-src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" src="{{ asset('uploads/photos/large-'.Auth::user()->photo) }}" alt="">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                    <span class="btn btn-primary btn-embossed btn-file">
                                        <span class="fileinput-new">Pilih Foto</span>
                                        <span class="fileinput-exists">Ubah Foto</span>
                                        <input type="file" name="photo">
                                    </span>
                                    <a href="#" class="btn btn-primary btn-embossed fileinput-exists" data-dismiss="fileinput">Hapus Foto</a>
                                </div>
                            </div>
                
                            @if ($errors->has('photo'))
                                <small id="photo" class="form-text text-danger">
                                    {{ $errors->first('photo') }}
                                </small>
                            @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" class="form-control" name="email" placeholder="Email" required value="{{ Auth::user()->email }}" style="color:#333;" readonly />
                
                        @if ($errors->has('email'))
                            <small id="email" class="form-text text-danger">
                                {{ $errors->first('email') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('username') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="username" placeholder="Username" required value="{{ Auth::user()->username }}" style="color:#333;" readonly />
            
                        @if ($errors->has('username'))
                            <small id="username" class="form-text text-danger">
                                {{ $errors->first('username') }}
                            </small>
                        @endif
                        </div>

                    @if (!empty(Auth::user()->merchant_id))
                        <div class="form-group mb-4 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama" required value="{{ Auth::user()->name }}" style="color:#333;" readonly />
                        
                        @if ($errors->has('name'))
                            <small id="name" class="form-text text-danger">
                                {{ $errors->first('name') }}
                            </small>
                        @endif
                        </div>
                    @else
                        <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama" required value="{{ Auth::user()->name }}">
                        
                        @if ($errors->has('name'))
                            <small id="name" class="form-text text-danger">
                                {{ $errors->first('name') }}
                            </small>
                        @endif
                        </div>
                    @endif

                        <div class="form-group mb-2 {{ $errors->has('place_birth') ? ' has-error' : '' }}">
                            <select name="place_birth" class="form-control select select-smart select-secondary select-block text-left m-0">
                                <option value="">Tempat Lahir</option>
                                
                            @php $match=''; @endphp
                            @foreach ($places as $kabupaten)
                            @if ($match != $kabupaten->provinsi->name)
                                <optgroup label="{{ $kabupaten->provinsi->name }}">
                            @endif
                                    <option value="{{ $kabupaten->id }}" @if(Auth::user()->place_birth == $kabupaten->id) selected="selected" @endif>{{ $kabupaten->name }}</option>
                            @if ($match != $kabupaten->provinsi->name)
                                </optgroup>
                            @endif
                            @php $match = $kabupaten->provinsi->name; @endphp
                            @endforeach
                            
                            </select>
                        
                        @if ($errors->has('place_birth'))
                            <small id="place_birth" class="form-text text-danger">
                                {{ str_replace('place_birth', 'Tempat Lahir', $errors->first('place_birth')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('date_birth') ? ' has-error' : '' }}">
                            <input type="text" name="date_birth" class="datepicker-01 form-control" id="date_birth" aria-describedby="date_birth" placeholder="Tanggal Lahir" value="{{ Auth::user()->date_birth }}" required>
                    
                        @if ($errors->has('date_birth'))
                            <small id="date_birth" class="form-text text-danger">
                                {{ $errors->first('date_birth') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" required value="{{ Auth::user()->phone }}" class="numeric form-control" name="phone" placeholder="Nomor Telpon (62)" />
                
                        @if ($errors->has('phone'))
                            <small id="phone" class="form-text text-danger">
                                {{ $errors->first('phone') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('bio') ? ' has-error' : '' }}">
                            <textarea placeholder="Catatan" class="form-control" name="bio" rows="5">{{ Auth::user()->bio }}</textarea>
            
                        @if ($errors->has('bio'))
                            <small id="bio" class="form-text text-danger">
                                {{ $errors->first('bio') }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Simpan Perubahan</button>

                    </form>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection