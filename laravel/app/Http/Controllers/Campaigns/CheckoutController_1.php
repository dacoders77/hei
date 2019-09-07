<?php

/**
 *
 * Legacy file used for Stripe Checkout
 * Test cards can be found here: https://stripe.com/docs/testing
 *
 * Successful test card:
 * Card No: 4000000000000077
 * Expiry: 12/22
 *
 *
 */

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirects;
use Response;
use Crypt;
use Mail;
use Submission;
use SubmissionMeta;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Log;

class CheckoutController_1 extends Controller
{
    function checkout(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return abort(500);
        }

        $submission = Submission::find($id);

        try {
            Stripe::setApiKey( config('stripe.secret') );

            $customer = Customer::create(array(
                'email' => $submission->meta('email'),
                'source'  => $request->stripeToken
            ));

            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount'   => 5000,
                'currency' => 'aud',
                'description' => 'Yellow Tail - 2 SCANPAN FRY PANS FOR $50',
                'receipt_email' => $submission->meta('email'),
            ));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        $submissionMeta = SubmissionMeta::firstOrNew([
            'submission_id' => $id,
            'meta_key' => 'status',
        ]);
        $submissionMeta->meta_value = '4';
        $submissionMeta->save();

        $submissionMeta = SubmissionMeta::firstOrNew([
            'submission_id' => $id,
            'meta_key' => 'payment_date',
        ]);
        $submissionMeta->meta_value = date('d/m/Y');
        $submissionMeta->save();

        try {
            Mail::send('campaigns.mail.email4', ['submission'=>$submission], function ($message) use ($submission) {

                $message->to($submission->meta('email'), $submission->meta('first_name') . ' ' . $submission->meta('last_name'));

                $message->replyTo('no-reply@yellowtailscanpan.com.au', 'Yellow Tail Scanpan');

                $message->subject('Your payment has been processed');
            });
        } catch (Exception $e) {
            Log::alert("Error sending payment email.\nFile:".__FILE__."\nError: ".print_r($e->getMessage(),true));
        }


        return redirect()->back()->with('success','Payment successful.');
    }


}