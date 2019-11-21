@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Edit {{ $pageTitle }}</h3>
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
                                    <form method="post" action="{{ route('admin.'.$page.'.update', ['id' => $item->id]) }}">
                                        {{ csrf_field() }}

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('start_time') ? ' has-danger' : '' }}">
                                                    <label>Waktu Mulai Tayang</label>
                                                    <input type="text"
                                                            name="start_time"
                                                            value="{{ $item->start_time }}"
                                                            class="form-control calendar"
                                                            data-enable-time="true"
                                                            data-time_24hr="true"
                                                            data-enable-seconds="true"
                                                            data-validation="required"
                                                            data-validation-error-msg="Waktu mulai tayang harus diisi."
                                                            placeholder="Waktu Mulai Tayang">
        
                                                    @if ($errors->has('start_time'))
                                                        <small class="form-text text-danger">{{ $errors->first('start_time') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('end_time') ? ' has-danger' : '' }}">
                                                    <label>Waktu Selesai Tayang (Optional)</label>
                                                    <input type="text"
                                                            name="end_time"
                                                            value="{{ $item->end_time }}"
                                                            class="form-control calendar"
                                                            data-enable-time="true"
                                                            data-time_24hr="true"
                                                            data-enable-seconds="true"
                                                            placeholder="Waktu Selesai Tayang">
        
                                                    @if ($errors->has('end_time'))
                                                        <small class="form-text text-danger">{{ $errors->first('end_time') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
                                                    <label>Link</label>
                                                    <input type="text"
                                                        name="url" 
                                                        value="{{ $item->url }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Link live harus di isi."
                                                        placeholder="Link Live">
        
                                                    @if ($errors->has('url'))
                                                        <small class="form-text text-danger">{{ $errors->first('url') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('show') ? ' has-danger' : '' }}">
                                                    <label>Status</label>
                                                    <select class="form-control ks-select-placeholder-single" name="show" >
                                                        <option value="1" @if($item->show === 1) selected="selected" @endif>Tayang</option>
                                                        <option value="0" @if($item->show === 0) selected="selected" @endif>Tidak Tayang</option>
                                                    </select>
    
                                                    @if ($errors->has('show'))
                                                        <small class="form-text text-danger">{{ $errors->first('show') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                                    <label>Judul</label>
                                                    <input type="text"
                                                        name="title" 
                                                        value="{{ $item->title }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Judul live harus di isi."
                                                        placeholder="Judul Live">
        
                                                    @if ($errors->has('title'))
                                                        <small class="form-text text-danger">{{ $errors->first('title') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('episode') ? ' has-danger' : '' }}">
                                                    <label>Episode</label>
                                                    <input type="text"
                                                        name="episode" 
                                                        value="{{ $item->episode }}"
                                                        class="form-control"
                                                        data-validation="required"
                                                        data-validation-error-msg="Episode Live harus di isi."
                                                        placeholder="Episode Live">
        
                                                    @if ($errors->has('episode'))
                                                        <small class="form-text text-danger">{{ $errors->first('episode') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0">
                                            <div class="col-md-12">
                                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                                    <label>Deskripsi</label>
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