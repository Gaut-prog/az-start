<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AZMessageConversation extends Model
{
    protected $primaryKey = 'id_conversation';
    protected $table = 'az_messages_conversationa';
    public $timestamps = false;
    
    protected $fillable = [
        'date_conversation',
        'id_message_sent',
        'respondent_az_id',
        'message',
        'file',
    ];
    protected $hidden = [
        'file',
    ];
    protected $casts = [
        'date_conversation' => 'datetime',
    ];

    public function message(): BelongsTo   
    {
        return $this->belongsTo(AZMessageSent::class, 'id_message_sent', 'id_message');
    }

    public function sender(): BelongsTo   
    {
        return $this->belongsTo(Customer::class, 'respondent_az_id', 'Customers_Numbers');
    }

    public function getFFileAttribute() : string
    {
        return $this->file ? "data:application/octet-stream;base64," . base64_encode($this->file) : '';
    }
}