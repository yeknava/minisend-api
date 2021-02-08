<?php

namespace App\Actions;

use App\Models\Email;
use App\Mail\BaseMailer;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class SendEmailAction
{
    use AsAction;

    public function handle(Email $email)
    {
        $mailer = new BaseMailer($email);

        foreach ($email->attachments as $attach) {
            $mailer = $mailer->attach($attach->path);
        }

        Mail::to($email->recipient)->send($mailer);
    }
}
