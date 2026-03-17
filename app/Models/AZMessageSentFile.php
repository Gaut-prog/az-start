<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AZMessageSentFile extends Model
{
    protected $primaryKey = 'id_files';
    protected $table = 'az_messages_sent_files';
    public $timestamps = false;
    
    protected $fillable = [
        'id_message',
        'file_name',
        'files',
    ];

    protected $hidden = [
        'files',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(AZMessageSent::class, 'id_message', 'id_message');
    }

    public function getFileAttribute() : string
    {
        return "data:application/octet-stream;base64," . base64_encode($this->files);
    }
}