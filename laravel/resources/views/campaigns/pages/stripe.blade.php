{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_1')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection

{{-- MAIN CONTENT--}}
@section('content')

    <div class="page-wrapper" id="age-splash1">
        <div style="border: 0px solid blue; text-align: center; padding: 30px">
            <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
        </div>
        <div class="claim-form row padding-horizontal-1">
            <div class="columns small-12 large-6 padding-1">
                <div class="claim-form__intro">
                    <img src="{{ asset('assets/images/H/claim_form.jpg') }}" alt="" style="width: 100%" class="claim-form-img" width="893" height="1266">
                </div>
            </div>
            <div class="columns small-12 large-6 padding-1">
                <div class="claim-form__form padding-right">
                    <div class="form-wrapper">
                        <h1 class="claim-form__title margin-bottom-2 text-center"> Payment</h1>
                        <!-- Stripe link -->
                        <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
                        <form accept-charset="UTF-8" action="/pay" class="require-validation"
                              data-cc-on-file="false"
                              data-stripe-publishable-key="pk_test_Z5nUrqZ0IFlhqtDwQrQAZNt700t6IudgzI"
                              id="payment-form" method="post">
                            {{ csrf_field() }}

                            {{--<fieldset class="margin-bottom-2">
                                <legend class="claim-form__legend">Step 1</legend>
                                <div class='form-row row'>
                                    <div class='col-xs-6 form-group cvc required padding-bottom columns padding-1'>
                                        <label class='control-label visually-hidden'>First name</label>
                                        <input autocomplete='off' class='form-control card-cvc' placeholder='First name' size='4' type='text'>
                                    </div>
                                    <div class='col-xs-6 form-group expiration required padding-bottom columns padding-1'>
                                        <label class='control-label visually-hidden'>Last name</label>
                                        <input class='form-control card-expiry-month' placeholder='Last name' size='2' type='text'>
                                    </div>
                                </div>
                                <div class='form-row'>
                                    <div class='padding-bottom col-xs-12 form-group required'>
                                        <label class='control-label visually-hidden'>Address</label>
                                        <input class='form-control' size='4' type='text'  placeholder="Address">
                                    </div>
                                </div>
                                <div class='form-row'>
                                    <div class='col-md-12 form-group text-center'>
                                        <button class='wizard-finish button primary --}}{{--form-control btn btn-primary submit-button--}}{{--'
                                                type='submit' style="margin-top: 10px;">Confirm</button>
                                    </div>
                                </div>
                            </fieldset>--}}

                            <fieldset>
                                {{--<legend class="claim-form__legend padding-bottom-1">Step 2</legend>--}}
                                <p>Please click Pay Now button to make the payment of 	&#36;129.95 for a UE Blast (RRP 279.95) </p>
                                <div class='form-row'>
                                    <div class='padding-bottom col-xs-12 form-group required'>
                                        <label class='control-label visually-hidden'>Name on Card</label>
                                        <input class='form-control' size='4' type='text'  placeholder="Name on card" value="Michael Jackson">
                                    </div>
                                </div>

                                <div class='form-row'>
                                    <div class='col-xs-12 form-group {{--card--}} required'>
                                        <label class='control-label visually-hidden' style="color: white;">Card Number</label>
                                        <input autocomplete='off' class='form-control card-number' size='20' type='text'
                                               placeholder="Card Number"
                                               value="4242424242424242">
                                    </div>
                                </div>

                                <div class='form-row row'>
                                    <div class='col-xs-4 form-group cvc required padding-bottom columns padding-1'>
                                        <label class='control-label visually-hidden'>CVC</label>
                                        <input autocomplete='off'
                                               class='form-control card-cvc' placeholder='ex. 311' size='4'
                                               type='text' value="781">
                                    </div>
                                    <div class='col-xs-4 form-group expiration required padding-bottom columns padding-1'>
                                        <label class='control-label visually-hidden'>Expiration</label>
                                        <input
                                                class='form-control card-expiry-month' placeholder='MM' size='2'
                                                type='text' value="03">
                                    </div>
                                    <div class='col-xs-4 form-group expiration required padding-bottom columns padding-1'>
                                        <label class='control-label visually-hidden'>&nbsp</label>
                                        <input
                                                class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                                type='text' value="2020">
                                    </div>
                                </div>

                                <div class='form-row'>
                                    <div class='col-md-12'>
                                        <div class='form-control total btn btn-info'>
                                            {{--Total: <span class='amount'>$300</span>--}}
                                            <label class='control-label'>Total: 300$</label>
                                        </div>
                                    </div>
                                </div>
                                <div class='form-row'>
                                    <div class='col-md-12 form-group text-center'>
                                        <button class='wizard-finish button primary {{--form-control btn btn-primary submit-button--}}'
                                                type='submit' style="margin-top: 10px;">Pay Now</button>
                                    </div>
                                </div>
                                <div class='form-row'>
                                    <div class='col-md-12 error form-group hide'>
                                        <div class='alert-danger alert'>Please correct the errors and try
                                            again.</div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        label {color: white}
        .has-error input {border-color: red}
        /*body {background-color: powderblue;}
        h1   {color: blue;}
        p    {color: red;}*/
    </style>


@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
    <script>
        // Validation
        $(function() {
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(e.target).closest('form'),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault(); // cancel on first error
                    }
                });
            });
        });
        // Generate stripe token
        $(function() {
            var $form = $("#payment-form");
            $form.on('submit', function(e) {
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }
            });
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    // Payer hash here
                    $form.get(0).submit();
                }
            }
        })
    </script>
@endsection


