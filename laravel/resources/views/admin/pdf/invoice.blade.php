<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Weekly Report: {{ $startDate }} – {{ $endDate }} | Swisse Instant Win and Main Draw Promotion | Australia and New Zealand</title>
	<meta name="author" content="Pilgrim Communications">
	<style>
	{!! $stylesheet !!}

	@media dompdf {
		body,html {
			background-color: #fff;
		}
		body {
			margin: 0;
			box-shadow: 0 none;
		}
		#header {
			position: fixed;
			height: 40px;
		}
		.page-break {
			border-bottom: 0 none;
		}
	}
	</style>
</head>
<body>

	<div id="header">
		<div class="container">
			<div class="row">
				<div class="span-3">
					<div class="header-logo-1">
						<img src="{{ $pilgrimLogo }}" alt="">
					</div>
				</div>
				<div class="span-3 span-last">
					<div class="header-logo-2">
						<img src="{{ $swisseLogo }}" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

	@foreach ($campaigns as $index => $campaign)

	<div class="page-wrapper">

		<div class="section">
			<div class="section-header">
				<h1 class="blue">Swisse {{ $index==0?'Instant':'Weekly' }} Win and Main Draw Promotion</h1>
				{{-- <h3>Australia and New Zealand</h3> --}}
				<p class="gray light">{{ $index==0?'AU':'NZ' }} Weekly Report: {{ $startDate }} – {{ $endDate }}</p>
			</div>
		</div>

		<hr>

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-4 border-right text-center">
						<div class="total-box">
							<h4 class="uppercase">Total Entries</h4>
							<p class="blue">{{ $campaign['total_entries'] }}</p>
						</div>
					</div>
					<div class="span-4 border-right text-center">
						<div class="total-box">
							<h4 class="uppercase">Unique Entries</h4>
							<p class="green">{{ $campaign['total_unique_entries'] }}</p>
						</div>
					</div>
					<div class="span-4 span-last text-center">
						<div class="total-box">
							<h4 class="uppercase">Marketing Opt-in</h4>
							<p class="orange">{{ $campaign['total_opt_in'] }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr>

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-4">
						<div class="graph donut">
							<img src="{!! $campaign['graphs'][0]['graph'] !!}" alt="">
						</div>
					</div>
					<div class="span-8 span-last">
						<table>
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">Frequency of entries</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$n = 0;
								@endphp
								@foreach ($campaign['graphs'][0]['values'] as $key => $val)
									<tr>
										<td style="width:50%;"><strong style="font-size:30px; line-height:0;vertical-align:middle;color: {{ $campaign['graphs'][0]['colors'][$n] }}">•</strong> {{ $key }}</td>
										<td style="width:50%;">{{ $val }}</td>
									</tr>
									@php
										$n++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		{{-- <hr> --}}

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-12">
						<div class="text-center">
							<strong class="uppercase">Daily entries</strong>
						</div>
						<div class="graph">
							<img src="{!! $campaign['graphs'][1]['graph'] !!}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-12">
						<div class="text-center">
							<strong class="uppercase">Hourly entries</strong>
						</div>
						<div class="graph">
							<img src="{!! $campaign['graphs'][2]['graph'] !!}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="page-break"></div>

	<div class="page-wrapper">
		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-6">
						<table class="tight">
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">Entries per State</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$n = 0;
								@endphp
								@foreach ($campaign['graphs'][3]['values'] as $key => $val)
									<tr>
										<td style="width:60%;">{{ $key }}</td>
										<td style="width:40%;">{{ $val }}</td>
									</tr>
									@php
										$n++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="span-6 span-last">
						<table class="tight">
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">Entries per Retailer</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$n = 0;
								@endphp
								@foreach ($campaign['graphs'][4]['values'] as $key => $val)
									<tr>
										<td style="width:60%;">{{ $key }}</td>
										<td style="width:40%;">{{ $val }}</td>
									</tr>
									@php
										$n++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-12 text-center">
						<h4 class="uppercase">Leading destination choices</h4>
					</div>
				</div>
				<div class="row">
					@php
						$n = 0;
					@endphp
					@foreach ($campaign['graphs'][5]['values'] as $key => $val)
						<div class="span- {{ $n==4?:'border-right' }} text-center" style="width:120.2px;">
							<div class="total-box">
								<h4 class="uppercase">{{ $key }}</h4>
								<p style="color:{{ $campaign['graphs'][5]['colors'][$n] }}">{{ $val }}</p>
							</div>
						</div>
						@php
							$n++;
						@endphp
					@endforeach
				</div>
			</div>
		</div>

		<hr>

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-12">
						<table class="tight">
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">Top 10 products purchased</span>
									</th>
								</tr>
								<tr>
									<th style="width:80%;">
										<span class="uppercase">Product</span>
									</th>
									<th style="width:20%;">
										<span class="uppercase">No. of Entries</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$n = 0;
								@endphp
								@foreach ($campaign['graphs'][6]['values'] as $key => $val)
									<tr>
										<td style="width:80%;">{{ $key }}</td>
										<td style="width:20%;">{{ $val }}</td>
									</tr>
									@php
										$n++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page-break"></div>

	<div class="page-wrapper">

		<div class="section">
			<div class="container">
				<div class="row" style="height: 400px;">
					@php
						$n = 1;
					@endphp
					@foreach ($campaign['graphs'][7]['values'] as $key => $val)
					<div class="span-6">
						<table class="tight small">
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">{{ $key }} - Top 10 products purchased</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = 0;
								@endphp
								@foreach ($val as $k => $v)
									<tr>
										<td style="width:90%;">{{ $k }}</td>
										<td style="width:10%;">{{ $v }}</td>
									</tr>
									@php
										$i++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
					@if ($n % 2 == 0 && $n !== count($campaign['graphs'][7]['values']))
				</div>
			</div>
		</div>
		@if ($n % 4 == 0)
	</div>
	<div class="page-break"></div>
	<div class="page-wrapper">
		@endif
		<div class="section">
			<div class="container">
				<div class="row" style="height: 400px;">
					@endif
					@php
						$n++;
					@endphp
					@endforeach
				</div>
			</div>
		</div>

	</div>

	<div class="page-break"></div>

	<div class="page-wrapper">

		<div class="section">
			<div class="container">
				<div class="row" style="height: 400px;">
					@php
						$n = 1;
					@endphp
					@foreach ($campaign['graphs'][8]['values'] as $key => $val)
					<div class="span-6">
						<table class="tight small">
							<thead>
								<tr>
									<th class="text-center" colspan="2">
										<span class="uppercase">{{ $key }} - Top 10 products purchased</span>
									</th>
								</tr>
							</thead>
							<tbody>
								@php
									$i = 0;
								@endphp
								@foreach ($val as $k => $v)
									<tr>
										<td style="width:90%;">{{ $k }}</td>
										<td style="width:10%;">{{ $v }}</td>
									</tr>
									@php
										$i++;
									@endphp
								@endforeach
							</tbody>
						</table>
					</div>
					@if ($n % 2 == 0 && $n !== count($campaign['graphs'][8]['values']))
				</div>
			</div>
		</div>
		@if ($n % 4 == 0)
	</div>
	<div class="page-break"></div>
	<div class="page-wrapper">
		@endif
		<div class="section">
			<div class="container">
				<div class="row" style="height: 400px;">
					@endif
					@php
						$n++;
					@endphp
					@endforeach
				</div>
			</div>
		</div>

	</div>

	<div class="page-break"></div>

	<div class="page-wrapper">
		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-2 text-center">&nbsp;</div>
					<div class="span-4 text-center">
						<div class="graph donut">
							<img src="{!! $campaign['graphs'][9]['graph'] !!}" alt="">
						</div>
					</div>
					<div class="span-4 text-center">
						<div class="total-box" style="padding-top: 10px;">
							<h4 class="uppercase">Approved</h4>
							<p class="blue">{{ $campaign['graphs'][9]['values']['Approved'] }}</p>
						</div>
					{{-- </div> --}}
					{{-- <div class="span-4 span-last text-center"> --}}
						<div class="total-box" style="padding-top: 10px;">
							<h4 class="uppercase">Rejected</h4>
							<p class="red">{{ $campaign['graphs'][9]['values']['Rejected'] }}</p>
						</div>
					{{-- </div> --}}
					{{-- <div class="span-4 text-center"> --}}
						<div class="total-box" style="padding-top: 10px;">
							<h4 class="uppercase">Pending</h4>
							<p class="green">{{ $campaign['graphs'][9]['values']['Pending'] }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		@if ($index==0)

		<hr style="margin-top: 40px;margin-bottom: 40px;">

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="span-4 text-center">
						<div class="total-box" style="padding-top: 70px;">
							<h4 class="uppercase">Instant Winners</h4>
							<p class="yellow">{{ $campaign['graphs'][10]['values']['Winners'] }}</p>
						</div>
					</div>
					<div class="span-4 text-center">
						<div class="graph donut">
							<img src="{!! $campaign['graphs'][10]['graph'] !!}" alt="">
						</div>
					</div>
					<div class="span-4 span-last text-center">
						<div class="total-box" style="padding-top: 70px;">
							<h4 class="uppercase">Instant Losers</h4>
							<p class="blue">{{ $campaign['graphs'][10]['values']['Losers'] }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		@endif
	</div>

	@endforeach
</body>
</html>