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
											
											<div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
												<label>Pilih user :</label><br>
												<select class="form-control ks-select-placeholder-single" name="user_id" >
													@foreach ($coupon as $item)
														<option name="users[]" value="{{$item->user_id}}">{{$item->username}} [{{$item->email}}]</option>
													<br/>
													@endforeach
												</select>
												<br>
											
												@if ($errors->has('user_id'))
													<small id="user_id" class="form-text text-danger">
														{{ $errors->first('user_id') }}
													</small>
												@endif

												@if ($errors->has('coupon_id'))
													<small id="coupon_id" class="form-text text-danger">
														{{ $errors->first('user_id') }}
													</small>
												@endif
											</div>

				                       		<div class="form-group">
							                    <button type="submit" id="btnSubmit" class="btn btn-success btn-block ks-split"  data-transaction="{{ config('app.balance_code') }}">
								                    <span class="la la-check ks-icon"></span>
								                    <span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
							                    </button>
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
    </div>

@endsection