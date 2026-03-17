<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPresence extends Model
{
     protected $primaryKey = 'id';
    protected $table = 'subscription_presences';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'reservation_id',
        'date',
    ];
     protected $casts = [
        'date' => 'datetime',
    ];

    public function subscription() : BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
   
}