@php
	// Get Form Fields
	$form_content = $campaign->meta('form_content');
@endphp

{{ Form::open(
	[
		'route' => isset($formRoute) ? $formRoute : ['campaigns.submissions.store',$campaign->id],
		'method' => 'post',
		'files' => jsFormHasFile($form_content),
		'class' => 'validate hide',
		'id' => 'jsform'
	]
) }}

<div style="color:chocolate">
	<h1 id="tt55" >JOPA from jsform.blade.php</h1>
</div>

	{{-- Include Honeypot --}}
	@honeypot

	{{-- Include Campaign ID --}}
	{{ Form::hidden('_campaign',$campaign->id) }}

	<div class="row">
	{{-- Loop Form Fields --}}
	@foreach ($form_content as $element)
		@switch($element->type)

			{{-- Hidden Field --}}
			@case('hidden')
				{{ Form::hidden($element->name,$element->value??null) }}
				@break

			{{-- Row Breaks --}}
			@case('row')
				</div><div class="row">
		        @break

		    {{-- Conditional Row --}}
			@case('crow')
				</div><div class="row hide" data-condition="{{ $element->conditions }}" {!! isset($element->required) ? 'data-required' : '' !!}>
		        @break

		    @case('paragraph')
		    	<div class="padding-bottom columns small-12">

		    		{{-- HTML --}}
		    		<{{ $element->subtype }} class="{{ $element->className??'' }}">{!! shortcode2HTML($element->label) !!}</{{ $element->subtype }}>

		        </div>
		    	@break

	        {{-- ReCaptcha --}}
			@case('recaptcha')
		    	<div class="padding-bottom columns small-12">

		    		{{-- JS --}}
		    		<script src='https://www.google.com/recaptcha/api.js'></script>

		    		{{-- Element --}}
				    <div class="g-recaptcha display-inline-block" data-sitekey="{{ Config::get('recaptcha.key') }}"></div>

				</div>
		    	@break

			{{-- Select Box --}}
		    @case('select')
		    	<div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

					{{-- Label --}}
		        	@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

					{{-- Setup variables --}}
		        	@php
		        		$options = [];
		        		$selected = false;
		        		foreach ($element->values as $option) {
		        			if( isset($option->selected) ) $selected = $option->value;
		        			$options[$option->value] = $option->label;
		        		}
		        	@endphp

					{{-- Field --}}
		        	{{ Form::select($element->name,$options,$selected,['placeholder'=>(isset($element->required)&&!isset($element->placeholder)?null:($element->placeholder??'Select...')),'class'=>($element->className??''),'required'=>isset($element->required)]) }}

					{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		    	@break

		    {{-- Checkboxes --}}
		    @case('checkbox-group')
		    	<div class="paddin-bottom columns {{ $element->wrapperColumns??'' }}">

		    		{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

		        	<div class="checkbox-group">

			        	{{-- Fields --}}
		        		@foreach ($element->values as $i => $option)
		        			<div class="checkbox">
		        				<label for="{{ $element->name.'-'.$i }}">
		        				{{ Form::checkbox($element->name, $option->value, isset($option->selected), ['id'=>$element->name.'-'.$i,'class'=>($element->className??''),'required'=>isset($element->required)]) }}
			        			<span class="check"></span>
			        			<span class="check-label">{!! shortcode2HTML($option->label) !!} {!! !isset($element->label) && isset($element->required) ? '<span class="required_hint">*</span>' : '' !!}</span></label>
			        		</div>
		        		@endforeach

		        	</div>

	        		{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		    	@break

		    {{-- Radios --}}
		    @case('radio-group')
		    	<div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		    		{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

		        	<div class="radio-group">

			        	{{-- Fields --}}
		        		@foreach ($element->values as $i => $option)
		        			<div class="radio">
		        				<label for="{{ $element->name.'-'.$i }}">
		        				{{ Form::radio($element->name, $option->value, isset($option->selected), ['id'=>$element->name.'-'.$i,'class'=>($element->className??''),'required'=>isset($element->required)]) }}
			        			<span class="check"></span>
			        			<span class="radio-label">{!! shortcode2HTML($option->label) !!}</span> {!! !isset($element->label) && isset($element->required) ? '<span class="required_hint">*</span>' : '' !!}</label>
			        		</div>
		        		@endforeach

		        	</div>

	        		{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		    	@break

			{{-- Textarea --}}
		    @case('textarea')
		    	<div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

		        	{{-- Field --}}
		        	{{ Form::textarea($element->name,$element->value??'',['placeholder'=>($element->placeholder??''),'class'=>($element->className??''),'required'=>isset($element->required)]) }}

		        	{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		    	@break

			{{-- Number --}}
		    @case('number')
		        <div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

		        	@php
		        		$settings = [
		        			'placeholder'=>($element->placeholder??''),
							'class'=>($element->className??''),
							'required'=>isset($element->required),
		        		];
		        		if(isset($element->pattern)) $settings['pattern'] = $element->pattern;
		        		if(isset($element->min)) $settings['min'] = $element->min;
		        		if(isset($element->max)) $settings['max'] = $element->max;
		        		if(isset($element->step)) $settings['step'] = $element->step;
		        	@endphp

					{{-- Field --}}
					{{ Form::number($element->name,$element->value??'',$settings) }}

		        	{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		        @break

			{{-- Address Autocomplete --}}
		    @case('autocomplete-address')
		        <div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

					{{-- Search Field --}}
		        	<div class="autocomplete-wrapper">
			        	{{ Form::text('_'.$element->name,null,['placeholder'=>($element->placeholder??'Start typing...'),'class'=>($element->className??''),'required'=>isset($element->required),'autocomplete'=>'off_'.rand(),'data-autocomplete-widget'=>$element->name,'data-country'=>$element->addressFinder]) }}
			        </div>

			        <div class="autocomplete-toggle-wrapper">
			        	<div class="checkbox">
	        				<label for="{{ $element->name.'-toggle' }}">
	        				{{ Form::checkbox(null, null, false, ['id'=>$element->name.'-toggle']) }}
		        			<span class="check"></span>
		        			Can't find your address?</label>
		        		</div>
			        </div>

		        	{{-- Hidden fields --}}
		        	<div class="hide autocomplete-hidden-wrapper">

		        		<div class="row">
		        			<div class="padding-bottom columns">
				        		{{-- Steet --}}
					    		@if (isset($element->label_street_1))
					        		{!! Form::label($element->name.'_line_1', shortcode2HTML($element->label_street_1) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
					        	@endif
					        	{{ Form::text($element->name.'_line_1',null,['placeholder'=>($element->placeholder_street_1??null),'class'=>($element->className??''),'required'=>isset($element->required)]) }}
					        </div>
					    </div>

					    <div class="row">
		        			<div class="padding-bottom columns">
				        		{{-- Steet 2 --}}
					    		@if (isset($element->label_street_2))
					        		{!! Form::label($element->name.'_line_2', shortcode2HTML($element->label_street_2),[],false) !!}
					        	@endif
					        	{{ Form::text($element->name.'_line_2',null,['placeholder'=>($element->placeholder_street_2??null),'class'=>($element->className??'')]) }}
					        </div>
					    </div>

			        	<div class="row">
			        		<div class="padding-bottom columns">
					        	{{-- Suburb --}}
					    		@if (isset($element->label_suburb))
					        		{!! Form::label($element->name.'_suburb', shortcode2HTML($element->label_suburb) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
					        	@endif
					        	{{ Form::text($element->name.'_suburb',null,['placeholder'=>($element->placeholder_suburb??null),'class'=>($element->className??''),'required'=>isset($element->required)]) }}
					        </div>
					    </div>

			        	<div class="row">
			        		<div class="padding-bottom columns">
					        	{{-- State --}}
					    		@if (isset($element->label_state))
					        		{!! Form::label($element->name.'_state', shortcode2HTML($element->label_state) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
					        	@endif

					        	@if ($element->addressFinder=='NZ')

					        	{{-- Field --}}
					        	{{ Form::text($element->name.'_state',null,['placeholder'=>(isset($element->required)&&!isset($element->placeholder_state)?null:($element->placeholder_state??null)),'class'=>($element->className??''),'required'=>isset($element->required)]) }}

					        	@else
					        	@php
					        		$options = [
					        			'NSW' => 'NSW',
										'QLD' => 'QLD',
										'SA' => 'SA',
										'TAS' => 'TAS',
										'VIC' => 'VIC',
										'WA' => 'WA',
										'ACT' => 'ACT',
										'NT' => 'NT',
					        		];
					        	@endphp

					        	{{-- Field --}}
					        	{{ Form::select($element->name.'_state',$options,null,['placeholder'=>(isset($element->required)&&!isset($element->placeholder_state)?null:($element->placeholder_state??'Select...')),'class'=>($element->className??''),'required'=>isset($element->required)]) }}

					        	@endif
					        </div>
					    </div>

			        	<div class="row">
			        		<div class="padding-bottom columns">
					        	{{-- Postcode --}}
					    		@if (isset($element->label_postcode))
					        		{!! Form::label($element->name.'_postcode', shortcode2HTML($element->label_postcode) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
					        	@endif
					        	{{ Form::number($element->name.'_postcode',null,['placeholder'=>($element->placeholder_postcode??null),'class'=>($element->className??''),'required'=>isset($element->required),'pattern'=>'[0-9]*','min'=>0,'max'=>9999,'step'=>1]) }}
					        </div>
					    </div>
			        </div>

			        {{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		        @break

			{{-- File Upload --}}
		    @case('file')
		        <div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

		        	{{-- Upload Button --}}
		        	{{ Form::label($element->name,($element->placeholder??'Choose file'),['class'=>'button expanded file hide-for-sr']) }}

		        	{{-- Field --}}
		        	{{ Form::file($element->name,['class'=>'show-for-sr '.($element->className??''),'required'=>isset($element->required),'accept'=>($element->accept??''),'placeholder'=>($element->placeholder??'Choose file')]) }}

					{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		        @break

			{{-- Date Picker --}}
		    @case('date')
		        <div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

					{{-- Field --}}
					@if ( !\Detect::isMobile() && isset($element->datePicker) && $element->datePicker == 'true')
						{{ Form::text($element->name,$element->value??'',['placeholder'=>($element->placeholder??''),'class'=>($element->className??''),'required'=>isset($element->required),'autocomplete'=>'off','data-datepicker'=>'','data-startdate'=>($element->startDate??null),'data-enddate'=>(isset($element->endDate)?maxDate('today',$element->endDate):null)]) }}
					@else
						{{ Form::date($element->name,$element->value??'',['placeholder'=>($element->placeholder??''),'class'=>($element->className??'').(isset($element->startDate)?' minDate':'').(isset($element->endDate)?' maxDate':''),'required'=>isset($element->required),'autocomplete'=>'off','data-min'=>(isset($element->startDate)?strtotime($element->startDate).'000':null),'data-max'=>(isset($element->endDate)?maxDate('today',$element->endDate).'000':null)]) }}
					@endif

		        	{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		        @break

			{{-- Everything Else --}}
		    @default
		        <div class="padding-bottom columns {{ $element->wrapperColumns??'' }}">

		        	{{-- Label --}}
		    		@if (isset($element->label))
		        		{!! Form::label($element->name, shortcode2HTML($element->label) . ( isset($element->required) ? '<span class="required_hint">*</span>' : '' ),[],false) !!}
		        	@endif

					{{-- Field --}}
		        	{{ Form::{$element->type}($element->name,$element->value??'',['placeholder'=>($element->placeholder??''),'class'=>($element->className??''),'required'=>isset($element->required),'data-inputmask'=>($element->inputMask??null),'data-inputmask-clearincomplete'=>(isset($element->inputMask)?'true':null)]) }}

		        	{{-- Description --}}
		        	{!! isset($element->description) ? '<p class="help-text">'.$element->description.'</p>' : '' !!}
		        </div>
		        @break


		@endswitch
	@endforeach
	</div>

	<div class="row padding-top padding-bottom form-submit">
		<div class="small-12 columns">
			<input id="submit222" type="submit" value="Submit" class="wizard-finish button primary">
			<a href="javascript:void(0);" class="button primary hide" id="submit_working"><i class="fa fa-refresh fa-spin"></i></a>
		</div>
	</div>

	<div class="row">
		<div class="columns small-12">
			<div class="thank-you hide padding-bottom"></div>
		</div>
	</div>


{{ Form::close() }}