
<input type="hidden" name="type_id" value="{{ $type->id }}" />
<div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
	<label>Nama</label>
	<input type="text"
			required
			name="name" 
			value="{{ old('name') }}"
			class="form-control"
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
			required
			name="code" 
			value="{{ old('code') }}"
			class="form-control"
			data-validation="required"
			data-validation-error-msg="Kode Promo harus di isi."
			placeholder="Kode Promo">

	@if ($errors->has('code'))
		<small class="form-text text-danger">{{ $errors->first('code') }}</small>
	@endif
</div>

<div class="form-group{{ $errors->has('expired') ? ' has-danger' : '' }}">
	<label>Batas Waktu</label>
	<input type="text"
			required
			name="expired"
			value="{{ old('expired') }}"
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
<div class="form-group{{ $errors->has('discount_type') ? ' has-danger' : '' }}">
		<label>Promo Type</label>
		<select name="discount_type_id" id="dicount_type" class="form-control ks-select-placeholder-single" >
			<option value="0">--Select Promo Type --</option>
			@foreach ($promo_type as $value)
				<option value="{{ $value->id }}">{{ $value->name }}</option>">
			@endforeach
		</select>
	
		@if ($errors->has('discount_type'))
			<small class="form-text text-danger">{{ $errors->first('expired') }}</small>
		@endif
	</div>

<div class="form-group{{ $errors->has('discount_type') ? ' has-danger' : '' }}">
	<label>Discount Type</label>
	<select name="discount_type_id" id="dicount_type" class="form-control ks-select-placeholder-single" >
		<option value="0">--Select Discount --</option>
		@foreach ($discount_type as $value)
			<option value="{{ $value->id }}">{{ $value->type }}</option>">
		@endforeach
	</select>

	@if ($errors->has('discount_type'))
		<small class="form-text text-danger">{{ $errors->first('expired') }}</small>
	@endif
</div>
<div class="form-group{{ $errors->has('product_type') ? ' has-danger' : '' }}">
	<label>Promo Product Type :</label><br>
	<input type="checkbox" id="select_all_product_type" />Check all<br />

	@foreach ($product_type as $item)
		<input class = "product_type" type="checkbox" name="product_type[]" value="{{$item->id}}"/>{{$item->name}}
	<br/>
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
			required
			name="transaction_min"
			class="form-control"
			id="transaction_min"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Nilai Minimal Transaksi"
			value="{{ old('transaction_min') }}">

@if ($errors->has('transaction_min'))
	<small id="transaction_min" class="form-text text-danger">
		{{ $errors->first('transaction_min') }}
	</small>
@endif
</div>

@if ($type->id == 3 )
	<div class="form-group{{ $errors->has('ppob_type') ? ' has-danger' : '' }}">
		<label>Jenis Penggunaan Promo :</label><br>
		<input type="checkbox" onclick="toggle(this);" checked />Check all?<br />

		@foreach ($ppob_type as $item)
		{{-- {{$item}} --}}
			<input type="checkbox" name="ppob_type[]" value="{{$item->id}}" checked/>{{$item->name}}
		<br/>
		@endforeach
		<br>
	
		@if ($errors->has('ppob_type'))
			<small id="ppob_type" class="form-text text-danger">
				{{ $errors->first('ppob_type') }}
			</small>
		@endif
	</div>
	

	<div class="form-group{{ $errors->has('term_condition') ? ' has-danger' : '' }}">
		<label>Term Condition</label>
		<textarea  id="ks-summernote-editor-default" name="term_condition" required>
		</textarea>
	
		@if ($errors->has('term_condition'))
			<small class="form-text text-danger">
				{{ $errors->first('term_condition') }}
			</small>
		@endif
    </div>
    
    <div class="form-group{{ $errors->has('term_condition') ? ' has-danger' : '' }}">
		<label>Term Condition</label>
		<textarea  id="ks-summernote-editor-default" name="term_condition" required>
		</textarea>
	
		@if ($errors->has('term_condition'))
			<small class="form-text text-danger">
				{{ $errors->first('term_condition') }}
			</small>
		@endif
	</div>
@else
<input type="text" name="term_condition" value="''" hidden>

@endif

@if ($type->id == 3 )
<label>Diskon Harga Dengan : </label><br>
	<input type="radio" name="check_type"  class="listing" value="0" checked="true"/>Rupiah (Rp) 
	<input type="radio" name="check_type"  class="listing" value="1" />Persentase (%)
<br/><br/>
@endif

@if ($type->id == 1) 
	<input type="hidden" name="shipping_code" value="MSE">
@endif

@if ($type->id == 1 || ($type->id == 3 ) )
	<div id="discount_price_form"  class="form-group{{ $errors->has('discount_price') ? ' has-danger' : '' }}">
		<label>Nilai Diskon (Rp)</label>
		<input type="text"
				name="discount_price"
				class="form-control"
				id="discount_price"
				data-validation="required|number"
				data-validation-error-msg="Harus di isi angka."
				placeholder="Nilai Diskon (Rp)"
				value="{{ old('discount_price') }}">
	
	@if ($errors->has('discount_price'))
		<small id="discount_price" class="form-text text-danger">
			{{ $errors->first('discount_price') }}
		</small>
	@endif
	</div>
@endif

@if ($type->id == 2 || ($type->id == 3))
	<div id="discount_max_form"  class="form-group{{ $errors->has('discount_max') ? ' has-danger' : '' }}">
	<label>Nilai Maksimal Diskon (Rp)</label>
	<input type="text"
			name="discount_max"
			class="form-control"
			id="discount_max"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Nilai Maksimal Diskon (Rp)"
			value="{{ old('discount_max') }}">

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
			value="{{ old('discount_percent') }}">

	@if ($errors->has('discount_percent'))
		<small id="discount_percent" class="form-text text-danger">
			{{ $errors->first('discount_percent') }}
		</small>
	@endif
