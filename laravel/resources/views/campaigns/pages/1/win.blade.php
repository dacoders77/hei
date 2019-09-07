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
				<div class="scratchpad-wrapper">
					@if ( $submission->meta('status') == '3' )
						<div id="scratchpad"></div>
						<div class="scratchpad-text-win text-center">
							<h2 class="text-uppercase">Scratch to Win!</h2>
						</div>
					@else
						<div id="scratchpad">
							<div id="scratchpad-{{ uniqid() }}">
								@if ($submission->meta('is_win'))
									@switch($submission->meta('prize'))
						                @case('$20 Gift Card')
									<img src="{{ asset('assets/images/Dulux/scratchcard20@2x.jpg') }}" alt="">
						                    @break
						                @case('$50 Gift Card')
						            <img src="{{ asset('assets/images/Dulux/scratchcard50@2x.jpg') }}" alt="">
						                    @break
						                @default
						            <img src="{{ asset('assets/images/Dulux/scratchcardBall@2x.jpg') }}" alt="">
						            @endswitch
						        @else
						        <img src="{{ asset('assets/images/Dulux/scratchcardSorry@2x.jpg') }}" alt="">
								@endif
							</div>
						</div>
					@endif
					@if ( !$submission->meta('prize_chosen') && $submission->meta('is_win') )
					<div class="redeem-form-wrapper {{ $submission->meta('status') == '3' ? 'hide' : ''}}">
						{{ Form::open(
							[
								'route' => ['campaign_1.redeem',$id],
								'method' => 'post',
								'class' => 'validate',
								'id' => 'redeem-form'
							]
						) }}
							{{-- Include Honeypot --}}
							@honeypot

							{{-- Include Campaign ID --}}
							{{ Form::hidden('_campaign',$campaign->id) }}

							@if($submission->meta('prize') == 'Football')
							<div class="row align-middle padding-bottom">
								<div class="columns small-12 large-6">
									{{ Form::label('retailer','Choose your football',['class'=>'gc-retailer-label'],false) }}
								</div>
								<div class="columns small-12 large-6">
									@php
						        		$options = [
						        			'Sherrin AFL Ball' => 'Sherrin AFL Ball',
											'Steeden NRL Ball' => 'Steeden NRL Ball',
											'Nike Soccer Ball' => 'Nike Soccer Ball',
											'Gilbert Rugby Union Ball' => 'Gilbert Rugby Union Ball',
						        		];
						        	@endphp
									{{ Form::select('retailer',$options,'',['placeholder'=>'Select preferred code','class'=>'form-control','required'=>true]) }}
								</div>
							</div>
							@else
							<div class="row align-middle padding-bottom">
								<div class="columns small-12 large-6">
									{{ Form::label('retailer','Choose your gift card',['class'=>'gc-retailer-label'],false) }}
								</div>
								<div class="columns small-12 large-6">
									@php
						        		$options = [
						        			'Supercheap Auto' => 'Supercheap Auto',
											'Rebel' => 'Rebel',
											'BCF' => 'BCF',
						        		];
						        	@endphp
									{{ Form::select('retailer',$options,'',['placeholder'=>'Select preferred retailer','class'=>'form-control','required'=>true]) }}
								</div>
							</div>
							@endif

							<div class="row padding-bottom">
								<div class="columns">
									{!! Form::label('address','Street Address / PO Box<span class="required_hint">*</span>',[],false) !!}

									<div class="autocomplete-wrapper">
										{{ Form::text('_address',null,['placeholder'=>'Type address or PO Box','class'=>'form-control','required'=>true,'autocomplete'=>'off_'.rand(),'data-autocomplete-widget'=>'address','data-country'=>'AU']) }}
									</div>

									<div class="autocomplete-toggle-wrapper">
							        	<div class="checkbox">
					        				<label for="address-toggle">
					        				<input id="address-toggle" type="checkbox">
						        			<span class="check"></span>
						        			Can't find your address?</label>
						        		</div>
							        </div>

							        {{-- Hidden fields --}}
						        	<div class="hide autocomplete-hidden-wrapper">
						        		<div class="row">
						        			<div class="padding-bottom columns">
								        		{{-- Steet --}}
								        		{!! Form::label('address_line_1', 'Street Address / PO Box<span class="required_hint">*</span>',[],false) !!}
									        	{{ Form::text('address_line_1',null,['placeholder'=>'(Enter Address)','class'=>'form-control','required'=>true]) }}
									        </div>
									    </div>

									    <div class="row">
						        			<div class="padding-bottom columns">
								        		{{-- Steet 2 --}}
								        		{!! Form::label('address_line_2', 'Street Address / PO Box Line 2 <small>(not mandatory)</small>',[],false) !!}
									        	{{ Form::text('address_line_2',null,['placeholder'=>'(Enter Address)','class'=>'form-control']) }}
									        </div>
									    </div>

							        	<div class="row">
							        		<div class="padding-bottom columns small-12 large-6">
									        	{{-- Suburb --}}
								        		{!! Form::label('address_suburb', 'Suburb<span class="required_hint">*</span>',[],false) !!}
									        	{{ Form::text('address_suburb',null,['placeholder'=>'(Enter Suburb)','class'=>'form-control','required'=>true]) }}
									        </div>
							        		<div class="padding-bottom columns small-12 large-6">
									        	{{-- State --}}
								        		{!! Form::label('address_state', 'State<span class="required_hint">*</span>',[],false) !!}

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
									        	{{ Form::select('address_state',$options,null,['placeholder'=>'(Select State)','class'=>'form-control','required'=>true]) }}

									        </div>
									    </div>

							        	<div class="row">
							        		<div class="padding-bottom columns small-12 large-6">
									        	{{-- Postcode --}}
								        		{!! Form::label('address_postcode', 'Postcode<span class="required_hint">*</span>',[],false) !!}
									        	{{ Form::number('address_postcode',null,['placeholder'=>'(Postcode)','class'=>'form-control','required'=>true,'pattern'=>'[0-9]*','min'=>0,'max'=>9999,'step'=>1]) }}
									        </div>
									    </div>
							        </div>
								</div>
							</div>

							<div class="row padding-top padding-bottom form-submit">
								<div class="small-12 columns">
									<input id="submit" type="submit" value="Redeem Prize" class="wizard-finish button primary">
									<a href="javascript:void(0);" class="button primary hide" id="submit_working"><i class="fa fa-refresh fa-spin"></i></a>
								</div>
							</div>

						{{ Form::close() }}
					</div>
					@elseif ( $submission->meta('is_win') )
					<div class="text-center text-uppercase"><h3>Thank you<br/>you're prize will be with you soon.</h3></div>
					@elseif ( $submission->meta('prize_chosen') )
					<div class="text-center text-uppercase"><h3>Better luck next time</div>
					@endif
				</div>
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
@if ( $submission->meta('status') == '3' )
<script>
	var initScratchPad = function() {
        var id = 'scratchpad-'+(Math.floor(Math.random() * (+111111 - +999999)) + +999999)+(Math.random().toString(36).substr(2, 5));
        jQuery('#scratchpad').html('<div id="'+id+'"></div>');
        var scContainer = document.getElementById(id);
        var width = scContainer.offsetWidth;
        var height = scContainer.offsetWidth*0.712241653418124;

        var sc = new ScratchCard('#'+id, {
            enabledPercentUpdate: true,
            scratchType: SCRATCH_TYPE.BRUSH,
            brushSrc: '{{ asset('assets/images/Dulux/brush.png') }}',
            containerWidth: width*2,
            containerHeight: height*2,
            imageForwardSrc: '{{ asset('assets/images/Dulux/scratchcard@2x.png') }}',
            @if ($submission->meta('is_win'))
            @switch($submission->meta('prize'))
                @case('$20 Gift Card')
			imageBackgroundSrc: '{{ asset('assets/images/Dulux/scratchcard20@2x.jpg') }}',
                    @break
                @case('$50 Gift Card')
            imageBackgroundSrc: '{{ asset('assets/images/Dulux/scratchcard50@2x.jpg') }}',
                    @break
                @default
            imageBackgroundSrc: '{{ asset('assets/images/Dulux/scratchcardBall@2x.jpg') }}',
            @endswitch
            @else
            imageBackgroundSrc: '{{ asset('assets/images/Dulux/scratchcardSorry@2x.jpg') }}',
            @endif
            percentToFinish: 70,
            callback: function () {
                $.ajax({
                	url: '{{ route('campaign_1.redeem', $id) }}',
                	type: 'POST',
                	data: {
                		scratch: 'check'
                	},
                	success: function(data){
                		if(data.win) {
                			$('.scratchpad-text-win').addClass('hide').hide();
			                $('.redeem-form-wrapper').hide().removeClass('hide').fadeIn(300);
                		}
                	}
                });
            }
        });

        sc.init().then(function(){
        	$('#'+id).addClass('init');
        	$('#'+id).height(height);
        	$('.sc__wrapper,.sc__container').height(height);
        });
    }

    var destroyScratchPad = function() {
    	jQuery('#scratchpad').html('');
    }

    var reloadScratchPad = function() {
        destroyScratchPad();
        initScratchPad();
    }

    initScratchPad();

    var wWidth = jQuery(window).width();

    jQuery(window).on('resize',function(){
    	if( jQuery(window).width() !== wWidth )
	    	reloadScratchPad();
	    wWidth = jQuery(window).width();
    });
</script>
@endif
@endsection