<<<<<<< HEAD
   <div class="page-wrapper hide" id="age-splash1">
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
                       <form accept-charset="UTF-8" action="/xxx" class="require-validation"
                                     data-cc-on-file="false"
                                     data-stripe-publishable-key="pk_test_Z5nUrqZ0IFlhqtDwQrQAZNt700t6IudgzI"
                                     id="payment-form" method="post">
                                   {{ csrf_field() }}

                                <fieldset class="margin-bottom-2">
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
                                             <button class='wizard-finish button primary {{--form-control btn btn-primary submit-button--}}'
                                              type='submit' style="margin-top: 10px;">Confirm</button>
                                             </div>
                                        </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="claim-form__legend padding-bottom-1">Step 2</legend>
                                    <p>Please click Pay Now button to make the payment of 	&#36;129.95 for a UE Blast (RRP 279.95) </p>
                                   <div class='form-row'>
                                       <div class='padding-bottom col-xs-12 form-group required'>
                                           <label class='control-label visually-hidden'>Name on Card</label>
                                           <input class='form-control' size='4' type='text'  placeholder="Card Number">
                                       </div>
                                   </div>

                                   <div class='form-row'>
                                       <div class='col-xs-12 form-group {{--card--}} required padding-bottom '>
                                           <label class='control-label visually-hidden' style="color: white;">Card Number</label>
                                           <input autocomplete='off' class='form-control card-number' size='20' type='text' placeholder="Card Number">
                                       </div>
                                   </div>

                                   <div class='form-row row'>
                                       <div class='col-xs-4 form-group cvc required padding-bottom columns padding-1'>
                                           <label class='control-label visually-hidden'>CVC</label>
                                           <input autocomplete='off'
                                                                                           class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                                           type='text'>
                                       </div>
                                       <div class='col-xs-4 form-group expiration required padding-bottom columns padding-1'>
                                           <label class='control-label visually-hidden'>Expiration</label>
                                            <input
                                                   class='form-control card-expiry-month' placeholder='MM' size='2'
                                                   type='text'>
                                       </div>
                                       <div class='col-xs-4 form-group expiration required padding-bottom columns padding-1'>
                                           <label class='control-label visually-hidden'> </label>
                                           <input
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
=======
