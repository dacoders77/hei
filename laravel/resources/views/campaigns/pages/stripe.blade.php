{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_1')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection

{{-- MAIN CONTENT--}}
@section('content')

    <h1>stripe.blade.php</h1>

    <div style="border: 0px solid darkred; padding: 0 200px 0 200px;">
        <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
        <form accept-charset="UTF-8" action="/pay" class="require-validation"
              data-cc-on-file="false"
              data-stripe-publishable-key="pk_test_Z5nUrqZ0IFlhqtDwQrQAZNt700t6IudgzI"
              id="payment-form" method="post">
            {{ csrf_field() }}

            <div class='form-row'>
                <div class='col-xs-12 form-group required'>
                    <label class='control-label'>Name on Card</label>
                    <input class='form-control' size='4' type='text' value="Boris Borisov">
                </div>
            </div>

            <div class='form-row'>
                <div class='col-xs-12 form-group {{--card--}} required'>
                    <label class='control-label' style="color: white;">Card Number</label>
                    <input autocomplete='off' class='form-control card-number' size='20' type='text' value="4242424242424242">
                </div>
            </div>

            <div class='form-row'>
                <div class='col-xs-4 form-group cvc required'>
                    <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                    type='text'
                                                                    value="781">
                </div>
                <div class='col-xs-4 form-group expiration required'>
                    <label class='control-label'>Expiration</label> <input
                            class='form-control card-expiry-month' placeholder='MM' size='2'
                            type='text' value="03">
                </div>
                <div class='col-xs-4 form-group expiration required'>
                    <label class='control-label'> </label> <input
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
                <div class='col-md-12 form-group'>
                    <button class='wizard-finish button primary {{--form-control btn btn-primary submit-button--}}'
                            type='submit' style="margin-top: 10px;">Pay</button>
                </div>
            </div>
            <div class='form-row'>
                <div class='col-md-12 error form-group hide'>
                    <div class='alert-danger alert'>Please correct the errors and try
                        again.</div>
                </div>
            </div>
        </form>

        @if ((Session::has('success-message')))
            <div class="alert alert-success col-md-12">{{
					Session::get('success-message') }}</div>
        @endif @if ((Session::has('fail-message')))
            <div class="alert alert-danger col-md-12">{{
					Session::get('fail-message') }}</div>
        @endif

    </div>

    <style>
        label {color: white}
        .has-error input {border-color: red}
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