</div>
@endif

<div class="form-group{{ $errors->has('quota') ? ' has-danger' : '' }}">
	<label>Kuota Harian</label>
	<input type="text"
			name="quota"
			class="form-control"
			id="quota"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Kuota Harian"
			value="{{ old('quota') }}">

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
				value="{{ old('quota_user_day') }}">
	
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
				value="{{ old('quota_user_total') }}">
	
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
				value="{{ old('total_quota') }}">
	
		@if ($errors->has('total_quota'))
			<small id="total_quota" class="form-text text-danger">
				{{ $errors->first('total_quota') }}
			</small>
		@endif
	</div>

<div class="form-group">
	<button type="submit" class="btn btn-success btn-block ks-split">
		<span class="la la-check ks-icon"></span>
		<span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
	</button>
</div>
        

{{--  
<input type="hidden" name="type_id" value="{{ $type->id }}" />
<div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
	<label>Nama</label>
	<input type="text"
			required
			name="name" 
			value="{{ old('name') }}"
			class="form-control"
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
			required
			name="code" 
			value="{{ old('code') }}"
			class="form-control"
			data-validation="required"
			data-validation-error-msg="Kode Promo harus di isi."
			placeholder="Kode Promo">

	@if ($errors->has('code'))
		<small class="form-text text-danger">{{ $errors->first('code') }}</small>
	@endif
</div>

<div class="form-group{{ $errors->has('expired') ? ' has-danger' : '' }}">
	<label>Batas Waktu</label>
	<input type="text"
			required
			name="expired"
			value="{{ old('expired') }}"
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

<div class="form-group{{ $errors->has('transaction_min') ? ' has-danger' : '' }}">
	<label>Nilai Minimal Transaksi</label>
	<input type="text"
			required
			name="transaction_min"
			class="form-control"
			id="transaction_min"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Nilai Minimal Transaksi"
			value="{{ old('transaction_min') }}">

@if ($errors->has('transaction_min'))
	<small id="transaction_min" class="form-text text-danger">
		{{ $errors->first('transaction_min') }}
	</small>
@endif
</div>

@if ($type->id == 3 )
	<div class="form-group{{ $errors->has('ppob_type') ? ' has-danger' : '' }}">
		<label>Jenis Penggunaan Promo :</label><br>
		<input type="checkbox" onclick="toggle(this);" checked />Check all?<br />

		@foreach ($ppob_type as $item)
			<input type="checkbox" name="ppob_type[]" value="{{$item->id}}" checked/>{{$item->name}}
		<br/>
		@endforeach
		<br>
	
		@if ($errors->has('ppob_type'))
			<small id="ppob_type" class="form-text text-danger">
				{{ $errors->first('ppob_type') }}
			</small>
		@endif
	</div>
	

	<div class="form-group{{ $errors->has('term_condition') ? ' has-danger' : '' }}">
		<label>Term Condition</label>
		<textarea  id="ks-summernote-editor-default" name="term_condition" required>
		</textarea>
	
		@if ($errors->has('term_condition'))
			<small class="form-text text-danger">
				{{ $errors->first('term_condition') }}
			</small>
		@endif
	</div>
@else
<input type="text" name="term_condition" value="''" hidden>

@endif

@if ($type->id == 3 )
<label>Diskon Harga Dengan : </label><br>
	<input type="radio" name="check_type"  class="listing" value="0" checked="true"/>Rupiah (Rp) 
	<input type="radio" name="check_type"  class="listing" value="1" />Persentase (%)
<br/><br/>
@endif

@if ($type->id == 1) 
	<input type="hidden" name="shipping_code" value="MSE">
@endif

@if ($type->id == 1 || ($type->id == 3 ) )
	<div id="discount_price_form"  class="form-group{{ $errors->has('discount_price') ? ' has-danger' : '' }}">
		<label>Nilai Diskon (Rp)</label>
		<input type="text"
				name="discount_price"
				class="form-control"
				id="discount_price"
				data-validation="required|number"
				data-validation-error-msg="Harus di isi angka."
				placeholder="Nilai Diskon (Rp)"
				value="{{ old('discount_price') }}">
	
	@if ($errors->has('discount_price'))
		<small id="discount_price" class="form-text text-danger">
			{{ $errors->first('discount_price') }}
		</small>
	@endif
	</div>
@endif

@if ($type->id == 2 || ($type->id == 3))
	<div id="discount_max_form"  class="form-group{{ $errors->has('discount_max') ? ' has-danger' : '' }}">
	<label>Nilai Maksimal Diskon (Rp)</label>
	<input type="text"
			name="discount_max"
			class="form-control"
			id="discount_max"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Nilai Maksimal Diskon (Rp)"
			value="{{ old('discount_max') }}">

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
			value="{{ old('discount_percent') }}">

	@if ($errors->has('discount_percent'))
		<small id="discount_percent" class="form-text text-danger">
			{{ $errors->first('discount_percent') }}
		</small>
	@endif
</div>
@endif

<div class="form-group{{ $errors->has('quota') ? ' has-danger' : '' }}">
	<label>Kuota Harian</label>
	<input type="text"
			name="quota"
			class="form-control"
			id="quota"
			data-validation="required|number"
			data-validation-error-msg="Harus di isi angka."
			placeholder="Kuota Harian"
			value="{{ old('quota') }}">

	@if ($errors->has('quota'))
		<small id="quota" class="form-text text-danger">
			{{ $errors->first('quota') }}
		</small>
	@endif
</div>

<div class="form-group">
	<button type="submit" class="btn btn-success btn-block ks-split">
		<span class="la la-check ks-icon"></span>
		<span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
	</button>
</div> --}}