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
                            <div class="card">
                                <div class="card-block">
                                    <form method="post" action="{{ route('admin.'.$page.'.update', ['id' => $item->id]) }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                                    <label>Tipe Pembayaran</label>
                                                    <select class="form-control ks-select-placeholder-single" name="type" >
                                                        <option value="0" @if($item->type === 0) selected="selected" @endif>e-Channel</option>
                                                        <option value="1" @if($item->type === 1) selected="selected" @endif>Balance</option>
                                                        <option value="2" @if($item->type === 2) selected="selected" @endif>Installment</option>
                                                    </select>
    
                                                    @if ($errors->has('type'))
                                                        <small class="form-text text-danger">{{ $errors->first('type') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                                    <label>Status</label>
                                                    <select class="form-control ks-select-placeholder-single" name="status" >
                                                        <option value="1" @if($item->status === 1) selected="selected" @endif>Show</option>
                                                        <option value="0" @if($item->status === 0) selected="selected" @endif>Hidden</option>
                                                    </select>
    
                                                    @if ($errors->has('status'))
                                                        <small class="form-text text-danger">{{ $errors->first('status') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                                    <label>Title</label>
                                                    <input type="text"
                                                        name="title" 
                                                        value="{{ $item->title }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Title harus di isi."
                                                        placeholder="Title">
        
                                                    @if ($errors->has('title'))
                                                        <small class="form-text text-danger">{{ $errors->first('title') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('slug') ? ' has-danger' : '' }}">
                                                    <label>Slug</label>
                                                    <input type="text"
                                                        name="slug" 
                                                        value="{{ $item->slug }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Slug harus di isi."
                                                        placeholder="Slug">
    
                                                    @if ($errors->has('slug'))
                                                        <small class="form-text text-danger">{{ $errors->first('slug') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                    <label>Deskripsi Nama Pembayaran</label>
                                                    <input type="text"
                                                        name="name" 
                                                        value="{{ $item->name }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Deskripsi Nama Pembayaran harus di isi."
                                                        placeholder="Deskripsi Nama Pembayaran">
        
                                                    @if ($errors->has('name'))
                                                        <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('image_path') ? ' has-danger' : '' }}">
                                                    <label>Logo</label>
                                                    <div id="previmage"></div>

                                                    @if ($item->image_path)
                                                        <div><img src="{{ asset('assets/'.$directory.'/'.$item->image_path) }}" width="100%" style="max-width: 300px;" class="mb-3" id="currentimage"></div>
                                                    @endif
    
                                                    <div class="custom-file">
                                                        <label class="btn btn-outline-success ks-btn-file">
                                                            <span class="la la-cloud-upload ks-icon"></span>
                                                            <span class="ks-text">Upload Logo</span>
                                                            <input type="file"
                                                                name="image_path"
                                                                id="choseimage"
                                                                class="custom-file-input">
                                                        </label>
    
                                                        <span class="custom-file-control"></span>
                                                    </div>
    
                                                    @if ($errors->has('image_path'))
                                                        <small class="form-text text-danger">{{ $errors->first('image_path') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-12">
                                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                                    <label>Deskripsi informasi pembayaran</label>
                                                    <textarea name="description"
                                                            required="required" 
                                                            id="ks-summernote-editor-default"
                                                            >{{ $item->description }}</textarea>
    
                                                    @if ($errors->has('description'))
                                                        <small class="form-text text-danger">{{ $errors->first('description') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success btn-block ks-split">
                                                        <span class="la la-check ks-icon"></span>
                                                        <span class="ks-text"><strong>Update</strong> {{ $pageTitle }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection