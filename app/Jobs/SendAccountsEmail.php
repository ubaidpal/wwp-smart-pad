<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SendAccountsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    protected $accounts;
    public function __construct($accounts ,$data)
    {
        $this->data     = $data;
        $this->accounts = $accounts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $data     = $this->data;
        $accounts = $this->accounts;

        Mail::send('emails.accountsAccount', $data, function ($message) use ($accounts) {
            $message->subject('Account Activation: Please activate your account.');
            $message->to($accounts->email);
        });

        /*$schedule->call(
            function(){
                // you can pass queue name instead of default
                Artisan::call('queue:listen', array('--queue' => 'default'));
            }
        )->name('ensurequeueisrunning')->withoutOverlapping()->everyMinute();*/
    }


}
