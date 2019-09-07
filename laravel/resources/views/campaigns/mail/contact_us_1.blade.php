@extends('campaigns.layouts.email_wrapper')

@section('content')

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#222222; text-align: left; font-weight: bold;padding-top: 50px;padding-bottom: 20px; padding-left: 20px;padding-right: 20px;" width="100%">
			NEW ENQUIRY
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#222222; text-align: left;padding-bottom: 60px; padding-left: 20px;padding-right: 20px;" width="100%">
			<p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 10px;"><strong style="font-weight:bold;">From:</strong> {{ $request->first_name }} {{ $request->last_name }}</p>
          <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 10px;"><strong style="font-weight:bold;">Email:</strong> {{ $request->email }}</p>
          <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 10px;"><strong style="font-weight:bold;">Mobile:</strong> {{ $request->phone }}</p>
          
          <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 10px;"><strong style="font-weight:bold;">Message:</strong></p>
          <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 20px;">{!! $request->comment !!}</p>
		</td>
	</tr>
</table>

@endsection