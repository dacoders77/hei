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
				<h1 class="text-center text-uppercase margin-top-3 margin-bottom-3">Sorry, this campaign has closed.</h1>
			</div>
		</div>
	</div>

	<div class="columns small-12 large-5 align-self-middle large-order-1">
		<div class="row">
			<div class="columns text-center large-text-left large-offset--60">
				<img src="{{ asset('assets/images/Dulux/WIN_Cards_and_Balls@2x.png') }}" alt="" class="header--pack" width="373">
				<img src="{{ asset('assets/images/Dulux/WIN_Kayo@2x.png') }}" alt="" class="header--pack" width="373">
			</div>
		</div>
	</div>
</div>


@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
@endsection