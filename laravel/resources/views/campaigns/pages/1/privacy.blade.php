{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_'.$campaign->id)

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection


{{-- MAIN CONTENT--}}
@section('content')

<div class="row text-center main-header">
	<div class="columns">
		<div class="row align-center">
			<div class="columns large-8">
				<img src="{{ asset('assets/images/Dulux/DuluxGameOn01@2x.png') }}" alt="" width="1119">
			</div>
		</div>
		<div class="row align-center">
			<div class="columns large-12">
				<h1>PRIVACY POLICY</h1>
			</div>
		</div>
	</div>
</div>

<div class="basic-wrapper padding-top--2x padding-bottom--2x">

<div class="row align-center">
	<div class="columns small-12">

		<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit facilis iure vero obcaecati eligendi doloremque dicta non in accusantium debitis. Nulla atque blanditiis facere odio, nam delectus, aliquid deserunt reprehenderit.</p>

		<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit facilis iure vero obcaecati eligendi doloremque dicta non in accusantium debitis. Nulla atque blanditiis facere odio, nam delectus, aliquid deserunt reprehenderit.</p>

		<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit facilis iure vero obcaecati eligendi doloremque dicta non in accusantium debitis. Nulla atque blanditiis facere odio, nam delectus, aliquid deserunt reprehenderit.</p>

	</div>
</div>

</div>


@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
<style>
	h3 {
		margin-bottom: 10px;
		margin-top: 30px;
	}
	p {
		margin-bottom: 10px;
	}
</style>
@endsection