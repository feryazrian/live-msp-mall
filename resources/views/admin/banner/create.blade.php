@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Tambah {{ $pageTitle }}</h3>
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

					                    <form method="post" action="{{ route('admin.'.$page.'.store') }}" enctype="multipart/form-data">
					              
					                        {{ csrf_field() }}

					                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
					                            <label>title</label>
					                            <input type="text"
					                            		name="title" 
					                            		value="{{ old('title') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Title harus di isi."
					                                	placeholder="title">

					                            @if ($errors->has('title'))
					                            	<small class="form-text text-danger">{{ $errors->first('title') }}</small>
					                            @endif
											</div>

											<div class="form-group{{ $errors->has('slug') ? ' has-danger' : '' }}">
					                            <label>Slug</label>
					                            <input type="text"
					                            		name="slug" 
					                            		value="{{ old('slug') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Slug harus di isi."
					                                	placeholder="slug">

					                            @if ($errors->has('slug'))
					                            	<small class="form-text text-danger">{{ $errors->first('slug') }}</small>
					                            @endif
											</div>
								
											<div class="form-group{{ $errors->has('flag') ? ' has-danger' : '' }}">
												<label>Status</label>
												<select class="form-control ks-select-placeholder-single" name="flag" >
													<option value="1" selected>Tayang</option>
													<option value="0">Tidak Tayang</option>
												</select>

												@if ($errors->has('flag'))
													<small class="form-text text-danger">{{ $errors->first('flag') }}</small>
												@endif
											</div>

											<div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
												<label>Deskripsi</label>
												<textarea name="description"
														required="required" 
														id="ks-summernote-editor-default"
														>{{ old('description') }}</textarea>

												@if ($errors->has('description'))
													<small class="form-text text-danger">{{ $errors->first('description') }}</small>
												@endif
											</div>

											<div class="form-group{{ $errors->has('link') ? ' has-danger' : '' }}">
					                            <label>link</label>
					                            <input type="text"
					                            		name="link" 
					                            		value="{{ old('link') }}"
					                                	class="form-control"
					                                	placeholder="link">

					                            @if ($errors->has('link'))
					                            	<small class="form-text text-danger">{{ $errors->first('link') }}</small>
					                            @endif
											</div>

											<div class="form-group{{ $errors->has('promo') ? ' has-danger' : '' }}">
												<label>Jenis Penggunaan Promo :</label><br>
												{{-- <input type="checkbox" name="ppob_type[]" value="0" checked />Semua Jenis<br> --}}
												{{-- <input type="checkbox" onclick="toggle(this);" />Check all?<br /> --}}

												@foreach ($promo as $it)
													<input type="radio" name="promo[]" value="{{$it->id}}"/>{{$it->name}}
												<br/>
												@endforeach
												<br>
											
												@if ($errors->has('promo'))
													<small id="ppob_type" class="form-text text-danger">
														{{ $errors->first('promo') }}
													</small>
												@endif
											</div>

											<script>
												function toggle(source) {
													var checkboxes = document.querySelectorAll('input[type="checkbox"]');
													for (var i = 0; i < checkboxes.length; i++) {
														if (checkboxes[i] != source)
															checkboxes[i].checked = source.checked;
													}
												}
											</script>


									
											<div class="form-group{{ $errors->has('image_path') ? ' has-danger' : '' }}">
					                            <label>Image Path</label>
				                                <div id="previmage3"></div>

				                                <div class="custom-file">
				                                    <label class="btn btn-outline-success ks-btn-file">
				                                    	<span class="la la-cloud-upload ks-icon"></span>
				                                        <span class="ks-text">Pilih Image</span>
				                                        <input type="file"
				                                        		name="image_path"
						                                    	data-validation="required"
						                                    	data-validation-error-msg="Cover harus di isi."
				                                        		id="choseimage3"
				                                        		class="custom-file-input">
				                                    </label>

				                                    <span class="custom-file-control"></span>
				                                </div>

					                            @if ($errors->has('image_path'))
						                            <small class="form-text text-danger">{{ $errors->first('image_path') }}</small>
					                            @endif
											</div> 
											
											<div class="form-group{{ $errors->has('publish_date') ? ' has-danger' : '' }}">
												<label>Tanggal Publikasi</label>
												<input type="text"
														name="publish_date"
					                            		value="{{ old('publish_date') }}"
														class="form-control calendar"
														data-validation="required"
														data-validation-error-msg="tanggal publikasi harus di isi."
														data-enable-time="true"
														data-time_24hr="true"
														data-enable-seconds="true"
														placeholder="Publish Date">

												@if ($errors->has('publish_date'))
													<small class="form-text text-danger">{{ $errors->first('publish_date') }}</small>
												@endif
											</div>

											<div class="form-group{{ $errors->has('end_date') ? ' has-danger' : '' }}">
												<label>Batas Akhir Waktu</label>
												<input type="text"
														name="end_date"
					                            		value="{{ old('end_date') }}"
														class="form-control calendar"
														data-validation="required"
														data-validation-error-msg="End date harus di isi."
														data-enable-time="true"
														data-time_24hr="true"
														data-enable-seconds="true"
														placeholder="Batas Waktu">

												@if ($errors->has('end_date'))
													<small class="form-text text-danger">{{ $errors->first('end_date') }}</small>
												@endif
											</div>

					                        <div class="form-group">
							                    <button type="submit" id=btnSubmit class="btn btn-success btn-block ks-split">
								                    <span class="la la-check ks-icon"></span>
								                    <span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
							                    </button>
											</div>
											
										</form>
										<script type="text/javascript" src="/public/assets/js/ppob.js"></script>

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