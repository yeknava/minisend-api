<?php

namespace App\Actions;

use App\Http\Resources\EmailResource;
use App\Models\Email;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEmailAction
{
    use AsAction;

    public function handle(Email $email)
    {
        $email->load('attachments');

        return $email;
    }

    public function asController(Email $email)
    {
        return $this->handle($email);
    }

    public function jsonResponse(Email $email) :EmailResource
    {
        $resource = new EmailResource($email);
        $resource->wrap('email');

        return $resource;
    }
}
