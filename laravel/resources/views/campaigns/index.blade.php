{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection


{{-- MAIN CONTENT--}}
@section('content')

<div class="row">
	<div class="columns small-12">
		@include('campaigns.layouts.jsform')
	</div>
</div>


@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
@endsection