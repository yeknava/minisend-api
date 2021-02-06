<?php

namespace App\Actions;

use DOMDocument;
use App\Models\Email;
use App\Models\Attachment;
use App\Http\Resources\EmailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class NewEmailAction
{
    use AsAction;

    public function rules()
    {
        return [
            'subject' => 'nullable|string|max:200',
            'sender' => 'required|email',
            'recipient' => 'required|email',
            'text' => 'nullable|string|min:1',
            'attachments' => 'array',
            'attachments.*' => 'file|max:5'
        ];
    }

    public function handle(
        array $data,
        array $files = [],
        string $ip = null
    ): Email {
        $email = new Email($data);

        if (!empty($email->text)) {
            $doc = new DOMDocument();
            $doc->loadHTML($email->text);
            $scriptTags = $doc->getElementsByTagName('script');
            $length = $scriptTags->length;

            for ($i = 0; $i < $length; $i++) {
                $scriptTags->item($i)->parentNode->removeChild($scriptTags->item($i));
            }

            $html = $doc->saveHTML();
            $email->html = $html;
        }

        $email->text = strip_tags($email->text);
        $email->save();

        $filePath = storage_path('app/attachments');
        $attachments = [];

        foreach ($files as $file) {
            $name = uniqid() . mt_rand(1, 99999);
            $path = $filePath . '/' . $name . '.' . $file->extension();

            $attachments[] = new Attachment([
                'path' => $path,
                'filename' => $name,
                'mimetype' => mime_content_type($path),
                'driver' => 'local',
                'extension' => 'jpg',
                'sha1' => sha1_file($path),
                'filesize' => $file->filesize(),
                'uploader_ip' => $ip
            ]);

            Storage::put($path, $file);
        }

        $email->attachments()->saveMany($attachments);

        SendEmailAction::dispatchAfterResponse($email);

        return $email;
    }

    public function asController(Request $request): Email
    {
        return $this->handle($request->input(), $request->allFiles(), request()->ip());
    }

    public function jsonResponse(Email $email): EmailResource
    {
        return new EmailResource($email);
    }
}
