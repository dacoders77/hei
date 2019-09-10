<?php

namespace App\Http\Controllers\TriggerMail;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Log;
use SMS;

class TriggerMailController extends Controller
{
	static $from;
	static $reply;
	static $text;

	public function __construct($text)
	{
		self::$from = config('mail.from');
		self::$reply = config('mail.reply');
		self::$text = $text;
	}

    /**
     * Send email
     * $text - an array which contains text variables shown in info.blade.php
     *
     * @param $view
     * @param array $args
     * @param array $text
     * @return bool
     */
	private function send($view, $args=[])
	{
        //\App\Classes\LogToFile::add(__FILE__, json_encode(self::$text, JSON_PRETTY_PRINT));
		$args = array_merge([
			'to' => [
				'address' => null,
				'name' => null,
			],
			'from' => self::$from,
			'reply' => self::$reply,
			'subject' => null,
		], $args);

		try {
		    $data = [
		        'args' => $args,
                // info.bale.php variables array. Accessed via $text in blade
                'text' => [
                    'title' => self::$text['title'],
                    'message' => self::$text['message'],
                    'link' => self::$text['link'],
                    'linkText' => self::$text['linkText']
                ]
            ];
			Mail::send($view, $data, function ($message) use ($data) {
			    $message->to($data['args']['to']['address'], $data['args']['to']['name']);
			    $message->sender($data['args']['from']['address'], $data['args']['from']['name']);
			    $message->replyTo($data['args']['reply']['address'], $data['args']['reply']['name']);
			    $message->subject($data['args']['subject']);
			});
			return true;
		} catch (\Exception $e) {
			Log::error('Error sending email: '.$e->getMessage());
			return false;
		}
	}

	public function contact_us_1($args)
	{
		$args = array_merge([
			'to' => self::$from,
			'from' => self::$from,
			'reply' => [
				'address' => $args['request']->email,
				'name' => "{$args['request']->first_name} {$args['request']->last_name}"
			],
			'subject' => "New contact from {$args['request']->first_name} {$args['request']->last_name}",
		],$args);

		return self::send('campaigns.mail.contact_us_1', $args);
	}

	public function contact_us_2($args)
	{
		$args['subject'] = "Thank you, your enquiry has been sumitted successfully.";

		return self::send('campaigns.mail.contact_us_2', $args);
	}

    /**
     * Called from SubmissionController_1.php on claim create.
     * @param $submission
     */
	public function submissionStatus($submission)
	{
		$args = [
			'to' => [
				'address' => $submission->meta('email'),
				'name' => $submission->meta('first_name') . ' ' . $submission->meta('last_name'),
			],
			'submission' => $submission,
			'campaign_id' => $submission->campaign_id,
		];

		if($submission->campaign_id == 1) {
			switch ($submission->meta('status')) {
				case '1':
				// Fresh claim created
				case '2':
					$args['subject'] = 'We are processing your entry';
					//self::send('campaigns.mail.pending', $args); // Default. Works good.
                    // ['title' => 'title-e', 'message' => 'msg']
                    self::send('campaigns.pages.info', array_merge($args)); // Boris

                    SMS::send($submission->meta('phone'), "Thank you for your GAME ON WITH DULUX claim.\n\nYour invoice will be validated within 2 business days and we will contact you via EMAIL and SMS.");
					break;

				case '3':
					$args['subject'] = 'Congratulations! Your claim has been approved';
					/*$args['kayo'] = false;
					$args['kayo_voucher'] = null;
					$args['kayo_link'] = null;
					$args['claim_link'] = route('campaign_1.tinyurl',$submission->meta('tiny_url'));
					self::send('campaigns.mail.approved',$args);*/
                    self::send('campaigns.pages.info', array_merge($args)); // Boris


					/*SMS::send($submission->meta('phone'), "Congratulations! Your claim has been approved.\n\nClick the link below Click on the link below by 20th October 2019 for your chance to Scratch & Win.\n\n".preg_replace('/^(https?:\/\/)?(www\.)?/i','',$args['claim_link']));

					if( $args['kayo'] = $submission->meta('kayo') ){
						$args['subject'] = 'Congratulations! Here is your Kayo Voucher';
						$args['kayo_voucher'] = $submission->meta('kayo_voucher');
						$args['kayo_link'] = $submission->meta('kayo_link');

						self::send('campaigns.mail.approved',$args);

						SMS::send($submission->meta('phone'), "Your Dulux Kayo Subscription Claim is approved.\n\nWe have sent your activation link to ".$submission->meta('email').". Activate your subscription by 20th November 2019.");
					}*/
					break;

				case '4':
					// Non Winner
					break;

				case '5':
					$args['subject'] = 'Congratulations! You\'re a winner!';
					$args['claim_link'] = route('campaign_1.win',\Crypt::encrypt($submission->id));
					self::send('campaigns.mail.winner',$args);

					// SMS::send($submission->meta('phone'));
					break;

				case '6':
					$args['subject'] = 'Your prize in on it\'s way';
					self::send('campaigns.mail.shipped',$args);

					// SMS::send($submission->meta('phone'));
					break;

				default:
					$args['subject'] = 'Unfortunately, your claim has been declined';
					$args['status_comment'] = $submission->meta('status_comment');
					self::send('campaigns.mail.rejected',$args);

					SMS::send($submission->meta('phone'),"Unfortunately, your claim has been declined.\n\nDeclined reason ".$args['status_comment'].".\nIf you have any questions or would like to provide additional information please Contact us here: ".route('campaign_1.pages','contact-us'));
					break;
			}
		}
	}
}