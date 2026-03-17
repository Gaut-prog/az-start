<?php

namespace App\Models;

use App\Enums\MessageSentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AZMessageSent extends Model
{
    protected $primaryKey = 'id_message';
    protected $table = 'az_messages_sent';
    public $timestamps = false;
    
    protected $fillable = [
        'date_sent',
        'company_az_id',
        'recipient',
        'activity_sector',
        'country',
        'object',
        'message',
        'status',
        'not_received_emails',
    ];
    protected $casts = [
        'date_sent' => 'datetime',
        'status' => MessageSentStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CustomerCategory::class, 'activity_sector', 'Category_id');
    }

    public function rcountry(): BelongsTo   
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }
    public function company(): BelongsTo   
    {
        return $this->belongsTo(Customer::class, 'company_az_id', 'Customers_Numbers');
    }

    public function files() : HasMany
    {
        return $this->hasMany(AZMessageSentFile::class, 'id_message', 'id_message');
    }
    
    public function conversations() : HasMany
    {
        return $this->hasMany(AZMessageConversation::class, 'id_message_sent', 'id_message');
    }
}