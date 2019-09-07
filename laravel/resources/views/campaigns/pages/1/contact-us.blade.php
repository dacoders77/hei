{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_'.$campaign->id)

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection


{{-- MAIN CONTENT--}}
@section('content')

<div class="row text-center main-header">
	<div class="columns">
		<img src="{{ asset('assets/images/Dulux/DuluxGameOn01@2x.png') }}" alt="" width="1119">
	</div>
</div>

<div class="row">
	<div class="columns small-12 large-7 large-order-2 padding-bottom--2x">
		<div class="form-wrapper">
			<div class="wrapper-inner">
				@include('campaigns.layouts.contactform',['formRoute'=>'campaign_'.$campaign->id.'.contactus.submit'])
			</div>
		</div>
	</div>

	<div class="columns small-12 large-5 align-self-middle large-order-1"></div>
</div>


@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
@endsection