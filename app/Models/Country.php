<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'country';
    protected $connection = 'mysql2';
    public $timestamps = false;
    
    protected $fillable = [
        'Icon',
        'code',
        'indicator',
        'Name',
        'fr_Name',
        'code_language',
        'currency',
    ];

    public function provinces() : HasMany
    {
        return $this->hasMany(Province::class, 'code_country', 'code');
    }

    public function getTNameAttribute()
    {
        return app()->getLocale() == 'fr' ? $this->fr_Name : $this->Name;
    }
}