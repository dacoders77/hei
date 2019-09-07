<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Consumer;
use ConsumerMeta;
use TriggerMail;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendreminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to campaign winners.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $consumers = Consumer::whereMeta([
            ['meta_key','status'],
            ['meta_value',1],
        ])->get();

        foreach ($consumers as $consumer) {
            if(!$consumer->meta('reminder_date')) continue;
            if( date('U') < strtotime( str_replace( '/', '-', $consumer->meta('reminder_date') ) ) ) continue;

            $mail_sent = TriggerMail::send('winner_reminder',[
                'to' => [
                    'address' => $consumer->meta('email'),
                    'name' => $consumer->meta('first_name') . ' ' . $consumer->meta('last_name'),
                ],
                'consumer' => $consumer,
            ]);

            if( !$mail_sent ) continue;

            $consumerMeta = ConsumerMeta::firstOrNew([
                'consumer_id' => $consumer->id,
                'meta_key' => 'reminder_date',
            ]);

            $consumerMeta->meta_value = null;
            $consumerMeta->save();
        }
    }
}
