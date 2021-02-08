<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    public static $wrap = 'email';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = parent::toArray($request);

        if (!empty($response['status'])) {
            $response['status'] = ucfirst($response['status']);
        }

        if (!empty($response['created_at'])) {
            $response['created_at'] = $this->created_at->toDayDateTimeString();
        }

        if ($count = $this->resource->attachments()->count()) {
            $response['has_attachment'] = true;
            $response['attachment_count'] = $count;
        } else {
            $response['has_attachment'] = false;
            $response['attachment_count'] = 0;
        }

        return $response;
    }
}
