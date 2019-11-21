@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Ubah {{ $pageTitle }}</h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body">
                <div class="ks-nav-body-wrapper">
                    <div class="container-fluid">

                    @if (session('status'))
                        <div class="alert alert-success ks-solid-light ks-active-border mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" class="la la-close"></span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-danger ks-solid-light ks-active-border mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" class="la la-close"></span>
                            </button>
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card panel">
                                    <div class="card-block">
                                        <form method="post" action="{{ route('admin.'.$page.'.update') }}" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                <label>Nama</label>
                                                <input type="text"
                                                        name="name" 
                                                        value="{{ $item->name }}"
                                                        class="form-control"
                                                        readonly
                                                        data-validation="required"
                                                        data-validation-error-msg="Nama harus di isi."
                                                        placeholder="Nama">

                                                @if ($errors->has('name'))
                                                    <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                                                @endif
                                            </div>
                                            
                                            <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                                <label>Kode Promo</label>
                                                <input type="text"
                                                        name="code" 
                                                        value="{{ $item->code }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Kode Promo harus di isi."
                                                        placeholder="Kode Promo">

                                                @if ($errors->has('code'))
                                                    <small class="form-text text-danger">{{ $errors->first('code') }}</small>
                                                @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('promo_type_id') ? ' has-danger' : '' }}">
                                                    <label>Promo Type</label>
                                                    <select name="promo_type_id" id="promo_type_id" class="form-control ks-select-placeholder-single" >
                                                        <option value="0">--Select Promo Type --</option>
                                                        @foreach ($promo_type as $value)
                                                            <option value="{{ $value->id }}" @if($item->type_id === $value->id) selected="selected" @endif>{{ $value->name }}</option>">
                                                        @endforeach
                                                    </select>
                                                
                                                    @if ($errors->has('promo_type_id '))
                                                        <small class="form-text text-danger">{{ $errors->first('expired') }}</small>
                                                    @endif
                                                </div>
                                            <div class="form-group{{ $errors->has('discount_type') ? ' has-danger' : '' }}">
                                                <label>Discount Type</label>
                                                <select name="discount_type_id" id="dicount_type" class="form-control ks-select-placeholder-single" >
                                                    <option value="0">--Select Discount --</option>
                                                    @foreach ($discount_type as $value)
                                                        <option value="{{ $value->id }}"@if($item->discount_type_id === $value->id) selected="selected" @endif>{{ $value->type }}</option>
                                                    @endforeach
                                                </select>
                                            
                                                @if ($errors->has('discount_type'))
                                                    <small class="form-text text-danger">{{ $errors->first('expired') }}</small>
                                                @endif
                                            </div>
                                                
                                            <div class="form-group{{ $errors->has('product_type') ? ' has-danger' : '' }}">
                                                <label>Promo Product Type :</label><br>
                                                <input type="checkbox" id="select_all_product_type" />Check all<br />
                                                @foreach ($product_type as $key=>$value)
                                                    <input class = "product_type" type="checkbox" name="product_type[]" value="{{$value->id}}"@if($item->product_type->contains('id',$value->id)) checked @endif/>{{$value->name}}
                                                    <br>
                                                @endforeach
                                                <br>
                                            
                                                @if ($errors->has('product_type'))
                                                    <small id="product_type" class="form-text text-danger">
                                                        {{ $errors->first('product_type') }}
                                                    </small>
                                                @endif
                                            </div>
                                           

                                            <div class="form-group{{ $errors->has('transaction_min') ? ' has-danger' : '' }}">
                                                <label>Nilai Minimal Transaksi</label>
                                                <input type="text"
                                                        name="transaction_min"
                                                        class="form-control"
                                                        id="transaction_min"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Nilai Minimal Transaksi"
                                                        value="{{ $item->transaction_min }}">
                                            
                                            @if ($errors->has('transaction_min'))
                                                <small id="transaction_min" class="form-text text-danger">
                                                    {{ $errors->first('transaction_min') }}
                                                </small>
                                            @endif
                                            </div>
                            

                                            @if ($item->type_id  == 3 )
                                                {{-- <div class="form-group">
                                                    <label>Promo Type :</label><br>
                                                    <select class="form-control" name="item_id">
                                                        @foreach($promo_type as $type)
                                                            <option value="{{$type->id}}" name="type_id">{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="form-group{{ $errors->has('ppob_type') ? ' has-danger' : '' }}">
                                                    <label>Jenis Penggunaan Promo :</label><br>
                                                    <input type="checkbox" onclick="toggle(this);" />Check all?<br />
                                                    @foreach ($ppob_type as $key => $check)
                                                        @if ( $ppob_type_check->contains('ppob_type_id',$check->id))
                                                            <input type="checkbox" name="ppob_type[]" value="{{$check->id}}" checked/>{{$check->name}}<br>
                                                            
                                                        @else
                                                            <input type="checkbox" name="ppob_type[]" value="{{$check->id}}"/>{{$check->name}}<br>
                                                        @endif
                                                    @endforeach
                                                
                                                    @if ($errors->has('ppob_type'))
                                                        <small id="ppob_type" class="form-text text-danger">
                                                            {{ $errors->first('ppob_type') }}
                                                        </small>
                                                    @endif
                                                </div>
                                    
                                                <div class="form-group{{ $errors->has('term_condition') ? ' has-danger' : '' }}">
                                                    <label>Term Condition</label>
                                                    <textarea name="term_condition"
                                                            required 
                                                            id="ks-summernote-editor-default"
                                                            >{{ $item->term_condition}}</textarea>

                                                    @if ($errors->has('term_condition'))
                                                        <small class="form-text text-danger">{{ $errors->first('term_condition') }}</small>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($item->type_id == 3 )
                                            <label>Diskon Harga Dengan : </label><br>
                                            <input type="radio" name="check_type"  class="listing" value="0" id="manual" @if($item->discount_price !== null) checked="true" @endif/>Rupiah (Rp) 
                                            <input type="radio" name="check_type"  class="listing" value="1" id="otomatis" @if($item->discount_price === null) checked="true" @endif/>Persentase (%)
                                            <br/><br/>
                                            @endif
                                        
                                            @if ($item->type_id == 1)
                                            <div class="form-group{{ $errors->has('shipping_code') ? ' has-danger' : '' }}">
                                                <label>Kode Shipping</label>
                                                <input type="text"
                                                        name="shipping_code"
                                                        class="form-control"
                                                        id="shipping_code"
                                                        readonly
                                                        data-validation="required"
                                                        data-validation-error-msg="Kode Shipping harus di isi."
                                                        placeholder="Kode Shipping"
                                                        value="{{ $item->shipping_code }}">
                                            
                                                @if ($errors->has('shipping_code'))
                                                    <small id="shipping_code" class="form-text text-danger">
                                                        {{ $errors->first('shipping_code') }}
                                                    </small>
                                                @endif
                                            </div>
                                            @endif

                                            

                                            @if ($item->type_id == 1 || ($item->type_id == 3 ))
                                            <div id="discount_price_form" class="form-group{{ $errors->has('discount_price') ? ' has-danger' : '' }}">
                                                <label>Nilai Diskon (Rp)</label>
                                                <input type="text"
                                                        name="discount_price"
                                                        class="form-control"
                                                        id="discount_price"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Nilai Diskon (Rp)"
                                                        value="{{ $item->discount_price }}">
                                            
                                                @if ($errors->has('discount_price'))
                                                    <small id="discount_price" class="form-text text-danger">
                                                        {{ $errors->first('discount_price') }}
                                                    </small>
                                                @endif
                                            </div>
                                            @endif

                                            @if ($item->type_id == 2 || ($item->type_id == 3 ))
												<div id="discount_max_form"  class="form-group{{ $errors->has('discount_max') ? ' has-danger' : '' }}">
                                                <label>Nilai Maksimal Diskon (Rp)</label>
                                                <input type="text"
                                                        name="discount_max"
                                                        class="form-control"
                                                        id="discount_max"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Nilai Maksimal Diskon (Rp)"
                                                        value="{{ $item->discount_max }}">
                                            
                                            @if ($errors->has('discount_max'))
                                                <small id="discount_max" class="form-text text-danger">
                                                    {{ $errors->first('discount_max') }}
                                                </small>
                                            @endif
											</div>
											
											<div id="discount_percent_form" class="form-group{{ $errors->has('discount_percent') ? ' has-danger' : '' }}">
                                                <label>Persentase Diskon (%)</label>
                                                <input type="text"
                                                        name="discount_percent"
                                                        class="form-control"
                                                        id="discount_percent"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Persentase Diskon (%)"
                                                        value="{{ $item->discount_percent }}">
                                            
                                            @if ($errors->has('discount_percent'))
                                                <small id="discount_percent" class="form-text text-danger">
                                                    {{ $errors->first('discount_percent') }}
                                                </small>
                                            @endif
                                            </div>
                                            @endif
                                            
                                            <div class="form-group{{ $errors->has('expired') ? ' has-danger' : '' }}">
                                                <label>Batas Waktu</label>
                                                <input type="text"
                                                        name="expired"
                                                        value="{{ $item->expired }}"
                                                        class="form-control calendar"
                                                        data-validation="required"
                                                        data-validation-error-msg="Batas Waktu harus di isi."
                                                        data-enable-time="true"
                                                        data-time_24hr="true"
                                                        data-enable-seconds="true"
                                                        placeholder="Batas Waktu">

                                                @if ($errors->has('expired'))
                                                    <small class="form-text text-danger">{{ $errors->first('expired') }}</small>
                                                @endif
                                            </div>

                                            <div class="form-group{{ $errors->has('quota') ? ' has-danger' : '' }}">
                                                <label>Kuota Harian</label>
                                                <input type="text"
                                                        name="quota"
                                                        class="form-control"
                                                        id="quota"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Kuota Harian"
                                                        value="{{ $item->quota }}">
                                            
                                            @if ($errors->has('quota'))
                                                <small id="quota" class="form-text text-danger">
                                                    {{ $errors->first('quota') }}
                                                </small>
                                            @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('quota_user_day') ? ' has-danger' : '' }}">
                                                <label>Kuota User Harian</label>
                                                <input type="text"
                                                        name="quota_user_day"
                                                        class="form-control"
                                                        id="quota_user_day"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Kuota User Perhari"
                                                        value="{{ $item->quota_user_day }}">
                                            
                                                @if ($errors->has('quota_user_day'))
                                                    <small id="quota_user_day" class="form-text text-danger">
                                                        {{ $errors->first('quota_user_day') }}
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <div class="form-group{{ $errors->has('quota_user_total') ? ' has-danger' : '' }}">
                                                <label>Kuota Total User</label>
                                                <input type="text"
                                                        name="quota_user_total"
                                                        class="form-control"
                                                        id="quota_user_total"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Kuota Total"
                                                        value="{{ $item->quota_user_total  }}">
                                                @if ($errors->has('quota_user_total'))
                                                    <small id="quota_user_total" class="form-text text-danger">
                                                        {{ $errors->first('quota_user_total') }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('total_quota') ? ' has-danger' : '' }}">
                                                <label>Kuota Total</label>
                                                <input type="text"
                                                        name="total_quota"
                                                        class="form-control"
                                                        id="total_quota"
                                                        data-validation="required|number"
                                                        data-validation-error-msg="Harus di isi angka."
                                                        placeholder="Kuota Total"
                                                        value="{{ $item->total_quota  }}">
                                                @if ($errors->has('total_quota'))
                                                    <small id="total_quota" class="form-text text-danger">
                                                        {{ $errors->first('total_quota') }}
                                                    </small>
                                                @endif
                                            </div>
                                            

                                            <div class="row my-0 py-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Diterbitkan</label>
                                                        <div class="text-dark">
                                                            <span class="mr-2">{{ $item->created_at }}</span>
                                                            <small>{{ $item->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Diperbarui</label>
                                                        <div class="text-dark">
                                                            <span class="mr-2">{{ $item->updated_at }}</span>
                                                            <small>{{ $item->updated_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row my-0">
                                                
                                                <div class="col-md-12 align-bottom">
                                                    <div class="mt-3 pt-2">
                                                        <button class="btn btn-success btn-block ks-split">
                                                            <span class="la la-check ks-icon"></span>
                                                            <span class="ks-text">Simpan Perubahan</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>

                                        <hr>
                                        
                                        <form method="post" action="{{ route('admin.'.$page.'.delete') }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $item->id }}">

                                            <button type="submit" class="btn btn-outline-danger btn-block ks-split">
                                                <span class="la la-trash ks-icon"></span>
                                                <span class="ks-text"><strong>Hapus</strong> {{ $pageTitle }}</span>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection