<?php

namespace App\Actions;

use DOMDocument;
use App\Models\Email;
use App\Models\Attachment;
use App\Http\Resources\EmailResource;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'attachments.*' => 'file|max:10000'
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

        $attachments = [];

        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $file->store('attachments');
            $path = storage_path('app/attachments/'.$file->hashName());

            $attachments[] = new Attachment([
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mimetype' => $file->getClientMimeType(),
                'driver' => 'local',
                'extension' => $file->getExtension(),
                'sha1' => sha1_file($path),
                'filesize' => $file->getSize(),
                'uploader_ip' => $ip
            ]);
        }

        $email->attachments()->saveMany($attachments);

        SendEmailAction::dispatchAfterResponse($email);

        return $email;
    }

    public function asController(Request $request): Email
    {
        return $this->handle($request->input(), $request->file('attachments') ?? [], request()->ip());
    }

    public function jsonResponse(Email $email): EmailResource
    {
        return new EmailResource($email);
    }
}
