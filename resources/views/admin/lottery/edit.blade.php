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
		
						{{-- {{$promo_type}}<br>{{$promo_type_check}} --}}
											
						<div class="row">

							<div class="col-lg-12">
								<div class="card panel">
									<div class="card-block">
										<form method="post" action="{{ route('admin.'.$page.'.update') }}" enctype="multipart/form-data">
											{{ csrf_field() }}
											<input type="hidden" name="id" value="{{ $item->id }}">

											<div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
												<label>User :</label><br>
												<input type="text"
														name="tgl_auto_debet"
														value="{{ $users->username.' ['.$users->email.']' }}"
														class="form-control"
														data-validation="required"
														readonly>
									

												<br>
												@if ($errors->has('user_id'))
													<small id="user_id" class="form-text text-danger">
														{{ $errors->first('user_id') }}
													</small>
												@endif
											</div>

											<div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
												<label>Phone :</label><br>
												<input type="text"
													value="{{$users->phone   }}"
													readonly
													class="form-control">
												<br>
											</div>

							
											<div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
												<label>Status</label>
												<select class="form-control ks-select-placeholder-single" name="status" >
													<option value="0" @if($item->status == 0) selected="selected" @endif>Belum Diundi</option>
													<option value="1" @if($item->status == 1) selected="selected" @endif>Sudah Diundi</option>
													{{-- <option value="2" @if($item->status == 2) selected="selected" @endif>Failed</option> --}}
												</select>

												@if ($errors->has('status'))
													<small class="form-text text-danger">{{ $errors->first('status') }}</small>
												@endif
											</div>

											<hr>
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
											<div class="col-md-12 align-bottom">
												<div class="mt-3 pt-2">
													<button class="btn btn-success btn-block ks-split">
														<span class="la la-check ks-icon"></span>
														<span class="ks-text">Simpan Perubahan</span>
													</button>
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
