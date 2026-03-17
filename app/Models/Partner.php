<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'web_partners';
    protected $primaryKey = 'id_partner';
    public $timestamps = false;
    protected $connection = 'mysql2';

    protected $fillable = [
        'logo',
        'website_link',
    ];

     protected $hidden = [
        'logo',
    ];

    // Image en base64 pour affichage
    public function getFLogoAttribute(): string
    {
        return $this->logo ? 'data:image/jpeg;base64,'.base64_encode($this->logo) : '';
    }
}