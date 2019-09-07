<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="7200">

    <title>{{ $campaign->title }}</title>

    <!-- Favicons -->
    <link rel="icon" type="img/png" href="{{ asset('assets/images/AGL/favicon.png') }}">

    <link href="{{ asset('assets/css/build.css') }}" rel="stylesheet">

    @if (config('app.debug'))<script>window.ajaxDebug=true;</script>@endif

    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138992729-1"></script>
    <script>
     window.dataLayer = window.dataLayer || [];
     function gtag(){dataLayer.push(arguments);}
     gtag('js', new Date());

     gtag('config', 'UA-138992729-1');
    </script> --}}
</head>
<body class="page--{{ \Request::segment(1)?:'front' }}">
    <div class="full-height">
        <div class="row expanded padding-top--3x">
            <div class="columns small-12">
                <div class="logo-wrapper">
                    <div class="row">
                        <div class="columns mall-12">
                            <img src="{{ asset('assets/images/AGL/agl_logo.png') }}" alt="AGL Logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="page-wrapper">
                <div class="row padding-bottom--3x">
                    @yield('content')
                </div>
            </div>
        </section>
    </div>
    <footer class="footer">
        @yield('footer')

        <div class="row">
            <div class="small-12 medium-10 float-center">
                <div class="footer__links text-center">
                    {{-- <p>The promotion commences on 15/04/19 and closes on 17/05/19 or while stocks last.</p> --}}

                    {{-- <ul>
                        <li><a href="{{ route('campaign1.faqs') }}" target="_blank">FAQs</a></li>
                        <li><a href="{{ route('campaign1.terms') }}" target="_blank">Terms & Conditions</a></li>
                        <li><a href="https://www.agl.com.au/privacy-policy" target="_blank">Privacy Policy</a></li>
                        <li><a href="{{ route('campaign1.contactus') }}" target="_blank">Contact Us</a></li>
                    </ul> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 medium-10 float-center text-center padding-top--2x">
                <img src="{{ asset('assets/images/AGL/agl_logo_white.png') }}" alt="AGL Logo White">
            </div>
        </div>
    </footer>
    {{-- <script src="{{ asset('assets/js/vendor/jquery-3.3.1.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/build.js') }}"></script>

    @yield('footer_scripts')

    @if (\Auth::guard('admin')->check())
        <div id="admin-login" style="position:fixed;top:10px;right:10px;"><a href="/admin" style="display:block;background:#333;color:#eee;border:1px solid rgba(125,125,125,0.5);padding:5px 10px;border-radius:5px;font-family:sans-serif;font-size: 13px;font-weight:normal;"><i class="fa fa-tachometer" aria-hidden="true"></i> Dash<strong>Board</strong></a></div><style>#admin-login a:hover{background:#555!important;}</style>
    @endif
</body>
</html>
