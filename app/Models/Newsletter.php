<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $primaryKey = 'email_id';
    protected $table = 'web_news_letter';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'email',
        'subject',
        'name',
        'phone',
        'note',
    ];
    
    protected $casts = [
        'date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Newsletter $newsletter) {
            $newsletter->date = now();
        });
    }
}