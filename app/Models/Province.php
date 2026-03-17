<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'province';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'code_province',
        'Name',
        'fr_Name',
        'code_country',
    ];

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'code_country', 'code');
    }

    public function cities() : HasMany
    {
        return $this->hasMany(City::class, 'code_province', 'code_province');
    }

    public function getTNameAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Name;
    }
}