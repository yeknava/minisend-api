<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    public const STATUS_INIT = 'init';
    public const STATUS_POSTED = 'posted';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'subject', 'sender', 'recipient',
        'text'
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function html()
    {
        if ($this->text != strip_tags($this->text))
        {
            return $this->text;
        }

        return '<p>'.$this->text.'</p>';
    }
}
