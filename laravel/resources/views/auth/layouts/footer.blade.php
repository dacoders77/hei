

<!-- jQuery 3 -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/admin/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/admin/admin-lte/plugins/iCheck/icheck.min.js') }}"></script>

{{-- <script src="{{ asset('assets/admin/admin-lte/dist/js/admin_script.js') }}?v={{ date('U') }}"></script> --}}

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

</body>
</html>