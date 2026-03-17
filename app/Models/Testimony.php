<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    use HasFactory;

    protected $table = 'web_testimonies';
    protected $primaryKey = 'id_testimony';
    public $timestamps = false; 
    protected $connection = 'mysql2';

    protected $fillable = [
        'Photo',
        'Name',
        'City',
        'Message',
        'fr_Message',
        'linked_item',
        'nom_projet',
        'description_projet',
        'lien_projet',
    ];

    protected $hidden = [
        'Photo'
    ];

    public function getTMessageAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->fr_Message : $this->Message;
    }

    public function getFPhotoAttribute(): string
    {
        return $this->Photo ? 'data:image/jpeg;base64,' . base64_encode($this->Photo) : '';
    }
}