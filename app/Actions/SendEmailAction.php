<?php

namespace App\Actions;

use App\Mail\BaseMailer;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class SendEmailAction
{
    use AsAction;

    public function handle(Email $email)
    {
        Mail::to($email->recipient)
            ->send(new BaseMailer($email));
        $email->status = Email::STATUS_POSTED;
        $email->save();
    }
}
