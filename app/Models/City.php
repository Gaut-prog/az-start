<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'city';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'code_city',
        'Name',
        'fr_Name',
        'code_province',
    ];

    public function province() : BelongsTo
    {
        return $this->belongsTo(Province::class, 'code_province', 'code_province');
    }

    public function getTNameAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Name;
    }
}