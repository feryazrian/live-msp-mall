@extends('layouts.shipping')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section bg-grey-light">
    <div class="container">
        <div class="row justify-content-md-center py-4">
            <div class="col col-12 col-sm-12 col-md-10">
                <div class="page-content bg-white p-4">
                    <div class="page-title mb-4">Ongkos Kirim</div>
                        
			        @include('shipping.pricing.form')
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
