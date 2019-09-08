{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_1')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection

{{-- MAIN CONTENT--}}
@section('content')

    <h1>stripe.blade.php</h1>

    <div style="border: 0px solid darkred; padding: 0 200px 0 200px;">

        <form accept-charset="UTF-8" action="/xxx" class="require-validation"
              data-cc-on-file="false"
              data-stripe-publishable-key="pk_test_Z5nUrqZ0IFlhqtDwQrQAZNt700t6IudgzI"
              id="payment-form" method="post">
            {{ csrf_field() }}

            <div class='form-row'>
                <div class='col-xs-12 form-group required'>
                    <label class='control-label'>Name on Card</label>
                    <input class='form-control' size='4' type='text'>
                </div>
            </div>

            <div class='form-row'>
                <div class='col-xs-12 form-group {{--card--}} required'>
                    <label class='control-label' style="color: white;">Card Number</label>
                    <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                </div>
            </div>

            <div class='form-row'>
                <div class='col-xs-4 form-group cvc required'>
                    <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                    type='text'>
                </div>
                <div class='col-xs-4 form-group expiration required'>
                    <label class='control-label'>Expiration</label> <input
                            class='form-control card-expiry-month' placeholder='MM' size='2'
                            type='text'>
                </div>
                <div class='col-xs-4 form-group expiration required'>
                    <label class='control-label'> </label> <input
                            class='form-control card-expiry-year' placeholder='YYYY' size='4'
                            type='text'>
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
        $(function() {
            $('form.require-validation').bind('submit', function(e) {
                var $form = $(e.target).closest('form'),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;

                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');

                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error'); // Make red in case of validation errors
                        $errorMessage.removeClass('hide');
                        e.preventDefault(); // cancel on first error
                    }
                });
            });
        });
    </script>
@endsection