{{-- SITE WRAPPER --}}
@extends('campaigns.layouts.wrapper_1')

{{-- <HEAD> STYLES--}}
@section('head_styles')
@endsection

{{-- MAIN CONTENT--}}
@section('content')

    <h1>info.blade.php</h1>
    <div class="page-wrapper" id="age-splash3">
        <div style="border: 0px solid blue; text-align: center; padding: 30px">
            <img src="{{ asset('assets/images/H/58480a5fcef1014c0b5e4919-1.png') }}" alt="Heineken logo" width="200" height="110" class="hlogo">
        </div>
        <div class="row affirmation full">
            <div class="columns small-12 affirmation__img">
                <img src="{{ asset('assets/images/H/claim_confirmation.jpg') }}" alt="" width="100%" >
            </div>
            <div class="columns small-12 medium-10 affirmation__text-wrap">
                <h3 class="text-center text-black affirmation__title">{{ $title }}</h3>
                {{--<p class="text-center text-black affirmation__text"></p>--}}
                <p class="text-center text-black affirmation__text">{{ $message }}</p>
            </div>
        </div>
    </div>


    <style></style>

@endsection

{{-- FOOTER CONTENT--}}
@section('footer')
@endsection

{{-- FOOTER SCRIPTS --}}
@section('footer_scripts')
    <script>
        //
    </script>
@endsection