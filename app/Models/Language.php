<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    protected $primaryKey = 'id_language';

    public $timestamps = false;

    protected $fillable = [
        'code_language',
        'name',
        'fr_name',
        'available',
    ];

    public function getTNameAttribute(): string   
    {
        return app()->getLocale() == 'fr' ? $this->fr_name : $this->name;
    }
}