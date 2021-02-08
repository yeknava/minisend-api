<?php

namespace App\Actions;

use App\Models\Attachment;
use App\Models\Email;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFileAction
{
    use AsAction;

    public function handle(Email $email, Attachment $attachment)
    {
        if ($attachment->email_id !== $email->id) {
            return abort(403);
        }

        return $attachment;
    }

    public function asController(Email $email, Attachment $attachment)
    {
        $attachment = $this->handle($email, $attachment);

        return response()->download($attachment->path, $attachment->filename);
    }
}
