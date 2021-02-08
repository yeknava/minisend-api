<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'path',
        'filename',
        'mimetype',
        'extension',
        'driver',
        'sha1',
        'filesize',
        'uploader_ip'
    ];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }
}
