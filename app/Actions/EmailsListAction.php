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
            'query' => 'nullable|string|max:200',
        ];
    }

    public function handle(string $query = null) {
        $emailQuery = Email::query()->select([
            'id', 'subject', 'sender',
            'recipient', 'text', 'status',
            'created_at',
        ]);

        if (!empty($query)) {
            $emailQuery->where('subject', 'like', $query. '%')
                ->orWhere('sender', 'like', $query . '%')
                ->orWhere('recipient', 'like', $query . '%');
        }

        return $emailQuery->orderBy('id', 'desc')->paginate(15);
    }

    public function asController(Request $request) :?LengthAwarePaginator
    {
        return $this->handle($request->query('query'));
    }

    public function jsonResponse(?LengthAwarePaginator $emails)
    {
        return new EmailResourceCollection($emails);
    }
}
