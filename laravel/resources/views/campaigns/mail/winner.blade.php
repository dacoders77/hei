@extends('campaigns.layouts.email_wrapper')

@section('content')

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#0b151e; text-align: center; font-weight: 800;padding-top: 50px;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Congratulations, You’re a Winner!
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			If you have not already claimed your prize,<br>
			click the link below
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 30px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			<a href="{{ $claim_link }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #F40909; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 16px; font-weight: bold; margin: 0; padding: 7px 15px;text-transform: uppercase;">Redeem Prize</a>
		</td>
	</tr>
</table>

@endsection