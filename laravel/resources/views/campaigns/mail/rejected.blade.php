@extends('campaigns.layouts.email_wrapper')

@section('content')

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#0b151e; text-align: center; font-weight: 800;padding-top: 50px;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Unfortunately,<br/>
			Your Claim Has Been Declined
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Declined reason: {{ $status_comment }}
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 60px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			If you have any questions or would like to provide additional information please <a href="{{ route('campaign_1.pages','contact-us') }}" style="color:#000000;text-decoration: underline;">contact us</a>
		</td>
	</tr>
</table>

@endsection