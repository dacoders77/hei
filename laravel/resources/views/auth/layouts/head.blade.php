
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>DashBoard</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!-- Favicons -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/apple-touch-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/apple-touch-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/apple-touch-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/apple-touch-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/apple-touch-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/apple-touch-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/apple-touch-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/apple-touch-icon-152x152.png') }}">
<link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-196x196.png') }}" sizes="196x196">
<link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-160x160.png') }}" sizes="160x160">
<link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-96x96.png') }}" sizes="96x96">
<link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-32x32.png') }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-16x16.png') }}" sizes="16x16">
<meta name="msapplication-TileColor" content="#009fee">
<meta name="msapplication-TileImage" content="{{ asset('assets/images/mstile-144x144.png') }}">
<meta name="msapplication-square70x70logo" content="{{ asset('assets/images/mstile-70x70.png') }}">
<meta name="msapplication-square144x144logo" content="{{ asset('assets/images/mstile-144x144.png') }}">
<meta name="msapplication-square150x150logo" content="{{ asset('assets/images/mstile-150x150.png') }}">
<meta name="msapplication-square310x310logo" content="{{ asset('assets/images/mstile-310x310.png') }}">
<meta name="msapplication-wide310x150logo" content="{{ asset('assets/images/mstile-310x150.png') }}">

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('assets/admin/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/admin/admin-lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('assets/admin/admin-lte/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/admin/admin-lte/dist/css/AdminLTE.min.css') }}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('assets/admin/admin-lte/plugins/iCheck/square/blue.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">

<script>
    var csrf_token = "{{ csrf_token() }}";
</script>