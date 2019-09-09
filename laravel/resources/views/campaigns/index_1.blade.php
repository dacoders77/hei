{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_1')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection


{{-- MAIN CONTENT--}}
@section('content')

    {{--<div style="border: 1px solid red; text-align: center; padding: 200px"> hello from index_1.blade.php</div>--}}

    <!-- Age gate -->
    <div id="age-splash" class="page-wrapper  {{--hide--}} ">
        <div class="row padding-top--2x padding-bottom--3x">
            <div class="large-8 large-offset-2 columns">

                <div style="border: 0px solid blue; text-align: center; padding: 30px">
                    <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="" width="200" height="110" class="hlogo">
                </div>

                <!-- Border -->
                {{--<div class="form-wrapper float-center padding-top--2x padding-bottom--2x content-light">--}}
                <div style="border: 0px solid red; width: 100%; background-color: #00851e">


                    <form id="age-form">
                        <div class="row">
                            <div class="columns small-12 text-center">
                                {{--<img src="{{ asset('assets/images/H/56caae3ce1272f5c19000151.png') }}" alt="" width="550">--}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="columns small-12 padding-top">
                                <h1 class="text-center">How old are you?</h1>
                                <p class="text-center">Enter your date of birth.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12">
                                <div class="input-wrap">
                                    <div class="input hide hide-for-large">
                                        <input type="date" name="ym" id="ym">
                                    </div>

                                    <div class="inputs">
                                        <input type="number" name="d1" id="d1" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="1" placeholder="D" required>
                                        <input type="number" name="d2" id="d2" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="2" placeholder="D" required>

                                        <span>-</span>

                                        <input type="number" name="m1" id="m1" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="3" placeholder="M" required>
                                        <input type="number" name="m2" id="m2" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="4" placeholder="M" required>

                                        <span>-</span>

                                        <input type="number" name="y1" id="y1" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="5" placeholder="Y" required>
                                        <input type="number" name="y2" id="y2" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="6" placeholder="Y" required>
                                        <input type="number" name="y3" id="y3" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="7" placeholder="Y" required>
                                        <input type="number" name="y4" id="y4" pattern="[0-9]*" size="1" maxlength="1" min="0" max="9" tabindex="8" placeholder="Y" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12 text-center">
                                <input type="submit" value="Enter" class="button">
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12 padding-top">
                                <p class="text-center disclaimer">By submitting this form, you opt into Heineken<sup>&reg;</sup> <a href="#" target="_blank" class="form-link ">Privacy Policy</a>.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="columns small-12 text-center">
                                {{--<img src="{{ asset('assets/images/H/HeinekenLogo_Stacked_01.png') }}" alt="" width="150" class="hlogo">--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Second step -->
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
                    <h1 class="claim-form__title margin-bottom-2 text-center"> Claim form</h1>
                    @include('campaigns.layouts.jsform',['formRoute'=>['campaign_'.$campaign->id.'.submissions.store',$campaign->id]])
                </div>
            </div>
            </div>
        </div>
    </div>

 <!-- Third step -->
    <div class="page-wrapper hide" id="age-splash3">
        <div style="border: 0px solid blue; text-align: center; padding: 30px">
             <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
        </div>
        <div class="row affirmation full">
            <div class="columns small-12 affirmation__img">
                 <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
            </div>
            <div class="columns small-12 medium-10 affirmation__text-wrap">
                <h3 class="text-center text-black affirmation__title">We’ve received your claim</h3>
                <p class="text-center text-black affirmation__text">Thank you for submitting your claim. Your claim will be reviewed and approved within 2 days. You will then be sent an email with further instructions outlining how to process payment for your UE BLAST Speaker. </p>
                <p class="text-center text-black affirmation__text">If you don’t receive an email please check your Junk and Spam folders. If you still can’t find it please <a href = "#" class="affirmation__link"> Contact Us.</a></p>
            </div>
        </div>
    </div>

    <!--Claim approved-->
     <div class="page-wrapper hide" id="age-splash3">
            <div style="border: 0px solid blue; text-align: center; padding: 30px">
                 <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
            </div>
            <div class="row affirmation full">
                <div class="columns small-12 affirmation__img">
                     <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
                </div>
                <div class="columns small-12 medium-10 affirmation__text-wrap">
                    <h3 class="text-center text-black affirmation__title">Your claim is approved</h3>
                    <p class="text-center text-black affirmation__text">
                    Congratulations! Your Heineken UE BLAST claim has been approved. Please click this <a href = "#" class="affirmation__link"> unique link </a> to process payment of &#36;129.95 for your UE BLAST Speaker by 12th November 2019.
                    </p>
                </div>
            </div>
     </div>

      <!--Claim rejection-->
     <div class="page-wrapper hide" id="age-splash3">
           <div style="border: 0px solid blue; text-align: center; padding: 30px">
                <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
           </div>
           <div class="row affirmation full">
               <div class="columns small-12 affirmation__img">
                    <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
               </div>
               <div class="columns small-12 medium-10 affirmation__text-wrap">
                   <h3 class="text-center text-black affirmation__title">Unfortunately your <br>claim has been declined</h3>
                   <p class="text-center text-black affirmation__text">Reason: &#91;Invalid reason&#93; </p>
                   <p class="text-center text-black affirmation__text">If you have any further information you would like to provide please <a href = "#" class="affirmation__link">Contact Us.</a></p>
               </div>
           </div>
     </div>
      <!--Payment processed-->
          <div class="page-wrapper hide" id="age-splash3">
                <div style="border: 0px solid blue; text-align: center; padding: 30px">
                     <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
                </div>
                <div class="row affirmation full">
                    <div class="columns small-12 affirmation__img">
                         <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
                    </div>
                    <div class="columns small-12 medium-10 affirmation__text-wrap">
                        <h3 class="text-center text-black affirmation__title">Your payment <br> has been processed</h3>
                        <p class="text-center text-black affirmation__text">Thank you for making your payment. Your payment will show up on your card from VCGPromorisk Pty Ltd within the next 72 working hours and you will receive your UE BLAST speaker within 28 business days.</p>
                        <p class="text-center text-black affirmation__text">You will receive one final message from us when your speaker is shipped to your nominated address.</p>
                    </div>
                </div>
          </div>
        <!--Delivery-->
                  <div class="page-wrapper hide" id="age-splash3">
                        <div style="border: 0px solid blue; text-align: center; padding: 30px">
                             <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
                        </div>
                        <div class="row affirmation full">
                            <div class="columns small-12 affirmation__img">
                                 <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
                            </div>
                            <div class="columns small-12 medium-10 affirmation__text-wrap">
                                <h3 class="text-center text-black affirmation__title">Your UE BLAST is on its way!</h3>
                                <p class="text-center text-black affirmation__text">Here is your tracking number for reference:
                                <span> XXXXX </span></p>
                            </div>
                        </div>
                  </div>

@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
@endsection
