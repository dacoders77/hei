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
				<h1>FREQUENTLY ASKED QUESTIONS</h1>
			</div>
		</div>
	</div>
</div>

<div class="basic-wrapper padding-top--2x padding-bottom--2x">

<div class="row align-center">
	<div class="columns small-12">
		<h3>How long is the promotion running?</h3>
<p>You can claim eligible purchases made before 5.00pm AEST on 20<sup>th</sup> September 2019. Entries must be received by 20<sup>th</sup> October 2019. </p>
<p>&nbsp;</p>
<h3>Where is the promotion running?</h3>
<p>The promotion is running at selected Dulux Trade Stores. If your store is involved with the promotion they will have signage up, but feel free to ask the friendly staff at your local Dulux Trade Store whether the promotion is running there.</p>
<p>&nbsp;</p>
<h3>Who can enter?</h3>
<p>This promotion is open only to Dulux Trade Account holders or New Housing Account holders. Commercial Account holders cannot enter. For full exclusions, see terms and conditions. </p>
<p>&nbsp;</p>
<h3>What is an eligible purchase?</h3>
<p>$250 or more in one transaction = 1 Scratch to Win</p>
<p>$500 or more in one transaction = 1 Scratch to Win + 3 Months of Free Kayo</p>
<p>Each entry requires an individual receipt, regardless of how many multiples of $250 are on that receipt. Each receipt can only be used once.</p>
<p>&nbsp;</p>
<h3>What are the Participating Products?</h3>
<p>Dulux Weathershield, Dulux Weatherhsield +Plus, Dulux Wash&amp;Wear, Dulux Wash&amp;Wear +Plus, Dulux Prepcoat, Dulux Precision, Dulux Ceiling, Dulux SuperEnamel, Dulux Aquanamel, Dulux Effects, Porter&rsquo;s Paint, Dulux Professional, Dulux Protective Coatings, Berger Premium, Feast Watson, Intergrain, Cabot&rsquo;s, Dumond, Avista Concrete Products, Emer Products, Dulux branded paint accessories, most accessories in the &ldquo;Game on with Dulux&rdquo; Catalogue (unless listed below) and most Dulux AcraTex products (unless listed below).</p>
<p>Products which do not count toward eligibility for a prize are Hadrian Contractor products, Dulux AcraTex Dry Render, Exsulite Panel &amp; Accessories, Exsulite Matrix, Multitex and Berger Gold Label, any Graco/Wagner spray machine or equipment or thinners.</p>
<p>&nbsp;</p>
<h3>What information will I need to complete my entry?</h3>
<p>You will need your personal details including email address, mobile number and your Dulux Trade Account Payer Number. You will also need to supply purchase details and proof of purchase. </p>
<p>&nbsp;</p>
<h3>What proof of purchase do I need?</h3>
<p>You will need to upload a copy of your eligible receipt or invoice. </p>
<p>&nbsp;</p>
<h3>How many times can I enter?</h3>
<p>For each eligible purchase of $250 or more, you will receive one entry. There is a limit of 1 entry per transaction.</p>
<p>You can claim up to 4 eligible purchases per Dulux account. </p>
<p>There is a limit of 1 Free 3-month Kayo Subscription per Dulux Account.</p>
<p>&nbsp;</p>
<h3>How long will it take to validate my entry?</h3>
<p>Entries will be validated within 2 business days. You will receive an email and SMS letting you know if your claim is valid.</p>
<p>&nbsp;</p>
<h3>How will I receive my Kayo Subscription Gift?</h3>
<p>You will receive your Kayo Voucher Code and instructions on how to activate your subscription by email.&nbsp; You must activate your Kayo Subscription before 20<sup>th</sup> November 2019.</p>
<p>Remember to cancel your subscription prior to the end of the 3-month period if you do not wish to continue. Charges will apply after your 3 months FREE Kayo voucher has expired.</p>
<p>&nbsp;</p>
<h3>How will I receive my Scratch to Win?</h3>
<p>If your entry is eligible, you will receive an SMS within 2 business days. This will contain a link to an online Scratch to Win card. </p>
<p>If you&rsquo;ve won a Gift Card, you will be asked to immediately select your preferred retailer from Rebel Sport, BCF or Supercheap Auto. If you have won a ball, you will be asked to immediately select from a Sherrin AFL ball, a Steeden Rugby League ball, a Gilbert Rugby Union ball or a Nike soccer ball.</p>
<p>You will also receive an email with a prize claim link in case you don&rsquo;t select your prize right away.</p>
<p>All instant win prizes must be claimed by 20th December 2019.</p>
<p>&nbsp;</p>
<h3>When will my Instant Win Prize arrive?</h3>
<p>Once you select your football or Gift Card it will arrive by post within 28 days. You will receive notification when it has been posted.</p>
<p>&nbsp;</p>
	<p>If you have any other questions or issues please <a href="{{ route('campaign_1.pages','contact-us') }}">Contact Us</a>.</p>

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

	.basic-wrapper a {
		text-decoration: underline;
	}
</style>
@endsection