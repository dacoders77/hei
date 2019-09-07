
@include('admin.layouts.header')
@include('admin.layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {!! $title !!}
      @if (isset($subtitle))
      	<small>{!! $subtitle !!}</small>
      @endif
    </h1>
  </section>

	@section('main-content')
		@show

</div>


@include('admin.layouts.footer')