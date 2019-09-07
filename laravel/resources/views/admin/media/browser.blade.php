<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Media Browser</title>

		<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="{{ asset('assets/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('assets/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('assets/admin/dist/css/AdminLTE.min.css') }}">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	     folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="{{ asset('assets/admin/dist/css/skins/_all-skins.min.css') }}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

	<style>
		.breadcrumb {
			margin: 0;
		}

		.modal-body img {
			max-width: 100%;
			height: auto;
		}
	</style>
</head>
<body class="skin-blue layout-top-nav">

	<div class="wrapper">
		<header class="main-header">
		    <nav class="navbar navbar-static-top">

		        <div class="navbar-header">
			    	<a class="logo">
				      <!-- mini logo for sidebar mini 50x50 pixels -->
				      <span class="logo-mini"><b>M</b>B</span>
				      <!-- logo for regular state and mobile devices -->
				      <span class="logo-lg"><b>Media</b>Browser</span>
				    </a>
				</div>

		    </nav>
		</header>

		<div class="content-wrapper">
			<section class="content-header">
				<h1><i class="fa fa-folder"></i> <span>Uploads</span></h1>
			</section>

			<section class="content">
				<div class="box">
					<div class="box-header with-border">
						<ol class="breadcrumb">
			        		<li><a href="#!/uploads/"><i class="fa fa-folder"></i> Uploads</a></li>
			        	</ol>
			        </div>
			        <div class="box-body">
			        	<table id="media" class="table table-bordered">
			        		<thead>
					            <tr>
						            <th>Name</th>
						            <th>Type</th>
						            <th>Size</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<tr>
						    		<td colspan="3">No files found</td>
						    	</tr>
						    </tbody>
			        	</table>
			        </div>
				</div>
			</section>
		</div>
	</div>

	<div id="file-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title">&nbsp;</h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary insert">Insert</button>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery 3 -->
	<script src="{{ asset('assets/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="{{ asset('assets/admin/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>

	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button);
	</script>

	<!-- Bootstrap 3.3.7 -->
	<script src="{{ asset('assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

	<!-- Slimscroll -->
	<script src="{{ asset('assets/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
	<!-- FastClick -->
	<script src="{{ asset('assets/admin/bower_components/fastclick/lib/fastclick.js') }}"></script>

	<!-- AdminLTE App -->
	<script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
	<script src="{{ asset('assets/admin/dist/js/demo.js') }}"></script>

	<script>
        // Helper function to get parameters from the query string.
        function getUrlParam( paramName ) {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );

            return ( match && match.length > 1 ) ? match[1] : null;
        }
        // Simulate user action of selecting a file to be returned to CKEditor.
        function returnFileUrl(fileUrl) {
            var funcNum = getUrlParam( 'CKEditorFuncNum' );
            window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl );
            window.close();
        }

        function breadcrumbs() {
        	var path = window.location.hash.replace(/^\#\!\/?(.*)$|^\#\!\/?(.*)\/$/,'$1').split('/');

        	$('ol.breadcrumb').html('<li><a href="#!/"><i class="fa fa-folder"></i> Uploads</a></li>');

        	$title = 'Uploads';

        	if(path.length){
        		$url = '#!/';
        		$.each(path,function(i,e){
        			if(e){
	        			$url += e + '/';

	        			if($url == window.location.hash || $url == window.location.hash + '/') {
	        				$('ol.breadcrumb').append('<li>'+e+'</li>');
	        				$title = e;
	        			} else {
	        				$('ol.breadcrumb').append('<li><a href="'+$url+'">'+e+'</a></li>');
	        			}
	        		}
        		});
        	}

        	$('.content-header>h1>span').text( $title );
        }

        function scan(){
        	$('table#media>tbody').html('');

			$.get('/admin/files/ajax', { dir: window.location.hash, type: '{{ app('request')->input('type') }}' }, function(data) {
				if(data.length){
					$.each(data,function(i,e){
						if(e.type == 'folder'){
							$('table#media>tbody').append('<tr><td><a href="#!'+e.path+'"><i class="fa fa-folder"></i> '+e.name+'</a></td><td>'+e.type+'</td><td>'+e.size+'</td></tr>');
						}
					});
					$.each(data,function(i,e){
						if(e.type !== 'folder'){
							$('table#media>tbody').append('<tr><td><a href="#" data-toggle="modal" data-target="#file-modal" class="file" data-file="'+e.path+'">'+e.name+'</a></td><td>'+e.type+'</td><td>'+e.size+'</td></tr>');
						}
					});
				} else {
					$('table#media>tbody').html('<tr><td colspan="3">No files found</td></tr>');
				}
			});
		}

        (function($){
        	$(window).on('hashchange', function(){
        		breadcrumbs();
        		scan();
        	}).trigger('hashchange');

        	$(document).on('click','#media .file',function(){
        		var href = $(this).attr('data-file');
        		var title = $(this).text();
        		if( (/\.(gif|jpg|jpeg|tiff|png)$/i).test(href) ){
        			$('#file-modal .modal-body').html('<p class="text-center"><img src="'+href+'" alt="'+title+'"></p><p class="text-center">'+title+'</p>');
        		} else {
        			$('#file-modal .modal-body').html('<p class="text-center"><span class="info-box-icon bg-yellow" style="float:none;display:inline-block;"><i class="fa fa-file-o"></i></span></p><p class="text-center">'+title+'</p>');
        		}
        		$('#file-modal .modal-header .modal-title').text( title );
        		$('#file-modal .modal-footer .insert').attr('data-file',href);
        	});

        	$(document).on('click','#file-modal .modal-footer .insert', function(){
        		returnFileUrl( $(this).attr('data-file') );
        	});
        })(jQuery);
    </script>
</body>
</html>