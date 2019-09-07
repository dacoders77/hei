<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=10">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="7200">

    <title>{{ isset($campaign) ? $campaign->title : 'Campaign' }}</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/H/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/H/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/H/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/H/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets/images/H/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ asset('assets/css/build.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/campaign.css') }}?d={{ date('U') }}" rel="stylesheet">
    @yield('head_styles')

    @if (config('app.debug'))<script>window.ajaxDebug=true;</script>@endif

    <script>
        if(!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/) && /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {var h = document.getElementsByTagName( 'html' )[0]; h.className += ' iosSafari';}
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146133941-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-146133941-1');
    </script> --}}
</head>
<body class="page--{{ \Request::segment(1)?:'front' }}">
    <section class="main-content">
        @yield('content')
    </section>
    <footer class="main-footer">
        <div class="row align-middle">
            <div class="columns small-12">
                <div class="row text-center">
                    <div class="columns">
                        <div class="icon-before">
                            <img src="{{ asset('assets/images/H/enjoyicon.png') }}" alt="">
                        </div>
                        <div class="footer-links">
                            <a href="{{ route('campaign_1.pages','privacy') }}" target="_blank">Privacy Policy</a>
                            <a href="{{ route('campaign_1.pages','terms') }}">Terms & Conditions</a>
                            <a href="{{ route('campaign_1.pages','faqs') }}">FAQs</a>
                            <a href="{{ route('campaign_1.pages','contact-us') }}">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <noscript>
        <div id="no-js">
            <div class="message">
                <i class="fa fa-info-circle"></i> &nbsp;Javascript is required. Please activate to use this website.
            </div>
        </div>
    </noscript>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/build.js') }}"></script>
    <script src="{{ asset('assets/js/campaign.js') }}?d={{ date('U') }}"></script>

    @yield('footer_scripts')

    @if (\Auth::guard('admin')->check())
        <div id="admin-login" style="position:fixed;top:10px;right:10px;">
            <a href="/admin"
               style="display:block;background:#3c8dbc;color:#fff;border:1px solid #fff;padding:5px 10px;border-radius:5px;font-family:sans-serif;font-size: 13px;font-weight:normal;"><i class="fa fa-tachometer" aria-hidden="true"></i> Dash<strong>Board</strong></a></div><style>#admin-login a:hover{background:#4499CB!important;}</style>
    @endif
</body>
</html>
