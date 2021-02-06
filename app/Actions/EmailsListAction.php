<?php

namespace App\Actions;

use App\Http\Resources\EmailResourceCollection;
use App\Models\Email;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;

class EmailsListAction
{
    use AsAction;

    public function rules()
    {
        return [
            'subject' => 'nullable|string|max:200',
            'sender' => 'nullable|email',
            'recipient' => 'nullable|email',
        ];
    }

    public function handle(
        string $subject = null,
        string $sender = null,
        string $recipient = null
    ) {
        $query = Email::query()->select([
            'id', 'subject', 'sender',
            'recipient', 'text', 'status',
            'created_at',
        ]);

        if ($subject) {
            $query->where('subject', 'like', '%' . $subject . '%');
        }

        if ($sender) {
            $query->where('sender', 'like', '%' . $sender . '%');
        }

        if ($recipient) {
            $query->where('recipient', 'like', '%' . $recipient . '%');
        }

        return $query->paginate(20);
    }

    public function asController(Request $request) :?LengthAwarePaginator
    {
        return $this->handle(
            $request->query('subject'),
            $request->query('sender'),
            $request->query('recipient')
        );
    }

    public function jsonResponse(?LengthAwarePaginator $emails)
    {
        return new EmailResourceCollection($emails);
    }
}
