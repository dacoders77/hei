{{ Form::open(
	[
		'route' => isset($formRoute) ? $formRoute : ['campaigns.contactus',$campaign->id],
		'method' => 'post',
		'files' => false,
		'class' => 'validate hide',
		'id' => 'contact-form'
	]
) }}
	{{-- Include Honeypot --}}
	@honeypot

	{{-- Include Campaign ID --}}
	{{ Form::hidden('_campaign',$campaign->id) }}

	<div class="row">

		<div class="columns small-12 padding-bottom">
			<h1 class="text-left">CONTACT US</h1>
		</div>

	</div>

	<div class="row">

		<div class="columns small-12 medium-6 padding-bottom">
			{!! Form::label('first_name', 'First Name <span class="required_hint">*</span>',[],false) !!}

			{{ Form::text('first_name',null,['placeholder'=>'First name','class'=>'form-control','required'=>true]) }}
		</div>
		<div class="columns small-12 medium-6 padding-bottom">
			{!! Form::label('last_name', 'Last Name <span class="required_hint">*</span>',[],false) !!}

			{{ Form::text('last_name',null,['placeholder'=>'Last name','class'=>'form-control','required'=>true]) }}
		</div>

	</div>

	<div class="row">

		<div class="columns small-12 medium-6 padding-bottom">
			{!! Form::label('email', 'Email Address <span class="required_hint">*</span>',[],false) !!}

			{{ Form::email('email',null,['placeholder'=>($campaign->id==2?'name@example.co.nz':'name@example.com.au'),'class'=>'form-control','required'=>true]) }}
		</div>
		<div class="columns small-12 medium-6 padding-bottom">
			{!! Form::label('phone', 'Mobile Number <span class="required_hint">*</span>',[],false) !!}

			{{ Form::tel('phone',null,['placeholder'=>($campaign->id==2?'02 + (your number)':'04 + (your number)'),'class'=>($campaign->id==2?'form-control nzmobile':'form-control aumobile'),'required'=>true]) }}
		</div>

	</div>

	<div class="row">

		<div class="columns small-12 padding-bottom">
			{!! Form::label('comment', 'Message <span class="required_hint">*</span>',[],false) !!}

			{{ Form::textarea('comment',null,['placeholder'=>null,'class'=>'form-control','required'=>true]) }}
		</div>

	</div>

	<div class="row padding-top padding-bottom form-submit">
		<div class="small-12 columns">
			<input id="submit" type="submit" value="Submit" class="wizard-finish button primary">
			<a href="javascript:void(0);" class="button primary hide" id="submit_working"><i class="fa fa-refresh fa-spin"></i></a>
		</div>
	</div>

	<div class="row">
		<div class="columns small-12">
			<div class="thank-you hide padding-bottom"></div>
		</div>
	</div>


{{ Form::close() }}