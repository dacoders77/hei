<!-- /.content-wrapper -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.2.0
  </div>
  <i class="fa fa-code" aria-hidden="true"></i> with <i class="fa fa-heart-o" aria-hidden="true"></i></a>
</footer>


</div>
<!-- ./wrapper -->

<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;"><input type="hidden" name="_token" value="{{ csrf_token() }}"></form>

<!-- jQuery 3 -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/admin/admin-lte/bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('assets/admin/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('assets/admin/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('assets/admin/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/fastclick/lib/fastclick.js') }}"></script>

<script src="{{ asset('assets/admin/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<!-- CK Editor -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/ckeditor/ckeditor.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('assets/admin/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/admin/admin-lte/dist/js/adminlte.js') }}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="{{ asset('assets/admin/admin-lte/dist/js/pages/dashboard.js') }}"></script> --}}
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/admin/admin-lte/dist/js/demo.js') }}"></script> --}}

<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>

<script src="{{ asset('assets/admin/js/admin.js') }}?v={{ date('U') }}"></script>

@section('footer-scripts')
@show

</body>
</html>