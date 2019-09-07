@extends('campaigns.layouts.email_wrapper')

@section('content')

@if ($kayo)

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#0b151e; text-align: center; font-weight: 800;padding-top: 50px;padding-bottom: 30px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Congratulations!<br/>
			Your claim has been approved.
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 10px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			<strong style="font-weight:bold;">Existing Kayo customer?</strong> <br>
			Login to your Kayo Account and enter this voucher code in the "Redeem Voucher" field within "My Account"
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#F40909; text-align: center;padding-bottom: 30px; padding-left: 20px;padding-right: 20px;font-weight: bold;" width="100%">
			{{ $kayo_voucher }}
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			<strong style="font-weight:bold;">New to Kayo?</strong><br>
			Click the ACTIVATE NOW link below and<br>follow the steps to sign up.
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			<a href="{{ $kayo_link }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #F40909; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 16px; font-weight: bold; margin: 0; padding: 7px 15px;text-transform: uppercase;">Activate Now</a>
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 40px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Activate your Kayo subscription by 20th November 2019.
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 10px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px;" width="100%">
			Remember to cancel your subscription prior to the end of the 3 month period if you do not wish to&nbsp;continue.<br/>To take advantage of this offer please ensure you sign up on desktop or mobile via your web browser, Google Chrome or Safari. Offer not available in conjunction with any other offer or available in conjunction with billing through iTunes or for new Kayo customers through T-bill.<br/>Charges will apply after your 3 months FREE Kayo voucher has&nbsp;expired.<br/>Offer not available in conjunction with any other offer or available in conjunction with billing through iTunes or for new Kayo customers through&nbsp;T-bill.
		</td>
	</tr>
</table>

@else

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 20px; vertical-align: middle;color:#0b151e; text-align: center; font-weight: 800;padding-top: 50px;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Congratulations!<br/>
			Your Claim Has Been Approved
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 20px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			Click on the link below by 20th October 2019<br/>
			for your chance to Scratch and Win<br/>
		</td>
	</tr>
	<tr>
		<td style="font-family: sans-serif; font-size: 16px; vertical-align: middle;color:#000000; text-align: center;padding-bottom: 30px; padding-left: 20px;padding-right: 20px; text-transform: uppercase;" width="100%">
			<a href="{{ $claim_link }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #F40909; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 16px; font-weight: bold; margin: 0; padding: 7px 15px;text-transform: uppercase;">Enter Now</a>
		</td>
	</tr>
</table>

@endif

@endsection