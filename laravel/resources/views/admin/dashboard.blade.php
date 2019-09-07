@extends('admin.layouts.app')

@section('main-content')

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-xs-12 col-md-8">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Submissions: Last 30 days</h3>
				</div>
				<div class="box-body chart-responsive">
					<div class="row">
						<div class="col-xs-4 border-right">
							<div class="description-block">
								<h5 class="description-header">289</h5>
								<span class="description-text">APPROVED</span>
							</div>
						</div>
						<div class="col-xs-4 border-right">
							<div class="description-block">
								<h5 class="description-header">50</h5>
								<span class="description-text">REJECTED</span>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="description-block">
								<h5 class="description-header">329</h5>
								<span class="description-text">TOTAL</span>
							</div>
						</div>
					</div>
					<div class="chart" id="submissions-last-30">
						
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="row">
				<div class="col-xs-12">
					<div class="small-box bg-aqua">
			            <div class="inner">
			              <h3>1</h3>

			              <p>Active Campaigns</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-edit"></i>
			            </div>
			            <a href="{{ route('campaigns.index') }}" class="small-box-footer">
			              View Campaigns <i class="fa fa-arrow-circle-right"></i>
			            </a>
			        </div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="small-box bg-green">
			            <div class="inner">
			              <h3>3,299</h3>

			              <p>Active Consumers</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-user"></i>
			            </div>
			            <a href="#" class="small-box-footer">
			              View Consumers <i class="fa fa-arrow-circle-right"></i>
			            </a>
			        </div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="small-box bg-red">
			            <div class="inner">
			              <h3>12</h3>

			              <p>Pending Submissions</p>
			            </div>
			            <div class="icon">
			              <i class="fa fa-list"></i>
			            </div>
			            <a href="#" class="small-box-footer">
			              View Submissions <i class="fa fa-arrow-circle-right"></i>
			            </a>
			        </div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

@endsection

@section('footer-scripts')
<script>
	(function($){
		function ordinal_suffix_of(i) {
		    var j = i % 10,
		        k = i % 100;
		    if (j == 1 && k != 11) {
		        return i + "st";
		    }
		    if (j == 2 && k != 12) {
		        return i + "nd";
		    }
		    if (j == 3 && k != 13) {
		        return i + "rd";
		    }
		    return i + "th";
		}

		var line = new Morris.Bar({
	      element: 'submissions-last-30',
	      resize: true,
	      data: [
	      	@for ($i = -30; $i <= 0; $i++)
	      		@php
	      			$a = rand(5, 15);
	      			$b = rand(0, 2);
	      			$c = rand(5, 15);
	      			$d = rand(5, 15);
	      		@endphp
	      		{
	      			date: '{{ date('Y-m-d',strtotime($i.' days')) }}',
	      			b: {{ $b }},
	      			c: {{ $c }},
	      			d: {{ $b+$c }},
	      		},
	      	@endfor
	      ],
	      xkey: 'date',
	      ykeys: ['b','c','d'],
	      xLabelFormat: function (date) {

	      	console.log(date.src.date);

	      	var monthNames = [
				"Jan", "Feb", "Marh",
				"Apr", "May", "Jun", "Jul",
				"Aug", "Sept", "Oct",
				"Nov", "Dec"
			];

			date = new Date(date.src.date);

			var day = date.getDate();
			var monthIndex = date.getMonth();

			return ordinal_suffix_of(day) + ' ' + monthNames[monthIndex];
	      },
	  //     dateFormat: function (date) {
	  //     	console.log(date);
	  //     	var monthNames = [
			//     "January", "February", "March",
			//     "April", "May", "June", "July",
			//     "August", "September", "October",
			//     "November", "December"
			// ];

			// date = new Date(date);

			// var day = date.getDate();
			// var monthIndex = date.getMonth();
			// var year = date.getFullYear();

			// return ordinal_suffix_of(day) + ' ' + monthNames[monthIndex] + ' ' + year;
	  //     },
		  stacked: 'true',
	      labels: ['Approved','Rejected','Total'],
	      barColors: ['#00a65a','#f56954','#d2d6de'],
	      hideHover: 'auto'
	    });
	})(jQuery);
</script>
@endsection