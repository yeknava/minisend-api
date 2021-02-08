<?php

namespace App\Listeners;

use App\Models\Email;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class EmailSentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $email = $event->data['email'];
        $email->status = Email::STATUS_POSTED;
        $email->save();
    }
}
